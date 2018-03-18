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
  }
?>
