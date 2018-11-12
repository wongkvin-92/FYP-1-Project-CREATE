<?php
error_reporting(E_ALL);
//include 'vendor/autoload.php';
$loader =require_once __DIR__ . '/vendor/autoload.php';

//$loader->add("\\", "src/controller");

include 'dbconfig.php';
$debug_mode = false;

$klein = new \Klein\Klein();

$admin  = new AdminController($con);
$common = new MainControl($con);
$lecturer = new LecturerController($con);
$student = new StudentController($con);
$deviceController = new DeviceController($con);
$test = new TestController($con);


$DATA = file_get_contents('php://input');
//convert string -> object -> array
$DATA = json_decode($DATA, true);
//Pass $DATA as server variable, so it can be accessed anywhere.
$_SERVER['data'] = $DATA;
$root = "/fypBackEnd";

session_start();

function getPost($var){
    if(isset($_POST[$var])){
	if($_POST[$var] == ""){
	    throw new \Exception($var. " was empty!");
	}
	return $_POST[$var];
    }
    throw new \Exception($var. " was not set.");
}

function getData($var){
    $DATA = $_SERVER['data'];
    if(isset($DATA[$var])){
    	if($DATA[$var] == ""){
    	    throw new \Exception($var. " was empty!");
    	}
    	return $DATA[$var];
    }
    throw new \Exception($var. " was not set.");
}


$klein->respond('GET', $root.'/', function () {
    return 'index is working';
});

/***********************
 *  ADMIN ROUTE SECTION *
 ************************/


$klein->respond('POST', $root.'/admin/semester/', function($r) use ($admin){
    $data = json_decode($r->body());
    if($data == NULL)
	throw new \Exception("Empty data sent from the client");
    $semStartDate = $data->startDate;
    $semEndDate = $data->endDate;
    $admin->updateSemesterDate($semStartDate, $semEndDate);
});

$klein->respond('POST', $root.'/admin/login/', function() use ($admin){

    $email = getPost('email');
    $password = getPost('password');

    $admin->login($email,$password);
});

