<?php
header("Access-Control-Allow-Origin: *"); // TODO insecure
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userID = $_POST['userID'];

  $result = $profile->getProfile($userID);

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
}