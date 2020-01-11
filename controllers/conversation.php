<?php

$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $thisUserEmail = $_POST['fromUser'];
  $otherUserID = $_POST['toUser'];

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