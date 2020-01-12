<?php
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $timezone = $_POST['timezone'];
  $userEmail = 'kim_subin@gmail.com';

  $user = new User($userEmail);
  $userID = $user->getID();

  $result = $user->updateUserDetails('timezone', $timezone, $userID);

  if ($result === 1) {
    $data = array(
      "success" => true,
      "timezone" => true
    );
  } else {
    $data = array(
      "success" => false
    );
  }
  echo json_encode($data);
}