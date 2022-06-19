<?php
//$obj = json_decode('{"email":"vodresurti@vusra.com","did_you_mean":"","user":"vodresurti","domain":"vusra.com","format_valid":true,"mx_found":true,"smtp_check":true,"catch_all":false,"role":false,"disposable":false,"free":false,"score":0.8}');",
//echo $obj->disposable == false ? "false" : "true";
//$arr = [false, true, false];
//$attr = ["issetAndNotEmpty", "email", "length" => "256"];
//$name = "Email";
//
//$contents = "";
//$min = 0;
//$max = 300;
//var_dump(strlen($contents) <= $max && strlen($contents) > $min);
//$database = new MD("ProjectPapa", "users");

// Dynamic route testing
//$test_values = array(
//    "/hello/" => "/hello/",
//    "/8990dasd" => "jasdknad",
//    "/test" => "/test",
//    "/movie/{id}/" => "/movie/400/",
//    "/shop/{ids}/{nameofshop}" => "/shop/409094/name/"
//);
//
//foreach ($test_values as $test_value => $test_valued) {
//    if ($test_value == $test_valued) {
//        continue;
//    }
//    $pattern = preg_replace('/\/{(.*?)}/', "(.*?)", $test_value);
//    $regex = boolval(preg_match_all('#^' . $pattern . "$#", $test_valued, $matches, PREG_OFFSET_CAPTURE));
//    echo "\n\n\n$test_valued";
//    print_r($matches);
//}

use app\db\MongoDatabase;
use Dotenv\Dotenv;
use MongoDB\BSON\ObjectId;

require_once "../../vendor/autoload.php";
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

//$router = new \app\router\Router();
//$qs = $router->processQueryString("hello=world&test=hi&this");
//var_dump($qs);

# Hashing passwords what the fuck (I didn't expect this bug)
//$authUtilty = new app\auth\authUtility();
//$password = password_hash("hello world", PASSWORD_BCRYPT);
//echo $password . PHP_EOL;
//echo $authUtilty->sanitise($password);

# Update document
$db = new MongoDatabase($_ENV["DB_NAME"], $_ENV["USER_TABLE"]);
$id = new ObjectId("62a7127a4199b36dd70515e2");
$result = $db->update(["_id" => $id], ["status" => "online"]);
echo "Matched count: ";
var_dump($result->getMatchedCount());
echo "Modified count: ";
var_dump($result->getModifiedCount());