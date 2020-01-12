<?php
require("../index.php");
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userEmail = 'kim_subin@gmail.com';

  $user = new User($userEmail);
  $userID = $user->getID();

  $result = [];

  if (isset($_POST['DOB'])) {
    $rowCount = $user->updateUserDetails('DOB', $_POST['DOB'], $userID);

    if ($rowCount === 1) {
      array_push($result, ["DOBSuccess" => true]);
    } else {
      array_push($result, ["DOBSuccess" => false]);
    }
  }

  if (isset($_POST['timezone'])) {
    $rowCount = $user->updateUserDetails('timezone', $_POST['timezone'], $userID);

    if ($rowCount === 1) {
      array_push($result, ["timezoneSuccess" => true]);
    } else {
      array_push($result, ["timezoneSuccess" => false]);
    }
  }

  if (isset($_POST['nationality'])) {
    $rowCount = $user->updateUserDetails('nationality', $_POST['nationality'], $userID);

    if ($rowCount === 1) {
      array_push($result, ["nationalitySuccess" => true]);
    } else {
      array_push($result, ["nationalitySuccess" => false]);
    }
  }

  echo json_encode($result);
}