<?php
class rms{
  function import(){
    global $module;
    
    $semester_model=$module->model('semester');

    if(!empty($_POST['semester'])){
      $_SESSION['semester']=$_POST['semester'];
    }

    if(empty($_SESSION['semester'])){
      $semester=$semester_model->getCurrentSemester();
      $_SESSION['semester']=$semester;
    }else{
      $semester=$_SESSION['semester'];
    }

    $all_semester=$semester_model->getSemester();
    $semesters=array();
    foreach($all_semester as $k=>$v){
      $semesters[$k]=$k;
    }

    $data['semesters']=$semesters;
    $data['semester']=$semester;

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

  
}