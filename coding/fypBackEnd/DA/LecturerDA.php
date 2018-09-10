<?php

  class LecturerDA extends DataAccessObject {
    public function __construct($con){
        //if($con == null)
        //throw new \Exception("LecturerDA: No connection received");
        //$this->con = $con;
        parent::__construct($con, "Lecturer");
    }

    //returns the user by email
    function fetchLecturerById($id){
      $result = $this->con->query("SELECT * FROM lecturer WHERE lecturerID='$id'");

      return $result->fetch_object('Lecturer');
    }

    function fetchLecturerByEmail($email){
      $result = $this->con->query("SELECT * FROM lecturer WHERE lecturerEmail ='$email'");

      return $result->fetch_object('Lecturer');
    }


      function fetchLecturerByName($name){
      $result = $this->con->query("SELECT * FROM lecturer WHERE lecturerName='$name'");

      return $result->fetch_object('Lecturer');
    }

  }

 ?>
