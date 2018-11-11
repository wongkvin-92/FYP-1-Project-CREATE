<?php


class TestController extends MasterController{
    public function __construct($con){
	$this->notifyService = new NotificationService();
	$this->con = $con;
    }

    public function testNotification($id, $msg){

	$deviceDA = new DeviceDA($this->con);
	$device = $deviceDA->getFirstDevice('lecturer', $id);

	if($device){
	    $this->notifyService->sendNotificationToLecturer($device->token, $msg);
	    $this->sendMsg("Success");
	}else{
	    new Exception("Sorry! Lecturer has no device registered to send notification to.");
	}
    }
}
?>
