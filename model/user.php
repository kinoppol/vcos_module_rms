<?php
helper("model/dummy_model");
class user extends dummy_model{
    function __construct($db_ref){
        parent::__construct($db_ref);
    }
   function clear_user($data=array()){
        $sql='delete from user_data where user_type_id != 1';
        //print $sql;
        $result=$this->db->query($sql);

        $sql='select max(id) as max_id from user_data';
        $result=$this->db->query($sql);
        
        $max_id_data=$result->fetch_assoc();

        $sql='ALTER TABLE user_data AUTO_INCREMENT = '.($max_id_data['max_id']+1);
        //print $sql;
        $result=$this->db->query($sql);

    }

    function add_user($data){
        $sql='insert into user_data set '.arr2set($data);
        $result=$this->db->query($sql);
    }

    function user_update($con,$data){
        $sql='update user_data set '.arr2set($data).' where '.arr2set($con);
        $result=$this->db->query($sql);
    }

}