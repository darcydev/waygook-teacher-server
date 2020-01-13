<?php
header("Access-Control-Allow-Origin: *"); // TODO insecure
header("Access-Control-Allow-Headers: Content-Type");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER["REQUEST_METHOD"];

require_once("config/config.php");
require_once("models/Account.php");
require_once("models/User.php");
require_once("models/Employment.php");
require_once("models/MyPDO.php");
require_once("models/Constants.php");

require_once("models/newUsers.php");

$account = new Account();
$user = new newUsers();
$db = MyPDO::instance();