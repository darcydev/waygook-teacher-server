<?php

$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $thisUserEmail = $_POST['fromUser'];
  $otherUserID = $_POST['toUser'];

  $user = new User($thisUserEmail);

  $result = $user->getConversation($otherUserID);

  echo json_encode($result);
}