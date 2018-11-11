<?php
class ClassReschedulingDA extends DataAccessObject{
    //creating sql object outside, to inject inside (create, remove, update..)
    public function __construct($con){
    	parent::__construct($con, "ClassRescheduling");
    	if($con == null)
                throw new \Exception("ClassReschedulingDA: No connection received!");
    	$this->con = $con;
    	$this->setPrimaryKey("id");
    	$this->setTableName("class_rescheduling");
    }

    public function getCountPending(){
    	$q = "SELECT COUNT(*) FROM class_rescheduling WHERE status ='pending';";
    	$result = $this->con->query($q);
    	return $result->fetch_array()[0];
    }

    public function getCountApproved(){
    	$q = "SELECT COUNT(*) FROM class_rescheduling WHERE status ='approved';";
    	$result = $this->con->query($q);
    	return $result->fetch_array()[0];
    }

    /*
       public function getPendingRequest(){
       $retrieveData = $this->con->query("SELECT * FROM class_rescheduling WHERE status ='pending'");

       $arr = [];
       if($retrieveData != false){
       // request empty constructor    //from models
       $obj = null;
       while($obj = $retrieveData->fetch_object('ClassRescheduling')){
       //Connecting ClassRescheduling model with Subject model
       $subjectDA = new SubjectDA($this->con);
       $subject = $subjectDA->fetchSubjectById($obj->getSubjectID());
       if($subject == null)
       throw new \Exception("Corrupt data. Invalid subject id {$obj->getSubjectID()}");

       $obj->setSubject($subject);

       //Connecting Subject model with Lecturer model
       $lecturerDA = new LecturerDA($this->con);
       $lecturer = $lecturerDA->fetchLecturerById($subject->lecturerID);
       if($lecturer == null)
       throw new \Exception("Corrupt data. Invalid lecturer id {$subject->lecturerID}");
       $subject->setLecturer($lecturer);


       array_push($arr, $obj);
       }
       return $arr;
       }
       return false;
       }
     */

