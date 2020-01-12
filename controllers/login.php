<?php
require("../NEWindex.php");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  // $result = $account->loginAccount($email, $password);

  $count = "SELECT COUNT(*) FROM Users WHERE email = ? AND password = ?";
  $query = $db->run($count, [$email, $password]);

  if ($query->fetchColumn() === 1) {
    $result = true;
  } else {
    $result = false;
  }

  /*   $sql = "SELECT email, password FROM users
				WHERE email = ? AND password = ?";
  $query = $db->run($sql, [$em, $pw]); */


  if ($result === true) {
    $user = new User($email);
    $userID = $user->getID();

    $data = array(
      "success" => $result,
      "userID"  => $userID
    );
  } else {
    $data = array(
      "success" => false
    );
  }

  echo json_encode($data);
}