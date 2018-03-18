<?php

  /**
  * User is an abstract class.
  **/
  class User {
    public $adminID, $adminEmail, $adminName, $adminPass;

    public function __construct(){

    }

    public function getId(){
      return $this->adminID;
    }

    public function getEmail(){
      return $this->adminEmail;
    }

    public function getName(){
      return $this->adminName;
    }

    public function checkPass($pass){
      return $this->adminPass == $pass;
    }

    public function setEmail($email){
      $this->adminEmail = $email;
    }

    public function setName($name){
      $this->adminName = $name;
    }

}


 ?>
