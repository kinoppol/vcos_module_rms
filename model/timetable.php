<?php
helper("model/dummy_model");
class timetable extends dummy_model{
    function __construct($db_ref){
        parent::__construct($db_ref);
    }
      function clear($data=array()){
        $sql='delete from rms_timetable';
        if(!empty($data)){
            $sql.=' where '.arr2and($data);
        }
        print $sql;
        $result=$this->db->query($sql);
    }

     function add($data){
        $sql='insert into rms_timetable set '.arr2set($data);
        //print $sql;
        $result=$this->db->query($sql);
    }

}