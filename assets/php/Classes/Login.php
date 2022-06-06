<?php

namespace auth;
use Verification\Verification;

include_once dirname(__DIR__) . "/autoload.php";

class Login extends authUtility
{
    private Verification $verification;
    const e_u_flags = ["notEmpty", ["length"]];
    const pwd_flags = ["notEmpty", ["length"]];
    const supported_validation_types = ["e_u", "pwd"];


    public function __construct(array $data, array $validate_options = []) {
        $this->verification = new Verification();
        $this->run($data, $validate_options);
    }

    private function validate(array $data, array $validate_options): void
    {
        $errors = [];
        foreach ($data as $name => $value) {
            $isPredefined = in_array(strtolower($name), self::supported_validation_types);
            $isPostDefined = array_key_exists(strtolower($name), $validate_options);
            if ($isPredefined) {
                $errors[$name] = $this->match_known_val($name, $value);
            } elseif ($isPostDefined) {
                $errors[$name] = $this->match_defined_val($name, $value, $validate_options[$name]);
            }
        }
        $this->returnJson($errors);
    }

    public function run(array $data, array $validate_options) :void
    {
        if (isset($data['login'])){
            $this->validate($data, $validate_options);
        }
    }

    private function match_known_val(string $name, mixed $value): bool|array
    {
        return match (strtolower($name)) {
            "e_u" => $this->verification->validate($value, "Email/Username", self::e_u_flags),
            "pwd" => $this->verification->validate($value, "Password", self::pwd_flags),
            default => "Unknown error has occured. Contact a developer to check Login Class"
        };
    }

    private function match_defined_val(int|string $name, mixed $value, mixed $options): bool|array
    {
        return $this->verification->validate($value, ucfirst(strval($name)), $options);
    }
}