if($admin->checkLoginState()){ //Only perform if I am logged in


    $klein->respond('POST', $root.'/test/notify/', function($r) use ($test){
	$d = json_decode($r->body());
	$id = $d->lecturerID;
	$msg= $d->msg;
	$test->testNotification($id,$msg);
    });

    $klein->respond('GET', $root.'/admin/', function() use ($admin){
	if($admin->checkLoginState()){
  	    $admin->getCredentials();
	}else{
  	    return "false";
	}
    });

    $klein->respond('GET', $root.'/admin/logout/', function() use ($admin){
    	$admin->logout();
    });
    $klein->respond('GET', $root.'/classes/pending/count/', function() use ($admin){
    	$admin->countPendingRequest();
    });
    $klein->respond('GET', $root.'/classes/pending/', function() use ($admin){
    	$admin->viewPendingRequest();
    });
    //i = int, id = var name    //r = request
    $klein->respond('GET', $root.'/classes/[i:id]/approve/', function($r) use ($admin){
    	$admin->approveClass($r->id);
    });

    /*$klein->respond('GET', $root.'/test/[*:scode]/[*:type]/', function($r) use ($admin){
       $admin->testCreateClass($r->scode, $r->type);
       });*/

    $klein->respond('PATCH', $root.'/requests/rescheduling/[i:id]/', function($r) use ($admin){
    	$ex = null;
    	$date = getData('date');
    	$time = getData('time');
    	$venue = getData('venue');
    	$admin->updateClassScheduling( $r->id, $date,$time, $venue);
    });

    //Room routes
    $klein->respond('GET', $root.'/rooms/', function($r) use ($admin){
    	$admin->listRooms();
    });
    $klein->respond('POST', $root.'/rooms/', function($r) use ($admin){
    	$name = getPost('name');
    	$capacity = getPost('capacity');
    	$admin->addRoom($name, $capacity);
    });

    //Venue related routes
    $klein->respond('GET', $root.'/venues/[*:venue]/[*:date]/[*:time]/[*:duration]', function($r, $resp) use ($admin){
    	$venue = $r->venue;
    	$date = $r->date;
    	$time = $r->time;
    	$duration = $r->duration;
    	$admin->findClasses($venue, $date, $time, $duration);
    });

    $klein->respond('GET', $root.'/venues/', function($r, $resp) use ($con){
    	$controller = new TimetableController($con);
    	$controller->getAllVenues();
    });

    $klein->respond('GET', $root.'/timetable/[*:date]/', function($r, $resp) use ($con){
    	$controller = new TimetableController($con);
    	$controller->getDailySchedule($r->date);
    });

    //Lesson related routes
    $klein->respond('GET', $root.'/lessons/', function($r) use ($admin){
    	$admin->listLessons();
    });

    /*  $klein->respond('POST', $root.'/lessons/[*:code]/', function($r) use ($admin){
       $lecturerName = getPost('lecturerName');
       $subjectName = getPost('lecturerName');
       $date = getPost('date');
       $time = getPost('time');
       $venue = getPost('venue');

       print "AOK!";
       });
     */

    $klein->respond('GET', $root.'/lessons/search/[*:query]', function($req, $resp) use ($admin){
    	$query = $req->param('query');
    	if(isset($query)){
    	    $admin->searchLesson($query);
    	}else{
    	    throw new \Exception("No query received !");
    	}
    });

    $klein->respond('GET', $root.'/lessons/[i:id]/', function($req, $resp) use ($admin){
    	$id = $req->param('id');
    	if(isset($id)){
    	    $admin->getLesson($id);
    	}else{
    	    throw new \Exception("Invalid id received !");
    	}
    });

    $klein->respond('DELETE', $root.'/lessons/[i:id]/', function($req, $resp) use ($admin){
    	$id = $req->param('id');
    	if(isset($id)){
    	    $admin->deleteLesson($id);
    	}else{
    	    throw new \Exception("Invalid id received !");
    	}
    });


    /*   $klein->respond('GET', $root.'/lessons/[i:id]/', function($req, $resp) use ($admin){
       $id = $req->param('id');
       if(isset($id)){
       $admin->getLesson($id);
       }else{
       throw new \Exception("No query received !");
       }
       });*/

    $klein->respond('POST', $root.'/lessons/', function($r) use ($admin){
    	$sid = getPost('subjectID');
    	$sname = getPost('subjectName');
    	$lid = getPost('lecturer');
    	$rid = getPost('venue');
    	$date = getPost('date');
    	$time = getPost('time');
    	$duration = getPost('duration');
    	$type = getPost('type');
    	$admin->addNewSubjectLesson($sid, $rid, $date, $time, $duration, $type, $lid, $sname);
    });

    $klein->respond('PATCH', $root.'/lessons/[*:id]/', function($resp, $reply) use ($admin){
    	$id = $resp->id;
    	$venue = getData('venue');
    	$type = getData('type');
    	$dateTime = getData('dateTime');
    	$duration = getData('duration');
    	$admin->editLesson($id, $venue, $type, $dateTime, $duration);
    });

    //Lecturer related routes
    $klein->respond('GET', $root.'/lecturers/', function($r) use ($admin){
	$admin->listLecturers();
    });

    //Subject related routes
    $klein->respond('GET', $root.'/subjects/', function($r) use ($admin){
	$admin->listSubjects();
    });

    //use ($admin) user control, only admin can use
    $klein->respond('GET', $root.'/subjects/[*:id]/', function($req,$resp) use ($admin){
    	$id = $req->param('id');
    	$admin->getSubject($id);
    });
    $klein->respond('POST', $root.'/subjects/', function($r) use ($admin, $con){
    	$name = getPost('name');
    	$lecturerID = getPost('lecturer');
    	$code = getPost('code');
    	$lectDA = new LecturerDA($con);
    	$lecturer = $lectDA->fetchLecturerById($lecturerID);

    	if($lecturer == null)
    	{
    	    throw new \Exception("Lecturer not found!");
    	}
    	$admin->addSubject($code, $name, $lecturer);
    });

    $klein->respond('DELETE', $root.'/subjects/[a:id]/', function($r) use ($admin){
	$admin->deleteSubject($r->id);
    });

    $klein->respond('PATCH', $root.'/subjects/[a:id]/', function($r) use ($admin){
	print("Todo");
    });
    /*
       $klein->respond('POST', $root.'/lessons/', function($r) use ($admin){
       $sid = getPost('subjectID');
       $sname = getPost('subjectName');
       $lid = getPost('lecturer');
       $rid = getPost('venue');
       $date = getPost('date');
       $time = getPost('time');
       $duration = getPost('duration');
       $type = getPost('type');
       $admin->addNewSubjectLesson($sid, $rid, $date, $time, $duration, $type, $lid, $sname);
       });
     */
    $klein->respond('GET', $root."/reports/", function($r) use ($admin){
    	//print(json_encode(["msg" => "test123"]));
	$admin->viewReport();
    });





}

/*****************************
 * END OF ADMIN ROUTE SECTION *
 ******************************/



