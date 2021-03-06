<?php
namespace app\views;

use JetBrains\PhpStorm\ArrayShape;

class Views
{
    public string $layout = "main";

    public function __construct(
        public string $home = "http://localhost/",
        public string $root_dir = ""
    )
    {
    }

    public function throwError(int $statusCode = 500): string
    {
        header("HTTP/1.0 404 Not Found");
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

    private function checkExt(string $file_name): bool
    {
        return preg_match("/.*\.php/i", $file_name);
    }

    public function render(string $file_name, array $options = []): void
    {
        foreach ($options as $key => $option) {
            $$key = $option;
        }

        if (isset($layout)) {
            $this->setLayout($layout);
        }

        echo $this->replace($file_name, $err ?? "", $msg ?? "");

        if (isset($exit) && $exit === true) {
            exit;
        }
    }

    #[ArrayShape(["pages_location" => "string", "raw_path" => "string"])]
    private function returnFileLocation(string $file_name): string|bool
    {
        $extension = $this->checkExt($file_name) ? "" : ".php";
        $path = $this->root_dir . $file_name . $extension;
        $pagesLocation = $this->root_dir . "views/" . $file_name . $extension;
        $layoutLocation = $this->root_dir . "public/layouts/" . $file_name . $extension;

        if (file_exists($path)) {
            return $path;
        } elseif (file_exists($pagesLocation)) {
            return $pagesLocation;
        } elseif (file_exists($layoutLocation)) {
            return $layoutLocation;
        }
        return false;
    }

    private function replace(string $file, string $err = "", string $msg = ""): string
    {
        $layout = $this->getLayout($this->layout);
        $view = $this->getView($file);

        $home = rtrim($this->home, "\\/");
        if ($err !== "") {
            $err = "<div class=\"alert alert-danger d-flex align-items-center justify-content-center form-w500\" role=\"alert\"> <div class=\"d-block\"> <div class=\"text-center\">$err</div> </div></div>";
        }

        if ($msg !== "") {
            $msg = "<div class=\"alert alert-success d-flex align-items-center justify-content-center form-w500\" role=\"alert\"> <div class=\"d-block\"> <div class=\"text-center\">$msg</div> </div></div>";
        }

        return str_replace(
            ["{{content}}", "{home}", "{{err}}", "{{msg}}"],
            [$view, $home, $err, $msg],
            $layout
        );
    }

    public function replaceRaw($string): string
    {
        return str_replace("{home}", rtrim($this->home, "\\/"), $string);
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    private function getLayout(string $layoutFile): string
    {
        $fileLocation = $this->returnFileLocation($layoutFile);
        if (!$fileLocation) {
            exit("Layout not found");
        }
        ob_start();
        include str_replace("/", "\\", $fileLocation);
        return ob_get_clean();
    }

    private function getView(bool|string $file)
    {
        $fileLocation = $this->returnFileLocation($file);
        if (!$fileLocation) {
            exit("View not found");
        }
        ob_start();
        include str_replace("/", "\\", $fileLocation);
        return ob_get_clean();
    }
}