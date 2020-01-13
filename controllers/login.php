<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $query = $account->login($email, $password);

  if ($query === true) {
    $userID = $profile->getUserID($email);

    $data = array(
      "success" => true,
      "userID"  => $userID
    );
  } else {
    $data = array(
      "success" => false
    );
  }

  echo json_encode($data);
}