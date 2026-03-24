<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$classSchedule = $content_data['data']['classSchedule'] ?? null;
$isEdit = isset($classSchedule);
$label = $isEdit ? 'Edit' : 'Add';
$disabled = '';
if($label == 'Edit'){ $disabled = 'disabled';}
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?= $label; ?> Class Schedule</h3>
                    </div>                    
                    <!-- Display validation errors -->
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>
                    <!-- Display success/error messages -->
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                    <?php endif ?>                         
                </div>
            </div>
            
            <div class="white_card_body">
                <form class="" autocomplete="off" method="post" action="<?= site_url('class-schedules/save') ?>">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $classSchedule['id'] ?>">
                        <input type="hidden" id="exclude_teacher_id" value="<?= $classSchedule['teacher_id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="financial_year_id" name="financial_year_id" <?=$disabled;?> required>
                                <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('financial_year_id', $id, 
                                        old('financial_year_id', isset($classSchedule['financial_year_id']) ? $classSchedule['financial_year_id'] : FINANCIAL_YEAR_ID) == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                            <?php if (session('errors.financial_year_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.financial_year_id')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" placeholder="Select Class" id="class_id" name="class_id" <?=$disabled;?> required>
                                    <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('class_id', $id, 
                                        old('class_id', isset($classSchedule['class_id']) ? $classSchedule['class_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                            <?php if (session('errors.class_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.class_id')) ?>
                                </div>
                            <?php endif; ?>                       
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" placeholder="Select Section" id="section_id" <?=$disabled;?> name="section_id" required>
                                <?php if ($isEdit): ?>
                                    <?php 
                                            $sections = get_advanced_dropdown([
                                            'tables' => ['m_sections ms'],
                                            'joins' => [
                                                [
                                                    'table' => 'sections s', 
                                                    'condition' => 'ms.id = s.section_id',
                                                    'type' => 'left'
                                                ]
                                            ],
                                            'key' => 's.id', 
                                            'value' => 'ms.name as section_name', 
                                            'where' => [
                                                'ms.status' => 0, 
                                                's.status' => 0, 
                                                's.class_id' => $classSchedule['class_id'],
                                                's.school_id' => auth()->user()->school_id,
                                            ],
                                            'orderBy' => 'ms.name ASC',
                                            'selectPostfix' => 'Empty'
                                        ]); 

                                    foreach ($sections as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_id', $id, 
                                            old('section_id', isset($classSchedule['section_id']) ? $classSchedule['section_id'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select> 
                            <?php if (session('errors.section_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.section_id')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" placeholder="Select Subject" id="subject_id" name="subject_id" required>
                                <!-- <option value="">Select Subject</option> -->
                                <?php if (!empty($content_data['data']['subjects'])): ?>
                                    <?php foreach ($content_data['data']['subjects'] as $subject): ?>
                                        <option value="<?= $subject['id'] ?>" 
                                            <?= set_select('subject_id', $subject['id'], 
                                            old('subject_id', isset($classSchedule['subject_id']) ? $classSchedule['subject_id'] : '') == $subject['id']) ?>>
                                            <?= esc($subject['name']) ?> (<?= esc($subject['category']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select> 
                            <?php if (session('errors.subject_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.subject_id')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" placeholder="Select Teacher" id="teacher_id" name="teacher_id" required>
                                <!-- <option value="">Select Teacher</option> -->
                                <?php foreach ($content_data['data']['teachers'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('teacher_id', $id, 
                                        old('teacher_id', isset($classSchedule['teacher_id']) ? $classSchedule['teacher_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                            <?php if (session('errors.teacher_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.teacher_id')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" placeholder="Select Day" id="day_of_week" name="day_of_week" required>
                                <!-- <option value="">Select Day</option> -->
                                <?php foreach ($content_data['data']['days'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('day_of_week', $id, 
                                        old('day_of_week', isset($classSchedule['day_of_week']) ? $classSchedule['day_of_week'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                            <?php if (session('errors.day_of_week')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.day_of_week')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>

                        <div class="col-lg-3">
                            <div class="common_input mb_15">
                                <input type="time" class="form-control select-required-asterisk <?= session('errors.start_time') ? 'is-invalid' : '' ?>" 
                                    name="start_time" id="start_time" required
                                    value="<?= old('start_time', isset($classSchedule['start_time']) ? date('H:i', strtotime($classSchedule['start_time'])) : '') ?>" required>
                                <?php if (session('errors.start_time')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.start_time')) ?>
                                    </div>
                                <?php endif; ?>      
                                <label>Start Time</label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="common_input mb_15">
                                <input type="time" class="form-control select-required-asterisk <?= session('errors.end_time') ? 'is-invalid' : '' ?>" 
                                    name="end_time" id="end_time" required
                                    value="<?= old('end_time', isset($classSchedule['end_time']) ? date('H:i', strtotime($classSchedule['end_time'])) : '') ?>" required>
                                <?php if (session('errors.end_time')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.end_time')) ?>
                                    </div>
                                <?php endif; ?>      
                                <label>End Time</label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="text" class="form-control <?= session('errors.room_number') ? 'is-invalid' : '' ?>" 
                                    name="room_number" id="room_number" 
                                    value="<?= old('room_number', $classSchedule['room_number'] ?? '') ?>" placeholder="Class Room Number">
                                <?php if (session('errors.room_number')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.room_number')) ?>
                                    </div>
                                <?php endif; ?>  
                            </div>
                        </div>

                        <?php if($isEdit): ?>
                            <div class="col-lg-6">
                                <div class="common_input mb_15">
                                    <select class="nice_Select2 nice_Select_line wide <?= session('errors.status') ? 'is-invalid' : '' ?>" name="status" id="status">
                                        <option value="0" <?= set_select('status', '0', old('status', isset($classSchedule['status']) ? $classSchedule['status'] : '') == 0) ?>>Active</option>
                                        <option value="1" <?= set_select('status', '1', old('status', isset($classSchedule['status']) ? $classSchedule['status'] : '') == 1) ?>>Inactive</option>
                                    </select>
                                    <?php if (session('errors.status')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.status')) ?>
                                        </div>
                                    <?php endif; ?>      
                                    <!-- <label>Status</label> -->
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <div class="create_report_btn mt_30">
                                <input type="submit" class="btn_1" value="<?= $label; ?> Schedule">                            
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<script>
$(document).ready(function() {
    // Load sections when class is selected
    $('#class_id').change(function() {
        var classId = $(this).val();
        var financial_year_Id = $('#financial_year_id').val();
        if (classId && financial_year_Id) {
            // Load sections
            $.ajax({
                url: '<?= site_url('class-schedules/get-sections/') ?>' + classId + '/' + financial_year_Id,
                type: 'GET',
                success: function(data) {
                    $('#section_id').html('<option value="">Select Section</option>');
                    $.each(data, function(key, value) {
                        $('#section_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                }
            });

            // Load subjects based on class level
            $.ajax({
                url: '<?= site_url('class-schedules/get-subjects/') ?>' + classId,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#subject_id').html('<option value="">Select Subject</option>');
                    $.each(data, function(key, value) {
                        $('#subject_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#subject_id').niceSelect('update');
                    }                    
                }
            });
        } else {
            $('#section_id').html('<option value="">Select Section</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');
        }
    });
    
    // Check teacher availability when time or day changes
    $('#day_of_week, #start_time, #end_time, #financial_year_id').change(function() {
        checkTeacherAvailability();
    });
    
    function checkTeacherAvailability() {         
        var dayOfWeek = $('#day_of_week').val();
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();
        var financialYearId = $('#financial_year_id').val();
        var excludeTeacherId = $('#exclude_teacher_id').val() || '';
        
        if (dayOfWeek && startTime && endTime && financialYearId) {
            $.ajax({
                url: '<?= site_url('class-schedules/get-available-teachers') ?>',
                type: 'POST',
                data: {
                    day_of_week: dayOfWeek,
                    start_time: startTime,
                    end_time: endTime,
                    financial_year_id: financialYearId,
                    exclude_teacher_id: excludeTeacherId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    
                    $('#teacher_id').html(response.teachers);
                    
                    // If editing, try to select the original teacher
                    if (excludeTeacherId) {
                        $('#teacher_id').val(excludeTeacherId);
                    }
                }
            });
        }
    }
});
</script>