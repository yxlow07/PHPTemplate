<?php

namespace app\views;

use app\validation\ValidationUtility;

class Widgets
{
    public static function rowWithTwoColumns(string $column1, mixed $column2): void
    {
        if (is_array($column2)) {
            $column2 = implode(self::checkValue($column2));
        }
        echo "
        <div class='row'>
            <div class='col' style='user-select: none'><b>$column1: </b></div>
            <div class='col'>$column2</div>
        </div><hr>
        ";
    }

    public static function rowWithTwoColumnsEditable(string $title, mixed $contents): void
    {
        if (is_array($contents)) {
            $contents = implode(self::checkValue($contents));
        }
        $id = strtolower(preg_replace("/\s+/", "_", $title));
        echo "
        <div class='row'>
            <div class='col' style='user-select: none'>$title:</div>
            <div class='col d-flex justify-content-between'>
                <input name='$id' value='$contents' class='form-control'>
            </div>
        </div><hr>
        ";
    }

    public static function checkValue(array $contents): array
    {
        $returns = [];
        foreach ($contents as $var => $key) {
            if ($var == "session") {
                $returns[] = ValidationUtility::existInSession($key, $var);
            } else {
                $returns[] = "<i>$var</i> is not a supported variable";
            }
        }
        return $returns;
    }

    public static function roundCenterProfilePicHover(string|array $img_source, string $altText, string $classes = "rounded-circle pfp editable-pic"): void
    {
        if (is_array($img_source)) {
            $img_source = implode(self::checkValue($img_source));
        }
        if ($img_source === "") {
            $img_source = "https://i.pinimg.com/736x/c9/e3/e8/c9e3e810a8066b885ca4e882460785fa.jpg";
        }
        echo "
        <div class=\"d-flex justify-content-center\">
            <div class=\"rounded-circle pfp camera\" id=\"\" draggable='true' data-bs-toggle=\"modal\" data-bs-target=\"#pfp\">
                <img
                        src=\"$img_source\"
                        alt=\"$altText\"
                        class=\"$classes\"
                >
                <div class=\"cam-content rounded-circle\">
                    <span class=\"icon-wrapper rounded-circle\">
                        <i class=\"fa-solid fa-camera\"></i>
                    </span>
                </div>
            </div>
        </div>
        ";
    }

    public static function roundCenterProfilePic(string|array $img_source, string $altText, string $classes = "rounded-circle pfp"): void
    {
        if (is_array($img_source)) {
            $img_source = implode(self::checkValue($img_source));
        }
        if ($img_source === "") {
            $img_source = "https://i.pinimg.com/736x/c9/e3/e8/c9e3e810a8066b885ca4e882460785fa.jpg";
        }
        echo "<div class='d-flex justify-content-center'><img src='$img_source' alt='$altText' class='$classes'></div>";
    }

    public static function js_script(string $link, bool $refreshOnReload = false): void
    {
        $link = $refreshOnReload ? $link . "?id=" . bin2hex(random_bytes(3)) : $link;
        echo "<script src='$link'></script>";
    }

    public static function centerDivLinkBtn(string $link, string $btnContent, $btnStyles = "btn btn-dark link"): void
    {
        echo "
            <div class=\"horizontal-center\">
                <a href=\"$link\"><button class=\"$btnStyles\">$btnContent</button></a>
            </div>
        ";
    }

    public static function startForm(string $action = "", string $method = "get", string $enctype = ""): void
    {
        echo "<form action=\"$action\" method=\"$method\" enctype=\"$enctype\">";
    }

    public static function submitBtn(
        string $styles = "btn btn-dark", string $btnContent = "Submit", string $wrappingDiv = "horizontal-center"
    ): void
    {
        echo "<div class=\"$wrappingDiv\"><button class=\"$styles\" type=\"submit\">$btnContent</button></div>";
    }

    public static function endForm(): void
    {
        echo "</form>";
    }
}