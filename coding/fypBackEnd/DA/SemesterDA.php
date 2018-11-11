<?php
class SemesterDA extends DataAccessObject{
    public function __construct($con){
	parent::__construct($con, "Semester");
	$this->setTableName("semester");
	$this->setPrimaryKey("id");
    }

    public function fetchSemester(){
        $result = $this->con->query("SELECT * FROM semester WHERE id='0'");
        	if($result){
        	    $obj = $result->fetch_object('Semester');
        	    return $obj;
        	}else{
        	    return new Semester();
        	}
    }

}
