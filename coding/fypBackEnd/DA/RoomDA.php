<?php
class RoomDA extends DataAccessObject {
    public function __construct($con){
        parent::__construct($con, "Room");
        $this->setPrimaryKey('roomID');
    }

    public function getRoomById($id){
      $result = $this->con->query("SELECT * FROM room WHERE roomID = '$id';");
      return $result->fetch_object('Room');
    }

    public function getAllRooms(){
        return $this->findAll();
    }

}
