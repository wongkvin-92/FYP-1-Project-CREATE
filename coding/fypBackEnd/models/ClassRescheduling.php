<?php
class ClassRescheduling{
  public $id;
  public $newDateTime;
  public $status;
  public $classID;
  public $Venue;
  public $oldDateTime;

    private $lesson;
    private $subject;



  //serialize only accept default constructor
  public function __construct(){}

  /*public function __construct($nDT){
    //pending by default
    $this->status = "pending";
    $this->newDateTime = $nDT;
  }*/

    public function getVenue(){
        return $this->newVenue;
    }

    public function setVenue($v){
        $this->newVenue = $v;
    }

    public function getLesson(){
      return $this->lesson;
    }

    public function getClassID(){
      return $this->classID;
    }

     public function getNewDateTime(){
       return $this->newDateTime;
     }
     public function setNewDateTime($date, $time){
       $this->newDateTime = date('Y-m-d H:i:s', strtotime("$date $time"));
     }
     public function oldDateTime(){
       return $this->oldDateTime;
     }

     public function getStatus(){
       return $this->status;
     }

     //getter
     public function isApproved(){
       return $this->status == "approved";
     }
     public function setLesson($les){
       $this->lesson = $les;
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
