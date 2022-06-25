<?php

namespace main\controllers;

use app\auth\authUtility;
use app\db\MongoDatabase;
use app\views\Views;
use Dotenv\Dotenv;
use JetBrains\PhpStorm\NoReturn;

class ProfileController
{
    private Views $views;
    private static array $profileInfo = ["email", "username", "date_created", "date_modified"];
    private static array $userInfo = ["name", "profile_img", "bio"];
    private static array $editableInfo = ["name", /*"profile_img",*/
        "bio", "email", "username"];

    public function __construct()
    {
        $this->start();
    }

    private function start(): void
    {
        $dir = dirname(__DIR__) . "\\";
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->load();
        $this->views = new Views($_ENV["ROOT"], $dir);
    }

    public function get(): void
    {
        $user_data = $this->processSession();
        $this->views->render("profile", $user_data);
    }

    public function edit(): void
    {
        $user_data = $this->processSession();
        $this->views->render("edit_profile", $user_data);
    }

    private function processSession(): array
    {
        $returns = [];
        foreach ($_SESSION as $key => $value) {
            if (in_array($key, self::$profileInfo) || in_array($key, self::$userInfo)) {
                $returns[$key] = $value;
            }
        }
        return $returns;
    }

    public static function run(array $data = []): void
    {
        if ($data === []) {
            $data = $_POST;
        }
        $data = authUtility::removeImmutable($data, self::$editableInfo);
        $sessionData = [...$_SESSION, ...$_SESSION["user_info"]];
        $modifiedData = authUtility::returnModifiedData($data, $sessionData);
        if (!empty($modifiedData)) {
            self::handleModifiedData($modifiedData);
        }
        authUtility::returnJson(["msg" => "No data is modified", "status" => false]);
    }

    #[NoReturn]
    public static function handleModifiedData(array $modifiedData): void
    {
        $bioValidation = ["notEmpty", ["length", "max" => 1000]];
        $registerController = new RegisterController(dirname(__DIR__) . "/");
        $db = new MongoDatabase($_ENV["DB_NAME"], $_ENV["USER_TABLE"]);
        $data = $registerController->validate($modifiedData, ["bio" => $bioValidation], false);

        self::checkUnique($data, $db); // Exits if an error is found
        $old_profile = $db->find(["_id" => $_SESSION["_id"]]);
        $result = $db->update(["_id" => $_SESSION["_id"]], self::categoriseData($data, (array)$old_profile));
        $new_profile = $db->find(["_id" => $_SESSION["_id"]]);
        authUtility::updateSession((array)$new_profile, true);
        MongoDatabase::checkUpdatedStatus($result); // Exits automatically
    }

    private static function checkUnique(array $data, MongoDatabase $database): void
    {
        foreach ($data as $key => $datum) {
            if ($key === "username") {
                $checkUnique = $database->find(["username" => $datum]) === null;
                if (!$checkUnique) {
                    authUtility::returnJson(["status" => false, "msg" => "Username is not unique"]);
                }
            }
            if ($key === "email") {
                $checkUnique = $database->find(["email" => $datum]) === null;
                if (!$checkUnique) {
                    authUtility::returnJson(["status" => false, "msg" => "Email is not unique"]);
                }
            }
        }
    }

    private static function categoriseData(array $data, array $old_profile = []): array
    {
        unset($old_profile["_id"]);
        $returns = $old_profile;
        foreach ($data as $key => $datum) {
            switch ($key) {
                case "username":
                    $returns["username"] = $datum;
                    break;
                case "email":
                    $returns["email"] = $datum;
                    break;
                case "name":
                    $returns["user_info"]["name"] = $datum;
                    break;
                case "bio":
                    $returns["user_info"]["bio"] = $datum;
                    break;
                default:
                    break;
            }
        }
        return $returns;
    }
}