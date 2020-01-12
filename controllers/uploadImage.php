<?php
require("../index.php");
header("Content-Type: multipart/form-data");
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userEmail = 'kim_subin@gmail.com';
  $user = new User($userEmail);
  $userID = $user->getID();

  $uid = $_POST['uid'];
  $name = $_POST['name'];
  $extension = $_POST['type'];
  $size = $_POST['size'];

  // TODO: do the verification on extension & size

  $DB_NAME = $uid . $name;
  $targetDir = 'C:\Users\Darcy\Projects\Waygook-Teacher\waygook-teacher-client\build\images\profiles';
  $uploadPath = $targetDir . '\\' . $DB_NAME;

  echo $uploadPath;

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