/************************
 *  LECTURER ROUTE SECTION *
 *************************/

$klein->respond('POST', $root.'/lecturers/login/', function() use ($lecturer){

    $email = getPost('email');
    $password = getPost('password');

    $lecturer->login($email,$password);
});


$klein->respond('GET', $root.'/login/lecturers/', function() use ($lecturer){
    if($lecturer->checkLoginState()){
	$lecturer->getCredentials();
    }else{
	return "false";
    }
});


if($lecturer->checkLoginState()){

    $klein->respond('GET', $root.'/lecturers/logout/', function() use ($lecturer){
    	$lecturer->logout();
    });

    $klein->respond('PATCH', $root.'/cancellation/reschedule/[i:id]/', function($r) use ($lecturer){
    	$date = getData('date');
    	$time = getData('time');

    	$lecturer->createCancellation($r->id, $date, $time);
    });

    $klein->respond('POST', $root.'/lessons/[i:id]/date/[*:date]/cancel/', function($r) use ($lecturer){

    	$lecturer->createCancellation($r->id, $r->date);
    });

    /*
       $klein->respond('DELETE', $root.'/cancellation/[i:id]/', function($r) use ($lecturer){
       $lecturer->deleteCancellation($r->id);
       }); */

    $klein->respond('DELETE', $root.'/reschedule/[i:id]/cancel/remove/', function($r) use ($lecturer){
    	$lecturer->deleteCancellation($r->id);
    });

    $klein->respond('GET', $root.'/lessons/', function($r) use ($lecturer){
    	$id = $lecturer->getLecturerID();
    	$lecturer->listLessonByLecturer($id);
    });

    $klein->respond('GET', $root.'/cancel/lecturer/list/', function($r) use ($lecturer){
    	$id = $lecturer->getLecturerID();
    	$lecturer->listOfCancellation($id, 'all');
    });

    $klein->respond('GET', $root.'/cancel/lecturer/list/filter/[*:filter_mode]', function($r) use ($lecturer){
    	$filter = $r->filter_mode;
    	$id = $lecturer->getLecturerID();
    	$lecturer->listOfCancellation($id, $filter);
    });


    $klein->respond('GET', $root.'/week/[i:weeknNumber]/lessons/', function($r) use ($lecturer){
    	$id = $lecturer->getLecturerID();
    	$lecturer->listConfirmedLessons($id, $r->weeknNumber);
    });


    $klein->respond('GET', $root.'/lecturers/schedule/[*:date]', function($r) use ($lecturer){
        $id = $lecturer->getLecturerID();
        $lecturer->listConfirmedLessonsForDate($id, $r->date);
    });

    $klein->respond('GET', $root.'/classes/approved/count/', function() use ($lecturer){
    	$lecturer->countApprovedRequest();
    });

    $klein->respond('GET', $root.'/classes/approved/', function() use ($lecturer){
    	$lecturer->viewApprovedList();
    });

    $klein->respond('PATCH', $root.'/reschedule/[i:id]/replace/', function($r) use ($lecturer){
    	$date = getData('date');
    	$time = getData('time');
    	//$filter = getData('filter');
    	$lecturer->applyClassReplacement($r->id, $date, $time);
    	//$passed_uri = parse_url($r->uri());
    	//var_dump($passed_uri['query']);
    });

    $klein->respond('POST', $root.'/reschedule/cancel/list/', function($r) use ($lecturer){
	$d = json_decode($r->body())->data;
	$lecturer->createCancellationList($d);
	//  var_dump($d);
    });



}


/********************************
 * END OF LECTURER ROUTE SECTION *
 *********************************/

/************************
 *  STUDENT ROUTE SECTION *
 *************************/

$klein->respond('POST', $root.'/student/login/', function() use ($student){

    $studentID = getPost('studentID');
    $password = getPost('password');

    $student->login($studentID,$password);
});


$klein->respond('GET', $root.'/state/student/', function() use ($student){
    if($student->checkLoginState()){
      	$student->getCredentials();
    }else{
      	return "false";
    }
});


//TODO: remove  this
/*$klein->respond('GET', $root.'/test/student/update/', function() use ($student){
   $student->updateSubjectList(['bit100', 'bit104']);
   });*/

$klein->respond('POST', $root.'/student/schedule/[*:date]', function($r) use ($student){
    //$id = $student->getStudentID();
    $subjectList = json_decode($r->body())->subjectList;
    $student->fetchStudentLessonForDate($subjectList, $r->date);
});


