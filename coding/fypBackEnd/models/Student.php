<?php
class Student{
  public $studentID, $studentName, $studentEmail, $studentPassword;

  public function getStudentId(){
    return $this->studentID;
  }

  public function getStudentName(){
    return $this->studentName;
  }
  public function getStudentEmail(){
    return $this->studentEmail;
  }
  public function getPass(){
    return $this->studentPassword;
  }

}

?>
