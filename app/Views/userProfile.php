<style>
    .profile-img-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .profile-img-upload {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid white;
    }
    .profile-img-upload:hover {
        background: #0b5ed7;
        transform: scale(1.1);
    }
    .profile-img-upload input {
        display: none;
    }
    .profile-img-upload i {
        font-size: 1.2rem;
    }
    
    /* Notification styles */
    #notificationArea {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        width: 300px;
    }
    #notificationArea .alert {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
</style>
<?php $permanent_address =  auth()->user()->permanent_address;    
      $present_address =  auth()->user()->present_address;       
      $groupUseFor = get_user_group_data(auth()->id(), 'use_for_name'); 
?>
    <div id="notificationArea"></div>    
    <div class="container py-2">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="profile-img-container mb-3">
                            <img src="<?= esc(base_url(trim(auth()->user()->profile_image)))?>" alt="Profile Image" class="profile-img" id="profileImage">
                            <label class="profile-img-upload" for="imageUpload">                            
                                <!-- <i class="bi bi-camera"></i> -->
                                <i class="fas fa-camera"></i>                                
                                <input type="file" id="imageUpload" accept="image/*">
                            </label>
                        </div>                        
                        <h5 class="my-3" id="profileName"><?= esc(auth()->user()->full_name) ?></h5>
                        <p class="text-muted mb-1" id="profileDesignation"><?= esc($LoggedInUserDesignation) ?></p>
                        <p class="text-muted mb-4" id="profileLocation"><?= esc($LoggedInUserPermanentCityName.', '.$LoggedInUserPermanentStateCode) ?></p>
                        <p class="text-muted mb-1" id="profileDesignation"><small><?= esc($LoggedInUserGroup); ?> (<?= esc($groupUseFor); ?>)</small></p>
                        <div class="d-flex justify-content-center mb-2">
                            <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
                            <button type="button" class="btn btn-outline-primary ms-1" id="cancelEditBtn" style="display:none;">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">First Name</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewFirstName"><?= esc(auth()->user()->first_name) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                <p class="text-muted mb-0" id="editFirstName"><?= esc(auth()->user()->first_name) ?></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Last Name</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewLastName"><?= esc(auth()->user()->last_name) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <p class="text-muted mb-0" id="editLastName"><?= esc(auth()->user()->last_name) ?></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewEmail"><?= esc(auth()->user()->email) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <input type="email" class="form-control" id="editEmail" value="<?= esc(auth()->user()->email) ?>">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Mobile</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewMobile"><?= esc(auth()->user()->mobile) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <input type="tel" class="form-control" id="editMobile" value="<?= esc(auth()->user()->mobile) ?>">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Designation</p>
                            </div>                            
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewDesignation"><?= esc($LoggedInUserDesignation) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <p class="text-muted mb-0" id="editDesignation"><?= esc($LoggedInUserDesignation) ?></p>
                                </div>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">About</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewAbout"><?= esc(auth()->user()->about) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <textarea class="form-control" id="editAbout" rows="3"><?= esc(auth()->user()->about) ?></textarea>
                                </div>
                            </div>
                        </div>                                    
                        <!-- Address -->
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Permanent Address</p>
                            </div>
                            <div class="col-sm-9"> 
                               <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewPermanentAddress"><?= esc($permanent_address);?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <textarea class="form-control" id="editPermanentAddressLine1" rows="1"><?= esc($LoggedInUserPermanentAddressLine1) ?></textarea>
                                </div> 
                                <br>                               
                                <div class="edit-mode" style="display:none;">
                                    <textarea class="form-control" id="editPermanentAddressLine2" rows="1"><?= esc($LoggedInUserPermanentAddressLine2) ?></textarea>
                                </div>
                            </div> 
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Landmark</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewLandmarkPermanent"><?= esc(auth()->user()->permanent_landmark) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                
                                <input type="text" class="form-control" id="editLandmarkPermanent" value="<?= esc(auth()->user()->permanent_landmark) ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">State</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    
                                    <p class="text-muted mb-0" id="viewStatePermanent"><?= esc($LoggedInUserPermanentStateName)?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                
                                <select name="editStatePermanent" class="form-control form-select" id="editStatePermanent" >
                                    <?php
                                    $options = get_dropdown('states', 'id', 'name', ['status' => 0]);
                                    $selectedState = auth()->user()->permanent_state;
                                    foreach ($options as $key => $value) {
                                        $selected = ($key == $selectedState) ? 'selected' : '';
                                        echo "<option value='{$key}' {$selected}>{$value}</option>";
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">City</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewCityPermanent"><?= esc($LoggedInUserPermanentCityName) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                
                                <select name="editCityPermanent" class="form-control form-select" id="editCityPermanent"> 
                                </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Pincode</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewPincodePermanent"><?= esc(auth()->user()->permanent_pincode) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                <input type="text" class="form-control" id="editPincodePermanent" name="editPincode" value="<?= esc(auth()->user()->permanent_pincode) ?>" pattern="^\d{6}$" maxlength="6" title="Please enter a valid 6-digit PIN code" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row align-items-center">
                            <div class="col-sm-3">
                                <p class="mb-0">Same as Permanent</p>
                            </div>
                            <div class="col-sm-9">
                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="copyAddressCheckbox" 
                                    <?= (auth()->user()->permanent_state == auth()->user()->present_state && 
                                    auth()->user()->permanent_city == auth()->user()->present_city && 
                                    auth()->user()->permanent_pincode == auth()->user()->present_pincode) ? 'checked' : '' ?>>
                                    
                                        <label class="form-check-label" for="copyAddressCheckbox">
                                            Use permanent address as present address
                                        </label>
                                </div> -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="copyAddressCheckbox">
                                    <label class="form-check-label" for="copyAddressCheckbox">
                                        Use permanent address as present address
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Present Address</p>
                            </div>
                            <div class="col-sm-9">                                
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewPresentAddress"><?= esc($present_address);?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">
                                    <textarea class="form-control" id="editPresentAddressLine1" rows="1"><?= esc($LoggedInUserPresentAddressLine1);?></textarea>
                                    <br>
                                    <textarea class="form-control" id="editPresentAddressLine2" rows="1"><?= esc($LoggedInUserPresentAddressLine2);?></textarea>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Landmark</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewLandmarkPresent"><?= esc(auth()->user()->present_landmark) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                
                                <input type="text" class="form-control" id="editLandmarkPresent" value="<?= esc(auth()->user()->present_landmark) ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">State</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewStatePresent"><?= esc($LoggedInUserPresentStateName)?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                
                                <select name="editStatePresent" class="form-control form-select" id="editStatePresent" >
                                    <?php
                                    $options = get_dropdown('states', 'id', 'name', ['status' => 0]);
                                    $selectedState = auth()->user()->present_state;                                    
                                    foreach ($options as $key => $value) {
                                        $selected = ($key == $selectedState) ? 'selected' : '';
                                        echo "<option value='{$key}' {$selected}>{$value}</option>";
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">City</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewCityPresent"><?= esc(get_value_by_id('cities', 'id', auth()->user()->present_city, 'city')) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                
                                <select name="editCityPresent" class="form-control form-select" id="editCityPresent">                                    
                                </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Pincode</p>
                            </div>
                            <div class="col-sm-9">
                                <div class="view-mode">
                                    <p class="text-muted mb-0" id="viewPincodePresent"><?= esc(auth()->user()->present_pincode) ?></p>
                                </div>
                                <div class="edit-mode" style="display:none;">                                    
                                <input type="text" class="form-control" id="editPincodePresent" name="editPincodePresent" value="<?= esc(auth()->user()->present_pincode) ?>" pattern="^\d{6}$" maxlength="6" title="Please enter a valid 6-digit PIN code" required>
                                </div>
                            </div>
                        </div>

                        <hr>                        
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="editProfileBtn">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>      
    <script>
        // Notification function
        function showNotification(type, message) {
            const notificationArea = document.getElementById('notificationArea');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            
            const notification = document.createElement('div');
            notification.className = `alert ${alertClass} alert-dismissible fade show`;
            notification.role = 'alert';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            notificationArea.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(notification);
                bsAlert.close();
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const editProfileBtn = document.getElementById('editProfileBtn');
            const saveProfileBtn = document.getElementById('saveProfileBtn');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const imageUpload = document.getElementById('imageUpload');            
            const copyAddressCheckbox = document.getElementById('copyAddressCheckbox');
            const profileImage = document.getElementById('profileImage');            
            const permanentFields = {
                addressLine1: document.getElementById('editPermanentAddressLine1'),
                addressLine2: document.getElementById('editPermanentAddressLine2'),
                landmark: document.getElementById('editLandmarkPermanent'),
                state: document.getElementById('editStatePermanent'),
                city: document.getElementById('editCityPermanent'),
                pincode: document.getElementById('editPincodePermanent')
            };            
            const presentFields = {
                addressLine1: document.getElementById('editPresentAddressLine1'),
                addressLine2: document.getElementById('editPresentAddressLine2'),
                landmark: document.getElementById('editLandmarkPresent'),
                state: document.getElementById('editStatePresent'),
                city: document.getElementById('editCityPresent'),
                pincode: document.getElementById('editPincodePresent')
            };

            // Function to load cities for a state
            async function loadCities(stateId, citySelect, selectedCityId = null) {
                if (!stateId) {
                    citySelect.innerHTML = '<option value="">Select State First</option>';
                    return;
                }
                citySelect.innerHTML = '<option value="">Loading cities...</option>';                
                try {
                    const response = await fetch('/get-cities-by-state', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ 
                            state_id: stateId,
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        })
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');                    
                    const data = await response.json();                    
                    citySelect.innerHTML = '<option value="">Select City</option>';                    
                    if(data && Array.isArray(data)) {
                        let cityFound = false;
                        
                        data.forEach(city => {
                            const option = new Option(city.name, city.id);
                            if (selectedCityId && city.id == selectedCityId) {
                                option.selected = true;
                                cityFound = true;
                            }
                            citySelect.add(option);
                        });                        
                        // Add current city if not found in results
                        if (selectedCityId && !cityFound) {
                            const option = new Option('Current City', selectedCityId);
                            option.selected = true;
                            citySelect.add(option);
                        }
                    }
                } catch (error) {
                    console.error('Error loading cities:', error);
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                }
            }


            // Function to copy permanent address to present address
            async function copyPermanentToPresent() {
                // Copy simple fields
                presentFields.addressLine1.value = permanentFields.addressLine1.value;
                presentFields.addressLine2.value = permanentFields.addressLine2.value;
                presentFields.landmark.value = permanentFields.landmark.value;
                presentFields.state.value = permanentFields.state.value;
                presentFields.pincode.value = permanentFields.pincode.value;

                // Make present fields read-only or disabled
                presentFields.addressLine1.readOnly = true;
                presentFields.addressLine2.readOnly = true;
                presentFields.landmark.readOnly = true;
                presentFields.state.disabled = true;
                presentFields.city.disabled = true;
                presentFields.pincode.readOnly = true;

                // Handle city selection - wait for cities to load
                if (permanentFields.city.value) {
                    // If permanent city is already selected, copy it directly
                    presentFields.city.value = permanentFields.city.value;
                } else {
                    // If not, trigger the city load and wait for it to complete
                    const stateId = permanentFields.state.value;
                    if (stateId) {
                        await loadCities(stateId, presentFields.city, permanentFields.city.value);
                    }
                }
            }

            // Function to enable editing of present fields
            function enablePresentFields() {
                presentFields.addressLine1.readOnly = false;
                presentFields.addressLine2.readOnly = false;
                presentFields.landmark.readOnly = false;
                presentFields.state.disabled = false;
                presentFields.city.disabled = false;
                presentFields.pincode.readOnly = false;
            }

            // On checkbox change
            copyAddressCheckbox.addEventListener('change', async function () {
                if (this.checked) {
                    await copyPermanentToPresent();
                } else {
                    enablePresentFields();
                }
            });

            // Sync if permanent fields change and checkbox is checked
            Object.values(permanentFields).forEach(field => {
                field.addEventListener('input', async function () {
                    if (copyAddressCheckbox.checked) {
                        await copyPermanentToPresent();
                    }
                });
            });  

            // Handle state changes for permanent address
            permanentFields.state.addEventListener('change', async function () {
                const stateId = this.value;
                if (stateId) {
                    await loadCities(stateId, permanentFields.city);
                    
                    // If checkbox is checked, also update present address
                    if (copyAddressCheckbox.checked) {
                        presentFields.state.value = stateId;
                        await loadCities(stateId, presentFields.city, permanentFields.city.value);
                    }
                }
            });

            // Handle state changes for present address
            presentFields.state.addEventListener('change', async function () {
                const stateId = this.value;
                if (stateId && !copyAddressCheckbox.checked) {
                    await loadCities(stateId, presentFields.city);
                }
            });

            // Toggle between view and edit modes
            function toggleEditMode(edit) {
                const viewModes = document.querySelectorAll('.view-mode');
                const editModes = document.querySelectorAll('.edit-mode');                
                if (edit) {
                    viewModes.forEach(el => el.style.display = 'none');
                    editModes.forEach(el => el.style.display = 'block');
                    editProfileBtn.style.display = 'none';
                    cancelEditBtn.style.display = 'inline-block';
                } else {
                    viewModes.forEach(el => el.style.display = 'block');
                    editModes.forEach(el => el.style.display = 'none');
                    editProfileBtn.style.display = 'inline-block';
                    cancelEditBtn.style.display = 'none';
                }
            }            
            // Event listeners
            editProfileBtn.addEventListener('click', () => toggleEditMode(true));
            cancelEditBtn.addEventListener('click', () => toggleEditMode(false));
            
            saveProfileBtn.addEventListener('click', function() {
                const val = (id) => document.getElementById(id)?.value ?? '';
                const formData = new FormData(); 
                var selectedStatePermanent = $('#editStatePermanent option:selected').text();
                var selectedCityPermanent = $('#editCityPermanent option:selected').text(); 
                var selectedStatePresent = $('#editStatePresent option:selected').text();
                var selectedCityPresent = $('#editCityPresent option:selected').text();                

                formData.append('email', val('editEmail'));
                formData.append('mobile', val('editMobile'));
                formData.append('about', val('editAbout'));
                formData.append('permanent_address1', val('editPermanentAddressLine1'));
                formData.append('permanent_address2', val('editPermanentAddressLine2'));
                formData.append('permanent_landmark', val('editLandmarkPermanent'));
                formData.append('permanent_state', val('editStatePermanent'));
                formData.append('permanent_city', val('editCityPermanent'));
                formData.append('permanent_pincode', val('editPincodePermanent'));
                formData.append('present_address1', val('editPresentAddressLine1'));
                formData.append('present_address2', val('editPresentAddressLine2'));
                formData.append('present_landmark', val('editLandmarkPresent'));
                formData.append('present_state', val('editStatePresent'));
                formData.append('present_city', val('editCityPresent'));
                formData.append('present_pincode', val('editPincodePresent'));                 
                // Add CSRF token for CodeIgniter
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');                
                if (imageUpload.files.length > 0) {
                    formData.append('profile_image', imageUpload.files[0]);
                }                
                // Show loading state
                saveProfileBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                saveProfileBtn.disabled = true;
                
                $.ajax({
                    url: '<?= site_url('profile/update') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Update view fields 
                            document.getElementById('viewEmail').textContent = val('editEmail');
                            document.getElementById('viewMobile').textContent = val('editMobile');                                                       
                            document.getElementById('viewAbout').textContent = val('editAbout');
                            document.getElementById('viewLandmarkPermanent').textContent = val('editLandmarkPermanent');
                            document.getElementById('viewLandmarkPresent').textContent = val('editLandmarkPresent');
                            document.getElementById('viewPermanentAddress').textContent = val('editPermanentAddressLine1') + (val('editPermanentAddressLine1') ? + val('editPermanentAddressLine1') : '');
                            document.getElementById('viewPresentAddress').textContent   = val('editPresentAddressLine1') + (val('editPresentAddressLine2') ?  + val('editPresentAddressLine2') : '');
                            document.getElementById('viewPincodePermanent').textContent = val('editPincodePermanent');
                            document.getElementById('viewPincodePresent').textContent = val('editPincodePresent');

                            document.getElementById('viewStatePermanent').textContent = selectedStatePermanent;
                            document.getElementById('viewCityPermanent').textContent = selectedCityPermanent;
                            document.getElementById('viewStatePresent').textContent = selectedStatePresent;
                            document.getElementById('viewCityPresent').textContent = selectedCityPresent;
                            
                            // Update profile image if changed
                            if (response.data.profile_image) {
                                profileImage.src = response.data.profile_image;
                            }                            
                            // Switch back to view mode
                            toggleEditMode(false);

                            // Show success message
                            showNotification('success', response.message || 'Profile updated successfully');
                        } else {
                            showNotification('error', response.message || 'Update failed');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMsg = 'Request failed';
                        try {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                errorMsg = xhr.responseText;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                        showNotification('error', errorMsg);
                        console.error('AJAX Error:', status, error, xhr.responseText);
                    },                    
                    complete: function() {
                        saveProfileBtn.innerHTML = 'Save Changes';
                        saveProfileBtn.disabled = false;
                    }
                });
            });
            
            // Handle image upload preview
            imageUpload.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        profileImage.src = event.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Initialize the form on page load
            async function initializeForm() {
        // Load permanent cities
        if (permanentFields.state.value) {
            await loadCities(
                permanentFields.state.value, 
                permanentFields.city, 
                '<?= auth()->user()->permanent_city ?>'
            );
        }
        
        // Handle present address
        if (copyAddressCheckbox.checked) {
            // Copy permanent to present if checkbox is checked
            await copyPermanentToPresent();
        } else {
            // Load present cities normally
            if (presentFields.state.value) {
                await loadCities(
                    presentFields.state.value, 
                    presentFields.city, 
                    '<?= auth()->user()->present_city ?>'
                );
            }
        }
    }

    // Enhanced copyPermanentToPresent function
    async function copyPermanentToPresent() {
                // Copy all fields
                presentFields.addressLine1.value = permanentFields.addressLine1.value;
                presentFields.addressLine2.value = permanentFields.addressLine2.value;
                presentFields.landmark.value = permanentFields.landmark.value;
                presentFields.state.value = permanentFields.state.value;
                presentFields.pincode.value = permanentFields.pincode.value;

                // Load cities for present address
                if (permanentFields.state.value) {
                    await loadCities(
                        permanentFields.state.value, 
                        presentFields.city, 
                        permanentFields.city.value
                    );
                }

                // Disable present address fields
                presentFields.addressLine1.readOnly = true;
                presentFields.addressLine2.readOnly = true;
                presentFields.landmark.readOnly = true;
                presentFields.state.disabled = true;
                presentFields.city.disabled = true;
                presentFields.pincode.readOnly = true;
            }

            // Call initialization
            initializeForm();
        });
    </script>