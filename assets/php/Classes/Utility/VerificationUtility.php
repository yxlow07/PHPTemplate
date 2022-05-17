<?php
namespace Verification;

class VerificationUtility
{
    protected function checkEmailReturnedJson($jsonObj): bool
    {
        return ($jsonObj->disposable == false && $jsonObj->format_valid == true && $jsonObj->smtp_check == true && $jsonObj->mx_found == true);
    }

    protected function issetAndNotEmpty($contents): bool
    {
        if (isset($contents) && !empty($contents)) {
            return true;
        }
        return false;
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

    protected function returnTrueIfBlankArray($returns) : array|bool
    {
        return $returns == [] ? true : $returns;
    }

    protected function checkReturns($arr): bool
    {
        if (in_array(false, $arr)) {
            return false;
        }
        return true;
    }
}