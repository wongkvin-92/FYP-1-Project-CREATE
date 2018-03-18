<?php
error_reporting(E_ALL);
//include 'vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';
include 'dbconfig.php';

$klein = new \Klein\Klein();

$admin  = new AdminController($con);

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

    //Lesson related routes
  $klein->respond('GET', $root.'/subjects/', function($r) use ($admin){
    $admin->listSubjects();
  });
  $klein->respond('POST', $root.'/subjects/', function($r) use ($admin, $con){      
      $name = getPost('name');
      $lecturerID = getPost('lecturer');
      $code = getPost('code');
      $lectDA = new LecturerDA($con);
      $lecturer = $lectDA->fetchLecturerById($lecturerID);
      $admin->addSubject($code, $name, $lecturer);
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
