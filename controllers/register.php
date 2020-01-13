<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $first = $_POST['first'];
  $last = $_POST['last'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $confirm = md5($_POST['confirm']);
  $role = $_POST['role'];

  $result = $account->register($email, $first, $last, $role, $password, $confirm);

  if ($result === true) {
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