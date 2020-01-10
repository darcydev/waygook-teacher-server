<?php
// GET DATA FORM REQUEST
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userEmail = $_POST['userEmail'];

  $user = new User($userEmail);
  $userID = $user->getID();

  $result = $user->getAllContacts();

  echo json_encode($result);

  /*   if ($result->rowCount() > 0) {
    $conversations = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      // extract($row);

      $conversation_item = [
        "messageID" => $row['messageID']
      ];

      array_push($conversations, $conversation_item);
    }

    echo json_encode($conversations);
  } else {
    //IF THERE IS NO POST IN OUR DATABASE
    echo json_encode(['message' => 'No post found']);
  } */
}