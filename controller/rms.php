<?php
class rms{
  function import(){
    global $module;

    $sync_record=$module->model('sync');
    $data['personal_sync_time']=$sync_record->get_last_record(array('sync_name'=>'personal'));
    $data['semester_sync_time']=$sync_record->get_last_record(array('sync_name'=>'semester'));
    $data['timetable_sync_time']=$sync_record->get_last_record(array('sync_name'=>'timetable'));
    $data['substitute_sync_time']=$sync_record->get_last_record(array('sync_name'=>'substitute'));
    
    
    $ret['content'] = $module->view('home/import',$data);
    $ret['title'] = 'นำเข้าข้อมูลจากระบบ RMS';
    return $ret;
  }
  function export(){
    global $module;
    $ret['content'] = $module->view('home/export');
    $ret['title'] = 'ส่งข้อมูลกลับเข้าระบบ RMS';
    return $ret;
  }

  function api_load_user(){
    global $module;
    $module->helper('rms');
    $user=api_load_user();
    $user_model = $module->model('user');
    $user_model->clear_user();
  }

  function api_getPersonal(){
    global $module;
    global $system;
    $module->helper('rms');
    $users=api_load_user();
    //print_r($user_data);
    //$users=json_decode($user_data);
    
    if(!is_array($users)){
      $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'Return value is not array'));
      redirect(module_url('rms','import'));
      return $ret;
    }else if(count($users)<1){      
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
      $data=array(
        'citizen_id'=>$u['people_id'],
        'username'=> $username,
        'password'=> $u['people_pass'],
        'name'=> $u['people_name'],
        'surname' => $u['people_surname'],
        'email'=>$u['people_email'],
        'user_type_id'=>4,
        'active'=>0,
        'picture'=>empty($u['people_pic'])?$u['people_pic']:$system['rms_url']."/files/".$u['people_pic'],
      );
      $user_model->add_user($data);
    }
    
    $sync_record=$module->model('sync');
    $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'ok'));

    $ret['content']='';//redirect(module_url('rms','import'));
    return $ret;
  }
}