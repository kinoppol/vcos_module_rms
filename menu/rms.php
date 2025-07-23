<?php

$menu['เชื่อมโยงข้อมูลระบบ RMS']=array(
    'import'=''>array(
        'label'=>'นำเข้าข้อมูลจากระบบ RMS',
        'bullet'=>'tf-icons bx bx-download',
        'url'=>module_url('rms','import'),
    ),
    'Export'=>array(
        'label'=>'ส่งข้อมูลกลับระบบ RMS',
        'bullet'=>'tf-icons bx bx-upload',
        'url'=>module_url('rms','export'),
        /*'item'=>array(
                'menu1'=>array(
                'label'=>'เมนูย่อย 1',
                'url'=>module_url('hello','menu1'),
            ),
                'menu2'=>array(
                'label'=>'เมนูย่อย 2',
                'url'=>module_url('hello','menu2'),
            ),
        ),*/
    ),
);
print gen_menu($menu);