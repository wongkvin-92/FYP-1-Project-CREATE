<?php
  class ApprovalRequest{
    public $dateTime;

    public function getDateTime(){
      return $this->dateTime;
    }
    public function setDateTime($dT){
      $this->dateTime = $dT;
    }

  }
?>
