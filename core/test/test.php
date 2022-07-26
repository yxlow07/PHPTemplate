<?php
namespace App\Controller;
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
use app\views\Views;
use Dotenv\Dotenv;
use main\controllers\ShopController;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;


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
//$db = new MongoDatabase($_ENV["DB_NAME"], $_ENV["USER_TABLE"]);
//$id = new ObjectId("62a7127a4199b36dd70515e2");
//$result = $db->update(["_id" => $id], ["status" => "online"]);
//echo "Matched count: ";
//var_dump($result->getMatchedCount());
//echo "Modified count: ";
//var_dump($result->getModifiedCount());

# Random bytes
//echo bin2hex(random_bytes(30));

# Insert items -> shop
$items = [
    "series_name" => "Seirei Gensouki",
    "book_name" => "The Dragon's Familiar",
    "volume" => 21,
    "author" => "Kitayama Yuri",
    "publisher" => "HJ Bunko by Japan",
    "price" => "781yen",
    "synopsis" => "",
    "illustrations" => [

    ],
    "star_rating" => random_int(1, 5),
    "publication_date" => new \MongoDB\BSON\UTCDateTime(strtotime('now') * 1000),
    "language" => "Japanese",
    "genres" => "adventure//romance//isekai//supernatural"
];

# Table for reset password tokens
$db = new MongoDatabase($_ENV["DB_NAME"], "tokens");
$token = [
    "token" => bin2hex(random_bytes(60)),
    "user_id" => new ObjectId("62c1445e7e77e6e1df03f865"),
    "for" => "testing",
    "created_at" => new UTCDateTime(strtotime('now') * 1000)
];

//$db->insert($token);
//
//$find = $db->find([]);
///**
// * @var $time UTCDateTime
// */
//$time = $find["created_at"];

//print_r(($time->toDateTime()->format("h:i:s A d M Y")));

//$newdb = new MongoDatabase($_ENV["DB_NAME"], "users");
//$res = $newdb->find(['status' => 'online']);
//$lc = new LoginController(new Views());
//$lc->handleMsg("hello", "mhmm", $res['_id']);

$shopC = new ShopController(new Views());
echo $shopC->generateImagesTemplate(['1.webp', '2.webp']);
