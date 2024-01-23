<?php

require_once "ApiException.php";

class InputValidator
{
    /**
     * @throws ApiException
     */
    public static function validateKeys($array, $requiredKeys): true
    {
        $missingKeys = array_diff($requiredKeys, array_keys($array));
        if (!empty($missingKeys)) {
            $missingKeysString = implode(', ', $missingKeys);
            throw new ApiException("Provide $missingKeysString", 400);
        }
        return true;
    }
}