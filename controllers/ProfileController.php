<?php

namespace main\controllers;

use app\views\Views;
use Dotenv\Dotenv;

class ProfileController
{
    private Views $views;
    private array $profileInfo = ["email", "username", "date_created", "date_modified"];
    private array $userInfo = ["name", "profile_img", "bio"];

    public function __construct()
    {
        $this->start();
    }

    private function start(): void
    {
        $dir = dirname(__DIR__) . "\\";
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->load();
        $this->views = new Views($_ENV["ROOT"], $dir);
    }

    public function get(): void
    {
        $user_data = $this->processSession();
        $this->views->render("profile", $user_data);
    }

    private function processSession(): array
    {
        $returns = [];
        foreach ($_SESSION as $key => $value) {
            if (in_array($key, $this->profileInfo) || in_array($key, $this->userInfo)) {
                $returns[$key] = $value;
            }
        }
        return $returns;
    }
}