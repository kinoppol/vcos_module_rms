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
    $module->helper('rms');
    $user_data=api_load_user();
    //print_r($user_data);
    $user_model = $module->model('user');
    $user_model->clear_user();
    
    $sync_record=$module->model('sync');
    $sync_record->add_record(array('sync_time'=>date('Y-m-d H:i:s'),'sync_name'=>'personal','result'=>'ok'));

    $ret['content']=redirect(module_url('rms','import'));
    return $ret;
  }
}