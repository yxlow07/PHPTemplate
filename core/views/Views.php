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
        if (isset($options["layout"])) {
            $this->setLayout($options["layout"]);
        }
        echo $this->replace($file_name);
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

    private function replace(string $file) : string
    {
        $layout = $this->getLayout($this->layout);
        $view = $this->getView($file);

        return str_replace(["{{content}}", "{home}"], [$view, rtrim($this->home, "\\/")], $layout);
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