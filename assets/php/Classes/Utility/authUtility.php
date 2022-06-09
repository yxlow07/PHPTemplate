<?php

namespace auth;

use JetBrains\PhpStorm\NoReturn;

class authUtility
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
}