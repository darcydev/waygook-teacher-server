<?php
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $userEmail = 'kim_subin@gmail.com';
  $user = new User($userEmail);
  $userID = $user->getID();

  $uid = $_POST['uid'];
  $name = $_POST['name'];
  $extension = $_POST['type'];
  $size = $_POST['size'];

  $DB_NAME = $name . $uid;

  $targetDir = "/public/images/profiles/";
  $uploadPath = $_SERVER['DOCUMENT_ROOT'] . $targetDir . basename($DB_NAME);
  $db_uploadPath = $targetDir . $DB_NAME;

  $rowsAffected = $user->updateProfilePic($db_uploadPath, $extension, $size, $uploadPath, $userID);

  if ($rowsAffected === 1) {
    $successUpload = move_uploaded_file($DB_NAME, $uploadPath);

    $data = array(
      "success" => true
    );
  } else {
    $data = array(
      "success" => false
    );
  }
  echo json_encode($data);
}