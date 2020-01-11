<?php
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $first = $_POST['first'];
  $last = $_POST['last'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $confirm = md5($_POST['confirm']);
  $role = $_POST['role'];

  $result = $account->registerAccount($email, $first, $last, $role, $password, $confirm);

  if ($result === 1) {
    $user = new User($email);
    $userID = $user->getID();

    $data = array(
      "success" => $result,
      "userID"  => $userID
    );
  } else {
    $data = array(
      "success" => $result
    );
  }

  echo json_encode($data);
}