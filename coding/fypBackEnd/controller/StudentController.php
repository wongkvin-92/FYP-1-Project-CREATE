<?php
class StudentController extends MasterController{
    public function __construct($con){
	     $this->con = $con;
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
                      'user' => $student
                  ];

                  $_SESSION['credentials'] = $obj;
                  print json_encode(['msg' => 'Login Sucessfully!', 'id'=> $student->studentID]);

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

      /************************
       *  END OF LOGIN SECTION *
       *************************/
}
?>
