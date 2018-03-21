<?php

/**
 * Room Model holds the venue where a class will take
 * place.
 *
*Precondition: Table with name and capacity attributes must exists.
 **/

// doesnt need to add room from UI, manually add in from phpmyadmin
class Room{
    public $roomID, $name, $capacity;

    public function setCapacity($capacity){
        $this->capacity = $capacity;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getCapacity(){
        return $this->capacity;
    }

    public function getName(){
        return $this->name;
    }
}
?>
