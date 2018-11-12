<?php
  class ReportDA extends DataAccessObject{

  public function __construct($con){
      parent::__construct($con, "Report");
      if($con == null)
        throw new \Exception("ReportDA: No connection received!");
        $this->con = $con;

    }

    public function getClassRescheduling(){
      $query = <<<EOF
      SELECT
        lec.lecturerName as "lecturer",
        cl.subjectID as "subjectCode",
      CAST(cl.dateTime as TIME) as "subStartTime",
        cl.duration as "duration",
        CAST(cr.`oldDateTime` as DATE) as "actualDate",
      CAST(cr.`newDateTime` as DATE) as "reDate",
        CAST(cr.`newDateTime` as TIME) as "reTime",
        cr.`status`,
        cr.`newVenue` as venue,
        CAST(cr.`createCancellationDate` as DATE) as "cancelledDate"
      FROM `class_rescheduling` as cr
      INNER JOIN `class_lesson` as cl ON cl.classID = cr.classID
      INNER JOIN `subject` as subj ON subj.subjectID = cl.subjectID
      INNER JOIN `lecturer` as lec ON lec.lecturerID = subj.lecturerID
      WHERE cr.status = "approved"
EOF;
      return $this->con->query($query)->fetch_all(MYSQLI_ASSOC);
              //->fetch_all(MYSQLI_ASSOC);
    }


  }

?>
