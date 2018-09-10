<?php
class AdminController extends MasterController{
    private $con;
    public function __construct($con){
	     $this->con = $con;
    }

    /**********************
    * ADMIN LOGIN SECTION *
    ***********************/

    function login($email, $pass){
      	$userda = new UserDA($this->con);

      	$user = $userda->fetchUserByEmail($email);

      	if($user != null){
                  if($user->checkPass($pass)){
      		$obj = [
      		    'type' => 'admin',
      		    'user' => $user
      		];

      		$_SESSION['credentials'] = $obj;
      		print json_encode(['msg' => 'Login Sucessfully!']);
      		return;
      		/* example to retrive session data
      		   $admin = $_SESSION['credentials']['user'];
      		   $admin->getName();
      		 */
                  }

                  throw new \Exception("Please key in the correct password!");
      	  }
              throw new \Exception("User not found!");
    }

    public function logout(){
    	session_destroy();
    	print json_encode(['msg'=> 'You\'re successfully logged out!']);
    }

    public function getCredentials(){
    	//print json_encode(["name" => $this->getAdmin()->getName()]);
    	$this->returnObject($this->getAdmin());
    }

    public function checkLoginState(){
    	if (isset($_SESSION['credentials'])
                && $_SESSION['credentials']['type'] == 'admin'
    	){
                //$admin = $this->getAdmin();
                return true;
    	}
    	else{
                return false;
    	}
    }


    private function getAdmin(){
	     return $_SESSION['credentials']['user'];
    }

    /*****************************
    * END OF ADMIN LOGIN SECTION *
    ******************************/

    /***********************
    * RESCHEDULING SECTION *
    ************************/


    public function countPendingRequest(){
      $da = new ClassReschedulingDA($this->con);
      $num = $da->getCountPending();
      print json_encode(['count' => $num]);

    }
/*
    public function viewPendingRequest(){
      $da = new ClassReschedulingDA($this->con);
      $result = $da->getPendingRequest();
      if($result != false){
                $arr = [];
                ///entry is an object of the ClassRescheduling

        foreach ($result as $entry) {
            $o['id'] = $entry->getId();
            $o['subjectCode'] = $entry->getSubject()->getSubId();
            $o['subjectName'] = $entry->getSubject()->getSubName();
            $o['lecturer'] = $entry->getSubject()->getLecturer()->getName();
            //explode = split string(timestamp)
            $date = explode(" ", $entry->getNewDateTime());
            $o['reDate'] = $date[0];
            $o['reTime'] = $date[1];
            $o['duration'] = '2';
            $o['venue'] = $entry->getVenue();

            $arr[] =$o;
                    }
                    $this->returnJSON($arr);
                    //print json encode data
                    //$this->returnObject($result);
       } else{
            throw new \Exception("No Pending Request!");
       }
    }
*/
    public function viewPendingRequest(){

      $rescheduleDA = new ClassReschedulingDA($this->con);

      $list = $rescheduleDA->findAll();

      $in = $list;
      $out = [];
      foreach($in as $v){

        $lessonDA = new ClassLessonDA($this->con);
        $lesson = $lessonDA->fetchLessonById($v->classID);
        if($lesson == null){
          throw new \Exception("System error: The lesson not found with id" . $v->classID);
        }

        $sda = new SubjectDA($this->con);
        $subject = $sda->fetchSubjectById($lesson->subjectID);
        if($subject == null){
          throw new \Exception("System error: The subject not found for lesson" . $lesson->subjectID);
        }

        $lda = new LecturerDA($this->con);
        $lecturer = $lda->fetchLecturerById($subject->lecturerID);
        if($lecturer == null){
          throw new \Exception("System error: The lecturer not found for subject" . $subject->lecturerID);
        }

        $o['id'] = $v->getId();
        $o['subjectCode'] = $subject->getSubId();
        $o['subjectName'] = $subject->getSubName();
        $o['type'] = $lesson->getType();
        $o['lecturer'] = $lecturer->getName();
        //explode = split string(timestamp)
        $date = explode(" ", $v->getNewDateTime());
        $o['reDate'] = $date[0];
        $o['reTime'] = $date[1];
        $o['duration'] = '2';
        $o['venue'] = $v->getVenue();
        $out[] = $o;
      }
      $this->returnObject($out);

    }

