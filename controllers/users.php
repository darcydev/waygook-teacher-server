<?php
$sql = "SELECT userID, first_name, profile_pic, nationality, gender, DOB, rate
        FROM Users WHERE role='teacher'";
$stmt = $db->run($sql);

if ($stmt->rowCount() > 0) {
  $users = [];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $user_item = [
      "userID" => $row['userID'],
      "first_name" => $row['first_name'],
      "profile_pic" => $row['profile_pic'],
      "nationality" => $row['nationality'],
      "gender" => $row['gender'],
      "DOB" => $row['DOB'],
      "rate" => $row['rate']
    ];

    array_push($users, $user_item);
  }


  echo json_encode($users);
} else {
  //IF THERE IS NO POST IN OUR DATABASE
  echo json_encode(['message' => 'No post found']);
}