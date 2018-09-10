<?php
  class ReportDA extends DataAccessObject{

  public function __construct($con){
      parent::__construct($con, "Report");
      if($con == null)
        throw new \Exception("ReportDA: No connection received!");
    }

    public function getClassRescheduling(){
      $query = <<<EOF
      SELECT
        lec.lecturerName as Lecturer,
        cl.subjectID as "Subject Code",
      CAST(cl.dateTime as TIME) as "Subject Start Time",
        cl.duration as "Duration",
        CAST(cr.`oldDateTime` as DATE) as "Actual Date",
      CAST(cr.`newDateTime` as DATE) as "Rescheduling Date",
        CAST(cr.`newDateTime` as TIME) as "Rescheduling Time",
        cr.`status`,
        cr.`Venue`,
        CAST(cr.`createCancellationDate` as DATE) as "Cancellation Date"
      FROM `class_rescheduling` as cr
      INNER JOIN `class_lesson` as cl ON cl.classID = cr.classID
      INNER JOIN `subject` as subj ON subj.subjectID = cl.subjectID
      INNER JOIN `lecturer` as lec ON lec.lecturerID = subj.lecturerID
EOF;
      return $this->con->query($query)
              ->fetch_all();
              //->fetch_all(MYSQLI_ASSOC);
    }


  }

?>
