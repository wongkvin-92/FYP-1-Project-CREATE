<?php
  class ReportDA extends DataAccessObject{

    public function __construct($con){
      if($con == null)
        throw new \Exception("ReportDA: No connection received!")
      $this->con = $con;
      $this->setPrimaryKey("id");
      $this->setTableName("report");
    }

    public function getClassRescheduling(){
      $retrieveData = $this->con->query("SELECT * FROM class_rescheduling");
    }


  }

?>
