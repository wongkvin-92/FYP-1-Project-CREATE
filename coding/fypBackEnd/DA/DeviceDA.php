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

    public function getAllDevices($type, $userID){
      $query = <<<EOF
            SELECT * FROM `device_list` WHERE `type`='$type' AND `userID` ='$userID';
EOF;
      $result = $this->con->query($query);
      $devices = [];
      while($dev = $result->fetch_object("Device")){
        $devices []= $dev;
      }
      return $devices;
    }

    /**
     * Fetch the first device as a Model
     *
     * @return Device object on success.
     **/
    public function getDeviceByID($uid){
	$query = <<<EOF
        SELECT * FROM `device_list` WHERE `uuid` = '$uid';
EOF;
	return $this->con->query($query)->fetch_object('Device');
    }







    public function getDevices($type, $userID){
        $query = <<<EOF
        SELECT * FROM `device_list` WHERE `type`='$type' AND `userID` ='$userID';
EOF;
	return $this->con->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function updateDevices($token, $type, $userID, $uid){
        $query = <<<EOF
        UPDATE `device_list` SET `token`='$token', `type`='$type', `userID` = '$userID'  WHERE uuid='$uid';
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
