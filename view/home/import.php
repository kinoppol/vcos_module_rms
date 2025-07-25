<?php
helper('time');
$data['title']='รายการข้อมูลที่นำเข้าจากระบบ RMS';
$personal_synctime=$personal_sync_time==NULL?'ยังไม่นำเข้า':$personal_sync_time.' ( '.xTimeAgo ($personal_sync_time, date('Y-m-d H:i:s')).' )';
$semester_synctime=$semester_sync_time==NULL?'ยังไม่นำเข้า':$semester_sync_time.' ( '.xTimeAgo ($semester_sync_time, date('Y-m-d H:i:s')).' )';
$timetable_synctime=$timetable_sync_time==NULL?'ยังไม่นำเข้า':$timetable_sync_time.' ( '.xTimeAgo ($timetable_sync_time, date('Y-m-d H:i:s')).' )';
$substitute_synctime=$substitute_sync_time==NULL?'ยังไม่นำเข้า':$substitute_sync_time.' ( '.xTimeAgo ($substitute_sync_time, date('Y-m-d H:i:s')).' )';
$data['content']='
<div class="table-responsive text-nowrap">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>ข้อมูล</th>
                                  <th>นำเข้าล่าสุด</th>
                                  <th>ดำเนินการ</th>
                              </tr>
                          </thead>
                          <tbody class="table-border-bottom-0">
                          <tr>
                            <td>ข้อมูลบุคลากร และแผนกวิชา</td>
                            <td>'.$personal_synctime.'</td>
                            <td>
                            <a href="'.module_api('rms','rms_api','getPersonal').'" class="import_bt btn btn-primary">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </a>
                            </td>
                          </tr>
                          <tr>
                            <td>ภาคเรียน และวันหยุด</td>
                            <td>'.$semester_synctime.'</td>
                            <td>
                            <button class="btn btn-primary">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </button>
                            </td>
                          </tr>
                          <tr>
                            <td>ตารางสอน</td>
                            <td>'.$timetable_synctime.'</td>
                            <td>
                            <button class="btn btn-primary">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </button>
                            </td>
                          </tr>
                          <tr>
                            <td>การสอนแทนสอนชดเชย</td>
                            <td>'.$substitute_synctime.'</td>
                            <td>
                            <button class="btn btn-primary import_btn">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </button>
                            </td>
                          </tr>
                          </tbody>
                        </table>
                        </div>

';
$SESSION['page_script']='<script>
$().function(
                        $(\'.import_btn\').click(function() {
                        alert(\'555\');
    $(this).removeClass(\'btn-primary\');
    $(this).addClass(\'btn-default\');
});
                        </script>
                        );
';
helper('view/card');
print card($data);