$klein->respond('GET', $root.'/subjects/enrolled/', function() use ($student){
    $student->getSubjectList();
});

$klein->respond('GET', $root.'/subjects/', function() use ($student){
    $student->listSubjects();
});

$klein->respond('POST', $root.'/student/all/schedule/hash/', function($r) use ($student){
    $subjectList = json_decode($r->body())->subjectList;
    if(gettype($subjectList)=="string"){ //bugfix for iphone
	$subjectList = json_decode($subjectList);
    }
    $student->fetchAllScheduleHash($subjectList);
});

$klein->respond('POST', $root.'/student/all/schedule/', function($r) use ($student){
    $subjectList = json_decode($r->body())->subjectList;
    if(gettype($subjectList)=="string"){ //bugfix for iphone
	$subjectList = json_decode($subjectList);
    }
    $student->fetchAllSchedule($subjectList);
});

/********************************
 * END OF STUDENT ROUTE SECTION *
 *********************************/




/************************
 *  DEVICE ROUTE SECTION *
 *************************/


$klein->respond('POST', $root.'/device/', function($r) use ($deviceController){
    $d = json_decode($r->body());

    if(isset($_SESSION['credentials']) && isset($_SESSION['credentials']['type']) ){
    	$c = $_SESSION['credentials'];

    	$token = $d->token;

	if($token == NULL)
            throw new \Exception("Empty token received.");

    	$type = $_SESSION['credentials']['type'];

    	$id_key = null;

    	if($type == "student")
    	    $id_key = "studentID";
    	else if($type=="lecturer")
    	    $id_key = "lecturerID";

        if($id_key == null){
            print(json_encode(["result" => false, "msg" => "Malformed session variable."]));
            http_response_code(501);
        }else{
            $userID = $c['user']->{$id_key};

	    $dev = $deviceController->fetchDevice($type, $userID);
	    if($dev == NULL){
		//Create a new device
        	$deviceController->createDevice($type,$userID,$token);
	    }else{
		$deviceController->updateDevice($token, $type, $userID);
	    }
    	}
    }else{
    	print(json_encode(["result" => false, "msg" => "Request to add device without login session set."]));
    	http_response_code(501);
    }
});



$klein->respond('GET', $root.'/device/[*:type]/[*:id]/', function($req,$resp) use ($deviceController){

    $type = $req->param('type');
    $id = $req->param('id');

    $deviceController->listMyDevices($type, $id);
});

$klein->respond('PATCH', $root.'/device/[*:type]/[*:id]/', function($req, $res) use ($deviceController){

    $type = $req->param('type');
    $id = $req->param('id');
    $token = getData('token');

    $deviceController->updateDevice($token,$type,$id);
});

$klein->respond('DELETE', $root.'/device/remove/[i:id]/', function($req, $res) use ($deviceController){
    $id = $req->param('id');
    $deviceController->deleteAllDevice($id);
});


/********************************
 * END OF DEVICE ROUTE SECTION *
 *********************************/


/**********************
 * Common routes
 **********************/


if($student->checkLoginState()){
    $klein->respond('GET', $root.'/student/logout/', function() use ($student){
      	$student->logout();
    });
}

$klein->respond('GET', $root.'/login/state/', function($req, $res) use ($common){
    $cr = $common->getCredentials();
    if($cr == false){
	$res->code(401);
	print(json_encode(["result" => false, "msg" => "No user logged in."]));
    }else{
	print(json_encode($cr));
    }
});

$klein->respond('GET', $root.'/semester/', function($req, $res) use ($common){
    $common->getSemesterDate();
});

$klein->respond('GET', $root.'/logout/', function($req, $res) use ($common){
    $common->logout();
});


$klein->respond('POST', $root.'/login/', function($req, $res) use ($common){
    $email = getPost('email');
    $password = getPost('password');
    $common->login($email, $password);
});


/*******************
 * End of common routes
 ***********************/



$klein->respond('GET', $root.'/test/', function() use ($lecturer){
    require("./test.php");
});



$klein->onHttpError(function(){
    print("ERROR OCCURED!");
});

try{
    $klein->dispatch();
}catch(\Exception $ex){
    $response = [
	'result' => 'error',
	'msg' => $ex->getMessage()
    ];
    if($debug_mode){
	$topTrace = $ex->getTrace()[sizeof($ex->getTrace())-1];
	$response['exception'] = [
	    'file' => $ex->getFile(),
	    'line' => $ex->getLine(),
	    'trace' => $topTrace,
	    'str' => $ex->__toString()
	];
    }
    print(json_encode($response));
    http_response_code(400);
}
?>
