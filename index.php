<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $uri = explode('/', $uri);
$requestMethod = $_SERVER["REQUEST_METHOD"];

//echo $uri;
// login.php
// users.php
// profile/{userID}.php
//echo $requestMethod;

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

if ($uri === '/users.php') {
  require_once("controllers/users.php");
}

if ($uri === '/profile.php') {
  require_once("controllers/profile.php");
}

if ($uri === '/sendMessage.php') {
  require_once("controllers/sendMessage.php");
}

// set bool for whether User is logged in or not
$isLoggedIn = isset($_SESSION['userEmail']) ? true : false;

if ($isLoggedIn) {

  $user = new User($_SESSION['userEmail']);
  $employment = new Employment($user->getID());

  /* FETCH USER FROM DB */
  $userLoggedInRow = $user->getOtherUser($user->getID());
}