<?php
  class Reports{
    public $id,$classReschedulingID, $classRescheduling;

    public function __construct(){}

    public function getID(){
      return $this->$id;
    }

    public function getClassReschedulingID(){
      return $this->$classReschedulingID;
    }

    public function getClassRescheduling(){
      return $this->$classRescheduling;
    }

    public function setID($reportID){
      return $this->$id = $reportID;
    }

    public function setClassReschedulingID($id){
      return $this->ClassReschedulingID = $id
    }

    public function setClassRescheduling($cr){
      return $this->classRescheduling = $cr;
    }
  }
?>
