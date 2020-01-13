<?php

class Employment
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

  public function getEmployment($userID1, $userID2)
  {
    $sql = "SELECT * FROM Employments
    WHERE (teacher_id = ? AND student_id = ?)
    OR (teacher_id = ? AND student_id = ?)";
    $stmt = $this->db->run($sql, [$userID1, $userID2, $userID2, $userID1]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getConversation($userID1, $userID2)
  {
    $sql = "SELECT * FROM Messages WHERE (to_user_id = ? AND from_user_id = ?) OR (to_user_id = ? AND from_user_id = ?)";
    $stmt = $this->db->run($sql, [$userID1, $userID2, $userID2, $userID1]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /* INSERTING */

  public function createEmployment($studentID, $teacherID, $rate)
  {
    $sql = "INSERT INTO Employments
    VALUES (
        employmentID,
        :teacher_id,
        :student_id,
        :prepaid_amount,
        :rate
    )";
    $stmt = $this->db->run($sql, [
      ':teacher_id' => $teacherID,
      ':student_id' => $studentID,
      ':prepaid_amount' => 0,
      ':rate' => $rate
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createLesson($datetime, $duration, $studentID, $teacherID)
  {
    $employment = $this->getEmployment($studentID, $teacherID);

    $gross = $employment['rate'];
    $total = ($gross / 60) * $duration;
    $fee = 0.5;
    $net = $gross - $fee;

    // validate Employment has a sufficient prepaid balance for this lesson
    $this->validateBalance($employment, $total);

    if (empty($this->errorArray)) {
      $sql = "INSERT INTO Lessons
                VALUES (lessonID, ?, ?, ?, ?, ?, NULL, ?, ?, ?, DEFAULT, DEFAULT)";
      $stmt = $this->db->run($sql, [
        $employment['employmentID'],
        $teacherID,
        $studentID,
        $datetime,
        $duration,
        $gross,
        $fee,
        $net
      ]);

      if ($stmt->rowCount() === 1) {
        $this->decreaseEmploymentBalance($employment['employmentID'], $total);
        return true;
      } else {
        return false;
      }
    }
  }

  public function createMessage($text, $fromUserID, $toUserID)
  {
    $sql = "INSERT INTO Messages VALUES (messageID, ?, ?, ?, ?)";
    $stmt = $this->db->run($sql, [$fromUserID, $toUserID, $text, date("Y-m-d H:i:s")]);
    return ($stmt->rowCount() === 1) ? true : false;
  }

  /* DELETING */

  /* UPDATING */

  // occurs when a student makes a deposit for a particular Employment
  public function increaseEmploymentBalance($employmentID, $amount)
  {
    $sql = "UPDATE Employments SET prepaid_amount = prepaid_amount + ? WHERE employmentID = ?";
    $stmt = $this->db->run($sql, [$amount, $employmentID]);
    return ($stmt->rowCount() === 1) ? true : false;
  }

  // occurs when a student confirms a lesson for a particular Employment
  public function decreaseEmploymentBalance($employmentID, $amount)
  {
    $sql = "UPDATE Employments SET prepaid_amount = prepaid_amount - ? WHERE employmentID = ?";
    $stmt = $this->db->run($sql, [$amount, $employmentID]);
    return ($stmt->rowCount() === 1) ? true : false;
  }

  public function cancelLesson($lessonID)
  {
    // TODO: validate that the lesson hasn't already been confirmed

    $sql = "UPDATE Lessons SET cancelled = 1 WHERE lessonID = ?";
    $stmt = $this->db->run($sql, [$lessonID]);
    return ($stmt->rowCount() === 1) ? true : false;
  }

  public function confirmLesson($lessonID)
  {
    // TODO: validate that the lesson is at a past date (can't confirm future lessons)

    $sql = "UPDATE Lessons SET confirmed = 1 WHERE lessonID = ?";
    $stmt = $this->db->run($sql, [$lessonID]);
    return ($stmt->rowCount() === 1) ? true : false;
  }

  /* VALIDATION */

  // validate Employment has sufficient balance for a specific lesson
  private function validateBalance($employmentObject, $amount)
  {
    if ($amount > $employmentObject['prepaid_amount']) {
      array_push($this->errorArray, Constants::$invalidBalanceForLesson);
    }
    return;
  }
}