    /*
       public function changeVenue($id, $venue){
       $da = new ClassReschedulingDA($this->con);
       $rq = $da->getApprovalRequest($id);
       $rq->setVenue($venue);
       $da->save($rq);
       $this->returnObject($rq);
       }*/

    public function approveClass($id){
        //link to db
    	$da = new ClassReschedulingDA($this->con);
    	$rqApprove = $da->getApprovalRequest($id);
    	$venue = $rqApprove->getVenue();
    	if($venue == "" || $venue == "NA")
    	    throw new \Exception("Please assign a venue first!");
    	if(!$rqApprove->isApproved()){
                $rqApprove->approve();
                $da->save($rqApprove);
                $this->sendMsg("Approved Successfully!");
    	}else{
                throw new \Exception("You've already approved!");
    	}
    }

    /*
     * Get a Subject
     */
    public function getSubject($id){
    	$subjectDA = new SubjectDA($this->con);
    	$lecturerDA = new LecturerDA($this->con);
    	$subject = $subjectDA->fetchSubjectById($id);
    	$lecturer = $lecturerDA->fetchLecturerById($subject->lecturerID);
    	//$subject->lecturerName = $lecturer->getName();
    	$subject->lecturerID = $lecturer->getId();

    	$this->returnObject($subject);
    }


    /******************************
    * END OF RESCHEDULING SECTION *
    *******************************/


    /*public function testCreateClass($subjCode, $type){
       $lesson = new ClassLesson();
       $lesson->subjectID = $subjCode;
       $lesson->type = $type;

       $da = new ClassLessonDA($this->con);
       print "Test result = ". $da->numExistingClasses($lesson);

       }*/



     /***********************
     * CLASS LESSON SECTION *
     ************************/

