<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
                        <h3 class="m-0"><?=$label;?> Teacher </h3>
                        <span><?php if($label == 'Edit'){?>Username: <?= old('username', $data['username'] ?? '') ?><?php }?></span>
                    </div>                                            
                        <form class="" enctype="multipart/form-data"  autocomplete="off" method="post" action="<?= site_url('teacher/create') ?>">    
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                        <?php endif; ?>

                        <!-- Display flash messages -->
                            <?php if (session()->has('success')) : ?>
                                <div class="alert alert-success"><?= session('success') ?></div>
                            <?php endif ?>

                            <?php if (session()->has('error')) : ?>
                                <div class="alert alert-danger"><?= session('error') ?></div>
                            <?php endif ?>

                            <!-- Display form validation errors -->
                            <?php if (session()->has('errors')) : ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach (session('errors') as $error) : ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif ?>
                        <!-- OR display field-specific errors -->

                </div>
            </div>
            <div class="white_card_body" 
                                        data-csrf-token-name="<?= csrf_token() ?>" 
                                        data-csrf-token-value="<?= csrf_hash() ?>"
                                        data-site-url="<?= site_url('/get-cities-by-state') ?>"
                                        data-permanent-city-id="<?= $data['permanent_city'] ?? '' ?>"
                                        data-present-city-id="<?= $data['present_city'] ?? '' ?>">    

                <div class="row">
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="First Name" class="form-control <?= session('errors.first_name') ? 'is-invalid' : '' ?>" name="first_name" id="first_name" value="<?= old('first_name', $data['first_name'] ?? '') ?>" oninput="validateLettersOnly(this)" required>
                        <?php if (session('errors.first_name')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.first_name')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>                   

                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                        <input type="text" placeholder="Last Name" class="form-control <?= session('errors.last_name') ? 'is-invalid' : '' ?>" name="last_name" id="last_name" value="<?= old('last_name', $data['last_name'] ?? '') ?>" >
                        <?php if (session('errors.last_name')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.last_name')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" name="email" id="email" value="<?= old('email', $data['email'] ?? '') ?>" required>
                        <?php if (session('errors.email')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.email')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Mobile" class="form-control <?= session('errors.mobile') ? 'is-invalid' : '' ?>" name="mobile" id="mobile" oninput="validateDigitsOnlyWithMax(this, 10)" value="<?= old('mobile', $data['mobile'] ?? '') ?>" required>
                        <?php if (session('errors.mobile')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.mobile')) ?>.'xml_error_string'
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select class="nice_Select2 nice_Select_line wide <?= session('errors.employement_type_id') ? 'is-invalid' : '' ?>" name="employement_type_id" id="employement_type_id" required>
                                <?php $selectedEmployementType = old('employement_type_id', $data['employement_type_id'] ?? NULL);
                                foreach ($content_data['data']['employementType'] as $key => $value) {
                                    $disabledKeyAttr = (empty($key) || !isset($key)) ? 'disabled' : '';
                                    $selected = ($key == $selectedEmployementType) ? 'selected' : '';
                                    echo "<option value='{$key}' {$disabledKeyAttr} {$selected}>{$value}</option>";
                                } ?>
                            </select>
                            <?php if (session('errors.employement_type_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.employement_type_id')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_151 select-required-asterisk">                            
                            <select class="form-control select2-multi mb_15 <?= session('errors.specialization_subject_ids') ? 'is-invalid' : '' ?>" placeholder="Select Specialization Subjects" multiple name="specialization_subject_ids[]" id="specialization_subject_ids" required>
                                <?php $selectedSpecializationSubjects = !empty($data['specialization_subject_ids']) 
                                                                        ? explode(',', $data['specialization_subject_ids']) 
                                                                        : [];
                                foreach ($content_data['data']['specializationSubjects'] as $key => $value) {                                    
                                    $selected = in_array($key, $selectedSpecializationSubjects) ? 'selected' : '';
                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                } ?>
                            </select>
                            <?php if (session('errors.specialization_subject_ids')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.specialization_subject_ids')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>                    

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select class="nice_Select2 nice_Select_line wide <?= session('errors.prefered_teaching_level_id') ? 'is-invalid' : '' ?>" name="prefered_teaching_level_id" id="prefered_teaching_level_id" required>
                                <?php $selectedPreferedTeachingLevel = old('prefered_teaching_level_id', $data['prefered_teaching_level_id'] ?? NULL);
                                foreach ($content_data['data']['preferedTeachingLevel'] as $key => $value) {
                                    $selected = ($key == $selectedPreferedTeachingLevel) ? 'selected' : '';
                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                } ?>
                            </select>
                            <?php if (session('errors.prefered_teaching_level_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.prefered_teaching_level_id')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select class="nice_Select2 nice_Select_line wide <?= session('errors.highest_qualification_id') ? 'is-invalid' : '' ?>" name="highest_qualification_id" id="highest_qualification_id" required>
                                <?php $selectedHighestQualification = old('highest_qualification_id', $data['highest_qualification_id'] ?? NULL);
                                foreach ($content_data['data']['highestQualification'] as $key => $value) {
                                    $selected = ($key == $selectedHighestQualification) ? 'selected' : '';
                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                } ?>
                            </select>
                            <?php if (session('errors.highest_qualification_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.highest_qualification_id')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php //echo "<pre>"; print_r($data); die; ?>
                    <div class="col-lg-6">
                        <?php if($isEdit == 'Edit'){
                                                    $service_start_from = $data['service_start_from'];
                                                    $service_start_from_formatted = date('d-m-Y', strtotime($service_start_from));
                         }?>
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" name="service_start_from" placeholder="Service Start From" id="service_start_from" class="form-control datepicker-here" <?= !$isEdit ? 'min="' . date('Y-m-d') . '"' : '' ?>  data-language="en" data-date-format="dd-mm-yyyy" value="<?= old('service_start_from', $service_start_from_formatted ?? '') ?>" required />
                        <?php if (session('errors.service_start_from')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.service_start_from')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div> 

                    <div class="col-lg-6">                            
                            <div class="common_input mb_15 select-required-asterisk">
                                <select class="nice_Select2 nice_Select_line wide" name="approval_status" id="approval_status" required>
                                    <option value="">Select Approval Status</option>
                                    <option value="Approved" <?= set_select('approval_status', 'Approved', isset($data['approval_status']) && $data['approval_status'] == 'Approved') ?>>Approved</option>
                                    <option value="Hold" <?= set_select('approval_status', 'Hold', isset($data['approval_status']) && $data['approval_status'] == 'Hold') ?>>Hold</option>
                                    <option value="Pending" <?= set_select('approval_status', 'Pending', isset($data['approval_status']) && $data['approval_status'] == 'Pending') ?>>Pending</option>                                    
                                </select>
                                <?php if (session('errors.approval_status')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.approval_status')) ?>
                                    </div>
                                <?php endif; ?>   
                            </div>
                    </div>  
                    <div class="section-divider">Permanent Address Details</div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <select name="permanent_state" class="nice_Select2 nice_Select_line wide <?= session('errors.permanent_state') ? 'is-invalid' : '' ?>" id="permanent_state" required>
                            <?php $selectedState = old('permanent_state', $data['permanent_state'] ?? NULL);
                            echo "<pre>Selected Permanent State: "; print_r($selectedState); echo "</pre>";
                            foreach ($content_data['data']['permanentState'] as $key => $value) {
                                $selected = ($key == $selectedState) ? 'selected' : '';
                                echo "<option value='{$key}' {$selected}>{$value}</option>";
                            }
                            ?>
                        </select> 
                        <?php if (session('errors.permanent_state')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_state')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <select name="permanent_city" id="permanent_city" class="nice_Select2 nice_Select_line wide <?= session('errors.permanent_city') ? 'is-invalid' : '' ?>" required> 
                            <option value="">Select Permanent City</option>
                        </select> 
                        <?php if (session('errors.permanent_city')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_city')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <textarea class="form-control" placeholder="Permanent Address" name="permanent_address" id="permanent_address" rows="2" <?= session('errors.permanent_address') ? 'is-invalid' : '' ?>" required><?= old('permanent_address', $data['permanent_address'] ?? '') ?></textarea>    
                        <?php if (session('errors.permanent_address')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_address')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <textarea class="form-control" placeholder="Permanent Address Landmark" name="permanent_landmark" id="permanent_landmark" rows="2" <?= session('errors.permanent_landmark') ? 'is-invalid' : '' ?>" required><?= old('permanent_landmark', $data['permanent_landmark'] ?? '') ?></textarea>
                        <?php if (session('errors.permanent_landmark')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_landmark')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Permanent Pincode" class="form-control <?= session('errors.permanent_pincode') ? 'is-invalid' : '' ?>" name="permanent_pincode" id="permanent_pincode" oninput="validateDigitsOnlyWithMax(this, 6)" value="<?= old('permanent_pincode', $data['permanent_pincode'] ?? '') ?>" required>
                        <?php if (session('errors.permanent_pincode')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_pincode')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="section-divider">Present Address Details</div>

                    <div class="col-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="same_as_permanent" 
                                name="same_as_permanent" <?= isset($data['same_as_permanent']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="same_as_permanent">
                                Same as Permanent Address
                            </label>
                        </div>
                    </div><hr>
                    
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select class="nice_Select2 nice_Select_line wide <?= session('errors.present_state') ? 'is-invalid' : '' ?>" name="present_state" id="present_state" required>
                                <?php $selectedState = old('present_state', $data['present_state'] ?? NULL);
                                foreach ($content_data['data']['presentState'] as $key => $value) {
                                    $selected = ($key == $selectedState) ? 'selected' : '';
                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                } ?>
                            </select>
                            <?php if (session('errors.present_state')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.present_state')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select name="present_city" id="present_city" class="nice_Select2 nice_Select_line wide <?= session('errors.present_city') ? 'is-invalid' : '' ?>" required> 
                                <option value="">Select Present City</option>
                            </select> 
                            <?php if (session('errors.present_city')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.present_city')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <textarea class="form-control" placeholder="Present Address" name="present_address" id="present_address" rows="2" <?= session('errors.present_address') ? 'is-invalid' : '' ?>" required><?= old('present_address', $data['present_address'] ?? '') ?></textarea>     
                        <?php if (session('errors.present_address')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.present_address')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Present Address Landmark" class="form-control <?= session('errors.present_landmark') ? 'is-invalid' : '' ?>" name="present_landmark" id="present_landmark" value="<?= old('present_landmark', $data['present_landmark'] ?? '') ?>" required>
                        <?php if (session('errors.present_landmark')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.present_landmark')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Present Address Pincode" class="form-control <?= session('errors.present_pincode') ? 'is-invalid' : '' ?>" name="present_pincode" id="present_pincode" oninput="validateDigitsOnlyWithMax(this, 6)" value="<?= old('present_pincode', $data['present_pincode'] ?? '') ?>" required>
                        <?php if (session('errors.present_pincode')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.present_pincode')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div> 

                    <div class="section-divider">Other Details</div>            
                    <?php if($label == 'Add'){?>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="<?= lang('Auth.password') ?>" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" name="password" id="floatingPasswordInput" value="<?= old('password', isset($data['password']) ? (int)$data['password'] : '') ?>" required>
                                <?php if (session('errors.password')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.password')) ?>
                                    </div>
                                <?php endif; ?>
                        </div>
                    </div>  
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="<?= lang('Auth.passwordConfirm') ?>" class="form-control <?= session('errors.passwordConfirm') ? 'is-invalid' : '' ?>" name="password_confirm" id="floatingPasswordConfirmInput" value="<?= old('password', isset($data['passwordConfirm']) ? (int)$data['passwordConfirm'] : '') ?>" required>
                            <?php if (session('errors.password_confirm')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.password_confirm')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>                    
            
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="<?= lang('Auth.username') ?>" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" name="username" id="username" value="<?= old('username', $data['username'] ?? '') ?>" required>
                            <?php if (session('errors.username')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.username')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>                    
                     
                    <?php }?>                    
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <select class="nice_Select2 nice_Select_line wide <?= session('errors.user_type_id') ? 'is-invalid' : '' ?>" name="user_type_id" id="user_type_id" required>
                            <?php $selecteduser_type = old('user_type_id', $data['user_type_id'] ?? NULL);                           
                            foreach ($content_data['data']['userType'] as $key => $value) { 
                                $selected = ($key == $selecteduser_type) ? 'selected' : '';
                                $parts = explode(',', $value);
                                $name  = $parts[0] ?? ''; 
                                $use_for_value  = $parts[1] ?? ''; 
                                $typeLabel = ($use_for_value === 'S') ? ' (School)' : 
                                                (($use_for_value === 'A') ? ' (Admin)' : 
                                                (($use_for_value === 'L') ? ' (Library)' : null));
                                echo "<option value='{$key}' {$selected}>{$name}{$typeLabel}</option>";
                            }
                            ?>
                        </select>                         
                        <?php if (session('errors.user_type_id')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.user_type_id')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>                                            
                    <div class="col-lg-6">
                        <div class="common_input mb_15 ">                            
                            <select class="nice_Select2 nice_Select_line wide <?= session('errors.school_id') ? 'is-invalid' : '' ?>" name="school_id" id="school_id">
                                <?php $selectedSchool = old('school_id', $data['school_id'] ?? NULL);
                                foreach ($content_data['data']['schools'] as $key => $value) {
                                    $selected = ($key == $selectedSchool) ? 'selected' : '';
                                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                                }
                                ?>
                            </select>                         
                        <?php if (session('errors.school_id')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.school_id')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">                            
                            <select class="form-control select2-multi mb_15 <?= session('errors.designation_id') ? 'is-invalid' : '' ?>" data-placeholder="Select Designation" multiple name="designation_id[]" id="designation_id" required>
                                <?php $selectedDesignations = !empty($data['designation_id']) ? explode(',', $data['designation_id']) : [];
                                foreach ($content_data['data']['designations'] as $key => $value) {
                                    $disabledAttr = (empty($key) || !isset($key)) ? 'disabled' : '';                                    
                                    $selected = in_array($key, $selectedDesignations) ? 'selected' : '';
                                    echo "<option value='{$key}' {$disabledAttr} {$selected}>{$value}</option>";
                                } ?>
                            </select>
                            <?php if (session('errors.designation_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.designation_id')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">                        
                            <label for="fileInput" class="select-required-asterisk1" id="fileLabel">Profile Image</label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
                            <?php if (session('errors.profile_image')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.profile_image')) ?>
                                </div>
                            <?php endif; ?>
                        <!-- </div> -->
                    </div>                   

                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                        <textarea class="form-control <?= session('errors.about') ? 'is-invalid' : '' ?>" placeholder="About" id="about" name="about" rows="2"><?= old('about', $data['about'] ?? '') ?></textarea>    
                        <?php if (session('errors.about')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.about')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                            <img id="imagePreview" class="img-fluid mt-2 d-none" style="max-height: 200px;">                            
                            <?php if ($isEdit && !empty($data['profile_image'])): ?>
                                <div class="mt-2">
                                <a href="<?= base_url('/' . $data['profile_image']) ?>" target="_blank" title="<?= esc($data['profile_image']) ?>">
                                                            <img src="<?= base_url('/' . $data['profile_image']) ?>"  
                                                    class="img-thumbnail" style="max-height: 150px;" alt="<?= esc($data['profile_image']) ?>"/></a>    
                                
                                </div>
                            <?php endif; ?> 
                        </div>
                    </div>   

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
<script src="<?= base_url('assets/adminAssets/js/select2_content.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('assets/adminAssets/js/select_state_city.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // $('#specialization_subject_ids').select2({
    //     placeholder: "Select11 Specialization Subjects",
    //     allowClear: true,
    //     width: '100%'
    // });
    $('#designation_id').select2({
        placeholder: "Select Designations",
        allowClear: true,
        width: '100%'
    });
    setupImagePreview('profile_image', 'imagePreview');
});
</script>





