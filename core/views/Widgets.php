<?php

namespace app\views;

class Widgets
{
    public static function rowWithTwoColumns(string $title, mixed $contents): void
    {
        if (is_array($contents)) {
            $contents = implode(self::checkValue($contents));
        }
        echo "
        <div class='row'>
            <div class='col'><b>$title: </b></div>
            <div class='col'>$contents</div>
        </div><hr>
        ";
    }

    public static function checkValue(array $contents): array
    {
        $returns = [];
        foreach ($contents as $var => $key) {
            if ($var == "session") {
                $returns[] = self::existInSession($key, $var);
            } else {
                $returns[] = "<i>$var</i> is not a supported variable";
            }
        }
        return $returns;
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
            $temp = $_SESSION[$exploded[0]];
            foreach ($exploded as $item) {
                if ($item !== $exploded[0]) {
                    $temp = $temp[$item] ?? [];
                }
            }
            return $temp !== [] ? $temp : $errMsg;
        }
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $errMsg;
    }

    public static function roundCenterImg(string $img_source, string $altText, string $classes = "rounded-circle pfp"): void
    {
        if ($img_source === "") {
            $img_source = "{home}/static/images/logo1.png";
        }
        echo "<div class='d-flex justify-content-center'><img src='$img_source' alt='$altText' class='$classes'></div>";
    }
}