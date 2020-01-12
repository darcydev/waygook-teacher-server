<?php
require("../index.php");
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $first = $_POST['first'];
  $last = $_POST['last'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $confirm = md5($_POST['confirm']);
  $role = $_POST['role'];

  $rowCount = $account->registerAccount($email, $first, $last, $role, $password, $confirm);

  if ($rowCount === 1) {
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