<?php

namespace app\auth;

use app\db\MongoDatabase;
use app\validation\Validation;
use app\validation\ValidationUtility;
use JetBrains\PhpStorm\ArrayShape;

abstract class authUtility extends ValidationUtility
{
    private Validation $verification;
    private MongoDatabase $db;

    protected function checkIfNoErrors(array $data): bool
    {
        foreach ($data as $key => $item) {
            if ($item !== true) {
                return false;
            }
        }
        return true;
    }

    protected function checkDBStatus(string $db_return): bool
    {
        return !preg_match("/Database.*failed/mi", $db_return);
    }

    public function sanitise(mixed $value): string
    {
        return addslashes(htmlspecialchars(filter_var($value, FILTER_SANITIZE_EMAIL)));
    }

    #[ArrayShape(["key" => "string", "item" => "string"])]
    protected function e_u(string $item) : array
    {
        $sanitised_item = $this->sanitise($item);
        $key = $this->isEmail($item) ? "email" : "username";
        return ["key" => $key, "item" => $sanitised_item];
    }

    public static function updateSession(array $data, bool $destroy_session = false): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($destroy_session) {
            session_destroy();
            session_start();
        }
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    abstract public function setDB(string $dbName, string $collectionName);
}