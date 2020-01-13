<?php
header("Content-Type: application/json");

require("../index.php");

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $result = $search->getTeachers();

  $teachers = [];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $teacher_item = [
      "userID" => $row['userID'],
      "first_name" => $row['first_name'],
      "profile_pic" => $row['profile_pic'],
      "nationality" => $row['nationality'],
      "gender" => $row['gender'],
      "DOB" => $row['DOB'],
      "rate" => $row['rate']
    ];

    array_push($teachers, $teacher_item);
  }

  echo json_encode($teachers);
}