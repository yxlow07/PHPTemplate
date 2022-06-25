<?php

namespace app\validation;

use JetBrains\PhpStorm\NoReturn;

class ValidationUtility
{
    public string $regexSpecialChar = "/[^a-zA-Z0-9_-]/mi";

    #[NoReturn]
    public static function returnJson(mixed $data): void
    {
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    public static function returnModifiedData(array $data, array $check, bool $debug = false): array
    {
        $returns = [];
        foreach ($data as $key => $datum) {
            $verf = $check[$key] ?? null;
            $checked = $verf === $datum ? "true" : "false";
            if ($debug) {
                echo "Key: <b>$key</b> <br/>";
                echo "Content in session: $verf, Data received: $datum <b>Is the same: $checked</b> <br/>";
            }
            if ($checked === "false") {
                $returns[$key] = $datum;
            }
        }
        return $returns;
    }

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

    /**
     * @param mixed $key
     * @param int|string $var
     * @return mixed|string
     */
    public static function existInSession(mixed $key, int|string $var): mixed
    {
        $errMsg = "<i>$key</i> does not exist in $var";
        if (str_contains($key, "||")) {
            $exploded = explode("||", $key);
            $temp = $_SESSION[$exploded[0]] ?? [];
            foreach ($exploded as $item) {
                if ($item !== $exploded[0]) {
                    $temp = $temp[$item] ?? [];
                }
            }
            return $temp !== [] ? $temp : $errMsg;
        }
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $errMsg;
    }

    public static function removeImmutable(array $data, array $mutable): array
    {
        foreach ($data as $key => $datum) {
            if (!in_array($key, $mutable)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}