<?php
class rms{
  function import(){
    global $module;
    $ret['content'] = $module->view('home/import');
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