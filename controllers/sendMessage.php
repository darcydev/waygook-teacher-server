<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $text = $_POST['text'];
  // send this from cookies
  $fromUserID = $_POST['fromUserID'];
  $toUserID = $_POST['toUserID'];
  /* $fromUserEmail = $_POST['fromUserEmail']; */
  /*   $user = new User($fromUserEmail);
  $userID = $user->getID(); */

  $result = $employment->createMessage($text, $fromUserID, $toUserID);

  if ($result === true) {
    $success = true;
  } else {
    $success = false;
    $message = "Failed to create the message in the database";
  }

  $data = array(
    "success" => $success,
    "message" => $message
  );

  echo json_encode($data);
}