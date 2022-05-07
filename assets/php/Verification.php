<?php

class Verification
{
    /**
     * Validate content
     * @param mixed $contents
     * @param array $attr <p>
     * Array to match cases <br/>
     * Available flags: 0->isset and not empty, email, password, length, 1->length <= 256
     * </p>
     * @return array True if passed all tests false if one fails
     */
    public static function validate($contents, $attr = []) : array
    {
        $returns = [];
        if (in_array(0, $attr)) {
            array_push($returns, self::issetAndNotEmpty($contents) ?? false);
        }
        if (in_array(1, $attr)) {
            array_push($returns, self::checkLength($contents) ?? false);
        }
        if (in_array("email", $attr)) {
            array_push($returns, self::checkEmail($contents) ?? false);
        }
        return $returns;
    }

    private static function issetAndNotEmpty($contents) : bool
    {
        if (isset($contents) && !empty($contents) && $contents != false) {
            return true;
        }
        return false;
    }

    private static function checkLength($contents, $min = 0, $max = 256)
    {
        if (self::issetAndNotEmpty($contents)){
            $contentType = self::checkType($contents);
            if ($contentType == "string" || $contentType == "integer" || $contentType == "double") {
                return (strlen($contents) <= $max && strlen($contents) > $min);
            } elseif ($contentType == "array") {
                return (sizeof($contents) <= $max && sizeof($contents) > $min);
            }
            return false;
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
    private static function checkType($contents)
    {
        return strtolower(gettype($contents));
    }

    public static function checkReturns($arr) : bool
    {
        if (in_array(false, $arr)) {
            return false;
        }
        return true;
    }

    // TODO: revert back to curl request on launch
    private static function checkEmail($email) {
        // $ch = curl_init(); // Initialize cURL.
        // curl_setopt($ch, CURLOPT_URL, "http://apilayer.net/api/check?access_key=8838e8038dbaad86d9431d3f362ff403&email=$email&smtp=1&format=1");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        // $data = curl_exec($ch); // Execute the request.
        // curl_close($ch); // Close the cURL handle.
        $data = json_decode('{"email":"support@apilayer.com","did_you_mean":"","user":"support","domain":"apilayer.net","format_valid":true,"mx_found":true,"smtp_check":true,"catch_all":false,"role":true,"disposable":false,"free":false,"score":0.8}');
        return self::checkEmailReturnedJson($data);
    }

    private static function checkEmailReturnedJson($jsonObj)
    {
        return ($jsonObj->disposable == false && $jsonObj->format_valid == true && $jsonObj->smtp_check == true && $jsonObj->mx_found == true);
    }

    /**
     * Returns error message based on predefined templates
     * @param array $attr
     * @param array $arr
     * @param string $name
     */
    public static function returnErrorMessage(array $attr, array $arr, string $name)
    {
        $array_keys = array_keys($attr);
        for ($i = 0; $i < sizeof($array_keys); $i++) {
            $index = $array_keys[$i];
            if ($arr[$i] == false) {
                if ($array_keys[$i] == "length") {
                    echo "$name's length must be less than " . $attr[$index] . PHP_EOL;
                } else {
                    switch ($attr[$i]) {
                        case "issetAndNotEmpty":
                            echo "$name must not be empty\n";
                            return;
                        case "email":
                            echo "Email must be valid\n";
                            return;
                    }
                }
            }
        }
    }
}