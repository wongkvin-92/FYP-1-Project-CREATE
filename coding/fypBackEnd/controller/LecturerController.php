<?php
class LecturerController extends MasterController{
  public function __construct($con){
    $this->con = $con;
  }

  /*****************
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
      		print json_encode(['msg' => 'Login Sucessfully!']);

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

    public function deleteCancellation($classID){
      $reschedulingDA = new ClassReschedulingDA($this->con);

      $rescheduling = $reschedulingDA->fetchClassById($classID);
      if($rescheduling->status == "approved")
        throw new Exception("Only pending request can be removed");
      if(  $reschedulingDA->remove($rescheduling)){
        $this->sendMsg("Successfully removed!");
      }
      else{
        throw new Exception("Failed to remove!");
      }
    }

    public function listLessonByLecturer($lecturerID){
      $lessonDA = new ClassLessonDA($this->con);
      $list = $lessonDA->getLessonByLecturer($lecturerID);
      print(json_encode($list));
    }

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




    /*******************************
    *  END OF CANCELLATION SECTION *
    ********************************/
  }
 ?>
