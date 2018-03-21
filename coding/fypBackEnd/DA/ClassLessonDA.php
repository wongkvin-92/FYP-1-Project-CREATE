<?php

/**
 *
 **/
class ClassLessonDA extends DataAccessObject{
    public function __construct($con){
        parent::__construct($con, "ClassLesson");
        $this->setTableName("class_lesson");
    }

    public function getClassesBySubject(Subject $s){
        $fk = $s->getSubId();
        return  $this->getListByAttribute('id', $fk);
    }

    public function getClassById($id){
        $this->con->query("SELECT * FROM class_lesson WHERE classID = '{$id}';");
        return $result->fetch_object('ClassLesson');
    }

}

?>
