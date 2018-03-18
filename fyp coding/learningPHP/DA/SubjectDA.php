<?php

  class SubjectDA {
    private $con;
    public function __construct($con){
      if($con == null)
        throw new \Exception("SubjectDA: No connection received");
      $this->con = $con;
    }

    //returns the user by email
    function fetchSubjectById($id){
      $result = $this->con->query("SELECT * FROM subject WHERE subjectID='$id'");

      return $result->fetch_object('Subject');
    }

  }

 ?>
