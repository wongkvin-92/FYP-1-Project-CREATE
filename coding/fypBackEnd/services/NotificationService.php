<?php

class NotificationService{
    function __construct(){
	$this->url = 'https://fcm.googleapis.com/fcm/send';
	$this->auth_key = "AAAAIYdyeIE:APA91bEJ358NfEDtozXWxw71kEQvBNBu5dt-0ZD-INfQuZP7FEG84O5hfyp9U_3F0YyO4ORhjxvo0aMgLlPJ-iAnSr3J7bVQlFCRoC5BCdPUn2mZxeaiWOc3_eLery2eOShk-5eWV02d"; //@FIXME: This key should not be here or pushed to the public repository!!
    }


    function sendNotificationToLecturer($dev_token, $msg){
	//sends notification to the specific lecturer
	$this->dispatchNotification($dev_token, $msg);
    }

    function sendNotificationsToManyLecturers($dev_array, $msg){
    	$dev_str = "";
    	foreach($dev_array as $d){
    	    $dev_str .= $d;
	}
	$this->dispatchNotification($dev_token, $msg);
    }


public function dispatchNotification($devstr, $msg){

  	$curl = curl_init();

  	//devtoken:
  	//fqC3PxjWD2Y:APA91bHXgOWBng97LRoidx5Ux_wPUBghPwihw7wOd1KgHVVTFxITu4iF3heIKy7FaODynzK0N1SIRxJh7ftasJZPAxj2NbUWQJ6T-3ETkmNUus92YhhLgEPpCR9kp6aRqRohOCd6BMM8
  	$d = $devstr;
    	curl_setopt_array($curl, array(
  	    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
  	    CURLOPT_RETURNTRANSFER => true,
  	    CURLOPT_ENCODING => "",
  	    CURLOPT_MAXREDIRS => 10,
  	    CURLOPT_TIMEOUT => 30,
  	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	    CURLOPT_CUSTOMREQUEST => "POST",
  	    CURLOPT_POSTFIELDS => "{\n\t\"to\":\"{$d}\", \n\t\"notification\": {\"body\":\"{$msg}\"}, \"priority\":10\n}",
  	    CURLOPT_HTTPHEADER => array(
  		"authorization: key=AAAAIYdyeIE:APA91bEJ358NfEDtozXWxw71kEQvBNBu5dt-0ZD-INfQuZP7FEG84O5hfyp9U_3F0YyO4ORhjxvo0aMgLlPJ-iAnSr3J7bVQlFCRoC5BCdPUn2mZxeaiWOc3_eLery2eOShk-5eWV02d",
  		"content-type: application/json"
  	    ),
  	));

  	$response = curl_exec($curl);
  	$err = curl_error($curl);

  	curl_close($curl);

  	if ($err) {
  	    throw \Exception("Failed to send to {$devstr}");
  	} else {
  	    return true;
  	}
  }

      function sendNotificationToStudents($studentList, $tmp){

      }
};


?>
