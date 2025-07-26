<?php
helper("model/dummy_model");
class semester extends dummy_model{
    function __construct($db_ref){
        parent::__construct($db_ref);
    }
      function clear($data=array()){
        $sql='delete from rms_semester';
        //print $sql;
        $result=$this->db->query($sql);
    }

     function add($data){
        $sql='insert into rms_semester set '.arr2set($data);
        //print $sql;
        $result=$this->db->query($sql);
    }

}