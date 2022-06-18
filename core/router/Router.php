<?php

namespace app\router;

use app\views\Views;
use JetBrains\PhpStorm\ArrayShape;

class Router
{
    protected array $methods = ["GET", "POST", "PUT", "DELETE", "OPTIONS", "PATCH", "HEAD"];
    protected array $handling = [];
    public Views $render;

    public function __construct(
        public string $home = "http://localhost/",
        public string $dir = ""
    )
    {
        $this->render = new Views($this->home, $this->dir);
    }

    public function GET(string $route, callable|object|string|array $fn, array $options = []): void
    {
        $route = trim($route, "/\\");
        $this->handling[$route]["GET"] = ["fn" => $fn, "options" => $options];
    }

    public function POST(string $route, callable|object|string|array $fn, array $options = []): void
    {
        $route = trim($route, "/\\");
        $this->handling[$route]["POST"] = ["fn" => $fn, "options" => $options];
    }

    public function ALL(string $route, callable|object|string|array $fn, array $options = []): void
    {
        $route = trim($route, "/\\");
        $methods = explode("|", "GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD");
        foreach ($methods as $method) {
            $this->handling[$route][strtoupper($method)] = ["fn" => $fn, "options" => $options];
        }
    }

    protected function getUri(): string
    {
        $uri = rawurldecode($_SERVER['REQUEST_URI']);
        if ($this->home !== "/") {
            $uri = str_replace(trim($this->home, "\\/"), "", $uri);
        }
        return trim($uri, " \t\n\r\0\x0B\\/");
    }

    #[ArrayShape(["uri" => "mixed|string", "method" => "mixed|string", "vars" => "mixed|string"])]
    protected function getData(): array
    {
        return [
            "uri" => $this->getUri(),
            "method" => $_SERVER['REQUEST_METHOD'] ?? "GET",
            "vars" => $_SERVER['QUERY_STRING'] ?? ""
        ];
    }

    public function processQueryString(string $query_string): array
    {
        $returns = [];
        if ($query_string !== "") {
            foreach (explode("&", $query_string) as $exploded) {
                $seperated = explode("=", $exploded);
                $returns[$seperated[0]] = $seperated[1] ?? null;
            }
        }
        return $returns;
    }

    private function match($uri, $method): bool
    {
        return array_key_exists($uri, $this->handling) && isset($this->handling[$uri][$method]);
    }

    public function run(): void
    {
        $data = $this->getData();
        $vars = $this->processQueryString($data['vars']);
        echo $this->handle($data["uri"], $data["method"]);
    }

    private function handle(string $url, string $method): string|null
    {
        $uri = explode("?", $url)[0];
        $match = $this->match($uri, $method);
        if ($match) {
            // TODO: the options idk how do rn
            $route = $this->handling[$uri][$method];
            $fn = $route["fn"];
            $options = $route["options"];
            if (is_string($fn)) {
                return $this->render->render($fn, $options);
            }
            if (is_array($route["fn"])) {
                $controller = new $fn[0];
                $fn[0] = $controller;
                return call_user_func_array($fn, $options);
            }
            if (is_callable($fn)) {
                return call_user_func_array($fn, $options);
            }
            return "";
        }
        return $this->render->throwError(404);
    }
}