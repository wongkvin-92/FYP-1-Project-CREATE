<?php
  class ClassReschedulingDA extends DataAccessObject{
    //creating sql object outside, to inject inside (create, remove, update..)
    public function __construct($con){
      if($con == null)
        throw new \Exception("ClassReschedulingDA: No connection received");
      $this->con = $con;
      $this->setPrimaryKey("id");
      $this->setTableName("class_rescheduling");
    }

    public function getPendingRequest(){
      $retrieveData = $this->con->query("SELECT * FROM class_rescheduling WHERE status ='pending'");

      $arr = [];
      if($retrieveData != false){
                  // request empty constructor    //from models
        $obj = null;
        while($obj = $retrieveData->fetch_object('ClassRescheduling')){
          //Connecting ClassRescheduling model with Subject model
          $subjectDA = new SubjectDA($this->con);
          $subject = $subjectDA->fetchSubjectById($obj->getSubjectID());
          if($subject == null)
            throw new \Exception("Corrupt data. Invalid subject id {$obj->getSubjectID()}");

          $obj->setSubject($subject);

          //Connecting Subject model with Lecturer model
          $lecturerDA = new LecturerDA($this->con);
          $lecturer = $lecturerDA->fetchLecturerById($subject->lecturerID);
            if($lecturer == null)
              throw new \Exception("Corrupt data. Invalid lecturer id {$subject->lecturerID}");
          $subject->setLecturer($lecturer);


          array_push($arr, $obj);
        }
        return $arr;
      }
      return false;
    }

    public function getApprovalRequest($id){
      $result = $this->con->query("SELECT * FROM class_rescheduling WHERE id = '$id';");
                                        //from models
      return $result->fetch_object('ClassRescheduling');

    }

      /*public function save($o){
      $result = $this->con->query("UPDATE class_rescheduling SET status = '{$o->getStatus()}', newDateTime = '{$o->getNewDateTime()}' WHERE id = '{$o->getId()}' ");

      return $result === true;
      }*/
  }
 ?>
