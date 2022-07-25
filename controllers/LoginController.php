<?php

namespace main\controllers;

use app\auth\authUtility;
use app\db\MongoDatabase;
use app\mail\Mailer;
use app\validation\Validation;
use app\views\Views;
use JetBrains\PhpStorm\NoReturn;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class LoginController extends authUtility
{
    private string $pwd_field_name;
    const e_u_flags = ["notEmpty", ["length"]];
    const pwd_flags = ["notEmpty", ["length"]];
    const supported_validation_types = ["e_u", "pwd"];
    private Validation $verification;
    private MongoDatabase $db;
    private Views $views;

    public function __construct(Views $views)
    {
        $this->views = $views;
        $this->verification = new Validation();
        $this->setPwdFieldName("pwd");
        $this->setDb($_ENV["DB_NAME"], $_ENV["USER_TABLE"]);
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

        authUtility::returnJson(["status" => false, "message" => "Password is wrong"]);
    }

    private function sanitiseInput(array $data, array $fields): array
    {
        $sanitised_data = [];
        foreach ($data as $key => $item) {
            if ($key === "e_u" ) {
                $e_u_res = $this->e_u($item);
                $sanitised_data[$e_u_res["key"]] = $e_u_res["item"];
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
        header("Location: ./");
    }

    public function setPwdFieldName(string $pwd_field_name): void
    {
        $this->pwd_field_name = $pwd_field_name;
    }

    public function setDb(string $dbName, string $collectionName): void
    {
        $this->db = new MongoDatabase($dbName, $collectionName);
    }

    public function resetPasswordGet()
    {
        $this->views->render("resetPassword");
    }

    public function findToken($options, $isGet = true, $reset_id = ""): array
    {
        $this->setDb($_ENV["DB_NAME"], "tokens");

        $reset_id = $_GET['reset_id'] ?? $reset_id;
        $err = "";
        $find = $this->db->find(["token" => $reset_id]);
        $userID = $find["user_id"] ?? null;

        if ($find == null) {
            $err .= "Sorry your token is not found. Please request a new one";
        } elseif ($find["for"] !== "resetPassword") {
            $err .= "Sorry your token is not to reset a password. Please go where you are supposed to go :')";
        } elseif ($userID === null) {
            $err .= "Something is wrong with the database. Contact an admin to check your account";
        }

        if ($isGet) {
            if ($err === "") {
                $this->views->render("resetPasswordForm", ['exit' => true]);
            }
            $this->views->render("resetPassword", ["err" => $err, 'exit' => true]);
        }
        return $err === "" ? ['return' => true, 'user_id' => $userID] : ['return' => false, 'msg' => $err];
    }

    #[NoReturn]
    public function resetPasswordResetPost()
    {
        $data = json_decode($_POST['resetPassword'], true) ?? [];
        $fields = ['pass' => 'Password', 'confPass' => 'Confirmed password', 'token' => 'Token'];

        foreach ($fields as $field => $identifier) {
            if (empty($data[$field])) {
                self::returnJson(["status" => false, "msg" => "$identifier is empty!"]);
            }
        }

        if ($data['pass'] !== $data['confPass']) {
            self::returnJson(["status" => false, "msg" => "Password does not match!"]);
        }

        if (strlen($data['pass']) < 5) {
            self::returnJson(["status" => false, "msg" => "Password is too short!"]);
        }

        $tokenFound = $this->findToken([], false, $data['token']);
        if ($tokenFound['return'] === false) {
            self::returnJson(["status" => false, "msg" => "Token is not found! Are you sure this link is correct?"]);
        }

        $this->setDb($_ENV['DB_NAME'], $_ENV['USER_TABLE']);
        $password = password_hash($data['pass'], PASSWORD_BCRYPT);
        $result = $this->db->update(
            ['_id' => $tokenFound['user_id']],
            ['pwd' => $password, 'date_modified' => new UTCDateTime(strtotime('now') * 1000)]
        );

        if ($result->isAcknowledged() && $result->getModifiedCount() === 1) {
            $this->setDb($_ENV['DB_NAME'], "tokens");
            $this->db->delete(['token' => $data['token']]);
            self::returnJson(["status" => true, "msg" => "Success!"]);
        }
        self::returnJson(["status" => false, "msg" => "Something went wrong with the server!"]);
    }

    #[NoReturn]
    public function resetPasswordPost()
    {
        $decodedData = json_decode($_POST["resetPassword"]);
        $emailOrUsername = $this->e_u($decodedData->e_u ?? "");
        $res = $this->db->find([$emailOrUsername["key"] => $emailOrUsername["item"]]);

        if ($res == null) {
            self::returnJson(["status" => false, "msg" => "Email / Username not found"]);
        }

        $reset_id = bin2hex(random_bytes(60));

        $this->handleMsg($reset_id, $res["email"], $res['_id']);
    }

    #[NoReturn]
    public function handleMsg(string $reset_id, string $email, string $userID)
    {
        $this->setDb($_ENV['DB_NAME'], 'tokens');
        $this->db->insert(
            [
                "token" => $reset_id,
                "user_id" => new ObjectId($userID),
                "for" => "resetPassword",
                "created_at" => new UTCDateTime(strtotime('now') * 1000)
            ]
        );

        $link = "http://localhost/ProjectPapa/resetPassword?reset_id=" . $reset_id;
        $text = "Please reset your password with this link. \n\n $link \n\n Not You? Ignore this email or send a feedback to us";
        $html = $this->generateEmailHTML("Reset Password", $link);
        $mail = new Mailer($_ENV["MAILER_DSN"]);

        $result = $mail->sendMail($_ENV["MAIL_FROM"], $email, "Reset Password", $text, $html);
        self::returnJson($result);
    }

    private function generateEmailHTML($title, $link = "http://localhost/"): string
    {
        $styles = "<style>@import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap');.container{margin-left:auto;margin-right:auto;max-width:80vw}.title{font-weight:900;font-size:2rem;text-decoration:dotted underline #41c9ff;color:#376fff;margin-bottom:30px}.text{font-family:\"Libre Baskerville\",\"Palatino Linotype\",Palatino,\"Century Schoolbook L\",\"Times New Roman\",serif}a{text-decoration:none;color:#00bfff}a:hover{text-decoration:underline deepskyblue}</style>";
        return "<!doctype html><html lang=\"en\"><head> <meta charset=\"UTF-8\"> <meta name=\"viewport\" content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\"> <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\"> <title>$title</title>$styles</head><body> <div class=\"container\"> <div class=\"title\">$title</div><div class=\"text\"> If you have not requested to change your password for your account on ProjectPapa, please ignore this email or you can email us regarding this issue. <br/> <br>If you have requested to change your password, please click on this link to reset your password. <br><br><a href=\"$link\" target=\"_blank\">$link</a> </div></div></body></html>";
    }
}