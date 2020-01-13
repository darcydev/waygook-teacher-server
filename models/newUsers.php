<?php

class newUsers
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

  // fetch all teachers
  public function getTeachers()
  {
    $sql = "SELECT userID, first_name, profile_pic, nationality, gender, DOB, rate
          FROM Users WHERE role='teacher'";
    $stmt = $this->db->run($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}