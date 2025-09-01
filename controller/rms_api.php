<?php
class rms_api{
  function getPersonal(){
    global $module;
    global $system;
    $module->helper('rms');
    $users=api_load('people');
    //print_r($user_data);
    //$users=json_decode($user_data);
    
    if(!is_array($users)){//If result not an array skip import
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'Return value is not array'));
      print redirect(module_url('rms','import'));
      //return $ret;
    }else if(count($users)<1){//If result is empty skip import   
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'Return value is zero user'));
      print redirect(module_url('rms','import'));
      //return $ret;
    }

    $user_model = $module->model('user');
    $user_model->clear_user();

    foreach($users as $u){

      $username=$u['people_id'];
      if(!empty($u['people_user'])){
        $username=$u['people_user'];
      }
      $data=array(//Binding values between systems
        'citizen_id'=>$u['people_id'],
        'username'=> $username,
        'password'=> $u['people_pass'],
        'name'=> $u['people_name'],
        'surname' => $u['people_surname'],
        'email'=>$u['people_email'],
        'user_type_id'=>0,
        'active'=>0,
        'picture'=>empty($u['people_pic'])?$u['people_pic']:$system['rms_url']."/files/".$u['people_pic'],
      );
      $user_model->add_user($data);
    }
    
    $sync_record=$module->model('sync');
    $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'ok'));

    $ret['content']="นำเข้าข้อมูลบุคลากรเรียบร้อย กำลังโหลดค่าตำแหน่งรับผิดชอบ โปรดรอสักครู่..";
    $ret['content'].= redirect(module_api('rms','rms_api','get_people_pro'));
    return $ret;
  }

  function get_people_pro(){
    global $module;
    global $system;
    $module->helper('rms');
    $user_model = $module->model('user');

    $pro=api_load('people_pro');
    $dep=api_load('people_dep');
    $stagov=api_load('people_stagov');

    if(count($dep)>0){
      $user_model->clear_dep();
    }

    foreach($dep as $d){
      $data=array();
      $data['dep_id']=$d['people_dep_id'];
      $data['dep_group_id']=$d['people_depgroup_id'];
      $data['dep_name']=$d['people_dep_name'];
      $user_model->add_dep($data);


      if($d['people_dep_name']=='งานพัฒนาหลักสูตรการเรียนการสอน'){//ค้นหา id งานหลักสูตร
        $people_dep_id=$d['people_dep_id'];
        continue;
      }
    }

    foreach($stagov as $s){
      if($s['people_stagov_name']=='ผู้ดูแลระบบ'){//ค้นหา id ผู้ดูแลระบบ
        $admin_id=$s['people_stagov_id'];
      }
      if($s['people_stagov_name']=='หัวหน้างาน'){//ค้นหา id หัวหน้างาน
        $admin_cur_id=$s['people_stagov_id'];
      }
      if($s['people_stagov_name']=='ผู้ช่วยหัวหน้า'){//ค้นหา id ผู้ช่วยหัวหน้า
        $assoc_cur_id=$s['people_stagov_id'];
      }
      if($s['people_stagov_name']=='เจ้าหน้าที่'){//ค้นหา id เจ้าหน้าที่
        $staff_cur_id=$s['people_stagov_id'];
      }
      if($s['people_stagov_name']=='ผู้ช่วยงาน'){//ค้นหา id ผู้ช่วยงาน
        $user_cur_id=$s['people_stagov_id'];
      }
    }

    

    //$people_dep_id=322;//งานหลักสูตร
    //print $people_dep_id;
    $stagov_user_type=array();
    if(!empty($admin_id))$stagov_user_type[$admin_id]=2;//ผู้ดูแลระบบ
    if(!empty($admin_cur_id))$stagov_user_type[$admin_cur_id]=2;//หัวหน้างาน
    if(!empty($assoc_cur_id))$stagov_user_type[$assoc_cur_id]=3;//ผู้ช่วยหัวหน้า
    if(!empty($staff_cur_id))$stagov_user_type[$staff_cur_id]=3;//เจ้าหน้าที่งาน
    if(!empty($user_cur_id))$stagov_user_type[$user_cur_id]=4;//ครูช่วยงาน
    //print_r($stagov_user_type);

    $update_data=array();
    if(count($pro)>0){
      $user_model->clear_pro();
    }
    foreach($pro as $p){
      $data=array();
      $data['citizen_id']=$p['people_id'];
      $data['people_stagov_id']=$p['people_stagov_id'];
      $data['dep_id']=$p['people_dep_id'];
      $data['school_id']=$p['school_id'];
      $user_model->add_pro($data);

      if($p['people_dep_id']!=$people_dep_id&&$p['people_id']!='9999999999999'){//Not in domain skip;
        continue;
      }
      foreach($stagov_user_type as $stagov=>$user_type){
        if($p['people_stagov_id']==$stagov){
          //print $p['people_id'].'<br>';
          if(empty($update_data[$p['people_id']])){
            $update_data[$p['people_id']]=array('active'=>1,'user_type_id'=>$user_type);
          }else if($update_data[$p['people_id']]['user_type_id']>$user_type){
            $update_data[$p['people_id']]['user_type_id']=$user_type;
            
          }
        }
     }

     foreach($update_data as $people_id=>$ud){
      $user_model->user_update(array('citizen_id'=>$people_id),$ud);
     }

    }

    //$ret['content']=
    $ret['content']= redirect(module_url('rms','rms','import'));
    return $ret;
  }

  function getSemester(){
    global $module;
    global $system;
    $module->helper('rms');
    $semester_data=api_load('dateedu');
    
    $semester_model = $module->model('semester');

    if(count($semester_data)>=1){
      $semester_model->clear();
      foreach($semester_data as $row){
      $data=array(
          'semester_start'=>$row['dateedu_start'],
          'semester_end'=>$row['dateedu_end'],
          'semester_eduyear'=>$row['dateedu_eduyear'],
          'datechk_end'=>$row['datechk_end'],
      );
      $result=$semester_model->add($data);
      }
      
      $sync_record=$module->model('sync');
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'semester','result'=>'ok'));
    }

    $ret['content']=redirect(module_url('rms','rms','import'));
    return $ret;
  }

  function getTimetableBlockcourse(){
    global $module;
    global $system;
    $module->helper('rms');
    $timetable_data=api_load('std2018_timetable_blockcourse');
    
    print_r($timetable_data);
  }

  function getTimetable(){
    global $module;
    global $system;
    $semester='1/2568';
    $module->helper('rms');
    $timetable_data=array();
    $round=0;
    $step=1000;
    $imported=0;
    do{
      $param=array(
        'semes'=>$semester,
        'limit'=>($round*$step).",".$step,
      );
      $rr=api_load('studing',$param);
      if(is_array($rr)&&count($rr)>1){
        $imported=count($rr);
        $timetable_data=array_merge($timetable_data,$rr);
        $round++;
        //print $round." ".$imported." ".$param['limit']."<br>";
      }else{
        $imported=0;
      }
    }while($imported>1);

    $timetable_model=$module->model('timetable');
    //print count($timetable_data);
    //exit();
    if(count($timetable_data)>0){
    
    $timetable_model->clear(array('semester'=>$semester));
    $day2num=array(
      'จันทร์'=>'1',
      'อังคาร'=>'2',
      'พุธ'=>'3',
      'พฤหัส'=>'4',
      'ศุกร์'=>'5',
      'เสาร์'=>'6',
      'อาทิตย์'=>'7',
    );
    foreach($timetable_data as $row){
      if(empty($day2num[$row['dpr2']])) continue;//ไม่นำเข้า Blockcourses
      $data=array(
          'timeTableID'=>$row['timeTableID'],
          'timeTableSubID'=>$row['timeTableSubID'],
          'semester'=>$row['semes'],
          'subject_code'=>$row['subject_id'],
          'subject_name'=>$row['subject_name'],
          'student_group_id'=>$row['student_group_id'],
          'time_range'=>$row['dpr3'],
          'day_of_week'=>$row['dpr2'],
          'day_of_week_no'=>$day2num[$row['dpr2']],
          'time_total'=>$row['dpr4'],
          'teacher_id'=>$row['teacher_id'],
          'teacher_co_id'=>$row['teacher_com_id'],
          'room'=>$row['roomName'],
      );
      $result=$timetable_model->add($data);
      }

    //print_r($timetable_data);
      
      $sync_record=$module->model('sync');
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'timetable','result'=>'ok'));
    }else{
      
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'timetable','result'=>'Return value is zero timetable'));
    }

    $ret['content']=redirect(module_url('rms','rms','import'));
    return $ret;
  }
}