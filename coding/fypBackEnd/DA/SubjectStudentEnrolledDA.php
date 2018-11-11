<?php
  class SubjectStudentEnrolledDA extends DataAccessObject {
    public function __construct($con){
      parent::__construct($con, "SubjectStudentEnrolled");
    	if($con == null)
                throw new \Exception("SubjectStudentEnrolledDA: No connection received!");
    	$this->con = $con;
    	$this->setTableName("subject_student_enrolled");
        }

        public function getStudententSubjectHash($studentID){
            $q = <<<EOF
            SELECT MD5(GROUP_CONCAT(CONCAT(subjectID))) as "key"  FROM (
              	SELECT subjectID FROM subject_student_enrolled as s where s.studentID = "{$studentID}"
              ) as t1
EOF;
          return $this->con->query($q)->fetch_all(MYSQL_ASSOC)[0]['key'];

        }

        public function calculateSubjectHash($subjectList){
          $subjStr = implode(",", $subjectList);
          $subjStr = "(\"{$subjStr}\")";

          $q = <<<EOF
              SELECT MD5{$subjStr} as "key"
EOF;
          return $this->con->query($q)->fetch_all(MYSQL_ASSOC)[0]['key'];
        }

        public function deleteSubjectsOfStudent($studentID){
          $q = <<<EOF
              DELETE FROM `subject_student_enrolled` WHERE studentID= '{$studentID}'
EOF;
              return $this->con->query($q);
        }

        public function fetchDeviceList($subjectID){
          $q = <<<EOF
          SELECT s.studentID, d.token as "device_id" FROM `subject_student_enrolled` as s
  JOIN device_list as d on d.userID = s.studentID AND d.type = "student"
  WHERE subjectID = "{$subjectID}"
EOF;
                    return $this->con->query($q)->fetch_all(MYSQL_ASSOC);
        }
  }
 ?>
