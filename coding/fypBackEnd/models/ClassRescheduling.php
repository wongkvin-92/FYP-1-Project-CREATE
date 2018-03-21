<?php
class ClassRescheduling{
  public $id;
  public $newDateTime;
  public $status;
  public $subjectID;

  private $subject;



  //serialize only accept default constructor
  public function __construct(){}

  /*public function __construct($nDT){
    //pending by default
    $this->status = "pending";
    $this->newDateTime = $nDT;
  }*/

  public function getSubject(){
    return $this->subject;
  }

  public function getSubjectID(){
    return $this->subjectID;
  }

   public function getNewDateTime(){
     return $this->newDateTime;
   }
   public function setNewDateTime($date, $time){
     $this->newDateTime = date('Y-m-d H:i:s', strtotime("$date $time"));
   }

   public function getStatus(){
     return $this->status;
   }

   //getter
   public function isApproved(){
     return $this->status == "approved";
   }
   public function setSubject($sub){
     $this->subject = $sub;
   }
   //setter
   public function approve(){
      $this->status = "approved";
   }
   // there is no disapproval
   public function disapprove(){
     $this->status = "disapproved";
   }

   public function getId(){
     return $this->id;
   }
}
 ?>
