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

function rms_get_data($rmsurl,$app_name,$data,$limit=null,$count=null){
    $target_url=$rmsurl."/api_connection.php?data=".$data."&app_name=".$app_name."&limit=".$limit."&count=".$count;
    //print ($target_url);
    $rawdata=file_get_contents($target_url);
    $json=json_decode($rawdata,true);
    return $json;
}