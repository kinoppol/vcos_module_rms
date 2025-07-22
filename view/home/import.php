<?php
helper('time');
$data['title']='รายการข้อมูลที่นำเข้าจากระบบ RMS';
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
                            <td>2025-07-21 11:05:00 ('.xTimeAgo ('2025-07-21 11:05:00', date('Y-m-d H:i:s')).')</td>
                            <td>
                            <a href="'.module_api('rms','api_getPersonal').'" class="import_bt btn btn-primary">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </a>
                            </td>
                          </tr>
                          <tr>
                            <td>ภาคเรียน และวันหยุด</td>
                            <td>'.xTimeAgo ('2025-07-22 09:15:00', date('Y-m-d H:i:s')).'</td>
                            <td>
                            <button class="btn btn-primary">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </button>
                            </td>
                          </tr>
                          <tr>
                            <td>ตารางสอน</td>
                            <td>'.xTimeAgo ('2025-07-22 08:13:00', date('Y-m-d H:i:s')).'</td>
                            <td>
                            <button class="btn btn-primary">
                            <i class="bx bx-download me-1"></i>
                            นำเข้า
                            </button>
                            </td>
                          </tr>
                          <tr>
                            <td>การสอนแทนสอนชดเชย</td>
                            <td>2025-07-21 11:05:00 ('.xTimeAgo ('2025-07-21 11:05:00', date('Y-m-d H:i:s')).')</td>
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