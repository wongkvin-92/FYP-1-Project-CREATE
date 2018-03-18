<?php
class MasterController{
    public function returnJSON($param){
      print(json_encode($param));
    }

    public function returnObject($params){
      print(json_encode(unserialize(serialize($params))));
    }

    public function sendMsg($param){
      print(json_encode(
        [
          'msg' => $param
        ]
      ));
    }
}
