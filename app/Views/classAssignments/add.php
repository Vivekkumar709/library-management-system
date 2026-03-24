<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$assignment = $content_data['data']['data'] ?? null;
$isEdit = isset($assignment);
$label = $isEdit ? 'Edit' : 'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?= $label; ?> Assignment</h3>
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
                <form class="" autocomplete="off" method="post" action="<?= site_url('class-assignments/save') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $assignment['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="financial_year_id" name="financial_year_id" required>                                
                                <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('financial_year_id', $id, 
                                        old('financial_year_id', isset($assignment['financial_year_id']) ? $assignment['financial_year_id'] : FINANCIAL_YEAR_ID) == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="class_id" name="class_id" required>                                
                                <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('class_id', $id, 
                                        old('class_id', isset($assignment['class_id']) ? $assignment['class_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk"  id="section_id" name="section_id" required>                                
                                <?php if ($isEdit): ?>
                                    <?php 
                                    // $sections = get_dropdown('sections', 'id', 'section_id', 
                                    //     ['class_id' => $assignment['class_id'], 'status' => 0], 'Select Section');
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
                                            's.class_id' => $assignment['class_id'],
                                            's.school_id' => auth()->user()->school_id,
                                        ],
                                        'orderBy' => 'ms.name ASC',
                                        'selectPostfix' => 'Empty'
                                    ]); 
                                    foreach ($sections as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_id', $id, 
                                            old('section_id', isset($assignment['section_id']) ? $assignment['section_id'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select> 
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="subject_id" name="subject_id" required>                                
                                <?php foreach ($content_data['data']['subjects'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('subject_id', $id, 
                                        old('subject_id', isset($assignment['subject_id']) ? $assignment['subject_id'] : '') == $id) ?>>
                                        <?php 
                                            if ($id === '' || $id === null) {
                                                $nameCategoryfull = $name;                                                 
                                            }else{
                                                $nameCategory = explode(',',$name);                                                
                                                $nameCategoryfull = $nameCategory[0].' ['.$nameCategory[1].']';
                                            }
                                        ?>
                                        <?= esc($nameCategoryfull) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                        </div>

                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="teacher_id" name="teacher_id" required>                                
                                <?php foreach ($content_data['data']['teachers'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('teacher_id', $id, 
                                        old('teacher_id', isset($assignment['teacher_id']) ? $assignment['teacher_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                        </div>

                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="text" class="form-control" placeholder="Assignment Title"
                                    name="title" id="title" 
                                    value="<?= old('title', $assignment['title'] ?? '') ?>" required>
                                <!-- <label>Assignment Title</label> -->
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <textarea class="form-control" placeholder="Description (Optional)"
                                    name="description" id="description" 
                                    rows="3"><?= old('description', $assignment['description'] ?? '') ?></textarea>
                                
                            </div>
                        </div>
                                                
                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="text" 
                                    class="form-control datepicker-here" 
                                    data-language="en" 
                                    data-timepicker="true" 
                                    data-date-format="dd-mm-yyyy" 
                                    data-time-format="hh:ii"
                                    name="due_date" 
                                    id="due_date" 
                                    placeholder="Due Date & Time"
                                    value="<?= old('due_date', $assignment['due_date_formatted'] ?? '') ?>" 
                                    required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="number" class="form-control" placeholder="Maximum Marks (Optional)"
                                    name="max_marks" id="max_marks" 
                                    value="<?= old('max_marks', $assignment['max_marks'] ?? '') ?>" min="0">                                
                            </div>
                        </div>

                        <?php if($isEdit): ?>
                            <div class="col-lg-6">
                                <div class="common_input mb_15">
                                    <select class="nice_Select2 nice_Select_line wide" name="status" id="status">
                                        
                                        <option value="0" <?= set_select('status', '0', old('status', isset($assignment['status']) ? $assignment['status'] : '') == 0) ?>>Active</option>
                                        <option value="1" <?= set_select('status', '1', old('status', isset($assignment['status']) ? $assignment['status'] : '') == 1) ?>>Inactive</option>
                                    </select>
                                    
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <input type="file" class="form-control" 
                                    name="attachment" id="attachment">
                                <label>Attachment (Optional)</label>
                                <?php if ($isEdit && !empty($assignment['attachment_path'])): ?>
                                    <div class="mt-2">
                                        <small>Current file: <?= basename($assignment['attachment_path']) ?></small>
                                        <a href="<?= site_url('class-assignments/download/' . $assignment['id']) ?>" class="btn btn-sm btn-link">Download</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="create_report_btn mt_30">
                                <input type="submit" class="btn_1" value="<?= $label; ?> Assignment">                            
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

    $('#section_id').prepend('<option value="" disabled selected>Select Section</option>');
    $('#section_id').niceSelect('update');

    var selectedSectionId = '<?= isset($assignment['section_id']) ? $assignment['section_id'] : '' ?>';
    var dataAttrSectionId = $('#section_id').data('selected-section');
    
    // Use data attribute if PHP variable is empty
    if (!selectedSectionId && dataAttrSectionId) {
        selectedSectionId = dataAttrSectionId;
    }
    // Load sections when class is selected
    $('#class_id').change(function() {
        var classId = $(this).val();
        var financialYearId = $('#financial_year_id').val();

        if (classId && financialYearId) {
            $.ajax({
                url: '<?= site_url('class-assignments/get-sections/') ?>' + classId + '/' + financialYearId,
                type: 'GET',
                success: function(data) {
                    $('#section_id').html('<option value="">Select Section</option>');                    
                    $.each(data, function(key, value) {
                        // Create option and select if it matches the stored section ID
                        var isSelected = (key == selectedSectionId);
                        var option = $('<option>', {
                            value: key,
                            text: value
                        });
                        
                        if (isSelected) {
                            option.attr('selected', 'selected');
                        }
                        
                        $('#section_id').append(option);
                    });
                    // $.each(data, function(key, value) {
                    //     $('#section_id').append('<option value="' + key + '">' + value + '</option>');
                    // });
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                }
            });
        } else {
            $('#section_id').html('<option value="">Select Section</option>');
        }
    });
    
    // Trigger change if class is already selected
    <?php if ($isEdit && isset($assignment['class_id'])): ?>
        $('#class_id').trigger('change');
    <?php endif; ?>
});
</script>