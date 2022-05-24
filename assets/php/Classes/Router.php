<?php

use app\Router\Render;
use JetBrains\PhpStorm\ArrayShape;

class Router
{
    protected array $methods = ["GET", "POST", "PUT", "DELETE", "OPTIONS", "PATCH", "HEAD"];
    protected array $handling = [];
    private Render $render;

    public function __construct(
        public string $root_dir = "",
        public string $route_excl = "",
        public string $home = "http://localhost/"
    ) {
        $this->render = new Render($this->root_dir, $this->home);
    }

    public function GET(string $route, callable|object|string $fn, array $options = []): void
    {
        $this->handling[$route] = ["fn" => $fn, "methods" => "GET", "options" => $options];
    }

    public function ALL(string $route, callable|object|string $fn, array $options = []): void
    {
        $this->handling[] = ["route" => $route, "fn" => $fn, "methods" => "GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD", "options" => $options];

    }

    #[ArrayShape(["uri" => "mixed|string", "method" => "mixed|string", "vars" => "mixed|string"])]
    protected function getData(): array
    {
        return [
            "uri" => str_replace($this->route_excl, "", rawurldecode($_SERVER['REQUEST_URI']) ?? "/"),
            "method" => $_SERVER['REQUEST_METHOD'] ?? "GET",
            "vars" => $_SERVER['QUERY_STRING'] ?? ""
        ];
    }

    protected function processQueryString(string $query_string): array
    {
        $returns = [];
        if ($query_string !== "") {
            foreach (explode("&", $query_string) as $exploded) {
                $returns[] = explode("=", $exploded);
            }
        }
        return $returns;
    }

    private function match($uri) : bool|int
    {
        return array_key_exists($uri, $this->handling);
    }

    public function run(): void
    {
        $data = $this->getData();
        $vars = $this->processQueryString($data['vars']);
        $this->handle($data["uri"]);
    }

    private function handle(string $uri): void
    {
        if ($this->match($uri)) {
            // TODO: the options idk how do rn
            $route = $this->handling[$uri];
            if (is_callable($route["fn"])) {
                call_user_func_array($route["fn"], $route["options"]);
            } else {
                $this->render->parseFile($route["fn"], $route["options"]);
            }
            return;
        }
        echo $this->render->throwError(404);
    }
}