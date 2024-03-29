<?php

namespace JWT;

use ArrayAccess;
use DomainException;
use Exception;
use InvalidArgumentException;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use stdClass;
use UnexpectedValueException;

class JWT
{
    private const ASN1_INTEGER = 0x02;
    private const ASN1_SEQUENCE = 0x10;
    private const ASN1_BIT_STRING = 0x03;

    public static int $leeway = 0;

    public static ?int $timestamp = null;

    public static array $supported_algs = [
        'ES384' => ['openssl', 'SHA384'],
        'ES256' => ['openssl', 'SHA256'],
        'ES256K' => ['openssl', 'SHA256'],
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
        'EdDSA' => ['sodium_crypto', 'EdDSA'],
    ];

    public static function decode(
        string                $jwt,
        array|ArrayAccess|Key $keyOrKeyArray,
        stdClass              &$headers = null
    ): stdClass
    {
        // Validate JWT
        $timestamp = is_null(static::$timestamp) ? time() : static::$timestamp;

        if (empty($keyOrKeyArray)) {
            throw new InvalidArgumentException('Key may not be empty');
        }
        $tks = explode('.', $jwt);
        if (count($tks) !== 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $headerRaw = static::urlsafeB64Decode($headb64);
        if (null === ($header = static::jsonDecode($headerRaw))) {
            throw new UnexpectedValueException('Invalid header encoding');
        }
        if ($headers !== null) {
            $headers = $header;
        }
        $payloadRaw = static::urlsafeB64Decode($bodyb64);
        if (null === ($payload = static::jsonDecode($payloadRaw))) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        if (is_array($payload)) {
            $payload = (object)$payload;
        }
        if (!$payload instanceof stdClass) {
            throw new UnexpectedValueException('Payload must be a JSON object');
        }
        $sig = static::urlsafeB64Decode($cryptob64);
        if (empty($header->alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if (empty(static::$supported_algs[$header->alg])) {
            throw new UnexpectedValueException('Algorithm not supported');
        }

        $key = self::getKey($keyOrKeyArray, property_exists($header, 'kid') ? $header->kid : null);

        if (!self::constantTimeEquals($key->getAlgorithm(), $header->alg)) {
            throw new UnexpectedValueException('Incorrect key for this algorithm');
        }
        if (in_array($header->alg, ['ES256', 'ES256K', 'ES384'], true)) {
            $sig = self::signatureToDER($sig);
        }
        if (!self::verify("$headb64.$bodyb64", $sig, $key->getKeyMaterial(), $header->alg)) {
            throw new SignatureInvalidException('Signature verification failed');
        }

        if (isset($payload->nbf) && floor($payload->nbf) > ($timestamp + static::$leeway)) {
            $ex = new BeforeValidException(
                'Cannot handle token with nbf prior to ' . date(DATE_ISO8601_EXPANDED, (int)$payload->nbf)
            );
            $ex->setPayload($payload);
            throw $ex;
        }

        if (!isset($payload->nbf) && isset($payload->iat) && floor($payload->iat) > ($timestamp + static::$leeway)) {
            $ex = new BeforeValidException(
                'Cannot handle token with iat prior to ' . date(DATE_ISO8601_EXPANDED, (int)$payload->iat)
            );
            $ex->setPayload($payload);
            throw $ex;
        }

        if (isset($payload->exp) && ($timestamp - static::$leeway) >= $payload->exp) {
            $ex = new ExpiredException('Expired token');
            $ex->setPayload($payload);
            throw $ex;
        }

        return $payload;
    }

    public static function encode(
        array                                          $payload,
        OpenSSLAsymmetricKey|string|OpenSSLCertificate $key,
        string                                         $alg,
        string                                         $keyId = null,
        array                                          $head = null
    ): string
    {
        $header = ['typ' => 'JWT'];
        if (isset($head)) {
            $header = array_merge($header, $head);
        }
        $header['alg'] = $alg;
        if ($keyId !== null) {
            $header['kid'] = $keyId;
        }
        $segments = [];
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($header));
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($payload));
        $signing_input = implode('.', $segments);

        $signature = static::sign($signing_input, $key, $alg);
        $segments[] = static::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }

    public static function sign(
        string                                         $msg,
        OpenSSLAsymmetricKey|string|OpenSSLCertificate $key,
        string                                         $alg
    ): string
    {
        if (empty(static::$supported_algs[$alg])) {
            throw new DomainException('Algorithm not supported');
        }
        list($function, $algorithm) = static::$supported_algs[$alg];
        switch ($function) {
            case 'hash_hmac':
                if (!is_string($key)) {
                    throw new InvalidArgumentException('key must be a string when using hmac');
                }
                return hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':
                $signature = '';
                $success = openssl_sign($msg, $signature, $key, $algorithm); // @phpstan-ignore-line
                if (!$success) {
                    throw new DomainException('OpenSSL unable to sign data');
                }
                if ($alg === 'ES256' || $alg === 'ES256K') {
                    $signature = self::signatureFromDER($signature, 256);
                } elseif ($alg === 'ES384') {
                    $signature = self::signatureFromDER($signature, 384);
                }
                return $signature;
            case 'sodium_crypto':
                if (!function_exists('sodium_crypto_sign_detached')) {
                    throw new DomainException('libsodium is not available');
                }
                if (!is_string($key)) {
                    throw new InvalidArgumentException('key must be a string when using EdDSA');
                }
                try {
                    $lines = array_filter(explode("\n", $key));
                    $key = base64_decode((string)end($lines));
                    if (strlen($key) === 0) {
                        throw new DomainException('Key cannot be empty string');
                    }
                    return sodium_crypto_sign_detached($msg, $key);
                } catch (Exception $e) {
                    throw new DomainException($e->getMessage(), 0, $e);
                }
        }

        throw new DomainException('Algorithm not supported');
    }

    private static function verify(
        string                                         $msg,
        string                                         $signature,
        OpenSSLAsymmetricKey|string|OpenSSLCertificate $keyMaterial,
        string                                         $alg
    ): bool
    {
        if (empty(static::$supported_algs[$alg])) {
            throw new DomainException('Algorithm not supported');
        }

        list($function, $algorithm) = static::$supported_algs[$alg];
        switch ($function) {
            case 'openssl':
                $success = openssl_verify($msg, $signature, $keyMaterial, $algorithm); // @phpstan-ignore-line
                if ($success === 1) {
                    return true;
                }
                if ($success === 0) {
                    return false;
                }
                throw new DomainException(
                    'OpenSSL error: ' . openssl_error_string()
                );
            case 'sodium_crypto':
                if (!function_exists('sodium_crypto_sign_verify_detached')) {
                    throw new DomainException('libsodium is not available');
                }
                if (!is_string($keyMaterial)) {
                    throw new InvalidArgumentException('key must be a string when using EdDSA');
                }
                try {
                    // The last non-empty line is used as the key.
                    $lines = array_filter(explode("\n", $keyMaterial));
                    $key = base64_decode((string)end($lines));
                    if (strlen($key) === 0) {
                        throw new DomainException('Key cannot be empty string');
                    }
                    if (strlen($signature) === 0) {
                        throw new DomainException('Signature cannot be empty string');
                    }
                    return sodium_crypto_sign_verify_detached($signature, $msg, $key);
                } catch (Exception $e) {
                    throw new DomainException($e->getMessage(), 0, $e);
                }
            case 'hash_hmac':
            default:
                if (!is_string($keyMaterial)) {
                    throw new InvalidArgumentException('key must be a string when using hmac');
                }
                $hash = hash_hmac($algorithm, $msg, $keyMaterial, true);
                return self::constantTimeEquals($hash, $signature);
        }
    }

    public static function jsonDecode(string $input): mixed
    {
        $obj = json_decode($input, false, 512, JSON_BIGINT_AS_STRING);

        if ($errno = json_last_error()) {
            self::handleJsonError($errno);
        } elseif ($obj === null && $input !== 'null') {
            throw new DomainException('Null result with non-null input');
        }
        return $obj;
    }

    public static function jsonEncode(array $input): string
    {
        if (PHP_VERSION_ID >= 50400) {
            $json = json_encode($input, JSON_UNESCAPED_SLASHES);
        } else {
            // PHP 5.3 only
            $json = json_encode($input);
        }
        if ($errno = json_last_error()) {
            self::handleJsonError($errno);
        } elseif ($json === 'null') {
            throw new DomainException('Null result with non-null input');
        }
        if ($json === false) {
            throw new DomainException('Provided object could not be encoded to valid JSON');
        }
        return $json;
    }

    public static function urlsafeB64Decode(string $input): string
    {
        return base64_decode(self::convertBase64UrlToBase64($input));
    }

    public static function convertBase64UrlToBase64(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return strtr($input, '-_', '+/');
    }

    public static function urlsafeB64Encode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    private static function getKey(
        array|ArrayAccess|Key $keyOrKeyArray,
        ?string               $kid
    ): Key
    {
        if ($keyOrKeyArray instanceof Key) {
            return $keyOrKeyArray;
        }

        if (empty($kid) && $kid !== '0') {
            throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
        }

        if (!isset($keyOrKeyArray[$kid])) {
            throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
        }

        return $keyOrKeyArray[$kid];
    }

    public static function constantTimeEquals(string $left, string $right): bool
    {
        if (function_exists('hash_equals')) {
            return hash_equals($left, $right);
        }
        $len = min(self::safeStrlen($left), self::safeStrlen($right));

        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= (ord($left[$i]) ^ ord($right[$i]));
        }
        $status |= (self::safeStrlen($left) ^ self::safeStrlen($right));

        return ($status === 0);
    }

