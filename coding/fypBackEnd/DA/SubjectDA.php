<?php

class SubjectDA extends DataAccessObject{
    public function __construct($con){
        parent::__construct($con, "Subject");
    }

    /**
     *retrieves the subject given the id
     **/
    function fetchSubjectById($id){
	$result = $this->con->query("SELECT * FROM subject WHERE subjectID='$id'");
	return $result->fetch_object('Subject');
    }

    function fetchSubjectsByLecturer($id){
	$result = $this->con->query("SELECT * FROM subject WHERE lecturerID='$id'");
	$res = [];
	while($obj = $result->fetch_object()){
	    $res []= $obj;
	}	  
	return $res;	  
    }
    
    function fetchSubjectCodesByLecturer($id){
	$result = $this->con->query("SELECT * FROM subject WHERE lecturerID='$id'");
	$res = [];
	while($obj = $result->fetch_object()){
	    $res []= $obj->subjectID;
	}	  
	return $res;	  
    }


    function remove($id){
	$result = $this->con->query("DELETE FROM subject WHERE subjectID = '$id';");	
	return $this->con->affected_rows != 0;
    }

    /**
     * Retrieves a list of subjects similar to the given subject code.
     **/
    function searchBySubjectId($code){
        $result = $this->con->query("SELECT * FROM subject WHERE subjectID LIKE '%{$code}%';");
        $arr = [];
        while($o = $result->fetch_object()){
            $arr [] = $o;
        }
        return $arr;
        
    }

}

?>
