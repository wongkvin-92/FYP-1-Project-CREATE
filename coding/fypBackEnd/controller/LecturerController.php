<?php
class LecturerController extends MasterController{
    public function __construct($con){
	$this->con = $con;
    }

    /******************
     *  LOGIN SECTION *
     ******************/
    function login($email, $pass){
    	$lecturerda = new LecturerDA($this->con);

      	$lecturer = $lecturerda->fetchLecturerByEmail($email);
      	if ($lecturer != null){
            if($lecturer->lecturerPassword == $pass){
          	$obj = [
                    'type' => 'lecturer',
                    'user' => $lecturer
                ];

        	$_SESSION['credentials'] = $obj;
          	print json_encode(['result'=> true, 'msg' => 'Login Sucessfully!', 'id'=> $lecturer->lecturerID]);
            }else{
          	throw new Exception("Incorrect username/password!");
            }
      	}else{
            throw new \Exception("User Not Found!");
      	}
      	return;
    }


    public function checkLoginState(){
    	if (isset($_SESSION['credentials'])
            && $_SESSION['credentials']['type'] == 'lecturer'
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

    public function getLecturerID(){
    	return $_SESSION['credentials']['user']->getID();
    }

    public function getLecturer(){
	return $_SESSION['credentials']['user'];
    }




    public function logout(){
    	session_destroy();
    	print json_encode(['msg'=> 'You\'re successfully logged out!']);
    }

    /************************
     *  END OF LOGIN SECTION *
     *************************/


    /************************
     *  CANCELLATION SECTION *
     *************************/
    /*
       public function createCancellation(  $classID, $date, $time){
       $rescheduleDA = new ClassReschedulingDA($this->con);
       $reschedule = new ClassRescheduling();
       $cDA = new ClassLessonDA($this->con);
       $class = $cDA->fetchClassById($classID);


       $reschedule->setNewDateTime($date, $time);
       $reschedule->classID = $classID;
       $reschedule->status = "pending";
       $reschedule->Venue = "NA";
       $reschedule->oldDateTime = $class->getDateTime();  //The first class of the semester.
       $rescheduleDA->save($reschedule);
       $this->sendMsg("Successfully Created!");
       }
     */

    /**
     *
     * Pre-condition:
     *  - TODO Date must be of the same day as the classLesson.DateTime
     *    for example if day(classLesson.dateTime) is "Tuesday" then
     *   we only accept a day($date) which is a "Tuesday"
     **/
    public function createCancellation($classID, $date){

    	$rescheduleDA = new ClassReschedulingDA($this->con);

    	$rescheduling = new ClassRescheduling();
    	$classDA = new ClassLessonDA($this->con);
    	$class = $classDA->fetchLessonById($classID);

    	$rescheduling->classID = $classID;
    	$rescheduling->oldDateTime = $date;
    	$rescheduling->status = "pending";
    	$rescheduleDA->save($rescheduling);
    	$this->sendMsg("Successfully Cancelled!");

    }

    public function createCancellationList($arr){
	$reschedulingDA = new ClassReschedulingDA($this->con);
	//$classDA = new ClassLessonDA($this->con);
	$lessonDA = new ClassLessonDA($this->con);
	$subjectDA = new SubjectDA($this->con);
	$ssda = new SubjectStudentEnrolledDA($this->con);
	foreach($arr as  $item){
            $rescheduling = new ClassRescheduling();
            $rescheduling->classID = $item->classID;
            $rescheduling->status = "pending";
            $rescheduling->oldDateTime = $item->cancelDate;
            $reschedulingDA->save($rescheduling);
            $class_obj =  $lessonDA->fetchClassById($item->classID);
            $subject = $subjectDA->fetchSubjectById($class_obj->subjectID);
            //4- Send notfication to $students
            $deviceList = $ssda->fetchDeviceList($subject->subjectID);
            foreach($deviceList as $d){
		if($d){
		    $msg = "Cancelled {$subject->subjectID}/{$class_obj->type} {$oldScheduleDate}.";
		    $this->notificationService->dispatchNotification($d['device_id'], $msg);
		}
            }
	}
	$this->sendMsg("Successfully Cancelled!");
	//$this->sendMsg("Successfully Created!");
    }

    /**
     * Pre-coditions:
     *  - Only the lecturer who teaches the class should be able to apply for
     *   rescheduling.
     * - A class must be cancelled first (Class cancellation record must exist)
     *PostCondition:
     *  - Update new DateTime
     *  - Status must remain "pending"
     **/
    public function applyClassReplacement($id, $date, $time){
	/*
	   $today = new DateTime();
	   $today->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));
	   $datetime = new DateTime($date . " " . $time, new DateTimeZone("Asia/Kuala_Lumpur"));
	   $validDayStart = new DateTime($date . " 08:30:00", new DateTimeZone("Asia/Kuala_Lumpur"));
	   $validDayEnd = new DateTime($date . " 18:00:00" , new DateTimeZone("Asia/Kuala_Lumpur"));

	   if($datetime < $today){
           $systime = $today->format("d-m-Y H:i:s");
           throw new \Exception("Cannot create a lesson for the passed date. \n Current system date is {$systime}");
	   }

	   //validate the time is between 5.30 to 6
	   if($datetime < $validDayStart || $datetime > $validDayEnd){
           throw new \Exception("Lesson can only take place between 8:30AM to 06:00PM");
	   }
	 */

    	$rescheduleDA = new ClassReschedulingDA($this->con);
    	$reschedule = $rescheduleDA->fetchClassById($id);
	//$lecturer = $this->getLecturer();
	//if ($lecturer != null){
    	$reschedule->setNewDateTime($date, $time);
    	$rescheduleDA->save($reschedule);
    	print json_encode(['result'=> true, 'msg'  => "Successfully Requested Scheduling!"]);
	//}
    }
    /*
       if ($date != "" || $time !=""){
       $reschedule->setNewDateTime($date, $time);
       $rescheduleDA->save($reschedule);
       print json_encode(['result'=> true, 'msg'  => "Successfully Requested Scheduling!"]);
       }else{
       throw new Exception("date or time is empty!");
       }
     */
    public function listOfCancellation($id, $filter){
    	$rescheduleDA = new ClassReschedulingDA($this->con);
    	//$list = $resschedulingDA->getListOfCancellation($id);

    	if($filter == "pending" || $filter == "approved"){
            $list = $rescheduleDA->getCancellationByStatus($id, $filter);
    	}else if($filter == "unscheduled"){
    	    $list = $rescheduleDA->getCancellationByScheduled($id);
    	}else{
    	    $list = $rescheduleDA->getListOfCancellation($id);
    	}
    	print(json_encode($list));
    }
    /*
       else if($filter == "scheduled" \\ $filter == "unscheduled" ){
       $test = $filter == 'unscheduled';
       $list = $rescheduleDA->getCancellationByScheduled($id, $test);
       }*/

    public function deleteCancellation($classID){

    	$reschedulingDA = new ClassReschedulingDA($this->con);

    	$rescheduling = $reschedulingDA->fetchClassById($classID);
    	if($rescheduling->status == "approved")
            throw new Exception("Only pending request can be removed");

    	if(  $reschedulingDA->remove($rescheduling)){
            $this->sendMsg("Successfully removed!");
    	} else{
            throw new Exception("Failed to remove!");
	}

    }

    public function countApprovedRequest(){
    	$da = new ClassReschedulingDA($this->con);
    	$num = $da->getCountApproved();
    	print json_encode(['count' => $num]);
    }

    public function viewApprovedList(){
    	$da = new ClassReschedulingDA($this->con);
    	$list = $da->getViewApprovedClassList($this->getLecturerID());
    	print(json_encode($list));
    }

    public function listLessonByLecturer($lecturerID){
    	$lessonDA = new ClassLessonDA($this->con);
    	$list = $lessonDA->getLessonByLecturer($lecturerID);
    	print(json_encode($list));
    }

    public function listConfirmedLessonsForDate($lecturerID, $date){
    	$lessonDA = new ClassLessonDA($this->con);
    	//$list = $lessonDA->getLessonByLecturerWithDetail($lecturerID);
    	$dateObject = new DateTime($date);  //Day
    	$sqlDate = $dateObject->format("Y-m-d");
    	$dayname = $dateObject->format("l");
    	$list = $lessonDA->getLecturerScheduleByDate($lecturerID, $dayname, $sqlDate);

    	print(json_encode($list));
    }


    //not using
    public function listConfirmedLessons($lecturerID, $weekNum){
        $lessonDA = new ClassLessonDA($this->con);
        $list = $lessonDA->getLessonByLecturerWithDetail($lecturerID);

        $start_of_week = new DateTime("monday");
        $end_of_week = new DateTime("sunday");
        $start_of_week->add(new DateInterval("P{$weekNum}W"));
        $end_of_week->add(new DateInterval("P{$weekNum}W"));

        $scheduleMap = [];
        foreach($list as $class){
            $day = substr($class["day"],0,3);
            if(!array_key_exists($day, $scheduleMap))
		$scheduleMap[$day] = [];
            $scheduleMap[$day] []= $class;
        }


        $dailySchedule = [];
        $scheduleDay = new DateTime("monday");
        // To add on 1 week
        $scheduleDay->add(new DateInterval("P{$weekNum}W"));
        for($i = 0; $i<7; $i++){
            $day = $scheduleDay->format("D");
            $dailySchedule[$day] = [];

            if(array_key_exists($day, $scheduleMap)){  //If lecturer have class for this day
		foreach($scheduleMap[$day] as $class){  //For every class on that day
                    $time = new DateTime($class['dateTime']);
                    $startTime = $time->format("H:m:s");

                    $endTimeObject = new DateTime($class['dateTime']);
                    $endTimeObject->add(new DateInterval("PT{$class['duration']}H"));
                    $endTime = $endTimeObject->format("H:m:s");

                    $classDate = $scheduleDay->format("d-m-Y");
                    $dailySchedule [$day] []= [
                	'subjectCode' => $class['title'],
                	'date' => $classDate,
                	'startTime' => $startTime,
                	'endTime' => $endTime,
                	'type' => $class['type'],
                	'venue' => $class['venue'],
                	'isCancelled' => "false",
                	"uuid" => md5($classDate . $lecturerID . $class['title'])
                    ];

		}
            }
            $scheduleDay->add(new DateInterval("P1D"));
        }



        print(json_encode($dailySchedule));


	/*
           if($weekNum < 0)
           throw new \Exception("Cannot determine schedule for passed dates");
           if($weekNum == 0)
           print("Schedule for this week");
           else if($weekNum == 1)
           print("Schedule for next week");
           else
           print("Schedule for upcoming ".$weekNum);*/
    }

    public function getSubjectList($id){
	$subjectDA = new SubjectDA($this->con);
	print(json_encode(['id' => $subjectDA->fetchSubjectsByLecturer($id)]));
    }

    public function fetchAllSchedule($id){
	/**
	 * @todo Write a single SQL query that fetch the schedule
	 **/
	$subjectDA = new SubjectDA($this->con);
	$subjectList = $subjectDA->fetchSubjectCodesByLecturer($id);
	

	if(count($subjectList) != 0) {
	    $lessonDA = new ClassLessonDA($this->con);
	    $allSchedule = $lessonDA->getEntireSchedule($subjectList);
	}else{
	    $allSchedule = [];
	}
	print(json_encode($allSchedule));
    }

    public function fetchScheduleHash($id){
	/**
	 * @todo Write a single SQL query that fetch the schedule hash
	 **/
	$lessonDA = new ClassLessonDA($this->con);
	$subjectDA = new SubjectDA($this->con);
	$subjectList = $subjectDA->fetchSubjectCodesByLecturer($id);

	if(count($subjectList) == 0){
	    print(json_encode(["key" => NULL]));
	    return;
	} 
	
	$hashSchedule = $lessonDA->getEntireScheduleHash($subjectList)[0];
      	print(json_encode($hashSchedule));	
    }





    /*******************************
     *  END OF CANCELLATION SECTION *
     ********************************/
}
?>
