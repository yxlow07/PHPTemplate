<?php
// Loading utility classes
// Note: Utility files are loaded before classes
include_once dirname(__DIR__, 2) . "/vendor/autoload.php";
include_once "autoload.php";

date_default_timezone_set("Asia/Kuala_Lumpur");
session_start();

$root_dir = "http://localhost/ProjectPapa/";