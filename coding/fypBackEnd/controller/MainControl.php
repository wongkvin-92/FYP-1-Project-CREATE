<?php

class MainControl extends MasterController{
    public function __construct($con){
	$this->con = $con;
    }

    function getCredentials(){
	if(isset($_SESSION['credentials']) ){
	    $type = $_SESSION['credentials']['type'];
	    $u = $_SESSION['credentials']['user'];
	    $obj = [];

	    if($type == "lecturer"){
    		$obj['id'] = $u->lecturerID;
    		$obj['name'] = $u->lecturerName;
    		$obj['email'] = $u->lecturerEmail;
    	    }else if($type == "admin"){
    		$obj['id'] = $u->adminID;
    		$obj['name'] = "Admin";
    		$obj['email'] = "itadmin@help.edu.my";
    	    }else if($type=="student"){
    		$obj['id'] = $u->studentID;
    		$obj['name'] = $u->studentName;
    		$obj['email'] = $u->studentEmail;
	    }

	    return [
    		'result' => true,
    		'type' => $type,
    		'id' => $obj['id'],
    		'name' => $obj['name'],
    		'email' => $obj['email'],

	    ];
	}else{
	    return false;
	}
    }


    public function logout(){
    	session_destroy();
      if($_SESSION)
        unset($_SESSION);
    	print(json_encode(['msg'=> 'You\'re successfully logged out!' ]));
    }

    function login($email, $pass){
    	//Check if it is a lecturer
    	$lecturerda = new LecturerDA($this->con);
    	$lecturer = $lecturerda->fetchLecturerByEmail($email);

    	$studentDA = new StudentDA($this->con);
    	$student = $studentDA->fetchStudentById($email);

      $semDA = new SemesterDA($this->con);
      $sem = $semDA->fetchSemester();

    	//IF lecturer login as lecturer
    	if($lecturer != null){
    	    if($lecturer->lecturerPassword == $pass){
        		$obj = [
        		    'type' => 'lecturer',
        		    'user' => $lecturer
        		];
        		unset($obj['user']->lecturerPassword);
        		$_SESSION['credentials'] = $obj;
        		print(json_encode(['result'=> true, 'msg' => 'Login Sucessfully!', 'id'=> $lecturer->lecturerID, 'credentials' => $this->getCredentials(), 'sem' => $sem ] ));
        	    }else{
    		      throw new Exception("Incorrect username/password!");
    	    }
    	}else if($student != null){
    	    if($student->getPass() == $pass){
    		$obj = [
    		    'type' => 'student',
    		    'user' => $student,
    		    'subject_list' => []
    		];
    		unset($obj['user']->studentPassword);
    		$_SESSION['credentials'] = $obj;
    		print(json_encode(['result'=> true, 'msg' => 'Login Sucessfully!', 'id'=> $student->studentID, 'credentials' => $this->getCredentials(), 'sem' => $sem ]));
    	    }else{
    		throw new Exception("Incorrect username/password!");
    	    }
    	}else{
    	    throw new \Exception("User Not Found!");
    	}
    }


    public function getSemesterDate(){
	$semDA = new SemesterDA($this->con);
	$sem = $semDA->fetchSemester();
	if($sem->id == null)
	    throw new \Exception("Semester not set by admin");
	print(json_encode($sem));
    }

}
?>
