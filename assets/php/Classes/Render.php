<?php

namespace app\Router;

use JetBrains\PhpStorm\ArrayShape;

class Render
{
    public array $static_file_types = ["js", "css", "html", "json"];
    public array $image_types = ["png", "jpg", "jpeg"];

    public function __construct(
        public string $root_dir = "",
        public string $home = "http://localhost/"
    ){}

    public function throwError(int $statusCode = 500) :string
    {
        $err_dir = $this->root_dir . "errors\\";
        $file_contents = file_get_contents($err_dir . "$statusCode.php") ?? false;
        if (!$file_contents) {
            return str_replace(
                ["{code}", "{home}"],
                [$statusCode, $this->home],
                file_get_contents($err_dir . "err_template.php")
            );
        }
        return str_replace("{home}", $this->home, $file_contents);
    }
    public function returnStaticFiles(string $uri, string $type, string $ext) : void
    {
        if ($type == "cnt") {
            $this->setHeader($ext);
            include_once $this->root_dir . $uri;
        } elseif ($type == "img") {
            $this->handleImage($this->root_dir . urldecode($uri), $ext);
        } else {
            echo "This file is not supported sorry";
            exit();
        }
    }
    private function handleImage(string $path_to_image, string $type) : void
    {
        if (file_exists($path_to_image)) {
            $image = fopen($path_to_image, "r");
//            header("Content-Type: image/$type");
//            header("Content-Length: " . filesize($path_to_image));
            fpassthru($image);
        } else {
            echo "This image does not exist sorry";
        }
    }
    #[ArrayShape(["check" => "bool", "type" => "string", "ext" => "string"])]
    public function checkIsStatic($uri) : array
    {
        $_uri = explode(".", $uri);
        $ext = end($_uri);

        if (in_array($ext, $this->static_file_types)) {
            return ["check" => true, "type" => "cnt", "ext" => $ext];
        } elseif (in_array($ext, $this->image_types)) {
            return ["check" => true, "type" => "img", "ext" => $ext];
        } else {
            return ["check" => false, "type" => ""];
        }
    }
    private function setHeader($ext): void
    {
        switch ($ext) {
            case "css":
                header("Content-Type: text/css"); break;
            case "js":
                header("Content-Type: text/javascript"); break;
            case "json":
                header("Content-Type: application/json"); break;
            default:
                header("Content-Type: text/html"); break;
        }
    }
}