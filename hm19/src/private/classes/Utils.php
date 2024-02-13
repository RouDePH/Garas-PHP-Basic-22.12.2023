<?php

namespace Classes;


class Utils
{
    static function signData(string $data, string $partnerId, string $partnerKey): string
    {
        $partnerHash = sha1($partnerId . $partnerKey);
        return sha1(sha1($data) . $partnerHash);
    }
    static function getUserIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
