<?php

namespace auth;

class authUtility
{
    protected function returnJson(mixed $data): void
    {
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}