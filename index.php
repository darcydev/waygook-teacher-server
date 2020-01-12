<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($uri === '/uploadImage.php') {
  header("Content-Type: multipart/form-data");
} else {
  header("Content-Type: application/json");
}

require_once("config/config.php");
require_once("models/Account.php");
require_once("models/User.php");
require_once("models/Employment.php");
require_once("models/MyPDO.php");
require_once("models/Constants.php");

$account = new Account();
$db = MyPDO::instance();

if ($uri === '/login.php') {
  require_once("controllers/login.php");
}

if ($uri === '/register.php') {
  require_once("controllers/register.php");
}

if ($uri === '/users.php') {
  require_once("controllers/users.php");
}

if ($uri === '/profile.php') {
  require_once("controllers/profile.php");
}

if ($uri === '/conversation.php') {
  require_once("controllers/conversation.php");
}

if ($uri === '/sendMessage.php') {
  require_once("controllers/sendMessage.php");
}

if ($uri === '/inbox.php') {
  require_once("controllers/inbox.php");
}

if ($uri === '/settings.php') {
  require_once("controllers/settings.php");
}

if ($uri === '/uploadImage.php') {
  require_once("controllers/uploadImage.php");
}

// set bool for whether User is logged in or not
$isLoggedIn = isset($_SESSION['userEmail']) ? true : false;