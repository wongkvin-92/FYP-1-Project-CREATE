<?php
class StudentController extends MasterController{
    public function __construct($con){
	$this->con = $con;
	$this->notificationService = new NotificationService();
    }

    /*****************
     *  LOGIN SECTION *
     ******************/
    function login($studentID, $pass){
	$studentda = new StudentDA($this->con);

        $student = $studentda->fetchStudentById($studentID);
        if ($student != null){
            if($student->sPassword == $pass){
		$obj = [
                    'type' => 'student',
                    'user' => $student,
		    'subject_list' => []
                ];

                $_SESSION['credentials'] = $obj;
                print json_encode(['result'=> true,'msg' => 'Login Sucessfully!', 'id'=> $student->studentID]);

            }else{
		throw new Exception("Incorrect username/password!");
            }
        }else{
            throw new \Exception("Not Found!");
        }
        return;
    }

    public function checkLoginState(){
	if (isset($_SESSION['credentials'])
            && $_SESSION['credentials']['type'] == 'student'
	){
            return true;
	}
	else{
            return false;
	}
    }

    public function getCredentials(){
        //print json_encode(["name" => $this->getAdmin()->getName()]);
        $this->returnObject($_SESSION['credentials']['user']);
    }

    public function getStudentID(){
        return $_SESSION['credentials']['user']->getStudentID();
    }

    public function logout(){
        session_destroy();
        print json_encode(['msg'=> 'You\'re successfully logged out!']);
    }

    public function listSubjects(){
        $subjectDA = new SubjectDA($this->con);

        $list = $subjectDA->findAll();
        $arr = [];
        foreach($list as $k => $v){

            $lda = new LecturerDA($this->con);
            $lecturer = $lda->fetchLecturerById($v->lecturerID);
            $v->setLecturer($lecturer);

            $o['subjectID'] = $v->subjectID;
            $o['subjectName'] = $v->getSubName();
            $o['lecturerID'] = $v->getLecturer()->getId();
            $o['lecturerName'] = $v->getLecturer()->getName();

            $arr[] = $o;
        }
        $this->returnObject($arr);
    }

    public function fetchStudentLessonForDate($subjectList, $date){
    	$lessonDA = new ClassLessonDA($this->con);
    	//$list = $lessonDA->getLessonByLecturerWithDetail($lecturerID);
    	$dateObject = new DateTime($date);  //Day
    	$sqlDate = $dateObject->format("Y-m-d");
    	$dayname = $dateObject->format("l");
    	$list = $lessonDA->getStudentScheduleByDate($sqlDate, $dayname, $subjectList);
    	print(json_encode($list));
    }

    public function fetchAllSchedule($subjectList){
	if(count($subjectList) != 0){
	    $this->storeSubjectList($subjectList); //store subject list in the session
	    $this->updateSubjectList($subjectList);
	    $lessonDA = new ClassLessonDA($this->con);
	    $allSchedule = $lessonDA->getEntireSchedule($subjectList);
	}else{
	    $allSchedule=[];
	}
	print(json_encode($allSchedule));
    }

    public function storeSubjectList($list){
	     $_SESSION['credentials']['subject_list'] = $list;
    }

    public function getSubjectList(){
	     print(json_encode($_SESSION['credentials']['subject_list']));
    }

    public function updateSubjectList($subjList){
    	$enrolledDA = new SubjectStudentEnrolledDA($this->con);
    	$studentID = $this->getStudentID();
    	$studentSubjectHash = $enrolledDA->getStudententSubjectHash($studentID);
    	$triggerSubjectHash = $enrolledDA->calculateSubjectHash($subjList);

    	if($studentSubjectHash == $triggerSubjectHash){
                return false;
    	}else{
          //Update subjects
          $enrolledDA->deleteSubjectsOfStudent($studentID);
          foreach($subjList as $sub){
          		$relation = new SubjectStudentEnrolled($this->con);
          		$relation->subjectID = $sub;
          		$relation->studentID = $studentID;
          		$enrolledDA->save($relation);
	         }
	    return true;
	     }

    }

    public function fetchAllScheduleHash($subjectList){
      	$this->storeSubjectList($subjectList); //store subject list in the session
        $this->updateSubjectList($subjectList);

      	if(count($subjectList) == 0){
      	    print(json_encode(["key" => NULL]));
      	    return;
      	}
      	//$this->notificationService(

      	//$deviceDA = new DeviceDA($this->con);
      	//$device =  $deviceDA->getFirstDevice('student', 'b1301744');

        //$msg = "Hello";
      	//$this->notificationService->dispatchNotification($device->token, $msg);

	      $lessonDA = new ClassLessonDA($this->con);
      	$hashSchedule = $lessonDA->getEntireScheduleHash($subjectList)[0];

      	print(json_encode($hashSchedule));

    }

    /*
       public function registerSubjects($subjArr){
       $registerSubjDA = new $registeredSubjects
       }*/


}
?>
