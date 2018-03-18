<?php
  class Admin extends User{
    public $adminID;

    public function getAdminId(){
      return $this->adminID;
    }

    public function setAdminId($id){
      $this->adminID = $id;
    }
  }
 ?>
