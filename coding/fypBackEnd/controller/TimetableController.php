<?php

class TimetableController extends MasterController{
    private $con;
    public function __construct($con){
	$this->con = $con;
    }

    public function getDailySchedule($date){
	$classDA = new ClassLessonDA($this->con);
	$venues = $classDA->getAllVenues();


	$classes = $classDA->getScheduleForDate($date);
	
	$obj = [];
	$obj['venues'] = $venues;
	$obj['classes'] = $classes;
	$this->returnObject($obj);
    }

    public function getAllVenues(){
	$classDA = new ClassLessonDA($this->con);
	$venues = $classDA->getAllVenues();
	$this->returnObject($venues);
    }
}

?>
