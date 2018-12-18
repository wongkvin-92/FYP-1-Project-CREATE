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
      subj.subjectName as "subjectName",
      cl.subjectID as "title",
      cl.venue as "venue"
      FROM `class_lesson`	as cl
      INNER JOIN `subject` as subj ON cl.subjectID = subj.subjectID
      WHERE subj.lecturerID = $lecturerID

EOF;
	return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }

    public function getLecturerScheduleByDate($lecturerID, $dayname, $sqlDate){
	//TODO: CHeck for class cancellation and remove.
	$sql = <<<EOF
      SELECT  cl.classID,
		cl.type,
        cl.subjectID,
        subj.subjectName as "subjectName"
        , CAST(cl.dateTime AS TIME) as startTime
        , CAST( date_add(cl.dateTime, INTERVAL cl.duration HOUR) AS TIME) as endTime,
        (clre.oldDateTime is not NULL) as "isCancelled",
        "{$sqlDate}" as curDate
       FROM `class_lesson` as cl
        INNER JOIN `subject` as subj
       		ON cl.subjectID = subj.subjectID
        LEFT JOIN `class_rescheduling` as clre
         	ON clre.classID = cl.classID AND clre.oldDateTime = "{$sqlDate}"
        WHERE subj.lecturerID = '{$lecturerID}'
        and DAYNAME(cl.dateTime) = "{$dayname}"
        ;
EOF;

	return $this->con->query($sql)
                    ->fetch_all(MYSQLI_ASSOC);

    }

    public function getStudentScheduleByDate($date, $dayname, $subjectList){
    	for($i=0; $i< count($subjectList); $i++){
    	    $subjectList[$i] = "'{$subjectList[$i]}'";
    	}
    	$subjStr = implode(", ", $subjectList);
    	$subjStr = "({$subjStr})";
	$query = <<<EOF
        SELECT  cl.classID,
      		cl.type,
              cl.subjectID,
              subj.subjectName as "subjectName"
              , CAST(cl.dateTime AS TIME) as startTime
              , CAST( date_add(cl.dateTime, INTERVAL cl.duration HOUR) AS TIME) as endTime,
              (clre.oldDateTime is not NULL) as "isCancelled",
              "$date" as curDate
             FROM `class_lesson` as cl
              INNER JOIN `subject` as subj
             		ON cl.subjectID = subj.subjectID
              LEFT JOIN `class_rescheduling` as clre
               	ON clre.classID = cl.classID AND clre.oldDateTime = "$date"
              WHERE subj.subjectID in {$subjStr}
              and DAYNAME(cl.dateTime) = "$dayname"
EOF;

	return $this->con->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonByLecturerWithDetail($lecturerID){
	$query = <<<EOF
      SELECT cl.classID,
      cl.type as "type",
      DAYNAME(CAST(cl.dateTime as DATE)) as "day",
      cl.dateTime as dateTime,
      cl.duration as "duration",
      subj.subjectName as "name",
      cl.subjectID as "title",
      cl.venue as "venue"
	FROM `class_lesson`	as cl
	INNER JOIN `subject` as subj ON cl.subjectID = subj.subjectID
	WHERE subj.lecturerID = $lecturerID

EOF;
	return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }
    //todo : change this query
    public function getEntireScheduleHash($subjectList){
	for($i=0; $i< count($subjectList); $i++){
            $subjectList[$i] = "'{$subjectList[$i]}'";
	}
	$subjStr = implode(",", $subjectList);
    	$subjStr = "({$subjStr})";
	$query = <<<EOF
SELECT MD5(GROUP_CONCAT(CONCAT(classID, subjectID, type, startTime, endTime, newVenue, status))) as "key"  FROM (SELECT
          cr.classID,
          cl.type,
          cl.subjectID,
          subj.subjectName,
          CAST( (case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end) as TIME) as startTime,
          CAST( date_add((case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end), INTERVAL cl.duration HOUR) AS TIME) as endTime,
          DAYNAME((case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end)) as "day",
          cr.status,
          CAST(cr.newDateTime as DATE) as newDateTime,
      	cr.newVenue,
      	CAST(cr.oldDateTime as DATE) as oldDateTime
      FROM class_rescheduling as cr
      JOIN class_lesson as cl
        ON	cr.classID = cl.classID
      INNER JOIN subject as subj
      	ON subj.subjectID = cl.subjectID
      WHERE subj.subjectID IN {$subjStr}

      UNION

      SELECT
      	cl.classID,
          cl.type,
          cl.subjectID,
          subj.subjectName,
          CAST(cl.dateTime as Time ) as startTime,
      	CAST( date_add((cl.dateTime), INTERVAL cl.duration HOUR) AS TIME) as endTime,
          DAYNAME(cl.dateTime) as "day",
          ('NULL') as status,
          ('NULL') as newDateTime,
      	venue as newVenue,
      	("NULL") as oldDateTime
      FROM class_lesson as cl
      INNER JOIN subject as subj
      	ON subj.subjectID = cl.subjectID
      WHERE subj.subjectID IN {$subjStr}) as t1
EOF;
	$result = $this->con->query($query);
	if($result == false)
	    throw new \Exception($subjStr. " - " .$this->con->error);
	return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEntireSchedule($subjectList){
	for($i=0; $i< count($subjectList); $i++){
            $subjectList[$i] = "'{$subjectList[$i]}'";
	}
	$subjStr = implode(", ", $subjectList);
	$subjStr = "({$subjStr})";
	$query = <<<EOF
      SELECT
          cr.classID,
          cl.type,
          cl.subjectID,
          subj.subjectName,
          CAST( (case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end) as TIME) as startTime,
          CAST( date_add((case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end), INTERVAL cl.duration HOUR) AS TIME) as endTime,
          DAYNAME((case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end)) as "day",
          cr.status,
          CAST(cr.newDateTime as DATE) as newDateTime,
      	cr.newVenue,
      	CAST(cr.oldDateTime as DATE) as oldDateTime
      FROM `class_rescheduling` as cr
      JOIN `class_lesson` as cl
        ON	cr.classID = cl.classID
      INNER JOIN `subject` as subj
      	ON subj.subjectID = cl.subjectID
      WHERE subj.subjectID IN {$subjStr}

      UNION

      SELECT
      	cl.classID,
          cl.type,
          cl.subjectID,
          subj.subjectName,
          CAST(cl.dateTime as Time ) as startTime,
      	CAST( date_add((cl.dateTime), INTERVAL cl.duration HOUR) AS TIME) as endTime,
          DAYNAME(cl.dateTime) as "day",
          (NULL) as status,
          (NULL) as newDateTime,
      	venue as newVenue,
      	(NULL) as oldDateTime
      FROM `class_lesson` as cl
      INNER JOIN `subject` as subj
      	ON subj.subjectID = cl.subjectID
      WHERE subj.subjectID IN {$subjStr}
EOF;
	return $this->con->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function save($o){
    	$ret = parent::save($o);
    	$o->classID = $this->con->insert_id;
    	return $ret;
    }

    public function getEntireScheduleAdmin(){

    $query = <<<EOF
      SELECT
          cr.classID,
          cl.type,
          cl.subjectID,
          subj.subjectName,
          CAST( (case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end) as TIME) as startTime,
          CAST( date_add((case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end), INTERVAL cl.duration HOUR) AS TIME) as endTime,
          DAYNAME((case when cr.newDateTime is NULL then cl.dateTime else cr.newDateTime end)) as "day",
          cr.status,
          CAST(cr.newDateTime as DATE) as newDateTime,
        cr.newVenue,
        CAST(cr.oldDateTime as DATE) as oldDateTime
      FROM `class_rescheduling` as cr
      JOIN `class_lesson` as cl
        ON	cr.classID = cl.classID
      INNER JOIN `subject` as subj
        ON subj.subjectID = cl.subjectID


      UNION

      SELECT
        cl.classID,
          cl.type,
          cl.subjectID,
          subj.subjectName,
          CAST(cl.dateTime as Time ) as startTime,
        CAST( date_add((cl.dateTime), INTERVAL cl.duration HOUR) AS TIME) as endTime,
          DAYNAME(cl.dateTime) as "day",
          (NULL) as status,
          (NULL) as newDateTime,
        venue as newVenue,
        (NULL) as oldDateTime
      FROM `class_lesson` as cl
      INNER JOIN `subject` as subj
        ON subj.subjectID = cl.subjectID

EOF;
    return $this->con->query($query)->fetch_all(MYSQLI_ASSOC);
    }

}

?>
