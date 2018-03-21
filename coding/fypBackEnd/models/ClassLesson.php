<?php

/**
 * This model handles all the
 * tutorial and lecture session
 * associated with a class.
 **/
class ClassLesson{
    public $classID, $dateTime, $roomID, $duration, $type;
    public $subjectID;

    public function getDateTime(){
        return $this->dateTime;
    }

    public function getRoomID(){
        return $this->roomID;
    }

    public function setSubject($subjectID){
      $this->subjectID = $subjectID;
    }

    public function setRoom($roomID){
      $this->roomID = $roomID;
    }
    public function setDuration($duration){
      $this->duration = $duration;
    }
    public function setType($type){
      $this->type = $type;
    }
    public function setDateTime($date, $time){
        $this->dateTime = date('Y-m-d H:i:s', strtotime("$date $time"));
    }

}
?>
