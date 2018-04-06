<?php
  class AdminController extends MasterController{
    private $con;
    public function __construct($con){
      $this->con = $con;
    }

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
          $arr[] =$o;
        }
        $this->returnJSON($arr);
                //print json encode data
        //$this->returnObject($result);
      } else{
          throw new \Exception("No Pending Request!");
      }
    }

    public function approveClass($id){
                          //link to db
      $da = new ClassReschedulingDA($this->con);
      $rqApprove = $da->getApprovalRequest($id);

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

      /**
       * Creates a new subject if subject does not exist.
       **/
      public function addNewSubjectLesson($sid, $rid, $date, $time, $duration, $type, $lid, $sname){
          $subjectDA = new SubjectDA($this->con);
          $lecturerDA = new LecturerDA($this->con);

          $da = new ClassLessonDA($this->con);
          $subject = $subjectDA->fetchSubjectById($sid);
          //$lecturer = $lecturerDA->fetchLecturerByName($lname);
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

          $lesson = new ClassLesson();
          $lesson->subjectID = $subject->subjectID;
          $lesson->setDateTime($date, $time);
          //$lesson->setRoom($rid);
          $lesson->venue = strtolower($rid);
          $lesson->setDuration($duration);
          $lesson->setType($type);
          if($da->save($lesson) != true){
              throw new \Exception("Cannot create lesson!");
          }
          $lesson->lecturerName = $lecturer->getName();
          //$lesson
          $this->returnObject($lesson);
      }

      /*      public function updateLesson($lessonID, $date, $time, $venue){

              }*/

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
              throw new \Exception("Failed to create a new lesson");
          }
      }

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


/*
      public function getLesson($id){
        $lessonDA = new ClassLessonDA($this->con);
        returnObject($lessonDA->getClassById($id));
      }*/

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

      /**
       * Returns all Lessons
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

      public function updateSubject($id, $name, Lecturer $lecturer){

      }

      public function updateClassScheduling($id, $date, $time){
        $rescheduleDA = new ClassReschedulingDA($this->con);
        $record = $rescheduleDA->getApprovalRequest($id);
        $record->setNewDateTime($date, $time);
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

  }
?>
