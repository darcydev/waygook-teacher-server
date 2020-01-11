<?php
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userEmail = $_POST['userEmail'];

  $user = new User($userEmail);
  $userID = $user->getID();

  $stmt = $user->getAllContacts();

  $conversations = [];
  $otherUsers = [];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['to_user_id'] === $userID) $otherUserID = $row['from_user_id'];
    else $otherUserID = $row['to_user_id'];

    /* TODO: limit the data extracted in the query */
    $otherUser = $user->getOtherUser($otherUserID);
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