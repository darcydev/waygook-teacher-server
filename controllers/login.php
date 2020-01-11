<?php
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $result = $account->loginAccount($email, $password);

  if ($result == true) {
    $user = new User($email);
    $userID = $user->getID();
  }

  $data = array(
    "success" => $result,
    "userID"  => $userID
  );

  echo json_encode($data);
}