<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1>Create New User</h1>
    
    <?= view('Auth/register') ?>
    
    <script>
    // Modify the registration form for admin panel
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        // Add user_type field
        const userTypeField = `
            <div class="mb-3">
                <label for="user_type" class="form-label">User Type</label>
                <select name="user_type" id="user_type" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="user" selected>Regular User</option>
                </select>
            </div>
        `;
        
        // Insert before submit button
        const submitButton = form.querySelector('[type="submit"]');
        submitButton.insertAdjacentHTML('beforebegin', userTypeField);
        
        // Change form action to admin route
        form.action = '<?= route_to('admin.users.store') ?>';
    });
    </script>
</div>
<?= $this->endSection() ?>