<?php
helper("model/dummy_model");
class sync extends dummy_model{
    function __construct($db_ref){
        parent::__construct($db_ref);
    }
    function add_record($data=array()){
        $sql='insert into rms_sync_record set '.arr2set($data);
        $result=$this->db->query($sql);
        
    }

    function get_last_record($data=array()){
        $sql='select * from rms_sync_record where sync_name='.pq($data['sync_name']).' order by sync_time desc limit 1';
        $result=$this->db->query($sql);
        //print_r($result);
        if($result->num_rows<1){
            return NULL;
        }else{
            $sync_data=$result->fetch_assoc();
            return $sync_data['sync_time'];
        }
    }

}