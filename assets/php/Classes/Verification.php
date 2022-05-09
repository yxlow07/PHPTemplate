<?php
namespace Verification;

class Verification extends VerificationUtility
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
    public function validate(mixed $contents, array $attr = []) : array
    {
        $returns = [];
        foreach ($attr as $item) {
            switch ($item) {
                case "notEmpty":
                    $returns["notEmpty"] = $this->issetAndNotEmpty($contents) ?? false; break;
                case "length":
                    $returns["length"] = $this->checkLength($contents, 0, 256) ?? false; break;// TODO: Get this from function attr call
                case "email":
                    $returns["email"] = $this->checkEmail($contents) ?? false; break;
            }
        }
        return $returns;
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
//        return VerificationUtility::checkEmailReturnedJson($data);
        return false;
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
                            $returns[] = "$name's length must be between " . trim($attr["length"]) . " characters";
                        } else {
                            $returns[] = "$name's length must be between 0 and 256 characters";
                        } break;
                    case "email":
                        $returns[] = "$name must be valid"; break;
                    case "notEmpty":
                        $returns[] = "$name must not be empty"; break;
                }
            }
        }

        return $this->returnTrueIfBlankArray($returns);
    }
}
