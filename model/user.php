<?php
helper("model/dummy_model");
class user extends dummy_model{
    function __construct($db_ref){
        parent::__construct($db_ref);
    }
    function clear_user($data=array()){
        $sql='delete from user_data where user_type_id > 1';
        //print $sql;
        $result=$this->db->query($sql);
        
    }

}