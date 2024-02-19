<?php

namespace Utils;

class LocaleHelper
{
    public static function getUserLocales(): array
    {
        $prefLocales = array_reduce(
            explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
            function ($res, $el) {
                list($l, $q) = array_merge(explode(';q=', $el), [1]);
                $res[$l] = (float)$q;
                return $res;
            }, []);
        return array_keys($prefLocales);
    }

    public static function getLocale() : string
    {
        $languages = self::getUserLocales();
        $selectedLanguage = SupportedLocales::cases()[0];

        foreach ($languages as $userLanguage) {
            if (in_array($userLanguage, SupportedLocales::cases())) {
                $selectedLanguage = $userLanguage;
                break;
            }
        }

        return $selectedLanguage->value;
    }
}