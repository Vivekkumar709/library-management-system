<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$classTeacher = $content_data['data']['data'] ?? null;
$isEdit = $content_data['data']['isEdit'] ?? false;
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
                        <h3 class="m-0"><?= $label; ?> Class Teacher</h3>
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
                <form class="" autocomplete="off" method="post" action="<?= site_url('class-teachers/save') ?>">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $classTeacher['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="financial_year_id" name="financial_year_id" required <?=$disabled;?> >
                                   <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('financial_year_id', $id, 
                                        old('financial_year_id', isset($classTeacher['financial_year_id']) ? $classTeacher['financial_year_id'] : FINANCIAL_YEAR_ID) == $id) ?>>
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
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="class_id" name="class_id" required <?=$disabled;?> >
                              <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('class_id', $id, 
                                        old('class_id', isset($classTeacher['class_id']) ? $classTeacher['class_id'] : '') == $id) ?>>
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
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="section_id" name="section_id" <?=$disabled;?> required>                                
                                <?php if ($isEdit): ?>
                                    <?php 
                                    $sections = get_dropdown('sections', 'id', 'section_id', 
                                        ['class_id' => $classTeacher['class_id'], 'status' => 0], 'Section');                                       
                                    foreach ($content_data['data']['sections'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_id', $id, 
                                            old('section_id', isset($classTeacher['section_id']) ? $classTeacher['section_id'] : '') == $id) ?>>
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
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="teacher_id" name="teacher_id" required>
                                <?php foreach ($content_data['data']['teachers'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('teacher_id', $id, 
                                        old('teacher_id', isset($classTeacher['teacher_id']) ? $classTeacher['teacher_id'] : '') == $id) ?>>
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

                        <?php if($isEdit): ?>
                            <div class="col-lg-6">
                                <div class="common_input mb_15">
                                    <select class="nice_Select2 nice_Select_line wide <?= session('errors.status') ? 'is-invalid' : '' ?>" name="status" id="status">
                                        <option value="0" <?= set_select('status', '0', old('status', isset($classTeacher['status']) ? $classTeacher['status'] : '') == 0) ?>>Active</option>
                                        <option value="1" <?= set_select('status', '1', old('status', isset($classTeacher['status']) ? $classTeacher['status'] : '') == 1) ?>>Inactive</option>
                                    </select>
                                    <?php if (session('errors.status')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.status')) ?>
                                        </div>
                                    <?php endif; ?>      
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <div class="create_report_btn mt_30">
                                <?php if (has_permission('/class-teachers', PERMISSION_CREATE) || has_permission('/class-teachers', PERMISSION_EDIT)): ?>
                                    <input type="submit" class="btn_1 d-block text-center" value="<?= $label; ?> Class Teacher"> 
                                <?php endif; ?>                           
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
    $('#class_id').change(function() { 
        var classId = $(this).val();
        var financial_year_Id = $('#financial_year_id').val();
        if (classId && financial_year_Id) {
            $.ajax({
                url: '<?= site_url('class-teachers/get-sections/') ?>' + classId + '/' + financial_year_Id,
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