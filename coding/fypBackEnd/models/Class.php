<?php

/**
 * This model handles all the 
 * tutorial and lecture session
 * associated with a class.
 **/
class ClassLesson{
    public $id, $date, $time, $roomID;
    public $subjectID;
    
    public function getDate(){
        return $this->date;
    }

    public function getTime(){
        return $this->time;
    }

    public function getRoomID(){
        return $this->roomID;
    }
    
}
?>
