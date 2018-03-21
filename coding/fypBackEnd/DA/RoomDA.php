<?php
class RoomDA extends DataAccessObject {
    public function __construct($con){
        parent::__construct($con, "Room");
        $this->setPrimaryKey('roomID');
    }

    public function getRoomById($id){

    }

    public function getAllRooms(){
        return $this->findAll();
    }

    /* todo
    public function getAvailableRooms(){

    }**/
}
