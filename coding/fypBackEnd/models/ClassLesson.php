<?php

/**
 * This model handles all the
 * tutorial and lecture session
 * associated with a class.
 **/
class ClassLesson{
    public $classID, $dateTime, $roomID, $duration, $type;
    public $venue;
    public $subjectID;

    public function getDateTime(){
        return $this->dateTime;
    }

    public function getDate(){
	     return $this->getStartTime()->format("Y-m-d");
    }

    public function getStrStartTime(){
	     return $this->getStartTime()->format("h:i");
    }

    public function getStrEndTime(){
	     return $this->getEndTime()->format("h:i");
    }

    public function getRoomID(){
        return $this->roomID;
    }

    public function getDuration(){
	     return $this->duration;
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

    public function getStartTime(){
	     return new DateTime($this->getDateTime(), new DateTimeZone("Asia/Kuala_Lumpur"));
    }

    public function isClashing($lesson){
    	$lessonComesBefore = $lesson->getStartTime() < $this->getEndTime();

    	if($lessonComesBefore){
    	    $test = $lesson->getEndTime() > $this->getStartTime();
    	}else{
    	    $test = $this->getEndTime() > $lesson->getStartTime();
    	}
    	return $test;
    }

    public function getEndTime(){
    	$date = new DateTime($this->getDateTime(), new DateTimeZone("Asia/Kuala_Lumpur"));
    	 $hour = $this->duration;

    	$hm = explode(".", $this->getDuration());

    	$hour = $hm[0];
    	if(isset($hm[1]))
    	    $min = $hm[1];
    	else
    	    $min = 0;
    	if($min == 5)
    	    $min = 30;

    	$date->add(new DateInterval("PT{$hour}H{$min}M"));
    	return $date;
    }

}
?>
