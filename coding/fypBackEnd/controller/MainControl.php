<?php

class MainControl extends MasterController{
  function login(){
    $da = new User();
    print "Login code reached";


  }

  function logout(){
    print "logout code here";
  }
}
 ?>
