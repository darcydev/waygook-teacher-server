<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $text = $_POST['text'];
  $fromUserID = $_POST['fromUser'];
  $toUserID = $_POST['toUser'];

  $result = $employment->createMessage($text, $fromUserID, $toUserID);

  if ($result === true) {
    $data = [
      "success" => true
    ];
  } else {
    $data = [
      "success" => false,
      "message" => "Failed to create the message in the database"
    ];
  }

  echo json_encode($data);
}