<?php
require("../index.php");
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $text = $_POST['text'];
  $fromUserEmail = $_POST['fromUserEmail'];
  $toUserID = $_POST['toUserID'];

  $user = new User($fromUserEmail);
  $userID = $user->getID();

  $result = $user->insertMessage($text, $toUserID);

  $data = array(
    "success" => $result
  );

  echo json_encode($data);
}