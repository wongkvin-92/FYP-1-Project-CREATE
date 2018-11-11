<?php

class DeviceDA extends DataAccessObject {
    public function __construct($con){
	parent::__construct($con, "Device");
	if($con == null)
            throw new \Exception("ClassReschedulingDA: No connection received!");
	$this->con = $con;
	$this->setTableName("device_list");
    }

    /*
       public function createDevice($token, $type, $userID){
       $query = <<<EOF
       INSERT INTO `device_list`(`token`, `type`, `userID`) VALUES ('$token', '$type', '$userID');
       EOF;
       }*/


    
    /**
     * Fetch the first device as a Model
     *
     * @return Device object on success.
     **/
    public function getFirstDevice($type, $userID){
	$query = <<<EOF
        SELECT * FROM `device_list` WHERE `type`='$type' AND `userID` ='$userID';
EOF;
	return $this->con->query($query)->fetch_object('Device');
    }



    
    
    public function getDevices($type, $userID){
        $query = <<<EOF
        SELECT * FROM `device_list` WHERE `type`='$type' AND `userID` ='$userID';
EOF;
	return $this->con->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function updateDevices($token, $type, $userID){
        $query = <<<EOF
        UPDATE `device_list` SET `token`='$token' WHERE `type` = '$type' AND userID='$userID';
EOF;
	return $this->con->query($query);
    }

    public function deleteDevices($userID){
        $query = <<<EOF
        DELETE FROM `device_list` WHERE `userID` ='$userID';
EOF;
	return $this->con->query($query);
    }

}
?>
