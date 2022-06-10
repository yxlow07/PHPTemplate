<?php

namespace auth;

include_once "VerificationUtility.php";
use JetBrains\PhpStorm\NoReturn;
use Verification\VerificationUtility;

class authUtility extends VerificationUtility
{
    #[NoReturn]
    protected function returnJson(mixed $data): void
    {
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }
    
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

    protected function sanitise(mixed $value): string
    {
        return addslashes(htmlspecialchars(filter_var($value, FILTER_SANITIZE_EMAIL)));
    }

    protected function e_u(string $item) : array
    {
        $sanitised_item = $this->sanitise($item);
        $key = $this->isEmail($item) ? "email" : "username";
        return [$key, $sanitised_item];
    }

    protected function updateSession(array $data): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}