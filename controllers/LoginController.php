<?php

namespace main\controllers;

use app\auth\authUtility;
use app\db\MongoDatabase;
use app\validation\Validation;
use JetBrains\PhpStorm\NoReturn;

class LoginController extends authUtility
{
    private string $pwd_field_name;
    const e_u_flags = ["notEmpty", ["length"]];
    const pwd_flags = ["notEmpty", ["length"]];
    const supported_validation_types = ["e_u", "pwd"];
    private Validation $verification;
    private MongoDatabase $db;

    public function __construct()
    {
        $this->verification = new Validation();
        $this->setPwdFieldName("pwd");
    }

    #[NoReturn]
    private function validate(array $data, array $validate_options): void
    {
        $errors = [];
        $fields = ["e_u"];
        foreach ($data as $name => $value) {
            $isPredefined = in_array(strtolower($name), self::supported_validation_types);
            $isPostDefined = array_key_exists(strtolower($name), $validate_options);
            if ($isPredefined) {
                $errors[$name] = $this->match_known_val($name, $value);
            } elseif ($isPostDefined) {
                $errors[$name] = $this->match_defined_val($name, $value, $validate_options[$name]);
            }
        }
        if (!$this->checkIfNoErrors($errors)) {
            authUtility::returnJson($errors);
        }
        $this->handleDB($data, $fields);
    }

    public function run(array $data, array $validate_options = []): void
    {
        if (isset($data['login'])) {
            $this->validate($data, $validate_options);
        }
    }

    private function match_known_val(string $name, mixed $value): bool|array
    {
        return match (strtolower($name)) {
            "e_u" => $this->verification->validate($value, "Email/Username", self::e_u_flags),
            "pwd" => $this->verification->validate($value, "Password", self::pwd_flags),
            default => "Unknown error has occurred. Contact a developer to check Login Class"
        };
    }

    private function match_defined_val(int|string $name, mixed $value, mixed $options): bool|array
    {
        return $this->verification->validate($value, ucfirst(strval($name)), $options);
    }

    #[NoReturn]
    private function handleDB(array $data, array $fields)
    {
        $filtered_data = $this->sanitiseInput($data, $fields);
        $pwd = $this->sanitise($data["pwd"] ?? "");
        $result = $this->db->find($filtered_data);
        if ($result === null) {
            authUtility::returnJson(["status" => false, "message" => "Username/Email not found"]);
        }
        if (password_verify($pwd, $result[$this->pwd_field_name])) {
            unset($result[$this->pwd_field_name]);
            $this->updateSession((array)$result);
            authUtility::returnJson(["status" => true]);
        }
        // TODO: remove on production
        authUtility::returnJson(["status" => false, "message" => "Password is wrong", "debug" => $result]);
    }

    private function sanitiseInput(array $data, array $fields): array
    {
        $sanitised_data = [];
        foreach ($data as $key => $item) {
            if ($key === "e_u" ) {
                $e_u_res = $this->e_u($item);
                $sanitised_data[$e_u_res[0]] = $e_u_res[1];
                continue;
            }
            if (in_array($key, $fields)) {
                $sanitised_data[$key] = $this->sanitise($item);
            }
        }
        return $sanitised_data;
    }

    public static function logout(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }
        header("Location: ./home?logout=true");
    }

    public function setPwdFieldName(string $pwd_field_name): void
    {
        $this->pwd_field_name = $pwd_field_name;
    }

    public function setDb(string $dbName, string $collectionName): void
    {
        $this->db = new MongoDatabase($dbName, $collectionName);
    }
}