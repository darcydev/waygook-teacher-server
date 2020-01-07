<?php
require_once("config/config.php");
require_once("models/Account.php");
require_once("models/MyPDO.php");

$account = new Account();

// TODO change this when live
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// TODO what does this do?
$rest_json = file_get_contents("php://input");
// TODO what does this do?
$_POST = json_decode($rest_json, true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  // verification is done on frontend do I need to do it again?
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = $account->loginAccount($email, $password);

  if ($result == true) {
    $_SESSION['userEmail'] = $email;
  }


  if ($result == false) : ?>
{
"status": "fail",
"error": "Sorry, there was an error!!!"
}
<?php endif; ?>

<?php if ($result == true) : ?>

{
"status": "success",
"message": "Your data was successfully submitted"
}
<?php endif;
}