    private static function handleJsonError(int $errno): void
    {
        $messages = [
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters' //PHP >= 5.3.3
        ];
        throw new DomainException(
            $messages[$errno] ?? 'Unknown JSON error: ' . $errno
        );
    }

    private static function safeStrlen(string $str): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }

    private static function signatureToDER(string $sig): string
    {
        $length = max(1, (int)(strlen($sig) / 2));
        list($r, $s) = str_split($sig, $length);

        $r = ltrim($r, "\x00");
        $s = ltrim($s, "\x00");

        if (ord($r[0]) > 0x7f) {
            $r = "\x00" . $r;
        }
        if (ord($s[0]) > 0x7f) {
            $s = "\x00" . $s;
        }

        return self::encodeDER(
            self::ASN1_SEQUENCE,
            self::encodeDER(self::ASN1_INTEGER, $r) .
            self::encodeDER(self::ASN1_INTEGER, $s)
        );
    }

    private static function encodeDER(int $type, string $value): string
    {
        $tag_header = 0;
        if ($type === self::ASN1_SEQUENCE) {
            $tag_header |= 0x20;
        }

        $der = chr($tag_header | $type);

        $der .= chr(strlen($value));

        return $der . $value;
    }

    private static function signatureFromDER(string $der, int $keySize): string
    {
        list($offset, $_) = self::readDER($der);
        list($offset, $r) = self::readDER($der, $offset);
        list($offset, $s) = self::readDER($der, $offset);

        $r = ltrim($r, "\x00");
        $s = ltrim($s, "\x00");

        $r = str_pad($r, $keySize / 8, "\x00", STR_PAD_LEFT);
        $s = str_pad($s, $keySize / 8, "\x00", STR_PAD_LEFT);

        return $r . $s;
    }

    private static function readDER(string $der, int $offset = 0): array
    {
        $pos = $offset;
        $size = strlen($der);
        $constructed = (ord($der[$pos]) >> 5) & 0x01;
        $type = ord($der[$pos++]) & 0x1f;

        $len = ord($der[$pos++]);
        if ($len & 0x80) {
            $n = $len & 0x1f;
            $len = 0;
            while ($n-- && $pos < $size) {
                $len = ($len << 8) | ord($der[$pos++]);
            }
        }

        if ($type === self::ASN1_BIT_STRING) {
            $pos++;
            $data = substr($der, $pos, $len - 1);
            $pos += $len - 1;
        } elseif (!$constructed) {
            $data = substr($der, $pos, $len);
            $pos += $len;
        } else {
            $data = null;
        }

        return [$pos, $data];
    }
}