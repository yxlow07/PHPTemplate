<?php

namespace app\validation;

class ValidationUtility
{
    public string $regexSpecialChar = "/[^a-zA-Z0-9_-]/mi";

    protected function issetAndNotEmpty(mixed $contents): bool
    {
        return isset($contents) && !empty($contents);
    }

    /**
     * Checks type of object
     * @param mixed $contents
     * @return string <p>
     * Available returns: string, integer, double, array, object, null, boolean
     * </p>
     */
    protected function checkType(mixed $contents): string
    {
        return strtolower(gettype($contents));
    }

    protected function returnTrueIfBlankArray(array $returns) : array|bool
    {
        return $returns == [] ? true : $returns;
    }

    protected function checkReturns(array $arr): bool
    {
        return !in_array(false, $arr);
    }

    protected function checkEmailReturnedJson(object $jsonObj): bool
    {
        return !$jsonObj->is_disposable && $jsonObj->syntax_valid;
    }

    protected function isEmail(string $value): bool
    {
        return preg_match("/[a-zA-Z\d+_.-]+@[a-zA-Z\d.-]/", $value);
    }

    protected function regexMatch(mixed $contents, array $attr): bool
    {
        $pattern = $attr["pattern"] === "default" ? $this->regexSpecialChar : $attr["pattern"];
        $flip = $attr["flip"] ?? false;
        $result = preg_match($pattern, $contents);
        return $flip ? !$result : $result;
    }
}