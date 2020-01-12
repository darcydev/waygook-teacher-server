<?php

class User
{
  protected $db;
  protected $data;
  private $errorArray;
  private $userLoggedIn;

  private $userID;
  private $first_name;
  private $last_name;
  private $email;
  private $profile_pic;
  private $description;

  /* REFACTOR THIS SO IT DOESN'T REQURE $userEmail? */
  public function __construct($userEmail)
  {
    $this->db = MyPDO::instance();
    $this->errorArray = array();
    // REFACTOR: do I have to pass '$userLoggedIn' in here, or can I
    // simply use $_SESSION['userLogggedIn'] here?
    $this->userLoggedIn = $userEmail;

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $this->db->run($sql, [$userEmail]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->userID = $row['userID'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->email = $row['email'];
    $this->profile_pic = $row['profile_pic'];
    $this->description = $row['description'];
  }

  public function getError($error)
  {
    if (!in_array($error, $this->errorArray)) {
      $error = "";
    }
    return "<span class='errorMessage'>$error</span>";
  }

  public function getID()
  {
    return $this->userID;
  }

  public function getUserIDs()
  {
    // create an array that holds all userIDs
    // $array = array();
    $sql = "SELECT userID FROM Users";
    $stmt = $this->db->run($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
  }

  public function getFirstName()
  {
    return $this->first_name;
  }

  public function getLastName()
  {
    return $this->last_name;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getPhoto()
  {
    return $this->profile_pic;
  }

  public function getDescription()
  {
    return $this->description;
  }

  // extracts only the (relevant?) details -- may need to extract more!
  public function getOtherUser($id)
  {
    $sql = "SELECT userID, first_name, last_name, email, profile_pic, nationality, timezone 
          FROM Users 
          WHERE userID = ?";
    $stmt = $this->db->run($sql, [$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // fetch all Employments (w/ prepaid balance > 0.15) associated with userLoggedIn
  public function getEmployments()
  {
    $sql = "SELECT * FROM Employments
      WHERE teacher_id = ?
      OR student_id = ?
      AND prepaid_amount > 0.15
      ORDER BY prepaid_amount DESC";
    $stmt = $this->db->run($sql, [$this->userID, $this->userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // fetch all Lessons associated with userLoggedIn
  public function getLessons()
  {
    $sql = "SELECT * FROM Lessons
      WHERE teacher_id = ?
      OR student_id = ?
      ORDER BY datetime DESC";
    $stmt = $this->db->run($sql, [$this->userID, $this->userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUnconfirmedLessons($id)
  {
    $sql = "SELECT * FROM Lessons
      WHERE confirmed = ?
      AND (teacher_id = ? OR student_id = ?)
      ORDER BY datetime DESC";
    $stmt = $this->db->run($sql, [0, $id, $id]);
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $lessons;
  }

  public function getReviews($id)
  {
    // fetch all reviews associated with User
    $sql = "SELECT * FROM Reviews
      WHERE teacher_id = ?
      OR student_id = ?";
    $stmt = $this->db->run($sql, [$id, $id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $reviews;
  }

  private function validateDescription($desc)
  {
    // check description length
    if (strlen($desc) > 5000) {
      array_push($this->errorArray, Constants::$descriptionCharacters);
      return;
    }
  }

  public function uploadProfilePic($uploadPath, $userID)
  {
    // check file name is unique
    if (file_exists($uploadPath)) {
      array_push($this->errorArray, Constants::$invalidFileName);
    } else {
      return $this->updateUserDetails('profile_pic', $uploadPath, $userID);
    }
  }

  private function validateProfilePic($extension, $size, $uploadPath)
  {
    // check file extension
    if (!in_array($extension, ['jpeg', 'jpg', 'png'])) {
      array_push($this->errorArray, Constants::$invalidFileExtension);
      return;
    }
    // check file size
    if ($size > 2000000) {
      array_push($this->errorArray, Constants::$invalidFileSize);
      return;
    }
    // check file name
    if (file_exists($uploadPath)) {
      array_push($this->errorArray, Constants::$invalidFileName);
    }
  }

  public function updatePassword($old_pw, $new_pw, $new_pw2)
  {
    $this->validateOldPassword($old_pw);
    $this->validatePasswords($new_pw, $new_pw2);

    /* TODO: REMOVED -- test if can delete
       if (empty($this->errorArray) == true) {
      $sql = "UPDATE Users
                    SET password = ?
                    WHERE userID = ?";
      $stmt = $this->db->run($sql, [$new_pw, $this->userID]);
      $rowsAffected = $stmt->rowCount();
      return $rowsAffected;
    } */
    $this->updateUserDetails('password', $new_pw);
  }

  private function validateOldPassword($pw)
  {
    // check password correct
    $sql = "SELECT userID, password FROM Users WHERE userID = ? AND password = ?";
    $query = $this->db->run($sql, [$this->userID, $pw]);
    // BUG: "rowCount()" is not for a SELECT query (https://stackoverflow.com/questions/40355262/pdo-rowcount-only-returning-one)
    if ($query->rowCount() == 1) {
      return true;
    } else {
      array_push($this->errorArray, Constants::$passwordIncorrect);
      return false;
    }
  }

  private function validatePasswords($pw, $pw2)
  {
    // check passwords match
    if ($pw != $pw2) {
      array_push($this->errorArray, Constants::$passwordsDoNoMatch);
      return;
    }
    // check password alphanumberic
    if (preg_match('/[^A-Za-z0-9]/', $pw)) {
      array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
      return;
    }
    // check password length
    if (strlen($pw) > 50 || strlen($pw) < 5) {
      array_push($this->errorArray, Constants::$passwordCharacters);
      return;
    }
  }

  public function insertMessage($message_text, $to_user_id)
  {
    if (empty($this->errorArray) == true) {
      $sql = "INSERT INTO Messages VALUES (messageID, ?, ?, ?, ?)";
      $stmt = $this->db->run($sql, [
        $this->userID, // from_user_id
        $to_user_id, // to_user_id
        $message_text, // message_content
        date("Y-m-d H:i:s") // date
      ]);
      return $stmt->rowCount();
    }
  }

  /* A generic function to update all User details in the DB
  The function takes two params: 
    ** the name of the column to be updated,
    ** the value of that column
   */
  public function updateUserDetails($columnName, $columnValue, $userID)
  {
    // TODO: how to validate each different column
    if (empty($this->errorArray) == true) {
      $sql = "UPDATE Users SET $columnName = ? WHERE userID = ?";
      $stmt = $this->db->run($sql, [$columnValue, $userID]);
      return $stmt->rowCount();
    }
  }

  /* INBOX: CONVERSATION & MESSAGES */

  // fetchAll of most recent conversations involving userLoggedIn
  public function getAllContacts()
  {
    $sql = "SELECT * FROM  `Messages` a
        INNER JOIN (
            SELECT MAX(  `messageID` ) AS id
            FROM  `Messages` AS  `alt`
            WHERE  `alt`.`to_user_id` = ?
            OR  `alt`.`from_user_id` = ?
            GROUP BY  least(`to_user_id` ,  `from_user_id`), greatest(`to_user_id` ,  `from_user_id`)
        ) b ON a.messageID = b.id";
    return $this->db->run($sql, [$this->userID, $this->userID]);
  }

  // fetch conversation between two specific Users
  public function getConversation($otherUserID)
  {
    $sql = "SELECT * FROM Messages
      WHERE (to_user_id = ? AND from_user_id = ?)
      OR (to_user_id = ? AND from_user_id = ?)";
    $stmt = $this->db->run($sql, [$otherUserID, $this->userID, $this->userID, $otherUserID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}