<?php
namespace Verification;

class Verification extends VerificationUtility
{
    /**
     * Validate content
     * @param mixed $contents
     * @param string $name
     * @param array $attr <p>
     * Array to match cases <br/>
     * Available flags: notEmpty, length, email, match
     * </p>
     * @param bool $autoReturnMsg
     * @return array|bool True if passed all tests false if one fails
     */
    public function validate(mixed $contents, string $name, array $attr = [], bool $autoReturnMsg = true) : array|bool
    {
        $returns = [];
        $special = [];
        foreach ($attr as $item) {
            if (is_array($item)) {
                $returned = $this->arrayValidation($item, $contents);
                $special[$returned[0]] = $returned[2];
            } else {
                $returned = $this->staticValidation($item, $contents);
            }
            $returns[$returned[0]] = $returned[1];
        }
        return $autoReturnMsg ? $this->returnErrorMessage($returns, $name, $special) : $returns;
    }

    private function staticValidation($item, $contents): array
    {
        return match ( strtolower($item) ) {
            "notempty" => ["notEmpty", $this->issetAndNotEmpty($contents) ?? false],
            "email" => ["email", $this->checkEmail($contents) ?? false],
            default => ["undefined", false],
        };
    }

    private function arrayValidation($item, $contents): array
    {
        $length_attr = [$item[1] ?? 0, $item[2] ?? 256];
        return match ( strtolower($item[0]) ) {
            "length" => ["length", $this->checkLength($contents, $length_attr[0], $length_attr[1]) ?? false, $length_attr],
            "match" => ["match", $this->checkMatch($contents, $item[2] ?? false) ?? false, $item[1] ?? "undefined"],
            default => ["undefined", false],
        };
    }

    public function checkLength($contents, $min = 0, $max = 256): bool
    {
        if (VerificationUtility::issetAndNotEmpty($contents)){
            $contentType = VerificationUtility::checkType($contents);
            if ($contentType == "string" || $contentType == "integer" || $contentType == "double") {
                return (strlen($contents) <= $max && strlen($contents) > $min);
            } elseif ($contentType == "array") {
                return (sizeof($contents) <= $max && sizeof($contents) > $min);
            }
            return false;
        }
        return false;
    }

    // TODO: revert back to curl request on launch
    private function checkEmail($email): bool
    {
        // $ch = curl_init(); // Initialize cURL.
        // curl_setopt($ch, CURLOPT_URL, "http://apilayer.net/api/check?access_key=8838e8038dbaad86d9431d3f362ff403&email=$email&smtp=1&format=1");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        // $data = curl_exec($ch); // Execute the request.
        // curl_close($ch); // Close the cURL handle.
        $data = json_decode('{"email":"support@apilayer.com","did_you_mean":"","user":"support","domain":"apilayer.net","format_valid":true,"mx_found":true,"smtp_check":true,"catch_all":false,"role":true,"disposable":false,"free":false,"score":0.8}');
        return VerificationUtility::checkEmailReturnedJson($data);
    }

    /**
     * Returns error message based on predefined templates
     * @param array $arr <p>Example: [0, 0, 1]</p>
     * @param string $name <p>Example: Email</p>
     * @param array $attr <p>Example: ["length" => "256"]</p>
     * @return array|bool
     */
    public function returnErrorMessage(array $arr, string $name, array $attr = []): array|bool
    {
        $returns = []; // Stored messages needed to be returned

        foreach ($arr as $item => $value) {
            if (!$value) {
                switch ($item) {
                    case "length":
                        if (array_key_exists("length", $attr)) {
                            $returns[] = "$name's length must be between " . $attr["length"][0] . " and " . $attr["length"][1] . " characters";
                        } else {
                            $returns[] = "$name's length must be between 0 and 256 characters";
                        } break;
                    case "match":
                        $returns[] = "$name must be match with " . $attr["match"]; break;
                    case "email":
                        $returns[] = "$name must be valid"; break;
                    case "notEmpty":
                        $returns[] = "$name must not be empty"; break;
                }
            }
        }

        return $this->returnTrueIfBlankArray($returns);
    }

    private function checkMatch($contents, string|bool $param): bool
    {
        return $contents == $param;
    }
}