    public function getApprovalRequest($id){
	$result = $this->con->query("SELECT * FROM class_rescheduling WHERE id = '$id';");
        //from models
	return $result->fetch_object('ClassRescheduling');

    }
    public function getPendingList(){
      $query = <<<EOF
      SELECT
        cr.id as "id",
        cl.subjectID as "subjectCode",
        subj.subjectName as "subjectName",
        lec.lecturerName as "lecturer",
        cr.newVenue	as "venue",
        cl.type as "type",
        DAYNAME(CAST(cr.newDateTime as DATE)) as "reDate",
        CAST(cr.newDateTime AS TIME) as "reTime",
        cl.duration as "duration"
        FROM `class_rescheduling` as cr
        INNER JOIN `class_lesson` as cl
        ON cr.classID = cl.classID
        INNER JOIN `subject` as subj
        ON cl.subjectID = subj.subjectID
        INNER JOIN `lecturer` as lec
        on subj.lecturerID = lec.lecturerID
        WHERE cr.status = "pending"
EOF;
return $this->con->query($query)
                  ->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchClassById($id){
        $result = $this->con->query("SELECT * FROM class_rescheduling WHERE id = '{$id}';");
        return $result->fetch_object('ClassRescheduling');
    }

    public function getListOfCancellation($lecturerID){
	$query = <<<EOF
      SELECT
      cr.id as "rescheduleID",
      cl.subjectID as "title",
      subj.subjectName as "subName",
      cl.type as "type",
      cr.oldDateTime as "oriDate",
      DAYNAME(CAST(cr.oldDateTime as DATE)) as "oriDay",
      CAST(cl.dateTime AS TIME) as oriStartTime,
      CAST(date_add(cl.dateTime, INTERVAL cl.duration HOUR) AS TIME) as oriEndTime,
      cr.createCancellationDate as "cancelDate",
      cr.status as status,
      cr.newDateTime as newDateTime,
      cr.newVenue as newVenue
      FROM `class_rescheduling` as cr
      INNER JOIN `class_lesson` as cl
      ON cr.classID = cl.classID
      INNER JOIN `subject` as subj
      ON cl.subjectID = subj.subjectID
      WHERE
      subj.lecturerID = "$lecturerID";
EOF;
	return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }


    public function getCancellationByStatus($lecturerID, $status){
      if($status == "pending"){
      $conditon = '
       cr.status = "pending" AND cr.newDateTime IS NOT NULL';
    } else{
        $conditon = '
         cr.status = "approved" ';
    }
	$query = <<<EOF
      SELECT
      cr.id as "rescheduleID",
      cl.subjectID as "title",
      subj.subjectName as "subName",
      cl.type as "type",
      cr.oldDateTime as "oriDate",
      DAYNAME(CAST(cr.oldDateTime as DATE)) as "oriDay",
      CAST(cl.dateTime AS TIME) as oriStartTime,
      CAST(date_add(cl.dateTime, INTERVAL cl.duration HOUR) AS TIME) as oriEndTime,
      cr.createCancellationDate as "cancelDate",
      cr.status as status,
      cr.newDateTime as newDateTime,
      cr.newVenue as newVenue
      FROM `class_rescheduling` as cr
      INNER JOIN `class_lesson` as cl
      ON cr.classID = cl.classID
      INNER JOIN `subject` as subj
      ON cl.subjectID = subj.subjectID
      WHERE subj.lecturerID = "$lecturerID" AND
      {$conditon}
;
EOF;
	return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }



    public function getCancellationByScheduled($lecturerID){



	$query = <<<EOF
      SELECT
      cr.id as "rescheduleID",
      cl.subjectID as "title",
      subj.subjectName as "subName",
      cl.type as "type",
      cr.oldDateTime as "oriDate",
      DAYNAME(CAST(cr.oldDateTime as DATE)) as "oriDay",
      CAST(cl.dateTime AS TIME) as oriStartTime,
      CAST(date_add(cl.dateTime, INTERVAL cl.duration HOUR) AS TIME) as oriEndTime,
      cr.createCancellationDate as "cancelDate",
      cr.status as status,
      cr.newDateTime as newDateTime,
      cr.newVenue as newVenue
      FROM `class_rescheduling` as cr
      INNER JOIN `class_lesson` as cl
      ON cr.classID = cl.classID
      INNER JOIN `subject` as subj
      ON cl.subjectID = subj.subjectID
      WHERE
      subj.lecturerID = "$lecturerID"
AND
      cr.newDateTime IS NULL
;
EOF;
	return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }

/*  public function getCancellationByScheduled($lecturerID, $t){
    if ($t){
      $condition = "  cr.newDateTime IS  NULL";
    }else{
        $condition = "  cr.newDateTime IS NOT NULL";
  }

    AND
    {$condition}
*/

    public function getViewApprovedClassList($lecturerID){
	$query = <<<EOF
      SELECT
      cl.subjectID as "subjCode",
      subj.subjectName as "subjName",
      cl.type as "type",
      cr.Venue as "venue",
      cr.newDateTime as "newDT",
      DAYNAME(CAST(cr.newDateTime as DATE)) as "day"
      FROM `class_rescheduling` as cr
      INNER JOIN `class_lesson` as cl ON cr.classID = cl.classID
      INNER JOIN `subject` as subj ON cl.subjectID = subj.subjectID
      WHERE cr.status = 'approved'
      AND subj.lecturerID = "$lecturerID";
EOF;
	return $this->con->query($query)
                    ->fetch_all(MYSQLI_ASSOC);
    }

    /*public function save($o){
       $result = $this->con->query("UPDATE class_rescheduling SET status = '{$o->getStatus()}', newDateTime = '{$o->getNewDateTime()}' WHERE id = '{$o->getId()}' ");

       return $result === true;
       }*/
}
?>
