<?php
function api_load_user(){
    global $system;
    global $rms_api_key;
    $rms=$system['rms_url'];
    if(empty(trim($rms))){
        print "No RMS URL.";
        exit();
    }
$people_data=rms_get_data($rms,$rms_api_key,'people');
//print_r($people_data);
return $people_data;
}

function api_load($data,$param=array()){
    global $system;
    global $rms_api_key;
    $rms=$system['rms_url'];
    if(empty(trim($rms))){
        print "No RMS URL.";
        exit();
    }
    $result_data=rms_get_data($rms,$rms_api_key,$data,$param);
    return $result_data;
}

function rms_get_data($rmsurl,$app_name,$data,$param=array()){
    $target_url=$rmsurl."/api_connection.php?data=".$data."&app_name=".$app_name;
    if(!empty($param)){
        $target_url.=arr2query($param);
    }
    //print ($target_url);
    $rawdata=file_get_contents($target_url);
    $json=json_decode($rawdata,true);
    return $json;
}

function arr2query($arr=array()){
    $ret='';
    if(!is_array($arr) || count($arr)==0){
        return $ret;
    }
    foreach($arr as $k=>$v){
        $ret.='&'.urlencode($k).'='.urlencode($v);
    }
    return $ret;
}