<?php
class Semester{
    public $id, $start_date, $end_date;

      public function setSemester($s, $e){
      	$this->id = "0";
      	$this->start_date = $s;
      	$this->end_date = $e;
      }
}
?>
