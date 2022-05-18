<?php
namespace app\Router;

use JetBrains\PhpStorm\ArrayShape;

class Render
{
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
}