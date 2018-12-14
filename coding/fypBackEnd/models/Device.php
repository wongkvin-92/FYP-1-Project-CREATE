<?php
class Device{
  public $id, $token, $type, $userID, $createDate, $uuid;

  public function getId(){
    return $this->id;
  }
  public function getToken(){
    return $this->token;
  }
  public function getType(){
    return $this->type;
  }
  public function getUserId(){
    return $this->userID;
  }
  public function getCreateDate(){
    return $this->createDate;
  }

}
?>
