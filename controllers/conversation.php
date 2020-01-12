<?php
require("../index.php");
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $thisUserEmail = $_POST['thisUserEmail'];
  $otherUserID = $_POST['otherUserID'];

  $user = new User($thisUserEmail);

  $conversation = $user->getConversation($otherUserID);
  $thisUser = $user->getOtherUser($user->getID());
  $otherUser = $user->getOtherUser($otherUserID);

  $result = array(
    "conversation" => $conversation,
    "thisUser" => $thisUser,
    "otherUser"  => $otherUser
  );

  echo json_encode($result);
}