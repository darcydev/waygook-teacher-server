<?php

class Profile
{
  protected $db;
  protected $data;

  private $errorArray;

  public function __construct()
  {
    $this->db = MyPDO::instance();
    $this->errorArray = array();
  }

  public function getError($error)
  {
    if (!in_array($error, $this->errorArray)) {
      $error = "";
    }
    return "<span class='errorMessage'>$error</span>";
  }

  /* FETCHING */

  public function getUserID($email)
  {
    $sql = "SELECT userID FROM Users WHERE email = ?";
    $stmt = $this->db->run($sql, [$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getProfile($userID)
  {
    $sql = "SELECT * FROM Users WHERE userID = ?";
    $stmt = $this->db->run($sql, [$userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getConversations($userID)
  {
    $sql = "SELECT * FROM  `Messages` a
    INNER JOIN (
        SELECT MAX(  `messageID` ) AS id
        FROM  `Messages` AS  `alt`
        WHERE  `alt`.`to_user_id` = ?
        OR  `alt`.`from_user_id` = ?
        GROUP BY  least(`to_user_id` ,  `from_user_id`), greatest(`to_user_id` ,  `from_user_id`)
    ) b ON a.messageID = b.id";
    $stmt = $this->db->run($sql, [$userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getEmployments($userID)
  {
    $sql = "SELECT * FROM Employments WHERE teacher_id = ? OR student_id = ? ORDER BY prepaid_amount DESC";
    $stmt = $this->db->run($sql, [$userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getLessons($userID)
  {
    $sql = "SELECT * FROM Lessons WHERE teacher_id = ? OR student_id = ? ORDER BY datetime DESC";
    $stmt = $this->db->run($sql, [$userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getConfirmedLessons($userID)
  {
    $sql = "SELECT * FROM Lessons WHERE confirmed = ? AND (teacher_id = ? OR student_id = ?) ORDER BY datetime DESC";
    $stmt = $this->db->run($sql, [1, $userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUnconfirmedLessons($userID)
  {
    $sql = "SELECT * FROM Lessons WHERE confirmed = ? AND (teacher_id = ? OR student_id = ?) ORDER BY datetime DESC";
    $stmt = $this->db->run($sql, [0, $userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getReviews($userID)
  {
    $sql = "SELECT * FROM Reviews WHERE teacher_id = ? OR student_id = ?";
    $stmt = $this->db->run($sql, [$userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /* INSERTING */

  /* UPDATING */

  /* DELETING */

  /* VALIDATING */
}