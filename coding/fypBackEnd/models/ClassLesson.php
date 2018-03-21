<?php

/**
 * This model handles all the
 * tutorial and lecture session
 * associated with a class.
 **/
class ClassLesson{
    public $classID, $dateTime, $roomID;
    public $subjectID;

    public function getDateTime(){
        return $this->dateTime;
    }

    public function getRoomID(){
        return $this->roomID;
    }

    public function setNewDateTime($date, $time){
        $this->dateTime = date('Y-m-d H:i:s', strtotime("$date $time"));
    }

}
?>
