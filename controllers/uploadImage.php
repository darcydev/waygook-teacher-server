<?php
header("Content-Type: multipart/form-data");

require("../index.php");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  // get this from the cookie
  $userID = $_POST['userID'];

  $uid = $_POST['uid'];
  $name = $_POST['name'];
  $extension = $_POST['type'];
  $size = $_POST['size'];

  $DB_NAME = $uid . $name;
  $targetDir = 'C:\Users\Darcy\Projects\Waygook-Teacher\waygook-teacher-client\build\images\profiles\\';
  $uploadPath = $targetDir . $DB_NAME;

  $rowsAffected = $user->uploadProfilePic($uploadPath, $userID);

  if ($rowsAffected === 1) {
    $successUpload = move_uploaded_file($DB_NAME, $uploadPath);

    if ($successUpload) {
      $success = true;
    } else {
      $success = false;
      $message = 'Failed move_uploaded_file';
    }
  } else {
    $success = false;
    $message = 'Failed insert into db';
  }

  $data = ["success" => $success, "message" => $message];
  echo json_encode($data);
}