<?php
/**
 * A Generalization of all DAO objects.
 **/
class DataAccessObject{
    private $className;  //Store the model name here.
    private $tableName; //assumed to be lowercase of model name by default
    private $primaryKey;
    protected $con;

    /**
     * Constructor
     * @param $con A valid SQLi connection object.
     * @param $className Name of the Model
     **/
    public function __construct($con, $className){
        if($con == null)
            throw new \Exception("{$className}DA: No connection received");
        $this->tableName = strtolower($className);
        $this->className = $className;
        $this->primaryKey = 'id';
        $this->con = $con;
    }

    public function setTableName($tableName){
        $this->tableName = $tableName;
    }

    public function getTableName(){
        return $this->tableName;
    }

    public function setPrimaryKey($pk){
        $this->primaryKey = $pk;
    }

    public function getPrimaryKey(){
        return $this->primaryKey;
    }


    /**
     * Retrieve a list of objects that has the given attribute set.
     * Model must have a default constructor, and public attributes.
     * <br> Do a simple select * from 'table' where 'key' = 'val'
     * @param $key column name
     * @param $val value
     **/
    public function getListByAttribute($key, $val){
        $q = "SELECT * FROM {$this->tableName} WHERE {$key} ='{$val}';";
        $result = $this->con->query($q);
        if($result == false)
            return false;

        $arr = [];
        while($obj = $result->fetch_object($this->className)){
            $arr[] = $obj;
        }

        return $arr;
    }

    public function findAll(){
        $q = "SELECT * FROM {$this->tableName};";
        $result = $this->con->query($q);
        if($result == false)
            return false;

        $arr = [];
        while($obj = $result->fetch_object($this->className)){
            $arr[] = $obj;
        }
        return $arr;
    }


    /**
     * Must save the object to the database.
     **/
    public function save($o){
        if(!isset($o->{$this->getPrimaryKey()}))
            $pk = null;
        else
            $pk = $o->{$this->getPrimaryKey()};

        $cond = "{$this->getPrimaryKey()} = '{$pk}'";
        $vars = get_object_vars($o);
        $vars = array_filter($vars);

        $qStr = "";
        $terms = count($vars);
        foreach ($vars as $field => $value)
        {
            $terms--;
            $qStr .= $field . ' = ' . "'{$value}'";
            if ($terms)
            {
                $qStr .= ' , ';
            }
        }

        if($pk == null){
            $keys = implode(",", array_keys($vars));
            $vals = implode("','", $vars);
            $q = "INSERT INTO {$this->tableName} ({$keys}) VALUES ('{$vals}');";
        }else{
            $q = "UPDATE {$this->tableName} SET {$qStr} WHERE {$cond};";
        }
        $result =  $this->con->query($q);

        if($result == false){
            throw new \Exception("Failed to create new {$this->className}: Query '{$q}' failed" );
        }

        return true;
    }
}
?>