     /**
        * Create new lesson and subject
        * Creates a new subject if subject does not exist.
        * Creates a new lecturer if lecturer name does not exist.
        * Creates a new Lesson.
        * @param $sid varchar subject ID,
        * @param $rid  varchar venue,
        * @param $date DateTime date,
        * @param $time DateTime time,
        * @param $duration int duration,
        * @param $type varchar type of class lesson,
        * @param $lid int lecturer id,
        * @param $sname varchaar subject name,
        *
        *$rid
      **/
    public function addNewSubjectLesson($sid, $rid, $date, $time, $duration, $type, $lid, $sname){

      	$today = new DateTime();
        $today->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));
      	$datetime = new DateTime($date . " " . $time, new DateTimeZone("Asia/Kuala_Lumpur"));
      	$validDayStart = new DateTime($date . " 08:30:00", new DateTimeZone("Asia/Kuala_Lumpur"));
      	$validDayEnd = new DateTime($date . " 18:00:00" , new DateTimeZone("Asia/Kuala_Lumpur"));
      	//validate date has not passed
        if($datetime < $today){
      	   $systime = $today->format("d-m-Y H:i:s");
           throw new \Exception("Cannot create a lesson for the passed date. \n Current system date is {$systime}");
        }
      	//validate the time is between 5.30 to 6
      	if($datetime < $validDayStart || $datetime > $validDayEnd){
      	    throw new \Exception("Lesson can only take place between 8:30AM to 06:00PM");
      	}

      	//validate duration
      	if($duration > 6)
      	    throw new \Exception("Duration cannot be longer than 6 hours.");
      	if($duration < 0)
      	    throw new \Exception("A lesson must atleast be one hour long.");

        $subjectDA = new SubjectDA($this->con);
        $lecturerDA = new LecturerDA($this->con);

        $da = new ClassLessonDA($this->con);
        $subject = $subjectDA->fetchSubjectById($sid);
        //$lecturer = $lecturerDA->fetchLecturerByName($lname);
      	//$lid = $subject->getLecturer();
        $lecturer = $lecturerDA->fetchLecturerById($lid);

        if($lecturer == null){
            //  print "Lecturer not found";
            $lecturer = new Lecturer();
            $lecturer->lecturerName = $lname;
            $lecturerDA->save($lecturer);
            $lecturer = $lecturerDA->fetchLecturerByName($lname);
        }else{
            // print "Lecturer found";
        }
        $lesson = new ClassLesson();
        $lesson->subjectID = $subject->subjectID;
        $lesson->setType($type);
        if($da->numExistingClasses($lesson) >= 1){
            throw new \Exception("Cannot create {$type} for subject {$subject->subjectID}. {$type} already exists.");
        }

      if($subject == null){
    	    //  print "Subject not found";
    	    $subject = new Subject();
    	    $subject->subjectID = $sid;
    	    $subject->lecturerID = $lecturer->lecturerID;
    	    $subject->subjectName = $sname;
    	    $subjectDA->save($subject);
    	    //$subject = $subjectDA->fetchSubjectById($sid);
        }else{
           //  print "subject found";
        }

        $lesson->setDateTime($date, $time);
        $lesson->venue = strtolower($rid);
        $lesson->setDuration($duration);
        if($this->classClash($lesson)){
            throw new \Exception("Cannot create lesson for that time. Another class is taking place at the same venue, in the given time range");
        }

        //$lesson->setRoom($rid);
        if($da->save($lesson) != true){
           throw new \Exception("Cannot create lesson!");
        }
        $lesson->lecturerName = $lecturer->getName();
        //$lesson
        $this->returnObject($lesson);
    }

    /*
      public function addClass($subjectId, $roomId, $date, $time, $duration, $type){
      	$da = new ClassLessonDA($this->con);
      	$subjectDA = new SubjectDA($this->con);
      	$roomDA = new RoomDA($this->con);


      	$room = $roomDA->getRoomById($roomId);
      	$subject = $subjectDA->fetchSubjectById($subjectId);
      	if($room == false ){
      	    throw new \Exception("Invalid request received! Room cannot be found.");
      	    return;
      	}else if($subject == false){
      	    throw new \Exception("Invalid request received! Subject cannot be found.");
      	    return;
      	}


      	$lesson = new ClassLesson();
      	$lesson->setSubject($subjectId);
      	$lesson->setRoom($roomId);
      	$lesson->setDateTime($date, $time);
      	$lesson->setDuration($duration);
      	$lesson->setType($type);

      	try{
      	    $da->save($lesson);
      	    $this->sendMsg("Lesson successfully created!");
      	}catch(\Exception $ex){
      	    throw new \Exception("Failed to create a new lesson: ");
      	}
      }
    */

   /**
     * Search through the lesson table and returns
     * list of lessons in a
     * presentable format.
     * @param $query string Subject code
     **/
      public function searchLesson($query){
      	$lessonda = new SubjectDA($this->con);
      	$result = $lessonda->searchBySubjectId($query);
      	$this->returnObject($result);
      }

      /**
       * Returns all rooms
       **/
      public function listRooms(){
  	      $roomDA = new RoomDA($this->con);
          $list = $roomDA->getAllRooms();
  	      $this->returnObject($list);
      }

      /**
       * Add a new Room
       **/

      public function addRoom($name, $capacity){
      	$roomDA = new RoomDA($this->con);
      	$room = new Room();
      	$room->setName($name);
      	$room->setCapacity($capacity);

        try{
            $roomDA->save($room);
            $this->sendMsg("Successfully Created!");
        }catch(\Exception $ex){
            throw new \Exception("Failed to create a new room, the room already exists.");
        }
      }


      public function getLesson($id){
      	$lessonDA = new ClassLessonDA($this->con);
      	$this->returnObject($lessonDA->fetchClassById($id));
      }

      public function deleteLesson($id){
      	$lessonDA = new ClassLessonDA($this->con);
      	$lesson = $lessonDA->fetchClassById($id);
      	$success = $lessonDA->remove($lesson);
      	print $success;
      }

      /**
       * Returns all Lessons
       * for testing purpose
       **/
      public function listLessons(){
      	$classDA = new ClassLessonDA($this->con);

      	$list = $classDA->findAll();

      	$in = $list;
      	$out = [];
      	foreach($in as $v){
      	    // $roomda = new RoomDA($this->con);
      	    //$room = $roomda->getRoomById($v->roomID);
      	    $sda = new SubjectDA($this->con);
      	    $subject = $sda->fetchSubjectById($v->subjectID);
      	    if($subject == null){
      		      throw new \Exception("System error: The subject not found with id " . $v->subjectID);
      	    }

      	    $lda = new LecturerDA($this->con);
      	    $lecturer = $lda->fetchLecturerById($subject->lecturerID);
      	    if($lecturer == null){
      		      throw new \Exception("System error: The lecturer not found for subject "+ $subject->getId());
      	    }
      	    $o['id'] = $v->classID;
      	    $o['venue'] = $v->venue;
      	    $o['type'] = $v->type;
      	    $o['dateTime'] = $v->getDateTime();
      	    //  $o['date'] = '12331231';
      	    //  $o['time'] = "12321";
      	    $o['duration']= $v->getDuration();
      	    $o['lecturer'] = $lecturer->getName();
      	    $o['subjectID'] = $subject->getSubId();

      	    $out[] = $o;
         }
          $this->returnObject($out);
      }

      /*
         public function updateSubject($id, $name, Lecturer $lecturer){

      }*/

      public function editLesson($id, $venue, $type, $dateTime, $duration){
      	$lessonDA = new ClassLessonDA($this->con);
      	$lesson = $lessonDA->fetchClassById($id);
      	if($lesson == null){
      	    throw new Exception("Lesson not found!");
  	    }
  	    //setters for lesson object
      	$lesson->venue = $venue;
      	$lesson->type = $type;
      	$lesson->dateTime = $dateTime;
      	$lesson->duration= $duration;

      	//Do validation here.
      	$today = new DateTime();
        $today->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));
      	$datetime = new DateTime($dateTime, new DateTimeZone("Asia/Kuala_Lumpur"));
      	$date = $datetime->format("Y-m-d");
      	$validDayStart = new DateTime($date . " 08:30:00", new DateTimeZone("Asia/Kuala_Lumpur"));
      	$validDayEnd = new DateTime($date . " 18:00:00" , new DateTimeZone("Asia/Kuala_Lumpur"));
      	if($datetime < $today){
      	    $systime = $today->format("d-m-Y H:i:s");
                  throw new \Exception("The given date/time has already passed.. \n Current system date/time is {$systime}");
              }
      	//validate the time is between 5.30 to 6
      	if($datetime < $validDayStart || $datetime > $validDayEnd){
      	    throw new \Exception("Lesson can only take place between 8:30AM to 06:00PM ");
      	}

      	//validate duration
      	if($lesson->duration > 6)
      	    throw new \Exception("Duration cannot be longer than 6 hours.");
      	if($lesson->duration < 0)
      	    throw new \Exception("A lesson must atleast be one hour long.");

      	if($this->classClash($lesson)){
      	    throw new \Exception("Cannot create lesson for that time. Another class is taking place at the same venue, in the given time range");
      	}
  	    $lessonDA->save($lesson);
  	    $this->sendMsg("Successfully Updated!");
     }

      public function updateClassScheduling($id, $date, $time, $venue){
      	$rescheduleDA = new ClassReschedulingDA($this->con);
      	$record = $rescheduleDA->getApprovalRequest($id);
      	$dt = new DateTime($date. " ". $time, new DateTimeZone("Asia/Kuala_Lumpur"));
      	$today = new DateTime();
      	$today->setTimeZone(new DateTimeZone("Asia/Kuala_Lumpur"));

      	$validDayStart = new DateTime($date . " 08:30:00", new DateTimeZone("Asia/Kuala_Lumpur"));
      	$validDayEnd = new DateTime($date . " 18:00:00" , new DateTimeZone("Asia/Kuala_Lumpur"));

      	//$i = $today->diff($dt);
      	//$cannotCreate = ($i->format("%R") == "-");
      	if($dt < $today){
      	    throw new \Exception("Cannot update class schedule, date has already passed.");
      	}
      	if($dt > $validDayEnd || $dt < $validDayStart){
      	    throw new \Exception("Lesson can only take place between 8.30AM to 6PM.");
      	}

      	$record->setNewDateTime($date, $time);
      	$record->setVenue($venue);
      	$rescheduleDA->save($record);
      	$this->sendMsg("Successfully Updated!");
      }


    public function listLecturers(){
    	$lecturerDA = new LecturerDA($this->con);
    	$in = $lecturerDA->findAll();
    	$out = [];
    	foreach($in as $v){
    	    $o['lecturerID'] = $v->getId();
    	    $o['lecturerName'] = $v->getName();
    	    $out[] = $o;
    	}
    	$this->returnObject($out);
    }

    /**
     *  Checks for clashes.
     **/
    public function findClasses($venue, $date, $time, $duration){
    	$da = new ClassLessonDA($this->con);
    	$lessons = $da->getClassesOnDate($venue, $date);
    	if($lessons == null){
    	    throw new \Exception("No classes found for the given parameters");
    	}

    	$newLesson = new ClassLesson();
    	$newLesson->setDateTime($date, $time);
    	$newLesson->setDuration($duration);
    	$newLesson->venue = $venue;

    	$arr = [];
    	foreach($lessons as $lesson){
    	    $arr []=
    		[
    		    'id' => $lesson->classID,
    		    'startTime' => $lesson->getDateTime(),
    		    'endTime' => $lesson->getEndTime()->format("Y-m-d h:i:s"),
    		    'duration' => $lesson->getDuration(),
    		    'clash' => $lesson->isClashing($newLesson)
    		];
    	}
    	$this->returnObject($arr);
    }

    /******************************
    * END OF CLASS LESSON SECTION *
    *******************************/


    /******************
    * SUBJECT SECTION *
    *******************/

    /**
     * Returns all Subject
     * for testing purpose
     **/

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

    public function addSubject($id, $name, Lecturer $lecturer){
      $subjectDA = new SubjectDA($this->con);
      $subject = new Subject();
      $subject->subjectID = $id;
      $subject->lecturerID = $lecturer->lecturerID;
      $subject->subjectName = $name;

      $subjectDA->save($subject);
      $this->sendMsg("Successfully Created!");
    }

    public function deleteSubject($id){
      $subjectDA = new SubjectDA($this->con);

      if ($subjectDA->remove($id) == true){
          $this->sendMsg("Successfully Deleted!");
      }
      else
          throw new Exception("Error! Failed to delete subject!");
   }

    /*************************
    * END OF SUBJECT SECTION *
    **************************/


    /*****************
    * REPORT SECTION *
    ******************/

    public function viewReport(){
      $reportDA = new ReportDA($this->con);
      $result = $reportDA->getClassRescheduling();
      print(json_encode($result));
    }

    /************************
    * END OF REPORT SECTION *
    *************************/

    /****************************
    * PRIVATE FUNCTIONs SECTION *
    *****************************/

    private function classClash($newLesson){
    	$da = new ClassLessonDA($this->con);
    	$venue = $newLesson->venue;
    	$date = $newLesson->getDate();
    	$lessons = $da->getClassesOnDate($venue, $date);

    	if($lessons == null){
    	    return false;
    	}

    	$arr = [];
    	foreach($lessons as $lesson){
    	    if($lesson->isClashing($newLesson)){
        		throw new \Exception("{$lesson->type} for {$lesson->subjectID} is taking place at {$lesson->venue} at {$lesson->getStrStartTime()} to {$lesson->getStrEndTime()}");
        		return true;
    	    }
    	}
    	return false;
    }

    /***********************************
    * END OF PRIVATE FUNCTIONs SECTION *
    ************************************/
  }
?>
