<?php

class Search
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

  public function getTeachers()
  {
    $sql = "SELECT userID, first_name, profile_pic, nationality, gender, DOB, rate FROM Users WHERE role='teacher'";
    $stmt = $this->db->run($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getStudents()
  {
    $sql = "SELECT userID, first_name, profile_pic, nationality, gender, DOB, rate FROM Users WHERE role='student'";
    $stmt = $this->db->run($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getKoreans()
  {
    $sql = "SELECT userID, first_name, profile_pic, nationality, gender, DOB, rate FROM Users WHERE nationality='Korean'";
    $stmt = $this->db->run($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /* INSERTING */

  /* UPDATING */

  /* DELETING */

  /* VALIDATION */
}