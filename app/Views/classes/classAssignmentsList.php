<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<!-- Main content -->
<style>
.modal-backdrop, .modal-backdrop.show, .modal-backdrop.fade.show {
    background-color: red !important;
}
</style>
<section class="content">
<div class="main_content_iner1 ">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_body">
                        <div class="QA_section">
                            
                            <div class="white_box_tittle list_header">
                                <h4> </h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                        <?php if (has_permission('/class-assignments', PERMISSION_CREATE)): ?>
                                        <a href="<?= base_url('addTeacher') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div> 

                            <!-- <?php if (session()->has('success')): ?>
                                <div class="alert alert-success"><?= esc(session('success')) ?></div>
                            <?php endif ?> -->
                            <?php if (session()->has('success')): ?>
                                <div class="alert alert-success"><?= session()->get('success') ?></div>
                            <?php endif; ?>

                            <?php if (session()->has('error')): ?>
                                <div class="alert alert-danger"><?= session()->get('error') ?></div>
                            <?php endif; ?>

                            <div class="QA_table mb_30">                                   
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.N</th>
                                                <th scope="col">Image</th>
                                                <th scope="col">Name</th>                                                
                                                <th scope="col">Mobile</th>
                                                <!-- <th scope="col">User Type</th> -->
                                                <th scope="col">School</th>
                                                <th scope="col">Designation</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['data'])): ?>
                                            <?php $counter = 1;                                                
                                                foreach ($content_data['data']['data'] as $data): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td>
                                                    <?php if (!empty($data['profile_image'])): ?>
                                                        <!-- <img src="<?= base_url($data['profile_image']) ?>" alt="Employee Image" width="30" height="30" style="object-fit:cover; border-radius:50%;"> -->
                                                        <a href="<?= base_url('/' . $data['profile_image']) ?>" target="_blank" title="<?= esc($data['profile_image']) ?>">
                                                            <img src="<?= base_url('/' . $data['profile_image']) ?>"  
                                                    class="img-thumbnail" style="max-height: 50px;" alt="<?= esc($data['profile_image']) ?>"/></a>
                                                    <?php else: ?>
                                                        <img src="<?= base_url('/uploads/profile_images/default_user.png') ?>" alt="No Image" width="30" height="30" style="object-fit:cover; border-radius:50%;">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($data['first_name'] ?? '') ?> <?= esc($data['last_name'] ?? '') ?>
                                                <small class="d-block text-muted"><?= esc($data['username'] ?? '') ?></small></td>                                                
                                                <td><?= esc($data['mobile'] ?? '') ?><small class="d-block text-muted"><?= esc($data['emailId'] ?? '') ?></small></td>
                                                <!-- <td><?= esc($data['usertype'] ?? '') ?></td> -->
                                                <td><?= esc($data['school_name'] ?? '') ?></td>
                                                <td><?= esc($data['designation'] ?? '') ?>
                                                    <small class="d-block text-muted">                                                        
                                                        <?= esc($data['user_type'] ?? '') ?>
                                                    </small>
                                                </td>                                                
                                                <td><?php if (has_permission('/class-assignments', PERMISSION_EDIT)): ?>
                                                    <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>"
                                                       data-id="<?= esc($data['id']) ?>" data-tbl="users"
                                                       data-status="<?= $data['status'] ?>" data-track-updates="yes">
                                                        <?= $data['status'] ? 'Active' : 'Inactive' ?>
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="no-export">
                                                    <!-- Action buttons as before -->
                                                    <div class="btn-group mb-3">
                                                        <button type="button" class="btn btn-primary btn-light dropdown-toggle btn-font" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                        </button>
                                                        <div class="dropdown-menu compact-dropdown">                                                            
                                                        <?php if (has_permission('/class-assignments', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="teacher/edit/<?= esc($data['id']) ?>"> <i class="fas fa-edit"></i> Edit</a><?php endif; ?>   
                                                            <?php if (has_permission('/class-assignments', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="javascript:void(0);" onclick="openResetPasswordModal(<?= $data['id'] ?>, '<?= esc($data['username']) ?>')"> <i class="ti-solid ti-key"></i> Reset Password</a><?php endif; ?>   
                                                            <!-- <a class="dropdown-item btn-font" href="employee/assign_role_to_user/<?= esc($data['id']) ?>"> <i class="ti-book"></i> Assign Role</a> -->
                                                            <?php if (has_permission('/class-assignments', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="/user_menu_access/<?= $data['id']?>" ><i class="fab fa-gg"></i> Assign Access</a><?php endif; ?>   
                                                            <?php if (has_permission('/class-assignments', PERMISSION_DELETE)): ?>
                                                                <form action="<?= site_url('teacher/delete/' . $data['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this Teacher?')">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="dropdown-item btn-font"><i class="fas fa-trash"></i> Delete</button>
                                                                </form>
                                                            <?php endif; ?>    
                                                            <!-- <a class="dropdown-item btn-font" href="employee/delete/<?= esc($data['id']) ?>"> <i class="fas fa-delete"></i> Delete</a> -->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="10" class="text-center">No Records Found!!!</td>
                                            </tr>
                                        <?php endif; ?>                                          
                                        </tbody>
                                    </table>
                                </div>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password [Username: <span id="modalUsernameDisplay" class="text-primary"></span>]</h5>                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                
            </div>
            
            <form id="resetPasswordForm" autocomplete="off" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="common_input required-asterisk">
                            <input type="password" class="form-control" id="newPassword" name="new_password" required minlength="8">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="common_input required-asterisk">
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required minlength="8">
                        </div>
                    </div>
                    <input type="hidden" id="userId" name="user_id" value="">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- <button type="submit" class="btn btn-primary btn_1">Reset Password</button> -->
                    <input type="submit" class="btn_popup d-block text-center" value="Reset Password" fdprocessedid="a38ti">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResetPasswordModal(userId, username) {
    document.getElementById('userId').value = userId;
    document.getElementById('resetPasswordForm').reset();
    document.getElementById('modalUsernameDisplay').textContent = username;
    
    // Initialize modal properly
    var modalElement = document.getElementById('resetPasswordModal');
    var modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static', // Stops clicks on backdrop
        keyboard: false      // Disables ESC key closing
    });
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const resetPasswordModal = document.getElementById('resetPasswordModal');
    
    if (resetPasswordModal) {
        // Clear validation states when modal is shown
        resetPasswordModal.addEventListener('show.bs.modal', function() {
            const form = document.getElementById('resetPasswordForm');
            const inputs = form.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
                input.value = ''; // Clear input values
            });
            
            // Reset any error messages
            const errorMessages = form.querySelectorAll('.invalid-feedback');
            errorMessages.forEach(msg => msg.style.display = 'none');
        });

        // Handle form submission
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        if (resetPasswordForm) {
            resetPasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear previous validation states
                const inputs = this.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid', 'is-valid');
                });
                
                // Get form values
                const newPassword = document.getElementById('newPassword').value.trim();
                const confirmPassword = document.getElementById('confirmPassword').value.trim();
                const userId = document.getElementById('userId').value;
                let isValid = true;
                
                // Validate new password
                if (!newPassword) {
                    showFieldError('newPassword', 'Password is required');
                    isValid = false;
                } else if (newPassword.length < 8) {
                    showFieldError('newPassword', 'Password must be at least 8 characters');
                    isValid = false;
                } else {
                    document.getElementById('newPassword').classList.add('is-valid');
                }
                
                // Validate confirm password
                if (!confirmPassword) {
                    showFieldError('confirmPassword', 'Please confirm your password');
                    isValid = false;
                } else if (confirmPassword !== newPassword) {
                    showFieldError('confirmPassword', 'Passwords do not match');
                    isValid = false;
                } else {
                    document.getElementById('confirmPassword').classList.add('is-valid');
                }
                
                if (!isValid) {
                    // Focus on first invalid field
                    const firstInvalid = this.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                    return;
                }
                
                // Get CSRF token
                const csrfToken = this.querySelector('input[name="<?= csrf_token() ?>"]').value;
                
                // Submit form via AJAX
                fetch('<?= site_url('employee/reset-password') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        new_password: newPassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('Password reset successfully!');
                        
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(resetPasswordModal);
                        if (modal) {
                            modal.hide();
                        }
                        
                        // Reset form (already handled by show.bs.modal event)
                    } else {
                        alert('Error: ' + (data.message || 'Failed to reset password'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while resetting password');
                });
            });
        }
    }
});
</script>





