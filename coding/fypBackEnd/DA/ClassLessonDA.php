<?php

/**
 *
 **/
class ClassLessonDA extends DataAccessObject{
    public function __construct($con){
        parent::__construct($con, "ClassLesson");
        $this->setTableName("class_lesson");
        $this->setPrimaryKey("classID");
    }

    public function getClassesBySubject(Subject $s){
        $fk = $s->getSubId();
        return  $this->getListByAttribute('id', $fk);
    }

    public function getClassById($id){
        $result = $this->con->query("SELECT * FROM class_lesson WHERE classID = '{$id}';");
        return $result->fetch_object('ClassLesson');
    }

    public function getLinkedClassById($id){
        $q = "SELECT * FROM class_lesson, subject, room WHERE classID = '{$id}' AND roomID = room.roomID AND subjectID = subject.subjectID;";
        $result = $this->con->query($q);
        //$o = [];

        // $result->fetch_object('');
        //while($obj = $result->fetch_object('ClassLesson')){
            //$subjectDA =
        //}
        return $result;
    }

    public function numExistingClasses($lesson){
      $code = $lesson->subjectID;
      $type = $lesson->type;
      $q = "SELECT count(*) FROM `class_lesson` WHERE subjectID = \"{$code}\" and type = \"{$type}\" ";
      $result = $this->con->query($q);
      return $result->fetch_array()[0];
    }

    public function save($o){
      $ret = parent::save($o);
      $o->classID = $this->con->insert_id;
      return $ret;
    }

}

?>
