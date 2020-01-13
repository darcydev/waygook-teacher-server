<?php
require("../index.php");
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $query = $account->loginAccount($email, $password);

  if ($query->fetchColumn() === 1) {
    $user = new User($email);
    $userID = $user->getID();

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