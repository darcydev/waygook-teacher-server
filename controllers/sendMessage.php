<?php
// GET DATA FORM REQUEST
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $text = $_POST['message'];
  $fromUserEmail = $_POST['fromUser'];

  $user = new User($fromUserEmail);
  $userID = $user->getID();

  $result = $user->insertMessage($text, 20);

  $data = array(
    "success" => $result
  );

  echo json_encode($data);
}