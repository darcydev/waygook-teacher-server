<?php
echo 10;

$role = "student";

echo $role;

require_once("config/config.php");
require_once("models/Account.php");
require_once("models/MyPDO.php");

$db = MyPDO::instance();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");



/* $sql = "SELECT userID, first_name, profile_pic
        FROM Users
        WHERE role = ?";
$stmt = $db->run($sql, [$role]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo $users; */