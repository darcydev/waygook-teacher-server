<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $thisUserID = $_POST['userID'];
  $otherUserID = $_POST['otherUserID'];

  $thisUser = $profile->getProfile($thisUserID);
  $otherUser = $profile->getProfile($otherUserID);
  $conversation = $employment->getConversation($thisUserID, $otherUserID);

  $result = array(
    "conversation" => $conversation,
    "thisUser" => $thisUser,
    "otherUser"  => $otherUser
  );

  echo json_encode($result);
}