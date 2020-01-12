<?php
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userID = $_POST['userID'];

  $sql = "SELECT * FROM Users WHERE userID = ? LIMIT 1";
  $stmt = $db->run($sql, [$userID]);

  if ($stmt->rowCount() === 1) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $user = [
      "userID" => $row['userID'],
      "first_name" => $row['first_name'],
      "last_name" => $row['last_name'],
      "email" => $row['email'],
      "profile_pic" => $row['profile_pic'],
      "description" => $row['description'],
      "role" => $row['role'],
      "gender" => $row['gender'],
      "nationality" => $row['nationality'],
      "education_level" => ucfirst($row['education_level']),
      "education_major" => $row['education_major'],
      "DOB" => $row['DOB'],
      "rate" => $row['rate'],
      "skype_name" => $row['skype_name'],
      "lesson_hours" => $row['lesson_hours'],
      "rating" => $row['rating'],
      "timezone" => $row['timezone']
    ];

    echo json_encode($user);
  } else {
    //IF THERE IS NO PROFILE IN OUR DATABASE
    echo json_encode(['message' => 'No profile found!', 'test' => $userID]);
  }
}