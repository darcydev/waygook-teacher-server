<?php

class Employment
{
    protected $db;
    protected $data;
    private $errorArray;
    private $userLoggedInID;

    public function __construct($userLoggedInID)
    {
        $this->db = MyPDO::instance();
        $this->errorArray = array();
        $this->userLoggedInID = $userLoggedInID;
    }

    public function getError($error)
    {
        if (!in_array($error, $this->errorArray)) {
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }

    // fetch a particular Employment between two Users
    public function getEmployment($userLoggedInID, $other_user)
    {
        $sql = "SELECT * FROM Employments
            WHERE (teacher_id = ? AND student_id = ?)
            OR (teacher_id = ? AND student_id = ?)";
        $stmt = $this->db->run($sql, [$userLoggedInID, $other_user, $other_user, $userLoggedInID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    /* ---------- LESSONS ---------- */
    public function scheduleLesson($date, $datetime, $duration, $studentID, $teacherID)
    {
        $emp = $this->getEmployment($studentID, $teacherID);

        $teacherRate = $emp['rate'];
        // TODO: calculate dynamically (% of $teacherRate rounded down to nearest whole number -- circa 15%)
        $waygookRate = 4.0;
        $teacherPayment = $teacherRate - $waygookRate;
        $lessonTotal = ($teacherRate / 60) * $duration;

        // validate that the Employment has sufficient prepaid balance for the lesson
        $this->validateBalance($emp, $lessonTotal);
        // valid that the date is within a certain range
        $this->validateDate($date);

        if (empty($this->errorArray)) {
            // TODO: change the SQL query to make it more clear format (ie :teacherID, :studentID, etc.)
            $sql = "INSERT INTO Lessons
                VALUES (lessonID, ?, ?, ?, ?, ?, NULL, ?, ?, ?, DEFAULT, DEFAULT)";
            $stmt = $this->db->run($sql, [
                $emp['employmentID'],
                $teacherID,
                $studentID,
                $datetime,
                $duration,
                $teacherRate,
                $waygookRate,
                $teacherPayment
            ]);

            // return the returned value from decreaseAmount if rows affected = 1, else return 0
            return ($stmt->rowCount()) ? $this->decreaseAmount($emp['employmentID'], $lessonTotal) : 0;
        }
    }
    public function cancelLesson($lessonID)
    {
        // TODO: validate that the lesson hasn't already been confirmed

        $sql = "UPDATE Lessons
            SET cancelled = 1
            WHERE lessonID = ?";
        $stmt = $this->db->run($sql, [$lessonID]);
        return $stmt->rowCount();
    }
    public function confirmLesson($lessonID)
    {
        // TODO: validate that the lesson is at a past date (can't confirm future lessons)

        $sql = "UPDATE Lessons
            SET confirmed = 1
            WHERE lessonID = ?";
        $stmt = $this->db->run($sql, [$lessonID]);
        return $stmt->rowCount();
    }
    /* ---------- \.LESSONS ---------- */

    // REPLACED: updateEmploymentAmount()
    // increase prepaid_amount for specific Employment
    // occurs when User makes a deposit
    public function increaseAmount($employmentID, $amount)
    {
        $sql = "UPDATE Employments
            SET prepaid_amount = prepaid_amount + ?
            WHERE employmentID = ?";
        $stmt = $this->db->run($sql, [$amount, $employmentID]);
        return $stmt->rowCount();
    }

    // decrease prepaid_amount for specific Employment 
    // occurs when User schedules a lesson
    public function decreaseAmount($employmentID, $amount)
    {
        $sql = "UPDATE Employments
            SET prepaid_amount = prepaid_amount - ?
            WHERE employmentID = ?";
        $stmt = $this->db->run($sql, [$amount, $employmentID]);
        return $stmt->rowCount();
    }

    /* ---------- VALIDATION ---------- */
    // validate that the lesson date is within a certain range
    private function validateDate($date)
    {
        // FIXME: this seems alot easier: https://stackoverflow.com/questions/19070116/php-check-if-date-between-two-dates
        // get today's date
        $today = date('Y-m-d');
        // convert today's date to time and add 60 days
        $max_date = strtotime('60 day', strtotime($today));
        // convert back to date
        $max_date = date('Y-m-d', $max_date);
        // convert today's date to time and minus 10 days
        $min_date = strtotime('-10 day', strtotime($today));
        // convert back to time
        $min_date = date('Y-m-d', $min_date);

        // if the date entered is not within the valid date range, push to $errorArray
        if (!($date > $min_date && $date < $max_date)) {
            array_push($this->errorArray, Constants::$invalidLessonDate);
            return;
        }
    }

    // validate that Employment has sufficient balance for specific lesson
    private function validateBalance($emp, $lessonTotal)
    {
        if ($lessonTotal > $emp['prepaid_amount']) {
            array_push($this->errorArray, Constants::$invalidBalanceForLesson);
            return;
        }
    }
    /* ---------- \.VALIDATION ---------- */
}