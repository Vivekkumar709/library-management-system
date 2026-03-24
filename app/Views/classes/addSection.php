<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$data = $content_data['data']['data'] ?? null;

$isEdit = isset($data);
$label = $isEdit?'Edit':'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?=$label;?> Section </h3>
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
                        <form class="" autocomplete="off" method="post" action="<?= site_url('classes/saveSection') ?>">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                        <?php endif; ?>                        
                </div>
            </div>
            
            <div class="white_card_body">
                <div class="row">

                    <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="financial_year_id" name="financial_year_id" required>
                                    <?php 
                                        foreach ($content_data['data']['financial_year'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('financial_year_id', $id, 
                                            old('financial_year_id', isset($data['financial_year_id']) ? $data['financial_year_id'] : FINANCIAL_YEAR_ID) == $id) ?>>
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
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="class_id" name="class_id" required>
                                    <?php 
                                        foreach ($content_data['data']['classes'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                        <?= set_select('class_id', $id, 
                                            old('class_id', isset($data['class_id']) ? $data['class_id'] : '') == $id) ?>>
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
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="section_id" name="section_id" required>
                                    <?php 
                                        foreach ($content_data['data']['sections'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_id', $id, 
                                            old('section_id', isset($data['section_id']) ? $data['section_id'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select> 
                            <?php if (session('errors.section_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.section_id')) ?>
                                </div>
                            <?php endif; ?>                        
                    </div>
                    
                    <div class="col-lg-6">                            
                        <div class="common_input mb_15 select-required-asterisk">
                            <input type="text" placeholder="Max Capacity" class="form-control <?= session('errors.max_capacity') ? 'is-invalid' : '' ?>" 
                                name="max_capacity" id="max_capacity" oninput="validateDigitsOnlyWithMax(this, 9)"
                                value="<?= old('max_capacity', $data['max_capacity'] ?? '') ?>" required>
                            <?php if (session('errors.max_capacity')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.max_capacity')) ?>
                                </div>
                            <?php endif; ?>      
                        </div>
                    </div>

                    <div class="col-lg-6">                            
                        <div class="common_input mb_15 select-required-asterisk">
                            <input type="text" placeholder="Current Strength" class="form-control <?= session('errors.current_strength') ? 'is-invalid' : '' ?>" 
                                name="current_strength" id="current_strength" oninput="validateDigitsOnlyWithMax(this, 3)"
                                value="<?= old('current_strength', $data['current_strength'] ?? '') ?>" required>
                            <?php if (session('errors.current_strength')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.current_strength')) ?>
                                </div>
                            <?php endif; ?>      
                        </div>
                    </div>

                    <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="section_for" name="section_for" required>
                                    <?php 
                                        foreach ($content_data['data']['section_for'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_for', $id, 
                                            old('section_for', isset($data['section_for']) ? $data['section_for'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select>                        
                    </div>  

                    <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="section_type" name="section_type" required>
                                    <?php 
                                        foreach ($content_data['data']['section_type'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_type', $id, 
                                            old('section_type', isset($data['section_type']) ? $data['section_type'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select> 
                            <?php if (session('errors.section_type')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.section_type')) ?>
                                </div>
                            <?php endif; ?>                         
                    </div>
                    
                    
                        <div class="col-lg-6">
                            <div class="dropdown-container" id="sectionTypeContainer" style="display: none;">
                                <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="special_section" name="special_section">
                                        <?php 
                                            foreach ($content_data['data']['special_section'] as $id => $name): ?>
                                            <option value="<?= $id ?>" 
                                                <?= set_select('special_section', $id, 
                                                old('special_section', isset($data['special_section']) ? $data['special_section'] : '') == $id) ?>>
                                                <?= esc($name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                </select> 
                                <?php if (session('errors.special_section')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.special_section')) ?>
                                    </div>
                                <?php endif; ?>                        
                        </div>
                    </div>                                      
                    
                    <?php if($label == 'Edit'){  ?>
                                <div class="col-lg-6">
                                    <div class="common_input mb_15">
                                        <select class="nice_Select2 nice_Select_line wide <?= session('errors.status') ? 'is-invalid' : '' ?>" name="status" id="status">
                                            <option value="0" <?= set_select('status', '0', old('status', isset($data['status']) ? $data['status'] : '') == 0) ?>>Active</option>
                                            <option value="1" <?= set_select('status', '1', old('status', isset($data['status']) ? $data['status'] : '') == 1) ?>>Inactive</option>
                                        </select>
                                        <?php if (session('errors.status')): ?>
                                            <div class="invalid-feedback">
                                                <?= esc(session('errors.status')) ?>
                                            </div>
                                        <?php endif; ?>      
                                    </div>
                                </div>
                        <?php }?>    
                    <div class="col-12">
                        <div class="create_report_btn mt_30">
                            <input type="submit" class="btn_1 d-block text-center" value="<?=$label;?>">                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<script>
        $(document).ready(function() {            
            $('#sectionTypeContainer').hide();
            $('#section_type').change(function() {
                const selectedValue = $(this).val();                
                if (selectedValue === '8') {                    
                    $('#sectionTypeContainer').slideDown(300);                   
                } else {                    
                    $('#sectionTypeContainer').slideUp(300);                   
                }
            });
            $('#section_type').change(function() {
                $('#selectedValue').text($(this).val() || 'None');
            }); 
            $('#selectedValue').text($('#section_type').val() || 'None');
        });
    </script>





