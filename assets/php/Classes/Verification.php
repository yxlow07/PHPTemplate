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
    public function validate(mixed $contents, string $name, array $attr, bool $autoReturnMsg = true) : array|bool
    {
        $returns = []; $errMsgAttr = [];
        foreach ($attr as $item) {
            if (is_array($item)) {
                $returned = $this->arrayValidation($item, $contents);
                var_dump($returned);
                $errMsgAttr[$returned[0]] = $returned[2];
            } else {
                $returned = [$item, $this->staticValidation($item, $contents)];
            }
            $returns[$returned[0]] = $returned[1];
        }
        return $autoReturnMsg ? $this->returnErrMsg($returns, $name, $errMsgAttr) : $returns;
    }

    /**
     * Returns error message based on predefined templates
     * @param array $arr <p>Example: [0, 0, 1]</p>
     * @param string $name <p>Example: Email</p>
     * @param array $attr <p>Example: ["length" => "256"]</p>
     * @return array|bool
     */
    public function returnErrMsg(array $arr, string $name, array $attr = []): array|bool
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

    private function staticValidation(string $item, mixed $contents): bool
    {
        return match(strtolower($item)){
            "notempty" => $this->issetAndNotEmpty($contents) ?? false,
            "email" => $this->checkEmail($contents) ?? false,
            default => false,
        };
    }

    private function arrayValidation(array $item, mixed $contents): array
    {
        $length_attr = [$item[1] ?? 0, $item[2] ?? 256];
        return match ( strtolower($item[0]) ) {
            "length" => ["length", $this->checkLength($contents, $length_attr[0], $length_attr[1]), $length_attr],
            "match" => ["match", $this->checkMatch($contents, $item[2] ?? ""), $item[1] ?? "undefined"],
            default => ["undefined", false],
        };
    }

    private function checkLength(mixed $contents, int $min = 0, int $max = 256): bool
    {
        if ($this->issetAndNotEmpty($contents)){
            $contentType = $this->checkType($contents);
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
    private function checkEmail(string $email): bool
    {
//        $email_checked_data = $this->emailAPIReturn($email);
//        $data = json_decode($email_checked_data);
//        return $this->checkEmailReturnedJson($data);
        return true;
    }

    private function checkMatch(mixed $contents, mixed $cntTwo, bool $strict = false): bool
    {
        return $strict ? $contents === $cntTwo : $contents == $cntTwo;
    }

    private function emailAPIReturn(string $email) :string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.apilayer.com/email_verification/$email",
            CURLOPT_HTTPHEADER => ["Content-Type: text/plain", "apikey: mLiXeRQXiVUKsc1O0XgLsDZcFXnDVSDf"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
