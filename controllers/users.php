<?php
require("../index.php");

header("Content-Type: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);

$sql = "SELECT userID, first_name, profile_pic, nationality, gender, DOB, rate
        FROM Users WHERE role='teacher'";
$stmt = $db->run($sql);

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