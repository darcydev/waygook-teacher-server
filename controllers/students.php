<?php
header("Content-Type: application/json");

require("../index.php");

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $result = $search->getStudents();

  $students = [];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $student_item = [
      "userID" => $row['userID'],
      "first_name" => $row['first_name'],
      "profile_pic" => $row['profile_pic'],
      "nationality" => $row['nationality'],
      "gender" => $row['gender'],
      "DOB" => $row['DOB'],
      "rate" => $row['rate']
    ];

    array_push($students, $student_item);
  }

  echo json_encode($students);
}