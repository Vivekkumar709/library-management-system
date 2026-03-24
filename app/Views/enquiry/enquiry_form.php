<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$data = $content_data['data']['data'][0] ?? null;
$isEdit = $content_data['data']['isEdit'] ?? false;
$label = $isEdit ? 'Edit' : 'Add';

?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?=$label?> Student Enquiry</h3>
                    </div>
                    
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>
                    
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                    <?php endif ?>
                    
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success"><?= esc(session('message')) ?></div>
                    <?php endif ?>
                </div>
            </div>
            
            <div class="white_card_body">
                <form class="" autocomplete="off" method="post" action="<?= site_url('enquiry/create') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="_method" value="post">
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Personal Details -->
                        <div class="section-divider">Personal Details</div>                       
                        <div class="col-lg-6">
                            <label for="student_name" class="div-lable">Student's Full Name</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Student's Full Name" class="form-control <?= session('errors.student_name') ? 'is-invalid' : '' ?>" 
                                    name="student_name" id="student_name" oninput="validateLettersOnly(this)"
                                    value="<?= old('student_name', $data['student_name'] ?? '') ?>" required>
                                    <?php if (session('errors.student_name')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.student_name')) ?>
                                        </div>
                                    <?php endif; ?>    
                            </div>
                        </div>
                        <?php 
                            if($label == 'Edit'){
                                    $dob = $data['date_of_birth']; 
                                    $dob_formatted = date('d-m-Y', strtotime($dob));
                            }
                        ?>
                        <div class="col-lg-6">
                            <label for="date_of_birth" class="div-lable">Date of Birth</label>
                            <div class="common_input mb_15 select-required-asterisk">                                
                                <input type="text" placeholder="Date of Birth"
                                    class="form-control datepicker-here <?= session('errors.date_of_birth') ? 'is-invalid' : '' ?>"  data-language="en" data-date-format="dd-mm-yyyy"
                                    name="date_of_birth" id="date_of_birth" 
                                    value="<?= old('date_of_birth', $dob_formatted ?? '') ?>" required>
                                    <?php if (session('errors.date_of_birth')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.date_of_birth')) ?>
                                        </div>
                                    <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="gender" class="div-lable">Gender</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <select class="nice_Select2 nice_Select_line wide <?= session('errors.gender') ? 'is-invalid' : '' ?>" name="gender" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?= set_select('gender', 'Male', isset($data['gender']) && $data['gender'] == 'Male') ?>>Male</option>
                                    <option value="Female" <?= set_select('gender', 'Female', isset($data['gender']) && $data['gender'] == 'Female') ?>>Female</option>
                                    <option value="Other" <?= set_select('gender', 'Other', isset($data['gender']) && $data['gender'] == 'Other') ?>>Other</option>                                    
                                </select>
                                <?php if (session('errors.gender')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.gender')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>                       
                        
                        <div class="col-lg-6">
                            <label for="nationality" class="div-lable">Nationality</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Nationality" class="form-control <?= session('errors.nationality') ? 'is-invalid' : '' ?>" 
                                    name="nationality" id="nationality" 
                                    value="<?= old('nationality', $data['nationality'] ?? '') ?>" required>
                                <?php if (session('errors.nationality')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.nationality')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="current_institution" class="div-lable">Current School/College</label>
                            <div class="common_input mb_15">
                                <input type="text" placeholder="Current School/College" class="form-control <?= session('errors.current_institution') ? 'is-invalid' : '' ?>" 
                                    name="current_institution" id="current_institution" oninput="validateLettersOnly(this)" 
                                    value="<?= old('current_institution', $data['current_institution'] ?? '') ?>">
                                <?php if (session('errors.current_institution')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.current_institution')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <!-- Parent Details -->
                        <div class="section-divider">Parent Details</div>                        
                        <div class="col-lg-6">
                            <label for="father_name" class="div-lable">Father's Name</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Father's Name" class="form-control <?= session('errors.father_name') ? 'is-invalid' : '' ?>" 
                                    name="father_name" id="father_name" oninput="validateLettersOnly(this)" 
                                    value="<?= old('father_name', $data['father_name'] ?? '') ?>" required>
                                <?php if (session('errors.father_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.father_name')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="mother_name" class="div-lable">Mother's Name</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Mother's Name" class="form-control <?= session('errors.mother_name') ? 'is-invalid' : '' ?>" 
                                    name="mother_name" id="mother_name" oninput="validateLettersOnly(this)"
                                    value="<?= old('mother_name', $data['mother_name'] ?? '') ?>" required>
                                <?php if (session('errors.mother_name')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mother_name')) ?>
                                    </div>
                                <?php endif; ?>      
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="mobile" class="div-lable">Mobile Number</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Mobile Number" class="form-control <?= session('errors.mobile') ? 'is-invalid' : '' ?>" 
                                    name="mobile" id="mobile" oninput="validateDigitsOnlyWithMax(this, 10)"
                                    value="<?= old('mobile', $data['mobile'] ?? '') ?>" required>
                                <?php if (session('errors.mobile')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.mobile')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="email" class="div-lable">Email Address</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="email" placeholder="Email Address" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                    name="email" id="email" 
                                    value="<?= old('email', $data['email'] ?? '') ?>" required>
                                <?php if (session('errors.email')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.email')) ?>
                                    </div>
                                <?php endif; ?>      
                            </div>
                        </div>
                        
                        <!-- Address Details -->
                        <div class="section-divider">Address Details</div>
                        
                        <div class="col-lg-12">
                            <label for="address" class="div-lable">Address</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <textarea placeholder="Address" class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>" 
                                    name="address" id="address" rows="2" required><?= old('address', $data['address'] ?? '') ?></textarea>
                                <?php if (session('errors.address')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.address')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="country_id" class="div-lable">Country</label>
                            <div class="common_input mb_15 select-required-asterisk1">
                                <select name="country_id" class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk <?= session('errors.country_id') ? 'is-invalid' : '' ?>" id="country_id" required>
                                    <option value="">Select Country</option>
                                    <?php
                                    $selectedCountries = $data['country_id']?? '105';                                    
                                    foreach ($content_data['data']['countries'] as $key => $value) {
                                        $selected = ($key == $selectedCountries) ? 'selected' : '';
                                        echo "<option value='{$key}' {$selected}>{$value}</option>";
                                    } ?>
                                </select>
                                <?php if (session('errors.country_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.country_id')) ?>
                                    </div>
                                <?php endif; ?>   
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="state_id" class="div-lable">State</label>
                            <div class="common_input mb_15 select-required-asterisk1">
                                    <select name="state_id" class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk <?= session('errors.state_id') ? 'is-invalid' : '' ?>" id="state_id" required>
                                        <option value="">Select State</option>
                                        <?php
                                        $selectedState = $data['state_id']?? NULL;
                                        //$school['state_id']?? auth()->user()->permanent_state;
                                        foreach ($content_data['data']['states'] as $key => $value) {
                                            $selected = ($key == $selectedState) ? 'selected' : '';
                                            echo "<option value='{$key}' {$selected}>{$value}</option>";
                                        } ?>
                                    </select>
                                <?php if (session('errors.state_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.state_id')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="city_id" class="div-lable">City</label>
                            <div class="common_input mb_15 select-required-asterisk1">
                                <select name="city_id" id="city_id" class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk <?= session('errors.city_id') ? 'is-invalid' : '' ?>" required> 
                                    <option value="">Select City</option>
                                </select>
                                <?php if (session('errors.city_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.city_id')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="address_pincode" class="div-lable">Pincode</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Pincode" class="form-control <?= session('errors.address_pincode') ? 'is-invalid' : '' ?>" 
                                    name="address_pincode" id="address_pincode" oninput="validateDigitsOnlyWithMax(this, 6)" 
                                    value="<?= old('address_pincode', $data['address_pincode'] ?? '') ?>" required>
                                <?php if (session('errors.address_pincode')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.address_pincode')) ?>
                                    </div>
                                <?php endif; ?>    
                            </div>
                        </div>
                        
                        <!-- Course Details -->
                        <div class="section-divider">Course Details</div> 
                        <div class="col-lg-6">
                            <label for="course_applying" class="div-lable">Class</label>
                            <div class="common_input mb_15 select-required-asterisk">                                
                                    <select class="nice_Select2 nice_Select_line wide <?= session('errors.course_applying') ? 'is-invalid' : '' ?>" name="course_applying" id="course_applying" required>
                                    <option value="">Select Class</option>
                                    <!-- Fixed options -->
                                    <option value="Pre-Nursery" <?= set_select('course_applying', 'Pre-Nursery', isset($data['course_applying']) && $data['course_applying'] == 'Pre-Nursery') ?>>Pre-Nursery</option>
                                    <option value="Nursery" <?= set_select('course_applying', 'Nursery', isset($data['course_applying']) && $data['course_applying'] == 'Nursery') ?>>Nursery</option>
                                    <option value="LKG" <?= set_select('course_applying', 'LKG', isset($data['course_applying']) && $data['course_applying'] == 'LKG') ?>>LKG</option>
                                    <option value="UKG" <?= set_select('course_applying', 'UKG', isset($data['course_applying']) && $data['course_applying'] == 'UKG') ?>>UKG</option>
                                    <!-- Numbers 1 to 20 -->
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= set_select('course_applying', $i, isset($data['course_applying']) && $data['course_applying'] == $i) ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php if (session('errors.course_applying')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.course_applying')) ?>
                                    </div>
                                <?php endif; ?> 
    
                            </div>
                        </div>                                           
                        <div class="col-lg-6">
                            <label for="academic_year" class="div-lable">Academic Year</label>
                            <div class="common_input mb_15 select-required-asterisk11">
                                <select name="academic_year" class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk <?= session('errors.academic_year') ? 'is-invalid' : '' ?>" id="academic_year" required>
                                    <option value="">Select Academic Year</option>
                                    <?php
                                    $selectedFinancial = $data['financial_year']?? FINANCIAL_YEAR;                                    
                                    foreach ($content_data['data']['financial_year'] as $key => $value) {
                                        $selected = ($value == $selectedFinancial) ? 'selected' : '';
                                        echo "<option value='{$value}' {$selected}>{$value}</option>";
                                    } ?>
                                </select>  
                                <?php if (session('errors.academic_year')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.academic_year')) ?>
                                    </div>
                                <?php endif; ?>                               
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="preferred_campus" class="div-lable">Preferred Campus</label>
                            <div class="common_input mb_15">
                                <input type="text" placeholder="Preferred Campus" class="form-control <?= session('errors.preferred_campus') ? 'is-invalid' : '' ?>" 
                                    name="preferred_campus" id="preferred_campus" 
                                    value="<?= old('preferred_campus', $data['preferred_campus'] ?? '') ?>">
                                <?php if (session('errors.preferred_campus')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.preferred_campus')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="heard_from" class="div-lable">How did you hear about us?</label>
                            <div class="common_input mb_15 select-required-asterisk">
                                <select class="nice_Select2 nice_Select_line wide <?= session('errors.heard_from') ? 'is-invalid' : '' ?>" name="heard_from" id="heard_from" required>
                                    <option value="">How did you hear about us?</option>
                                    <option value="Website" <?= set_select('heard_from', 'Website', isset($data['heard_from']) && $data['heard_from'] == 'Website') ?>>Website</option>
                                    <option value="Social Media" <?= set_select('heard_from', 'Social Media', isset($data['heard_from']) && $data['heard_from'] == 'Social Media') ?>>Social Media</option>
                                    <option value="Newspaper" <?= set_select('heard_from', 'Newspaper', isset($data['heard_from']) && $data['heard_from'] == 'Newspaper') ?>>Newspaper</option>
                                    <option value="Friend/Family" <?= set_select('heard_from', 'Friend/Family', isset($data['heard_from']) && $data['heard_from'] == 'Friend/Family') ?>>Friend/Family</option>
                                    <option value="Other" <?= set_select('heard_from', 'Other', isset($data['heard_from']) && $data['heard_from'] == 'Other') ?>>Other</option>
                                </select>
                                <?php if (session('errors.heard_from')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.heard_from')) ?>
                                    </div>
                                <?php endif; ?> 
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="section-divider">Additional Information</div>                        
                        <div class="col-lg-6">
                            <label for="special_requirements" class="div-lable">Special Requirements</label>
                            <div class="common_input mb_15">
                                <textarea placeholder="Special Requirements" class="form-control <?= session('errors.special_requirements') ? 'is-invalid' : '' ?>" 
                                    name="special_requirements" id="special_requirements" rows="3"><?= old('special_requirements', $data['special_requirements'] ?? '') ?></textarea>
                                <?php if (session('errors.special_requirements')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.special_requirements')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="questions" class="div-lable">Questions/Queries</label>
                            <div class="common_input mb_15">
                                <textarea placeholder="Questions/Queries" class="form-control <?= session('errors.questions') ? 'is-invalid' : '' ?>" 
                                    name="questions" id="questions" rows="3"><?= old('questions', $data['questions'] ?? '') ?></textarea>
                                <?php if (session('errors.questions')): ?>
                                    <div class="invalid-feedback">
                                        <?= esc(session('errors.questions')) ?>
                                    </div>
                                <?php endif; ?>     
                            </div>
                        </div>
                        
                        <!-- Status Fields (for edit mode) -->
                        <?php if ($isEdit): ?>
                            <div class="col-lg-6">
                                <div class="common_input mb_15">
                                    <select class="nice_Select2 nice_Select_line wide <?= session('errors.status') ? 'is-invalid' : '' ?>" name="status" id="status">
                                        <option value="0" <?= set_select('status', '0', isset($data['status']) && $data['status'] == 0) ?>>Active</option>
                                        <option value="1" <?= set_select('status', '1', isset($data['status']) && $data['status'] == 1) ?>>Inactive</option>
                                    </select>
                                    <?php if (session('errors.status')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session('errors.status')) ?>
                                        </div>
                                    <?php endif; ?>      
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="common_input mb_15">
                                    <textarea placeholder="Status Note" class="form-control <?= session('errors.status_note') ? 'is-invalid' : '' ?>" 
                                        name="status_note" id="status_note" rows="1"><?= old('status_note', $data['status_note'] ?? '') ?></textarea>
                                        <?php if (session('errors.status_note')): ?>
                                            <div class="invalid-feedback">
                                                <?= esc(session('errors.status_note')) ?>
                                            </div>
                                        <?php endif; ?>    
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="col-12">
                            <div class="create_report_btn mt_30">
                                <?php if (has_permission('enquiry/', PERMISSION_CREATE) || has_permission('enquiry/', PERMISSION_EDIT)): ?>
                                <button type="submit" class="btn_1 d-block text-center"><?= $isEdit ? 'Update' : 'Submit' ?> Enquiry</button>
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
document.addEventListener('DOMContentLoaded', function() {           
        const isEditMode = <?= $isEdit ? 'true' : 'false' ?>; // PHP to JS
        const validFromInput = document.getElementById('valid_from');

        // if (!isEditMode) {
        //     validFromInput.min = new Date().toISOString().split('T')[0];
        // }
        
        const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;
        const stateSelect = document.getElementById('state_id');        
        const citySelect = document.getElementById('city_id');
        const selectedCityId = "<?= $data['city_id'] ?? '' ?>";

        if (stateSelect.value && selectedCityId) {
            loadCities(stateSelect.value, citySelect, selectedCityId);
        }
        stateSelect.onchange = function() {            
            const stateId = this.value; 
            loadCities(stateId, citySelect);
        };                 
        
        async function loadCities(stateId, citySelect, selectedCityId = null) {
            if (!citySelect) {
                console.error("City select element not found!");
                return;
            }
            // Get reference to the custom dropdown elements
            const customDropdown = citySelect.nextElementSibling;
            const customList = customDropdown?.querySelector('.list');            
            // Set loading state for both elements
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Loading cities...</option>';

            if (customDropdown) {
                customDropdown.querySelector('.current').textContent = 'Loading cities...';
                if (customList) customList.innerHTML = '<li class="option disabled">Loading cities...</li>';
            }
            try {
                const response = await fetch('<?= site_url("/get-cities-by-state") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ state_id: stateId })
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);                
                const data = await response.json();
               // console.log("Received cities data:", data);

                // Clear existing options in both select and custom dropdown
                citySelect.innerHTML = '';
                if (customList) customList.innerHTML = '';

                // Add default option to both elements
                const defaultOption = new Option('Select City', '');
                defaultOption.selected = true;
                defaultOption.disabled = true;
                citySelect.add(defaultOption);

                if (customList) {
                    const defaultLi = document.createElement('li');
                    defaultLi.className = 'option disabled selected';
                    defaultLi.dataset.value = '';
                    defaultLi.textContent = 'Select City';
                    customList.appendChild(defaultLi);
                }
                // Add cities to both elements
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(city => {                        
                        // Add to native select
                        const option = new Option(city.name, city.id);                        
                        if (selectedCityId && city.id == selectedCityId) {
                            option.selected = true;
                        }
                        citySelect.add(option);

                        // Add to custom dropdown
                        if (customList) {
                            const li = document.createElement('li');
                            li.className = 'option';
                            li.dataset.value = city.id;
                            li.textContent = city.name;
                            
                            if (selectedCityId && city.id == selectedCityId) {
                                li.classList.add('selected');
                                if (customDropdown) {
                                    customDropdown.querySelector('.current').textContent = city.name;
                                }
                            }                            
                            customList.appendChild(li);
                        }
                    });
                } else {                   
                    citySelect.add(new Option('No cities found', ''));
                    if (customList) {
                        const li = document.createElement('li');
                        li.className = 'option disabled';
                        li.textContent = 'No cities found';
                        customList.appendChild(li);
                    }
                }

            } catch (error) {
                console.error('Error loading cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
                if (customList) {
                    customList.innerHTML = '<li class="option disabled">Error loading cities</li>';
                }
            } finally {
                citySelect.disabled = false;                
                // Trigger change event if needed
                const event = new Event('change');
                citySelect.dispatchEvent(event);
                
                // If you're using a custom dropdown library, you might need to refresh it here
                if (typeof $.fn.niceSelect === 'function') {
                    $(citySelect).niceSelect('update');
                }
            }
        }         
}); 
</script>