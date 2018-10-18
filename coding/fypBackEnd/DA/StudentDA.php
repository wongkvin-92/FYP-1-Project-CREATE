<?php

  class StudentDA extends DataAccessObject {
    public function __construct($con){
        //if($con == null)
        //throw new \Exception("LecturerDA: No connection received");
        //$this->con = $con;
        parent::__construct($con, "Student");
    }

    //returns the user by email
    function fetchStudentById($id){
      $result = $this->con->query("SELECT * FROM student WHERE studentID='$id'");

      return $result->fetch_object('Student');
    }

    function fetchStudentByEmail($email){
      $result = $this->con->query("SELECT * FROM student WHERE studentEmail ='$email'");

      return $result->fetch_object('Student');
    }


      function fetchLecturerByName($name){
      $result = $this->con->query("SELECT * FROM student WHERE studentName='$name'");

      return $result->fetch_object('Student');
    }

  }

 ?>
