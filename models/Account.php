<?php

class Account
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

  public function login($email, $password)
  {
    $sql = "SELECT COUNT(*) FROM Users WHERE email = ? AND password = ?";
    $stmt = $this->db->run($sql, [$email, $password]);
    return ($stmt->rowCount() === 1) ? true : false;
  }

  /* INSERTING */

  public function register($em, $fn, $ln, $role, $pw, $pw2)
  {
    $this->validateEmail($em);
    $this->validateConfirmPassword($pw, $pw2);

    if (empty($this->errorArray)) {
      $sql = "INSERT INTO Users
					VALUES (
						userID,
						:first_name,
						:last_name,
						:email,
						:password,
						DEFAULT,
						NULL,
						:role,
						NULL, NULL, NULL, NULL,
						NULL, NULL, NULL, NULL,
						NULL, NULL, NULL, NULL
					)";
      $stmt = $this->db->run($sql, [
        ':first_name' => $fn,
        ':last_name' => $ln,
        ':email' => $em,
        ':password' => $pw,
        ':role' => $role
      ]);

      return ($stmt->rowCount() === 1) ? true : false;
    } else {
      return false;
    }
  }

  /* UPDATING */

  // generic function to update any User column
  public function updateUserDetails($userID, $columnName, $columnValue)
  {
    if (empty($this->errorArray) == true) {
      $sql = "UPDATE Users SET $columnName = ? WHERE userID = ?";
      $stmt = $this->db->run($sql, [$columnValue, $userID]);
      return ($stmt->rowCount() === 1) ? true : false;
    }
  }

  public function updatePassword($userID, $oldPassword, $newPassword, $confirmPassword)
  {
    $this->validateOldPassword($userID, $oldPassword);
    $this->validateConfirmPassword($newPassword, $confirmPassword);

    if (empty($this->errorArray)) {
      $this->updateUserDetails($userID, 'password', $newPassword);
    }
  }

  public function updateImage($userID, $uploadPath)
  {
    $this->validateImageNameUnique($uploadPath);

    if (empty($this->errorArray)) {
      return $this->updateUserDetails($userID, 'profile_pic', $uploadPath);
    }
  }

  /* DELETING */

  /* VALIDATION */

  private function validateEmail($em)
  {
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      array_push($this->errorArray, Constants::$emailInvalid);
      return;
    }
    // check email unique
    $sql = "SELECT email FROM Users WHERE email = ?";
    $stmt = $this->db->run($sql, [$em]);
    $rowsAffected = $stmt->rowCount();
    if ($rowsAffected != 0) {
      array_push($this->errorArray, Constants::$emailTaken);
      return;
    }
  }

  // validate the old password is correct
  private function validateOldPassword($userID, $password)
  {
    $sql = "SELECT COUNT(*) FROM Users WHERE userID = ? AND password = ?";
    $query = $this->db->run($sql, [$userID, $password]);

    if ($query->rowCount() !== 1) {
      array_push($this->errorArray, Constants::$passwordIncorrect);
    }

    return;
  }

  private function validateConfirmPassword($password1, $password2)
  {
    if ($password1 != $password2) {
      array_push($this->errorArray, Constants::$passwordsDoNoMatch);
      return;
    }
  }

  private function validateImageNameUnique($uploadPath)
  {
    if (file_exists($uploadPath)) {
      array_push($this->errorArray, Constants::$invalidFileName);
    }
    return;
  }
}