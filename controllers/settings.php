<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  // get this from the cookie
  $userID = $_POST['userID'];

  $result = [];

  if (isset($_POST['DOB'])) {
    $rowCount = $account->updateUserDetails($userID, 'DOB', $_POST['DOB']);

    if ($rowCount === 1) {
      array_push($result, ["DOBSuccess" => true]);
    } else {
      array_push($result, ["DOBSuccess" => false]);
    }
  }

  if (isset($_POST['timezone'])) {
    $rowCount = $account->updateUserDetails($userID, 'timezone', $_POST['timezone']);

    if ($rowCount === 1) {
      array_push($result, ["timezoneSuccess" => true]);
    } else {
      array_push($result, ["timezoneSuccess" => false]);
    }
  }

  if (isset($_POST['nationality'])) {
    $rowCount = $account->updateUserDetails($userID, 'nationality', $_POST['nationality']);

    if ($rowCount === 1) {
      array_push($result, ["nationalitySuccess" => true]);
    } else {
      array_push($result, ["nationalitySuccess" => false]);
    }
  }

  echo json_encode($result);
}