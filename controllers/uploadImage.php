<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data");

require("../index.php");
$_FILES = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userID = $_POST['userID'];

  $name = $_FILES['name'];

  // random number between 0 and 100,000
  $random = mt_rand(0, 100000);

  $name = $_POST['name'];
  $extension = $_POST['type'];
  $size = $_POST['size'];

  $DB_NAME = "/" . $name . $random;
  $targetDir = "C:/Users/Darcy/Projects/Waygook-Teacher/waygook-teacher-client/public/images/profile_pics";
  $uploadPath = $targetDir . $DB_NAME;

  $rowsAffected = $account->updateImage($userID, $uploadPath);

  if ($rowsAffected === true) {
    $successUpload = move_uploaded_file($DB_NAME, $uploadPath);

    if ($successUpload) {
      $success = true;
    } else {
      $success = false;
      $message = 'Failed move_uploaded_file to:' . $uploadPath;
    }
  } else {
    $success = false;
    $message = 'Failed insert into db';
  }

  $data = ["success" => $success, "message" => $message];
  echo json_encode($data);
}