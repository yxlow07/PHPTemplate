<?php
namespace auth;
include_once dirname(__DIR__) . "/autoload.php";

use JetBrains\PhpStorm\NoReturn;
use Verification\Verification;

class Register extends authUtility
{
    private Verification $verification;
    private MongoDatabase $db;
    private array $default_values = [];
    const email_flags = ["notEmpty", ["length"], "email"];
    const username_flags = ["notEmpty", ["length", 5, 30]];
    const pwd_flags = ["notEmpty", ["length"]];
    const supported_validation_types = ["email", "username", "pwd"];

    public function __construct(array $data, string $db_name, string $collection_name, array $default_values = [], array $validate_options = []) {
        $this->verification = new Verification();
        $this->db = new MongoDatabase($db_name, $collection_name);
        $this->default_values = $default_values;

        $this->run($data, $validate_options);
    }

    //TODO: MOVE CLASS TO AUTHUTILITY
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

    public function run(array $data, array $validate_options) :void
    {
        if (isset($data['reg'])){
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
        $result = $this->db->insert($filtered_data);
        if ($this->checkDBStatus($result)) {
            $this->returnJson(["status" => true, "id" => $result]);
        }
        $this->returnJson(["status" => false, "message" => $result]);
    }

    private function sanitiseInput(array $data, array $fields): array
    {
        $sanitised_data = [];
        foreach ($data as $key => $item) {
            if ($key === "pwd") {
                $item = password_hash($item, PASSWORD_BCRYPT, ["cost" => 11]);
            }
            if (in_array($key, $fields)) {
                $sanitised_data[$key] = addslashes(htmlspecialchars(filter_var($item, FILTER_SANITIZE_EMAIL)));
            }
        }
        return [...$sanitised_data, ...$this->default_values];
    }


}