<?php
class RoomDA extends DataAccessObject {
    public function __construct($con){
        parent::__construct($con, "Room");
        $this->setPrimaryKey('roomID');      
    }

    public function getRoomById($id){
        
    }

    public function getAllRooms(){
        //$q = "SELECT * FROM `room`;";
        //$result = $this->con->query($q);
        return $this->findAll();
    }

    /** todo
    public function getAvailableRooms(){

    }**/
}
