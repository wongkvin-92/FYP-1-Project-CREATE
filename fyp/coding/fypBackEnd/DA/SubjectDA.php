<?php

  class SubjectDA extends DataAccessObject{
    public function __construct($con){
        parent::__construct($con, "Subject");
    }

    //returns the user by email
    function fetchSubjectById($id){
      $result = $this->con->query("SELECT * FROM subject WHERE subjectID='$id'");      
      return $result->fetch_object('Subject');
    }

  }

 ?>
