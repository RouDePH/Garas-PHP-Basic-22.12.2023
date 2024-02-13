<?php

namespace Traits;

use Closure;
use Classes\{Request,ApiException};

trait InputValidators
{
    use ExceptionHandling;

    static function validateBodyKeys(...$requiredKeys): Closure
    {
        return self::handleException(
            function (Request $req) use ($requiredKeys) {
                $missingKeys = array_diff($requiredKeys, array_keys($req->getBody()));
                if (!empty($missingKeys)) {
                    $missingKeysString = implode(', ', $missingKeys);
                    throw new ApiException("Provide $missingKeysString", 400);
                }
            }
        );
    }

    static function validateQueryParams(...$requiredKeys): Closure
    {
        return self::handleException(
            function (Request $req) use ($requiredKeys) {
                $missingKeys = array_diff($requiredKeys, array_keys($req->getQueryParams()));
                if (!empty($missingKeys)) {
                    $missingKeysString = implode(', ', $missingKeys);
                    throw new ApiException("Provide $missingKeysString", 400);
                }
            }
        );
    }
}