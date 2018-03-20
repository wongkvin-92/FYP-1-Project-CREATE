<?php

  class UserDA {
    private $con;
    public function __construct($con){
      if($con == null)
        throw new \Exception("UserDA: No connection received");
      $this->con = $con;
    }

    //returns the user by email
    function fetchUserByEmail($email){
      $result = $this->con->query("SELECT * FROM admin WHERE adminEmail='$email'");

      return $result->fetch_object('User');
    }

  }

 ?>
