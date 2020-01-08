<?php
require_once("config/config.php");
require_once("models/Account.php");
require_once("models/User.php");
require_once("models/Employment.php");
require_once("models/MyPDO.php");
require_once("models/Constants.php");

$account = new Account();

require_once("controllers/login.php");
require_once("controllers/users.php");

// set bool for whether User is logged in or not
$isLoggedIn = isset($_SESSION['userEmail']) ? true : false;

if ($isLoggedIn) {

  $user = new User($_SESSION['userEmail']);
  $employment = new Employment($user->getID());

  /* PDO VARIABLE */
  $db = MyPDO::instance();

  /* FETCH USER FROM DB */
  $userLoggedInRow = $user->getOtherUser($user->getID());

  // set bool for whether User is student or not
  /* $isStudent = $userLoggedInRow['role'] == 'student' ? true : false; */

  /* INCLUDE FILES */
  /*   require_once("src/controllers/sendMessage.php");
  require_once("src/controllers/scheduleLesson.php");
  require_once("src/views/modals/scheduleLesson.php");
  require_once("src/views/modals/sendMessage.php");
  require_once("src/views/modals/confirmAction.php"); */
} else {
  // if the User isn't logged in, redirect them to index.php
  // TODO: not working (infinite redirects)
  // header("Location: http://waygookteacher.com");
}