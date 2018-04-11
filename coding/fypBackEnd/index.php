<?php
error_reporting(E_ALL);
//include 'vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';
include 'dbconfig.php';

$klein = new \Klein\Klein();

$admin  = new AdminController($con);

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

$klein->respond('POST', $root.'/admin/login/', function() use ($admin){

  $email = getPost('email');
	$password = getPost('password');

  $admin->login($email,$password);
});

$klein->respond('GET', $root.'/admin/', function() use ($admin){
  if($admin->checkLoginState()){
    $admin->getCredentials();
  }else{
    return "false";
  }
});

if($admin->checkLoginState()){ //Only perform if I am logged in
  $klein->respond('GET', $root.'/admin/logout/', function() use ($admin){
    $admin->logout();
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


}

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
  print(json_encode($response));
  http_response_code(400);
}
 ?>
