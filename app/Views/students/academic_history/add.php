<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$record = $content_data['data']['record'] ?? null;
$isEdit = $content_data['data']['isEdit'] ?? false;
$student = $content_data['data']['student'];
$label = $isEdit ? 'Edit' : 'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?= $label; ?> Academic Record - <?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h3>
                        <p class="m-0 text-muted">Admission No: <?= esc($student['admission_no']) ?></p>
                    </div>
                </div>
                
                <!-- Display validation errors -->
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>

                <!-- Display success/error messages -->
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger mt-3"><?= esc(session('error')) ?></div>
                <?php endif ?> 
            </div>
            
            <div class="white_card_body">
                <form class="" autocomplete="off" method="post" action="<?= site_url('students/academic-history/save') ?>">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $record['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15" id="financial_year_id" name="financial_year_id" required>
                                <option value="">Select Financial Year</option>
                                <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('financial_year_id', $id, 
                                        old('financial_year_id', isset($record['financial_year_id']) ? $record['financial_year_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>                      
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15" id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('class_id', $id, 
                                        old('class_id', isset($record['class_id']) ? $record['class_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>                      
                        </div>
                               
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15" id="section_id" name="section_id" required>                                
                                <option value="">Select Section</option>
                                <?php if ($isEdit): ?>
                                    <?php 
                                    $sections = get_dropdown('sections', 'id', 'section_id', 
                                        ['class_id' => $record['class_id'], 'status' => 0], 'Section');                                       
                                    foreach ($sections as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_id', $id, 
                                            old('section_id', isset($record['section_id']) ? $record['section_id'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>                      
                        </div>

                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="Roll Number" 
                                    value="<?= set_value('roll_no', $record['roll_no'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="number" class="form-control" id="percentage" name="percentage" placeholder="Percentage" 
                                    value="<?= set_value('percentage', $record['percentage'] ?? '') ?>" step="0.01" min="0" max="100">
                                <small class="text-muted">Enter percentage (0-100)</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15" id="status" name="status">
                                <option value="Completed" <?= set_select('status', 'Completed', old('status', isset($record['status']) ? $record['status'] : 'Completed') == 'Completed') ?>>Completed</option>
                                <option value="Promoted" <?= set_select('status', 'Promoted', old('status', isset($record['status']) ? $record['status'] : '') == 'Promoted') ?>>Promoted</option>
                                <option value="Failed" <?= set_select('status', 'Failed', old('status', isset($record['status']) ? $record['status'] : '') == 'Failed') ?>>Failed</option>
                                <option value="Dropped" <?= set_select('status', 'Dropped', old('status', isset($record['status']) ? $record['status'] : '') == 'Dropped') ?>>Dropped</option>
                            </select>                        
                        </div>

                        <div class="col-12">
                            <div class="create_report_btn mt_30">
                                <a href="<?= site_url('students/academic-history/' . $student['id']) ?>" class="btn btn-secondary me-2">Cancel</a>
                                <input type="submit" class="btn btn_1" value="<?= $label; ?> Academic Record">                            
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {    
    $('#class_id').change(function() { 
        var classId = $(this).val();
        var financial_year_Id = $('#financial_year_id').val();
        if (classId && financial_year_Id) {
            $.ajax({
                url: '<?= site_url('students/academic-history/get-sections/') ?>' + classId + '/' + financial_year_Id,
                type: 'GET',
                success: function(data) {                    
                    $('#section_id').html('<option value="">Select Section</option>');
                    $.each(data, function(key, value) {
                        $('#section_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    
                    // Refresh the nice-select to reflect the changes
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                }
            });
        } else {
            $('#section_id').html('<option value="">Select Section</option>');
            if (typeof $.fn.niceSelect !== 'undefined') {
                $('#section_id').niceSelect('update');
            }
        }
    });
});
</script>