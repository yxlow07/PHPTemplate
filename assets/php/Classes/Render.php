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

    public function parseFile(mixed $fn, mixed $options = []): void
    {
        if ($this->checkFileExists($fn)) {
            // TODO: render the file with a function
            $this->render($fn, $options);
        }
    }

    private function checkExt(string $file_name): bool
    {
        return preg_match("/.*\.php/i", $file_name);
    }

    /**
     * Caution when using this function: the file mustn't have the same name as a file in the root directory
    */
    private function checkFileExists(string $file_name, bool $inc_file_location = false) :bool|array
    {
        $file_location = $this->returnFileLocation($file_name);
        $file_exists = file_exists($file_location["raw_path"]);
        $page_exists = file_exists($file_location["pages_location"]);
        $check = $file_exists || $page_exists;
        if ($inc_file_location) {
            return ["loc" => $check ? ($file_exists ? $file_location["raw_path"] : $file_location["pages_location"]) : "", "exists" => $check];
        }
        return $check;
    }

    public function render(string $file_name, array $options): void
    {
        if (in_array("wrap", $options)) {
            echo $this->replace(["header.php", $file_name, "footer.php"]);
        }
    }

    #[ArrayShape(["pages_location" => "string", "raw_path" => "string"])]
    private function returnFileLocation(string $file_name): array
    {
        $extension = $this->checkExt($file_name) ? "" : ".php";
        $path_to_file = $this->root_dir . $file_name . $extension;
        $pages_loc = $this->root_dir . "pages/" . $file_name . $extension;
        return ["pages_location" => $pages_loc, "raw_path" => $path_to_file];
    }

    private function replace(array $files) : string
    {
        ob_start([$this, "callback"]);
        foreach ($files as $file) {
            $check_exists = $this->checkFileExists($file, true);
            if ($check_exists["exists"]) {
                include str_replace("/", "\\", $check_exists["loc"]);
            }
        }
        ob_end_flush();
        return "";
    }

    public function callback($buffer = ''): array|string
    {
        // TODO: do this dynamically
        return str_replace("{home}", $this->home, $buffer);
    }
}