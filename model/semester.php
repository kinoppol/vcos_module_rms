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

    function getSemester($data=array()){
        $sql='select * from rms_semester order by semester_start desc';
        if(count($data)>=1){
            $sql.= ' where '.arr2set($data);
        }
        //print $sql;
        $result=$this->db->query($sql);

        $ret=array();
        while($s=$result->fetch_assoc()){
            $ret[$s['semester_eduyear']]=$s;
        }

        return $ret;
    }

    function getCurrentSemester($presentDay=''){
        if(empty($presentDay)){
            $presentDay=date('Y-m-d');
        }
        $sql='select * from rms_semester where semester_start <='.pq($presentDay).' AND semester_end >='.pq($presentDay);

        //print $sql;
        $result=$this->db->query($sql);

        if($result->num_rows<1){
            return false;
        }else{
            $r=$result->fetch_assoc();
            return $r['semester_eduyear'];
        }
    }

}