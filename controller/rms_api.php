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
      redirect(module_url('rms','import'));
      return $ret;
    }else if(count($users)<1){//If result is empty skip import   
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'Return value is zero user'));
      redirect(module_url('rms','import'));
      return $ret;
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

    print "นำเข้าข้อมูลบุคลากรเรียบร้อย กำลังโหลดค่าตำแหน่งรับผิดชอบ โปรดรอสักครู่..";
    $ret['content']=redirect(module_api('rms','rms_api','get_people_pro'));
    return $ret;
  }

  function get_people_pro(){
    global $module;
    global $system;
    $module->helper('rms');

    $pro=api_load('people_pro');
    $dep=api_load('people_dep');
    $stagov=api_load('people_stagov');

    foreach($dep as $d){
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

    $user_model = $module->model('user');

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
    foreach($pro as $p){
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
    print redirect(module_url('rms','rms','import'));
    //return $ret['content'];
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

    print redirect(module_url('rms','rms','import'));
    //return $ret['content'];
  }

  function getTimetableBlockcourse(){
    global $module;
    global $system;
    $module->helper('rms');
    $timetable_data=api_load('std2018_timetable_blockcourse');
    
    print_r($timetable_data);
  }
}