<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
$school = $content_data['data']['school'] ?? null;
$isEdit = isset($school);
$label = $isEdit?'Edit':'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?=$label;?> School </h3>
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
                        <form class="" id="studentForm" autocomplete="off" method="post" enctype="multipart/form-data" action="<?= site_url('schools/save') ?>">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $school['id'] ?>">
                        <?php endif; ?>
                        <!--  -->
                </div>
            </div>
            
            <div class="white_card_body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="common_input mb_15 required-asterisk1 select-required-asterisk" >
                            <input type="text" placeholder="School Name" class="form-control" name="school_name" id="school_name" value="<?= old('school_name', $school['school_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6">                        
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="school_type_id" name="school_type_id" data-required="true" required>
                                <?php foreach ($content_data['data']['schoolTypes'] as $id => $name): ?>
                                <option value="<?= $id ?>" 
                                    <?= set_select('school_type_id', $id, 
                                        isset($school['school_type_id']) && $school['school_type_id'] == $id) ?>>
                                    <?= esc($name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>                        
                    </div>
                    <div class="col-lg-6">
                                <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="school_medium_id" name="school_medium_id" data-required="true" required>
                                    <?php 
                                         foreach ($content_data['data']['schoolMediums'] as $id => $name): ?>
                                            <option value="<?= $id ?>" 
                                                <?= set_select('school_medium_id', $id, 
                                                    isset($school['school_medium_id']) && $school['school_medium_id'] == $id) ?>>
                                                <?= esc($name) ?>
                                          </option>
                                        <?php endforeach; ?>
                                </select>                        
                    </div>
                    <div class="col-lg-6">                        
                                <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="school_affiliation_id" name="school_affiliation_id" data-required="true" required>
                                        <?php foreach ($content_data['data']['affiliationBoards'] as $id => $name): ?>
                                            <option value="<?= $id ?>"
                                            <?= set_select('school_affiliation_id', $id, 
                                                    isset($school['school_affiliation_id']) && $school['school_affiliation_id'] == $id) ?>>
                                            <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                </select>                        
                    </div>
                    <div class="col-lg-6">                        
                                <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="school_education_level_id" name="school_education_level_id" data-required="true" required>
                                        <?php foreach ($content_data['data']['educationLevels'] as $id => $name): ?>
                                            <option value="<?= $id ?>"
                                            <?= set_select('school_education_level_id', $id, 
                                                    isset($school['school_education_level_id']) && $school['school_education_level_id'] == $id) ?>>
                                            <?= esc($name) ?></option>
                                        <?php endforeach;?>
                                </select>                        
                    </div>
                    <div class="col-lg-6">                        
                                <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="school_tradition_id" name="school_tradition_id" data-required="true" required>
                                        <?php foreach ($content_data['data']['schoolTraditions'] as $id => $name): ?>
                                            <option value="<?= $id ?>"
                                            <?= set_select('school_tradition_id', $id, 
                                                    isset($school['school_tradition_id']) && $school['school_tradition_id'] == $id) ?>>
                                            <?= esc($name) ?></option>
                                        <?php endforeach;?>
                                </select>                        
                    </div>                    
                    <div class="col-lg-6"> 
                        <div class="common_input mb_15 select-required-asterisk">                       
                            <input type="text" class="form-control" placeholder="Approximate Staff" name="total_no_staff" id="total_no_staff" value="<?= old('total_no_staff', $school['total_no_staff'] ?? '') ?>" oninput="validateDigitsOnly(this)" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" class="form-control" placeholder="Owner's Name" name="owner_name" id="owner_name" value="<?= old('owner_name', $school['owner_name'] ?? '') ?>" oninput="validateLettersOnly(this)" required />
                        </div>
                    </div>                    
                   
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" class="form-control" placeholder="Owner's Mobile" name="owner_mobile" id="owner_mobile" value="<?= old('owner_mobile', $school['owner_mobile'] ?? '') ?>" oninput="validateDigitsOnlyWithMax(this, 10)" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" class="form-control" placeholder="Registration No." name="school_registration_no" id="school_registration_no" value="<?= old('school_registration_no', $school['school_registration_no'] ?? '') ?>" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="school_branch" name="school_branch" required> 
                            <option value="">Select Branch (if any)</option>                                                  
                            <option value="No" <?= set_select('school_branch', 'No', isset($school['school_branch']) && $school['school_branch'] == 'No') ?>>No</option>
                            <option value="Yes" <?= set_select('school_branch', 'Yes', isset($school['school_branch']) && $school['school_branch'] == 'Yes') ?>>Yes</option>                                                   
                        </select>
                    </div>

                    <div class="section-divider">Contact Person Details</div>

                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <input type="text" class="form-control" placeholder="Contact Person Name" name="contact_person_name" id="contact_person_name" value="<?= old('contact_person_name', $school['contact_person_name'] ?? '') ?>" oninput="validateLettersOnly(this)" required />                    
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <input type="text" class="form-control" placeholder="Contact Person Mobile" name="contact_person_mobile" id="contact_person_mobile" value="<?= old('contact_person_mobile', $school['contact_person_mobile'] ?? '') ?>" oninput="validateDigitsOnlyWithMax(this, 10)" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <input type="email" class="form-control" placeholder="Contact Person Email" name="contact_person_email" id="contact_person_email" value="<?= old('contact_person_email', $school['contact_person_email'] ?? '') ?>" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 ">
                            <input type="text" class="form-control" placeholder="Work Details" name="contact_person_work_details" id="contact_person_work_details" value="<?= old('contact_person_work_details', $school['contact_person_work_details'] ?? '') ?>" />
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="common_input mb_15 select-required-asterisk">
                            <textarea class="form-control" placeholder="Contact Person Address" id="contact_person_address" name="contact_person_address" rows="2" required><?= old('contact_person_address', $school['contact_person_address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="section-divider">School Address Details</div>                    
                    <div class="col-lg-12">
                        <div class="common_input mb_15 select-required-asterisk">
                            <textarea class="form-control" placeholder="School Address" id="school_address" name="school_address" rows="2" required><?= old('school_address', $school['school_address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">                        
                        <select name="state_id" class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="state_id" required>
                            <option value="">Select State</option>
                            <?php
                            $selectedState = $school['state_id']?? NULL;
                            //$school['state_id']?? auth()->user()->permanent_state;
                            foreach ($content_data['data']['states'] as $key => $value) {
                                $selected = ($key == $selectedState) ? 'selected' : '';
                                echo "<option value='{$key}' {$selected}>{$value}</option>";
                            } ?>
                        </select>                       
                    </div>
                    <div class="col-lg-6">
                        <select name="city_id" id="city_id" class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" required> 
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" class="form-control" placeholder="Pincode" name="pincode" id="pincode" oninput="validateDigitsOnlyWithMax(this, 6)" value="<?= old('pincode', $school['pincode'] ?? '') ?>" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                        <input type="text" class="form-control" placeholder="Landmark" name="landmark" id="landmark" value="<?= old('landmark', $school['landmark'] ?? '') ?>" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                        <input type="text" class="form-control" placeholder="School Website" name="school_website" id="school_website" value="<?= old('school_website', $school['school_website'] ?? '') ?>" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                        <input type="text" class="form-control" placeholder="School Email" name="school_email_id" id="school_email_id" value="<?= old('school_email_id', $school['school_email_id'] ?? '') ?>" />
                        </div>
                    </div>
                    <div class="section-divider">Plan Details</div>                    
                    <div class="col-lg-6">                        
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="plan_id" name="plan_id" data-required="true" required>
                                    <option value="">Select Plan</option>
                                    <?php $planDetails = get_records('plan_packages pp', [
                                        'joins' => [                                            
                                            [
                                                'table' => 'plan_tenure ptr',
                                                'condition' => 'pp.tenure_id = ptr.id',
                                                'type' => 'left'
                                            ],
                                            [
                                                'table' => 'plan_type pt',
                                                'condition' => 'pp.plan_type_id = pt.id',
                                                'type' => 'left'
                                            ]
                                        ],                                        
                                        'select' => [
                                            'pp.id',
                                            'pp.name',
                                            'pp.price',                                            
                                            'ptr.name as tenure',
                                            'pt.name as plan_types'                                                         
                                        ],   
                                        'filters'=>['pp.status' => 0],         
                                        'groupBy' => 'pp.id, ptr.name, pt.name' 
                                    ]);                                                                     
                                        if (!empty($planDetails)): ?>
                                            <?php foreach ($planDetails as $mName)://$mid =>  ?>
                                            <option value="<?= $mName['id'] ?>" data-price="<?= (int)$mName['price'] ?>" <?= (isset($school['plan_id']) && $school['plan_id'] == $mName['id']) ? 'selected':'';?>>
                                                <?php echo ($mName['name'].' / '.$mName['tenure'].' / &#8377;'.(int)$mName['price']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                    <?php endif; ?>                                   
                            </select>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="common_input mb_15 select-required-asterisk">
                            <input type="text" class="form-control" placeholder="Plan Amount" name="plan_payable_amount" id="plan_payable_amount" value="<?= old('plan_payable_amount', $school['plan_payable_amount'] ?? '') ?>" oninput="validateDigitsOnly(this)" readonly required />                    
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="payment_mode_id" name="payment_mode_id" required>
                                    <option value="">Select Payment Mode</option>
                                    <?php 
                                    foreach ($content_data['data']['paymentModes'] as $id => $name): ?>
                                        <option value="<?= $id ?>"
                                        <?= set_select('payment_mode_id', $id, 
                                                isset($school['payment_mode_id']) && $school['payment_mode_id'] == $id) ?>>
                                        <?= esc($name) ?></option>
                                    <?php endforeach; ?>       
                        </select> 
                    </div>
                    <div class="col-lg-6">
                        <?php if($isEdit == 'Edit'){
                                                    $valid_from = $school['valid_from'];
                                                    $valid_from_formatted = date('d-m-Y', strtotime($valid_from));
                         }?>
                        <div class="common_input mb_15 select-required-asterisk">
                        <input type="text" name="valid_from" placeholder="Valid From Date" id="valid_from" class="form-control datepicker-here" <?= !$isEdit ? 'min="' . date('Y-m-d') . '"' : '' ?>  data-language="en" data-date-format="dd-mm-yyyy" value="<?= old('valid_from', $valid_from_formatted ?? '') ?>" required />
                        </div>
                    </div>                    
                    <div class="section-divider">Other Details</div>

                    <div class="col-lg-6">
                        <!-- <div class="common_input mb_15"> -->
                            <label for="fileInput" id="fileLabel">School Logo</label>
                            <input type="file" name="school_logo" id="school_logo" class="form-control" accept="image/*">
                        <!-- </div> -->
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15">
                            <img id="imagePreview" class="img-fluid mt-2 d-none" style="max-height: 200px;">
                            <?php if ($isEdit && !empty($school['school_logo'])): ?>
                                <div class="mt-2">
                                    <img src="<?= base_url('uploads/schools/' . $school['school_logo']) ?>" 
                                        class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            <?php endif; ?> 
                        </div>
                    </div>                    
                    <div class="col-12">
                        <div class="create_report_btn mt_30">                                      
                        <?php if (has_permission('/schools', PERMISSION_CREATE) || has_permission('/schools', PERMISSION_EDIT)): ?>
                            <input type="submit" class="btn_1 d-block text-center" value="<?= $label; ?>">
                        <?php endif; ?>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
        setupImagePreview('school_logo', 'imagePreview');       
        const isEditMode = <?= $isEdit ? 'true' : 'false' ?>; // PHP to JS
        const validFromInput = document.getElementById('valid_from');
        
        if (!isEditMode) {
            validFromInput.min = new Date().toISOString().split('T')[0];
        }
        
        const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;
        const stateSelect = document.getElementById('state_id');        
        const citySelect = document.getElementById('city_id');
        const selectedCityId = "<?= $school['city_id'] ?? '' ?>";

        if (stateSelect.value && selectedCityId) {
            loadCities(stateSelect.value, citySelect, selectedCityId);
        }
        stateSelect.onchange = function() {            
            const stateId = this.value; 
            loadCities(stateId, citySelect);
        };
                 
        // Load cities if state is already selected
        // if (stateSelect.value) {
        //     loadCities(stateSelect.value, citySelect);
        // } 
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
        //===============
        $('#plan_id').on('change', function() { 
            const price = $(this).find(':selected').data('price');
            if(price == 100000){
                $('#plan_payable_amount').removeAttr('readonly');
                $('#plan_payable_amount').val(price);
            }else{
                $('#plan_payable_amount').attr('readonly', true);
                $('#plan_payable_amount').val(price);
            }
            
        }).trigger('change');
        //========  
}); 
</script>




