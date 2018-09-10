<?php

/**
 *
 **/
class ClassLessonDA extends DataAccessObject{
    public function __construct($con){
        parent::__construct($con, "ClassLesson");
        $this->setTableName("class_lesson");
        $this->setPrimaryKey("classID");
    }

    public function getAllVenues(){
    	$q = "select DISTINCT(venue) from class_lesson;";
    	$result =$this->con->query($q);
    	$arr = [];
    	while($o = $result->fetch_array(MYSQLI_NUM)){
    	    $arr []=$o[0];
	    }
	    return $arr;
    }

    public function getScheduleForDate($inDate){
        $dateTime = new DateTime($inDate);
      	$date = $dateTime->format("Y-m-d");
      	$query = "select * from class_lesson where dateTime BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59';";
      	$result = $this->con->query($query);
      	$arr = [];

      	while($o = $result->fetch_object("ClassLesson")){
      	    $d['start'] = $o->getStartTime()->format("Y-m-d H:i:00");
      	    $d['end']   = $o->getEndTime()->format("Y-m-d H:i:00");
      	    $d['venue']   = $o->venue;
      	    $d['code'] = $o->subjectID;
      	    $arr []= $d;
	      }
	      return $arr;
    }

    public function fetchLessonById($id){
          $result = $this->con->query("SELECT * FROM class_lesson WHERE classID='$id'");
          return $result->fetch_object('ClassLesson');
    }

    public function getClassesOnDate($venue, $inDate){
        $dateTime = new DateTime($inDate);
      	$date = $dateTime->format("Y-m-d");
      	$query = "select * from class_lesson where dateTime BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59' AND venue = '{$venue}';";
      	$result = $this->con->query($query);
      	$arr = [];
      	while($o = $result->fetch_object("ClassLesson")){
      	    $arr []= $o;
  	}
	   return $arr;
	    //return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getClassesBySubject(Subject $s){
        $fk = $s->getSubId();
        return  $this->getListByAttribute('id', $fk);
    }

    public function fetchClassById($id){
        $result = $this->con->query("SELECT * FROM class_lesson WHERE classID = '{$id}';");
        return $result->fetch_object('ClassLesson');
    }

    public function getLinkedClassById($id){
        $q = "SELECT * FROM class_lesson, subject, room WHERE classID = '{$id}' AND roomID = room.roomID AND subjectID = subject.subjectID;";
        $result = $this->con->query($q);
        //$o = [];

        // $result->fetch_object('');
        //while($obj = $result->fetch_object('ClassLesson')){
        //$subjectDA =
        //}
        return $result;
    }

    public function numExistingClasses($lesson){
    	$code = $lesson->subjectID;
    	$type = $lesson->type;
    	$q = "SELECT count(*) FROM `class_lesson` WHERE subjectID = \"{$code}\" and type = \"{$type}\" ";
    	$result = $this->con->query($q);
    	return $result->fetch_array()[0];
    }

    public function getLessonByLecturer($lecturerID){
      $query = <<<EOF

      SELECT cl.classID,
      cl.type as "type",
      DAYNAME(CAST(cl.dateTime as DATE)) as "day",
      cl.duration as "duration",
      subj.subjectName as "subName",
      cl.subjectID as "title",
      cl.venue as "venue"
      FROM `class_lesson`	as cl
      INNER JOIN `subject` as subj ON cl.subjectID = subj.subjectID
      WHERE subj.lecturerID = $lecturerID

EOF;
    return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonByLecturerWithDetail($lecturerID){
      $query = <<<EOF

      SELECT cl.classID,
      cl.type as "type",
      DAYNAME(CAST(cl.dateTime as DATE)) as "day",
      cl.dateTime as dateTime,
      cl.duration as "duration",
      subj.subjectName as "subName",
      cl.subjectID as "title",
      cl.venue as "venue"
      FROM `class_lesson`	as cl
      INNER JOIN `subject` as subj ON cl.subjectID = subj.subjectID
      WHERE subj.lecturerID = $lecturerID

EOF;
    return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }

    public function save($o){
    	$ret = parent::save($o);
    	$o->classID = $this->con->insert_id;
    	return $ret;
    }

}

?>
