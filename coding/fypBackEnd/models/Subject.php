<?php
class Subject{
    public $subjectID, $lecturerID , $subjectName, $date, $time;

    private $lecturer;
    private $classList;

    public function setLecturer($l){
        $this->lecturer = $l;
    }

    public function getLecturer(){
        return $this->lecturer;
    }

    public function getSubId(){
        return $this->subjectID;
    }
    public function getSubName(){
        return $this->subjectName;
    }

    /*
    public function getDate(){
        return $this->date;
    }
    public function getTime(){
        return $this->time;
        }**/


}

?>
