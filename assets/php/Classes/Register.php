<?php
namespace auth;
include_once dirname(__DIR__) . "/autoload.php";

use Verification\Verification;

class Register extends authUtility
{
    private Verification $verification;
    const email_flags = ["notEmpty", ["length"], "email"];
    const username_flags = ["notEmpty", ["length", 5, 30]];
    const pwd_flags = ["notEmpty", ["length"]];
    const supported_validation_types = ["email", "username", "pwd"];


    public function __construct(array $data, array $validate_options = []) {
        $this->verification = new Verification();
        $this->run($data, $validate_options);
    }

    //TODO: MOVE CLASS TO AUTHUTILITY
    private function validate(array $data, array $validate_options): void
    {
        $errors = [];
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
        $this->returnJson($errors);
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
}