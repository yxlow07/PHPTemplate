<?php
namespace main\controllers;

use app\auth\authUtility;
use app\db\MongoDatabase;
use app\validation\Validation;
use JetBrains\PhpStorm\NoReturn;

class RegisterController extends authUtility
{
    private array $default_values;
    private string $not_unique_msg = "Account exists. Please login.";
    const email_flags = ["notEmpty", ["length"], "email"];
    const username_flags = [
        "notEmpty",
        ["length", "min" => 5, "max" => 30],
        ["regex", "pattern" => "default", "flip" => true, "msg" => "Username must not contain special characters"]
    ];
    const pwd_flags = ["notEmpty", ["length"]];
    const supported_validation_types = ["email", "username", "pwd"];

    public function __construct()
    {
        $this->verification = new Validation();
    }

    //TODO: MOVE CLASS TO AUTHUTILITY
    #[NoReturn]
    private function validate(array $data, array $validate_options): void
    {
        $errors = [];
        $fields = ["username", "email", "pwd"];
        $pwd_flag_match = ["match", "confirmed password", $data['pwdConf'] ?? ""];
        foreach ($data as $name => $value) {
            $isPredefined = in_array(strtolower($name), self::supported_validation_types);
            $isPostDefined = array_key_exists(strtolower($name), $validate_options);
            if ($isPredefined) {
                $errors[$name] = $this->match_known_val($name, $value, $pwd_flag_match);
            } elseif ($isPostDefined) {
                $errors[$name] = $this->match_defined_val($name, $value, $validate_options[$name]);
            }
        }
        if (!$this->checkIfNoErrors($errors)) {
            $this->returnJson($errors);
        }
        $this->handleDB($data, $fields);
    }

    public function run(array $data, array $validate_options = []): void
    {
        if (isset($data['reg'])) {
            $this->validate($data, $validate_options);
        }
    }

    private function match_known_val(string $name, mixed $value, array $pwd_flag_match): bool|array
    {
        return match (strtolower($name)) {
            "email" => $this->verification->validate($value, "Email", self::email_flags),
            "username" => $this->verification->validate($value, "Username", self::username_flags),
            "pwd" => $this->verification->validate($value, "Password", [...self::pwd_flags, $pwd_flag_match])
        };
    }

    private function match_defined_val(int|string $name, mixed $value, mixed $options): bool|array
    {
        return $this->verification->validate($value, ucfirst(strval($name)), $options);
    }

    #[NoReturn]
    protected function handleDB(array $data, array $fields)
    {
        $filtered_data = $this->sanitiseInput($data, $fields);
        $unique = $this->checkUnique($filtered_data, ["email", "username"]);
        if ($unique) {
            $result = $this->db->insert($filtered_data);
            if ($this->checkDBStatus($result)) {
                $this->returnJson(["status" => true, "id" => $result]);
            }
            $this->returnJson(["status" => false, "message" => $result]);
        }
        $this->returnJson(["status" => false, "message" => $this->not_unique_msg]);
    }

    private function sanitiseInput(array $data, array $fields): array
    {
        $sanitised_data = [];
        foreach ($data as $key => $item) {
            if ($key === "pwd") {
                $sanitised_data[$key] = password_hash($item, PASSWORD_BCRYPT);
                continue;
            }
            if (in_array($key, $fields)) {
                $sanitised_data[$key] = $this->sanitise($item);
            }
        }
        return [...$sanitised_data, ...$this->default_values];
    }

    private function checkUnique(array $filtered_data, array $attr): bool
    {
        foreach ($attr as $item) {
            if (!array_key_exists($item, $filtered_data)) {
                return false;
            }
            if ($this->db->find([$item => $filtered_data[$item]]) !== null) {
                $this->not_unique_msg = ucfirst("$item already exists. Please try another or log in to your existing account");
                return false;
            }
        }
        return true;
    }

    public function setDb(string $dbName, string $collectionName): void
    {
        $this->db = new MongoDatabase($dbName, $collectionName);
    }

    /**
     * @return array
     */
    public function getDefaultValues(): array
    {
        return $this->default_values;
    }

    public function setDefaultValues(string $dir, string $file): void
    {
        $fileName = $dir . "/app/schema/" . $file;
        if (!file_exists($fileName)) {
            exit("$fileName does not exist.");
        }
        $this->default_values = require_once $fileName;
    }
}