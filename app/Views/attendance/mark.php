<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <h3>Mark Daily Attendance</h3>
            </div>
            <div class="white_card_body">
                <form method="post" action="<?= site_url('attendance/save') ?>">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-lg-3">
                            <select name="class_id" id="class_id" class="nice_Select2" required>
                                <option value="">Select Class</option>
                                <?= $content_data['data']['classes'] ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="section_id" id="section_id" class="nice_Select2" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="financial_year_id" class="nice_Select2" required>
                                <?= $content_data['data']['financial_years'] ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="attendance_date" class="form-control datepicker-here" 
                                   value="<?= date('d-m-Y') ?>" data-date-format="dd-mm-yyyy">
                        </div>
                    </div>

                    <hr>
                    <div id="student_list"></div>   <!-- AJAX populated table -->

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">✅ Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#class_id, #section_id').change(function(){
        var classId = $('#class_id').val();
        var secId = $('#section_id').val();
        var fyId = $('select[name="financial_year_id"]').val();

        if(classId && secId && fyId){
            $.post('<?= site_url("attendance/getStudents") ?>', {
                class_id: classId,
                section_id: secId,
                financial_year_id: fyId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            }, function(data){
                var html = '<table class="table"><thead><tr><th>Roll</th><th>Student</th><th>Present</th><th>Absent</th><th>Late</th><th>Remark</th></tr></thead><tbody>';
                $.each(data, function(i, s){
                    html += `<tr>
                        <td>${s.roll_no}</td>
                        <td>${s.first_name} ${s.last_name}</td>
                        <td><input type="radio" name="status[${s.id}]" value="Present" checked></td>
                        <td><input type="radio" name="status[${s.id}]" value="Absent"></td>
                        <td><input type="radio" name="status[${s.id}]" value="Late"></td>
                        <td><input type="text" name="remark[${s.id}]" class="form-control"></td>
                    </tr>`;
                });
                html += '</tbody></table>';
                $('#student_list').html(html);
            });
        }
    });
});
</script>