<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<?php 
$student = $content_data['data']['data'] ?? null;
$isEdit = $content_data['data']['isEdit'] ?? false;
$label = $isEdit ? 'Edit' : 'Add';
 
if($label == 'Edit'){
        $dob = $student['date_of_birth']; 
        $dob_formatted = date('d-m-Y', strtotime($dob));

        $admissionDate = $student['admission_date']; 
        $admission_date_formatted = date('d-m-Y', strtotime($admissionDate));
}
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?= $label; ?> Student</h3>                        
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
            
            <div class="white_card_header">                
                <form class="" autocomplete="off" method="post" action="<?= site_url('students/save') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $student['id'] ?>">
                        <!-- Hidden fields for preserved data in edit mode -->
                        <input type="hidden" name="user_id" value="<?= $student['user_id'] ?? '' ?>">
                        <input type="hidden" name="admission_no" value="<?= $student['admission_no'] ?? '' ?>">
                        <input type="hidden" name="roll_no" value="<?= $student['roll_no'] ?? '' ?>">
                    <?php endif; ?>

                    <div class="white_card_body" 
                    data-csrf-token-name="<?= csrf_token() ?>" 
                    data-csrf-token-value="<?= csrf_hash() ?>"
                    data-site-url="<?= site_url('/get-cities-by-state') ?>"
                    data-permanent-city-id="<?= $student['permanent_city'] ?? '' ?>"
                    data-present-city-id="<?= $student['present_city'] ?? '' ?>">
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <!-- <div class="col-12">
                            <h5 class="mb-3">Basic Information</h5>
                        </div> -->
                        <div class="section-divider">Basic Information</div>
                        
                        <!-- <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Admission No">
                                <input type="text" class="form-control <?= session('errors.admission_no') ? 'is-invalid' : '' ?>" 
                                    id="admission_no" name="admission_no" placeholder="Admission No" 
                                    value="<?= set_value('admission_no', $student['admission_no'] ?? '') ?>" required 
                                    <?= (!$isEdit && empty($student['admission_no'])) ? 'readonly' : '' ?>>
                                </div>    
                                <?php if (session('errors.admission_no')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.admission_no')) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$isEdit && empty($student['admission_no'])): ?>
                                    <small class="form-text text-muted">
                                        <a href="#" id="generateAdmissionNo">Generate Admission No</a>
                                        <span id="regenerateAdmissionNo" style="display:none; margin-left: 10px;">
                                            | <a href="#" id="regenerateLink">Regenerate</a>
                                        </span>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Roll No">
                                    <input type="text" class="form-control" id="roll_no_display" placeholder="Roll No" 
                                        value="<?= set_value('roll_no', $student['roll_no'] ?? 'Auto-generated after save') ?>" readonly>
                                    <input type="hidden" id="roll_no" name="roll_no" value="<?= set_value('roll_no', $student['roll_no'] ?? '') ?>">
                                </div>
                                <small class="form-text text-muted">Will be auto-generated based on class and section</small>
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Admission No">
                                    <input type="text" class="form-control <?= session('errors.admission_no') ? 'is-invalid' : '' ?>" 
                                        id="admission_no" name="admission_no" placeholder="Admission No" 
                                        value="<?= set_value('admission_no', $student['admission_no'] ?? '') ?>" 
                                        <?= ($isEdit) ? 'readonly' : 'required' ?>
                                        <?= (!$isEdit && empty($student['admission_no'])) ? 'readonly' : '' ?>>
                                </div>    
                                <?php if (session('errors.admission_no')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.admission_no')) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$isEdit && empty($student['admission_no'])): ?>
                                    <small class="form-text text-muted">
                                        <a href="#" id="generateAdmissionNo">Generate Admission No</a>
                                        <span id="regenerateAdmissionNo" style="display:none; margin-left: 10px;">
                                            | <a href="#" id="regenerateLink">Regenerate</a>
                                        </span>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Roll No">
                                    <input type="text" class="form-control" id="roll_no_display" placeholder="Roll No" 
                                        value="<?= set_value('roll_no', $student['roll_no'] ?? ($isEdit ? 'No roll number assigned' : 'Auto-generated after save')) ?>" 
                                        <?= ($isEdit) ? 'readonly' : 'readonly' ?>>
                                    <input type="hidden" id="roll_no" name="roll_no" value="<?= set_value('roll_no', $student['roll_no'] ?? '') ?>">
                                </div>
                                <small class="form-text text-muted">
                                    <?= $isEdit ? 'Roll number cannot be changed' : 'Will be auto-generated based on class and section' ?>
                                </small>
                            </div>
                        </div> -->
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Admission No">
                                    <input type="text" class="form-control <?= session('errors.admission_no') ? 'is-invalid' : '' ?>" 
                                        id="admission_no" name="admission_no" placeholder="Admission No" 
                                        value="<?= set_value('admission_no', $student['admission_no'] ?? '') ?>" 
                                        <?= ($isEdit) ? 'readonly' : 'required' ?>
                                        <?= (!$isEdit && empty($student['admission_no'])) ? 'readonly' : '' ?>>
                                </div>    
                                <?php if (session('errors.admission_no')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.admission_no')) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$isEdit && empty($student['admission_no'])): ?>
                                    <small class="form-text text-muted">
                                        <a href="#" id="generateAdmissionNo">Generate Admission No</a>
                                        <span id="regenerateAdmissionNo" style="display:none; margin-left: 10px;">
                                            | <a href="#" id="regenerateLink">Regenerate</a>
                                        </span>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Roll No Field -->
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Roll No">
                                    <input type="text" class="form-control" id="roll_no_display" placeholder="Roll No" 
                                        value="<?= set_value('roll_no', $student['roll_no'] ?? ($isEdit ? $student['roll_no'] : 'Auto-generated after save')) ?>" 
                                        readonly>
                                    <input type="hidden" id="roll_no" name="roll_no" value="<?= set_value('roll_no', $student['roll_no'] ?? '') ?>">
                                </div>
                                <small class="form-text text-muted">
                                    <?= $isEdit ? 'Roll number cannot be changed' : 'Will be auto-generated based on class and section' ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="tooltip-wrapper" data-tooltip="Financial Year">
                            <select class="nice_Select2 nice_Select_line wide mb_15 <?= session('errors.financial_year_id') ? 'is-invalid' : '' ?>" 
                                id="financial_year_id" name="financial_year_id" data-tooltip="Choose your caste category" required>
                                   <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('financial_year_id', $id, 
                                        old('financial_year_id', isset($student['financial_year_id']) ? $student['financial_year_id'] : FINANCIAL_YEAR_ID) == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                            </div>                            
                            <?php if (session('errors.financial_year_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.financial_year_id')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>

                        <div class="col-lg-4">
                          <div class="common_input mb_15">
                          <div class="tooltip-wrapper" data-tooltip="Class">
                            <select class="nice_Select2 nice_Select_line wide mb_151 <?= session('errors.class_id') ? 'is-invalid' : '' ?>" 
                                id="class_id" name="class_id" required>
                              <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                    <option value="<?= $id ?>" 
                                        <?= set_select('class_id', $id, 
                                        old('class_id', isset($student['class_id']) ? $student['class_id'] : '') == $id) ?>>
                                        <?= esc($name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> 
                            </div> 
                            <!-- <small class="text-muted">Class</small> -->
                            <?php if (session('errors.class_id')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.class_id')) ?>
                                </div>
                            <?php endif; ?>   
                            </div>                    
                        </div>
                               
                        <div class="col-lg-4">
                          <div class="common_input mb_15">
                          <div class="tooltip-wrapper" data-tooltip="Section">                          
                            <select class="nice_Select2 nice_Select_line wide mb_151 <?= session('errors.section_id') ? 'is-invalid' : '' ?>" 
                                id="section_id" name="section_id" required>                                
                                <?php if ($isEdit): ?>                                    
                                    <?php foreach ($content_data['data']['sections'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('section_id', $id, 
                                            old('section_id', isset($student['section_id']) ? $student['section_id'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Select Section</option>
                                <?php endif; ?>
                                <small class="text-muted">Section</small>
                            </select> 
                            </div>                                
                                <?php if (session('errors.section_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.section_id')) ?>
                                    </div>
                                <?php endif; ?>  
                            </div>                      
                        </div>

                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                            <div class="tooltip-wrapper" data-tooltip="First Name">
                                <input type="text" class="form-control <?= session('errors.first_name') ? 'is-invalid' : '' ?>" 
                                    id="first_name" name="first_name" placeholder="First Name" 
                                    value="<?= set_value('first_name', $student['first_name'] ?? '') ?>" required> 
                                </div>                                  
                                <?php if (session('errors.first_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.first_name')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Last Name">
                                <input type="text" class="form-control <?= session('errors.last_name') ? 'is-invalid' : '' ?>" 
                                    id="last_name" name="last_name" placeholder="Last Name" 
                                    value="<?= set_value('last_name', $student['last_name'] ?? '') ?>" required>
                                    </div>
                                <?php if (session('errors.last_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.last_name')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="tooltip-wrapper" data-tooltip="Gender">   
                            <select class="nice_Select2 nice_Select_line wide mb_151 <?= session('errors.gender') ? 'is-invalid' : '' ?>" 
                                id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <?php foreach ($content_data['data']['genders'] as $gender): ?>
                                    <option value="<?= $gender ?>" 
                                        <?= set_select('gender', $gender, 
                                        old('gender', isset($student['gender']) ? $student['gender'] : '') == $gender) ?>>
                                        <?= esc($gender) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            </div>
                            <?php if (session('errors.gender')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.gender')) ?>
                                </div>
                            <?php endif; ?>                        
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                    <div class="tooltip-wrapper" data-tooltip="Date of Birth">
                                        <input type="text" class="form-control datepicker-here <?= session('errors.date_of_birth') ? 'is-invalid' : '' ?>" 
                                        data-language="en" data-date-format="dd-mm-yyyy" id="date_of_birth" name="date_of_birth" placeholder="Date of Birth" 
                                        value="<?= set_value('date_of_birth', $dob_formatted ?? '') ?>" required> 
                                    </div>                                   
                                    <?php if (session('errors.date_of_birth')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.date_of_birth')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Blood Group">
                                <select class="nice_Select2 nice_Select_line wide mb_151" id="blood_group" name="blood_group">
                                    <option value="">Select Blood Group</option>
                                    <?php foreach ($content_data['data']['blood_groups'] as $bg): ?>
                                        <option value="<?= $bg ?>" 
                                            <?= set_select('blood_group', $bg, 
                                            old('blood_group', isset($student['blood_group']) ? $student['blood_group'] : '') == $bg) ?>>
                                            <?= esc($bg) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select> 
                                </div>                                 
                                <?php if (session('errors.blood_group')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.blood_group')) ?>
                                    </div>
                                <?php endif; ?>  
                            </div>                    
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                    <div class="tooltip-wrapper" data-tooltip="Religion">
                                        <input type="text" class="form-control" id="religion" name="religion" placeholder="Religion" 
                                        value="<?= set_value('religion', $student['religion'] ?? '') ?>">
                                    </div> 
                                    <?php if (session('errors.religion')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.religion')) ?>
                                        </div>
                                    <?php endif; ?>    
                            </div>
                        </div>
                                                
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Caste Category">
                                <select class="nice_Select2 nice_Select_line wide mb_151" id="caste" name="caste">
                                    <option value="">Select Caste Category</option>
                                    <?php foreach ($content_data['data']['caste_categories'] as $id=>$name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('caste', $id, 
                                            old('caste', isset($student['caste']) ? $student['caste'] : '') == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>                                   
                                </select>
                                </div>                                 
                                <!-- caste_categories -->                                      
                                    <?php if (session('errors.caste')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.caste')) ?>
                                        </div>
                                    <?php endif; ?> 

                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Nationality">
                                    <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nationality" 
                                    value="<?= set_value('nationality', $student['nationality'] ?? 'Indian') ?>">
                                </div>  
                                <?php if (session('errors.nationality')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.nationality')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Aadhaar Number">
                                    <input type="text" oninput="validateDigitsOnlyWithMax(this, 12)" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="Aadhaar No" 
                                    value="<?= set_value('aadhaar_no', $student['aadhaar_no'] ?? '') ?>">
                                </div>  
                                <?php if (session('errors.aadhaar_no')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.aadhaar_no')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                    <div class="tooltip-wrapper" data-tooltip="Mobile No">
                                    <input type="text" class="form-control <?= session('errors.mobile_no') ? 'is-invalid' : '' ?>" 
                                    id="mobile_no" name="mobile_no" placeholder="Mobile No" oninput="validateDigitsOnlyWithMax(this, 10)"
                                    value="<?= set_value('mobile_no', $student['mobile_no'] ?? '') ?>">
                                    </div>    
                                <?php if (session('errors.mobile_no')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mobile_no')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                    <div class="tooltip-wrapper" data-tooltip="Email">
                                        <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                        id="email" name="email" placeholder="Email" 
                                        value="<?= set_value('email', $student['email'] ?? '') ?>">
                                    </div>    
                                <?php if (session('errors.email')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.email')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Admission Date">
                                    <input type="text" class="form-control datepicker-here <?= session('errors.admission_date') ? 'is-invalid' : '' ?>" 
                                    data-language="en" data-date-format="dd-mm-yyyy" id="admission_date" name="admission_date" placeholder="Admission Date" 
                                    value="<?= set_value('admission_date', $admission_date_formatted ?? date('d-m-Y')) ?>" required>
                                </div>
                                <?php if (session('errors.admission_date')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.admission_date')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Parent/Guardian Information -->
                        <!-- <div class="col-12 mt-4">
                            <h5 class="mb-3">Parent/Guardian Information</h5>
                        </div> -->
                        <div class="section-divider">Parent/Guardian Information</div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Father's Name">
                                    <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Father's Name" 
                                    value="<?= set_value('father_name', $student['father_name'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.father_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.father_name')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Father's Occupation">
                                    <input type="text" class="form-control" id="father_occupation" name="father_occupation" placeholder="Father's Occupation" 
                                    value="<?= set_value('father_occupation', $student['father_occupation'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.father_occupation')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.father_occupation')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Father's Mobile">
                                <input type="text" class="form-control" id="father_mobile" name="father_mobile" placeholder="Father's Mobile" 
                                    value="<?= set_value('father_mobile', $student['father_mobile'] ?? '') ?>" oninput="validateDigitsOnlyWithMax(this, 10)">
                                </div>
                                <?php if (session('errors.father_mobile')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.father_mobile')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Father's Email">
                                    <input type="email" class="form-control" id="father_email" name="father_email" placeholder="Father's Email" 
                                    value="<?= set_value('father_email', $student['father_email'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.father_email')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.father_email')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Mother's Name">
                                    <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Mother's Name" 
                                    value="<?= set_value('mother_name', $student['mother_name'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.mother_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mother_name')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Mother's Occupation">
                                    <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" placeholder="Mother's Occupation" 
                                    value="<?= set_value('mother_occupation', $student['mother_occupation'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.mother_occupation')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mother_occupation')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Mother's Mobile">
                                    <input type="text" class="form-control" id="mother_mobile" name="mother_mobile" placeholder="Mother's Mobile" 
                                    value="<?= set_value('mother_mobile', $student['mother_mobile'] ?? '') ?>" oninput="validateDigitsOnlyWithMax(this, 10)">
                                </div>
                                <?php if (session('errors.mother_mobile')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mother_mobile')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Mother's Email">
                                    <input type="email" class="form-control" id="mother_email" name="mother_email" placeholder="Mother's Email" 
                                    value="<?= set_value('mother_email', $student['mother_email'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.mother_email')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mother_email')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Guardian's Name">
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="Guardian's Name" 
                                    value="<?= set_value('guardian_name', $student['guardian_name'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.guardian_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.guardian_name')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Relation with Guardian">
                                    <input type="text" class="form-control" id="guardian_relation" name="guardian_relation" placeholder="Relation with Guardian" 
                                    value="<?= set_value('guardian_relation', $student['guardian_relation'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.guardian_relation')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.guardian_relation')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Guardian's Occupation">
                                    <input type="text" class="form-control" id="guardian_occupation" name="guardian_occupation" placeholder="Guardian's Occupation" 
                                    value="<?= set_value('guardian_occupation', $student['guardian_occupation'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.guardian_occupation')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.guardian_occupation')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Guardian's Mobile">
                                    <input type="text" class="form-control" id="guardian_mobile" name="guardian_mobile" placeholder="Guardian's Mobile" 
                                    value="<?= set_value('guardian_mobile', $student['guardian_mobile'] ?? '') ?>" oninput="validateDigitsOnlyWithMax(this, 10)">
                                </div>
                                <?php if (session('errors.guardian_mobile')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.guardian_mobile')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Guardian's Email">
                                    <input type="email" class="form-control" id="guardian_email" name="guardian_email" placeholder="Guardian's Email" 
                                    value="<?= set_value('guardian_email', $student['guardian_email'] ?? '') ?>">
                                </div>
                                <?php if (session('errors.guardian_email')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.guardian_email')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>

                <div class="section-divider">Permanent Address Details</div>
                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Permanent State">
                            <div class="common_input mb_15 select-required-asterisk">
                                <select name="permanent_state" class="nice_Select2 nice_Select_line wide <?= session('errors.permanent_state') ? 'is-invalid' : '' ?>" id="permanent_state" required>
                                    <?php $selectedState = old('permanent_state', $student['permanent_state'] ?? NULL);
                                    //echo "<pre>Selected Permanent State: "; print_r($selectedState); echo "</pre>";
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
                    </div>

                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Permanent City">
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
                    </div>

                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Permanent Address">
                        <div class="common_input mb_15 select-required-asterisk">
                        <textarea class="form-control" placeholder="Permanent Address" name="permanent_address" id="permanent_address" rows="2" <?= session('errors.permanent_address') ? 'is-invalid' : '' ?>" required><?= old('permanent_address', $student['permanent_address'] ?? '') ?></textarea>    
                        <?php if (session('errors.permanent_address')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_address')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Permanent Address Landmark">
                        <div class="common_input mb_15 select-required-asterisk">
                        <textarea class="form-control" placeholder="Permanent Address Landmark" name="permanent_landmark" id="permanent_landmark" rows="2" <?= session('errors.permanent_landmark') ? 'is-invalid' : '' ?>" required><?= old('permanent_landmark', $student['permanent_landmark'] ?? '') ?></textarea>
                        <?php if (session('errors.permanent_landmark')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_landmark')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                    <div class="tooltip-wrapper" data-tooltip="Permanent Pincode">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Permanent Pincode" class="form-control <?= session('errors.permanent_pincode') ? 'is-invalid' : '' ?>" name="permanent_pincode" id="permanent_pincode" oninput="validateDigitsOnlyWithMax(this, 6)" value="<?= old('permanent_pincode', $student['permanent_pincode'] ?? '') ?>" required>
                        <?php if (session('errors.permanent_pincode')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.permanent_pincode')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div>

                    <div class="section-divider">Present Address Details</div>

                    <div class="col-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="same_as_permanent" 
                                name="same_as_permanent" <?= isset($student['same_as_permanent']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="same_as_permanent">
                                Same as Permanent Address
                            </label>
                        </div>
                    </div><hr>
                    
                    <div class="col-lg-6">
                    <div class="tooltip-wrapper" data-tooltip="Present State">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select class="nice_Select2 nice_Select_line wide <?= session('errors.present_state') ? 'is-invalid' : '' ?>" name="present_state" id="present_state" required>
                                <?php $selectedState = old('present_state', $student['present_state'] ?? NULL);
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
                    </div>

                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Present City">
                        <div class="common_input mb_15 select-required-asterisk">
                            <select name="present_city" id="present_city" class="nice_Select2 nice_Select_line wide <?= session('errors.present_city') ? 'is-invalid' : '' ?>" required> 
                                <option value="">Select Present </option>
                            </select> 
                            <?php if (session('errors.present_city')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.present_city')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Present Address">
                        <div class="common_input mb_15 select-required-asterisk">
                        <textarea class="form-control" placeholder="Present Address" name="present_address" id="present_address" rows="2" <?= session('errors.present_address') ? 'is-invalid' : '' ?>" required><?= old('present_address', $student['present_address'] ?? '') ?></textarea>     
                        <?php if (session('errors.present_address')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.present_address')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Present Address Landmark">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Present Address Landmark" class="form-control <?= session('errors.present_landmark') ? 'is-invalid' : '' ?>" name="present_landmark" id="present_landmark" value="<?= old('present_landmark', $student['present_landmark'] ?? '') ?>" required>
                        <?php if (session('errors.present_landmark')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.present_landmark')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="Present Address Pincode">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" placeholder="Present Address Pincode" class="form-control <?= session('errors.present_pincode') ? 'is-invalid' : '' ?>" name="present_pincode" id="present_pincode" oninput="validateDigitsOnlyWithMax(this, 6)" value="<?= old('present_pincode', $student['present_pincode'] ?? '') ?>" required>
                        <?php if (session('errors.present_pincode')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.present_pincode')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div> 

                    <div class="col-lg-6"> 
                    </div> 
                    
                    <div class="section-divider">Other Information</div>
                    <div class="col-lg-6">                        
                            <label for="fileInput" class="select-required-asterisk1" id="fileLabel">Student Image</label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
                            <?php if (session('errors.profile_image')): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors.profile_image')) ?>
                                </div>
                            <?php endif; ?>
                        <!-- </div> -->
                    </div>    

                    <div class="col-lg-6">
                        <div class="tooltip-wrapper" data-tooltip="About">
                        <div class="common_input mb_15">
                        <textarea class="form-control <?= session('errors.about') ? 'is-invalid' : '' ?>" placeholder="About" id="about" name="about" rows="2"><?= old('about', $student['about'] ?? '') ?></textarea>    
                        <?php if (session('errors.about')): ?>
                            <div class="invalid-feedback">
                                <?= esc(session('errors.about')) ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                            <img id="imagePreview" class="img-fluid mt-2 d-none" style="max-height: 200px;">                            
                            <?php if ($isEdit && !empty($student['profile_image'])): ?>
                                <div class="mt-2">
                                <a href="<?= base_url('/' . $student['profile_image']) ?>" target="_blank" title="<?= esc($student['profile_image']) ?>">
                                                            <img src="<?= base_url('/' . $student['profile_image']) ?>"  
                                                    class="img-thumbnail" style="max-height: 150px;" alt="<?= esc($student['profile_image']) ?>"/></a>    
                                
                                </div>
                            <?php endif; ?> 
                        </div>
                    </div>  
                        
                    <?php if($isEdit): ?>
                        <div class="col-lg-6">
                            <div class="common_input mb_15">
                                <div class="tooltip-wrapper" data-tooltip="Status">
                                <select class="nice_Select2 nice_Select_line wide <?= session('errors.status') ? 'is-invalid' : '' ?>" name="status" id="status">
                                    <option value="0" <?= set_select('status', '0', old('status', isset($student['status']) ? $student['status'] : '') == 0) ?>>Active</option>
                                    <option value="1" <?= set_select('status', '1', old('status', isset($student['status']) ? $student['status'] : '') == 1) ?>>Inactive</option>
                                </select>
                                </div>
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
                                <input type="submit" class="btn_1" value="<?= $label; ?> Student">                            
                            </div>
                        </div>
                    </div>
                </form>
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
$(document).ready(function() {
    // Function to load sections based on class         
    function loadSections(classId, financialYearId, selectedSectionId = '') {
        if (classId && financialYearId) {
            $.ajax({
                url: '<?= site_url('students/get-sections/') ?>' + classId + '/' + financialYearId,
                type: 'GET',
                success: function(data) {
                    $('#section_id').html('<option value="">Select Section</option>');
                    $.each(data, function(key, value) {
                        var selected = (key == selectedSectionId) ? 'selected' : '';
                        $('#section_id').append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
                    });
                    
                    // Refresh the nice-select to reflect the changes
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                    
                    // After populating sections, check if we should preview roll number
                    checkAndPreviewRollNumber();
                },
                error: function() {
                    $('#section_id').html('<option value="">Select Section</option>');
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                    // Clear roll number on error
                    clearRollNumber();
                }
            });
        } else {
            $('#section_id').html('<option value="">Select Section</option>');
            if (typeof $.fn.niceSelect !== 'undefined') {
                $('#section_id').niceSelect('update');
            }
            // Clear roll number when sections are cleared
            clearRollNumber();
        }
    } 
    // Function to clear roll number
    function clearRollNumber() {
        $('#roll_no_display').val('Select class, section, and financial year first');
        $('#roll_no').val('');
        $('#roll_no_display').removeClass('text-danger text-warning');
    } 
    // Function to check conditions and preview roll number
    function checkAndPreviewRollNumber() {
        var classId = $('#class_id').val();
        var sectionId = $('#section_id').val();
        var financialYearId = $('#financial_year_id').val();
        
        if (classId && sectionId && financialYearId) {
            previewRollNumber();
        } else {
            clearRollNumber();
        }
    }
    // Always try to load sections on page load - enhanced version    
    function initializeSectionsOnLoad() {
        var existingClassId = $('#class_id').val();
        var existingFinancialYearId = $('#financial_year_id').val();
        
        // Get the selected section ID from form submission or existing data
        var existingSectionId = '<?= set_value('section_id', $student['section_id'] ?? '') ?>';
        
        // If we have both class and financial year, load sections
        if (existingClassId && existingFinancialYearId) {
            loadSections(existingClassId, existingFinancialYearId, existingSectionId);
        } 
        // If we have class but no financial year, try to get financial year from form
        else if (existingClassId) {
            var formFinancialYearId = '<?= set_value('financial_year_id', $student['financial_year_id'] ?? '') ?>';
            if (formFinancialYearId) {
                loadSections(existingClassId, formFinancialYearId, existingSectionId);
            } else {
                clearRollNumber();
            }
        } else {
            clearRollNumber();
        }
        
        // In edit mode, show the existing roll number without AJAX call
        if (<?= $isEdit ? 'true' : 'false' ?>) {
            var existingRollNo = '<?= set_value('roll_no', $student['roll_no'] ?? '') ?>';
            if (existingRollNo) {
                $('#roll_no_display').val(existingRollNo);
                $('#roll_no').val(existingRollNo);
            } else {
                $('#roll_no_display').val('No roll number assigned');
            }
        }
    }
    // Initialize sections when page loads
    initializeSectionsOnLoad();
    // Also disable the section change handlers for roll number generation in edit mode
    <?php if (!$isEdit): ?>
        // Class change handler - clear roll number and load sections
        $('#class_id').change(function() { 
            var classId = $(this).val();
            var financialYearId = $('#financial_year_id').val();
            
            // Clear roll number immediately when class changes
            clearRollNumber();
            
            if (classId && financialYearId) {
                loadSections(classId, financialYearId);
            } else {
                // If no financial year, clear sections too
                $('#section_id').html('<option value="">Select Section</option>');
                if (typeof $.fn.niceSelect !== 'undefined') {
                    $('#section_id').niceSelect('update');
                }
            }
        });
        // Financial year change handler - clear roll number and load sections
        $('#financial_year_id').change(function() {
            var classId = $('#class_id').val();
            var financialYearId = $(this).val();
            
            // Clear roll number immediately when financial year changes
            clearRollNumber();
            
            if (classId && financialYearId) {
                loadSections(classId, financialYearId);
            } else {
                // If no class, clear sections too
                $('#section_id').html('<option value="">Select Section</option>');
                if (typeof $.fn.niceSelect !== 'undefined') {
                    $('#section_id').niceSelect('update');
                }
            }
        });

        // Section change handler - clear roll number and check if we can generate
        $('#section_id').change(function() {
            // Clear roll number immediately when section changes
            clearRollNumber();
            
            // Then check if we have all required fields to generate new roll number
            var classId = $('#class_id').val();
            var sectionId = $(this).val();
            var financialYearId = $('#financial_year_id').val();
            
            if (classId && sectionId && financialYearId) {
                previewRollNumber();
            }
        });
    <?php endif; ?>    

    // Rest of your existing functions remain the same... 
    function previewRollNumber() { 
        if (<?= $isEdit ? 'true' : 'false' ?>) {
            return;
        }        
        var classId = $('#class_id').val();
        var sectionId = $('#section_id').val();
        var financialYearId = $('#financial_year_id').val();
        
        console.log('Preview roll number called:', {classId, sectionId, financialYearId});
        
        if (classId && sectionId && financialYearId) {
            $.ajax({
                url: '<?= site_url('students/preview-roll-no') ?>',
                type: 'POST',
                data: {
                    class_id: classId,
                    section_id: sectionId,
                    financial_year_id: financialYearId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                beforeSend: function() {
                    $('#roll_no_display').val('Generating...');
                    $('#roll_no_display').removeClass('text-danger text-warning');
                },
                success: function(response) {                    
                    console.log('Roll number preview response:', response);
                    if (response.success) {
                        $('#roll_no_display').val(response.roll_no);
                        $('#roll_no').val(response.roll_no);
                        if (response.message && response.message.includes('fallback')) {
                            $('#roll_no_display').addClass('text-warning');
                            $('#roll_no_display').attr('title', 'Generated using fallback method');
                        } else {
                            $('#roll_no_display').attr('title', 'Preview - counter will be incremented on save');
                        }
                    } else {
                        var simpleRollNo = 'CLS' + classId + '-SEC' + sectionId + '-001';
                        $('#roll_no_display').val(simpleRollNo + ' (preview)');
                        $('#roll_no').val(simpleRollNo);
                        $('#roll_no_display').addClass('text-warning');
                        $('#roll_no_display').attr('title', 'Error: ' + (response.message || 'Using fallback preview'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Roll number preview error:', error);
                    var simpleRollNo = 'CLS' + classId + '-SEC' + sectionId + '-001';
                    $('#roll_no_display').val(simpleRollNo + ' (preview)');
                    $('#roll_no').val(simpleRollNo);
                    $('#roll_no_display').addClass('text-warning');
                    $('#roll_no_display').attr('title', 'Network error - using fallback preview');
                }
            });
        } else {
            clearRollNumber();
        }
    }

    // Your existing admission number generation code...
    $('#generateAdmissionNo').click(function(e) {
        e.preventDefault();
        generateAdmissionNumber();
    });

    $(document).on('click', '#regenerateLink', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to generate a new admission number? The current one will be replaced.')) {
            generateAdmissionNumber();
        }
    });

    function generateAdmissionNumber() {
        var financialYearId = $('#financial_year_id').val();
        var currentAdmissionNo = $('#admission_no').val();
        var regenerate = currentAdmissionNo !== '' && currentAdmissionNo !== 'Generating...';
        
        if (!financialYearId) {
            alert('Please select financial year first');
            return;
        }
        
        $.ajax({
            url: '<?= site_url('students/preview-admission-no') ?>',
            type: 'POST',
            data: {
                financial_year_id: financialYearId,
                regenerate: regenerate,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            beforeSend: function() {
                $('#admission_no').val('Generating...');
                $('#generateAdmissionNo').hide();
                $('#regenerateAdmissionNo').hide();
            },
            success: function(response) {
                if (response.success) {
                    $('#admission_no').val(response.admission_no);
                    $('#admission_no').removeAttr('readonly');
                    $('#regenerateAdmissionNo').show();
                } else {
                    alert('Error: ' + (response.message || 'Failed to generate admission number'));
                    $('#generateAdmissionNo').show();
                    if (currentAdmissionNo) {
                        $('#regenerateAdmissionNo').show();
                    }
                }
            },
            error: function() {
                alert('Network error. Please try again.');
                $('#generateAdmissionNo').show();
                if (currentAdmissionNo) {
                    $('#regenerateAdmissionNo').show();
                }
            }
        });
    }

    $('#financial_year_id').change(function() {
        if (!$('#admission_no').val() && !<?= $isEdit ? 'true' : 'false' ?>) {
            generateAdmissionNumber();
        }
    });

    if ($('#admission_no').val()) {
        $('#generateAdmissionNo').hide();
        $('#regenerateAdmissionNo').show();
        $('#admission_no').removeAttr('readonly');
    }

    setupImagePreview('profile_image', 'imagePreview');
});
</script>
