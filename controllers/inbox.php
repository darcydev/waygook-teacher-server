<?php
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $userID = $_POST['userID'];

  $stmt = $profile->getContacts($userID);

  $conversations = [];
  $otherUsers = [];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['to_user_id'] === $userID) $otherUserID = $row['from_user_id'];
    else $otherUserID = $row['to_user_id'];

    $otherUser = $profile->getProfile($otherUserID);
    $conversationRow = $row;

    array_push($otherUsers, $otherUser);
    array_push($conversations, $conversationRow);
  }

  $result = array(
    "conversations" => $conversations,
    "otherUsers" => $otherUsers
  );

  echo json_encode($result);
}