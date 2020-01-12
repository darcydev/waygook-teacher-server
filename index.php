<?php
/* TODO change this for security reasons */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER["REQUEST_METHOD"];

require_once("config/config.php");
require_once("models/Account.php");
require_once("models/User.php");
require_once("models/Employment.php");
require_once("models/MyPDO.php");
require_once("models/Constants.php");

$account = new Account();
$db = MyPDO::instance();