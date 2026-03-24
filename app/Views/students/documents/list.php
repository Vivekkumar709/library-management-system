<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Documents - <?= esc($content_data['data']['student']['first_name'] . ' ' . $content_data['data']['student']['last_name']) ?></h3>
                        <p class="m-0 text-muted">Admission No: <?= esc($content_data['data']['student']['admission_no']) ?> | Class: <?= esc($content_data['data']['student']['class_name']) ?> | Section: <?= esc($content_data['data']['student']['section_name']) ?></p>
                    </div>
                    
                    <!-- Display messages -->
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= esc(session('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= esc(session('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="white_card_body">
                <!-- Upload Document Form -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Upload New Document</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?= site_url('students/documents/upload/' . $content_data['data']['student']['id']) ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="document_type" required>
                                                <option value="">Select Document Type</option>
                                                <?php foreach ($content_data['data']['documentTypes'] as $key => $value): ?>
                                                    <option value="<?= $key ?>"><?= esc($value) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="text" class="form-control mb_15" name="document_name" placeholder="Document Name" required>
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="file" class="form-control mb_15" name="document_file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required>
                                            <small class="text-muted">Allowed formats: JPG, PNG, PDF, DOC, DOCX (Max: 5MB)</small>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="submit" class="btn btn-primary btn-block">Upload</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents List -->
                <!-- <div class="row">
                    <div class="col-12"> -->
                        <div class="QA_section">
                        <div class="QA_table mb_30">
                            <?php if (!empty($content_data['data']['documents'])): ?>                                
                                    <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                    <!-- <table class="table table-striped"> -->
                                        <thead>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Document Name</th>
                                                <th>File Name</th>
                                                <th>Upload Date</th>
                                                <th>Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($content_data['data']['documents'] as $document): ?>
                                                <tr>
                                                    <td><?= esc($content_data['data']['documentTypes'][$document['document_type']] ?? $document['document_type']) ?></td>
                                                    <td><?= esc($document['document_name']) ?></td>
                                                    <td>
                                                        <small class="text-muted"><?= esc(basename($document['document_path'])) ?></small>
                                                    </td>
                                                    <td><?= date('d M Y, h:i A', strtotime($document['created_at'])) ?></td>
                                                    <td>
                                                        <a href="#" class="status_btn status-<?= $document['status'] ? 'active' : 'inactive' ?>" 
                                                           data-id="<?= esc($document['id']) ?>" data-tbl="student_documents"  
                                                           data-status="<?= $document['status'] ?>">
                                                            <?= $document['status'] ? 'Active' : 'Inactive' ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="<?= site_url('students/documents/view/' . $document['id']) ?>" target="_blank">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                                <a class="dropdown-item" href="<?= site_url('students/documents/download/' . $document['id']) ?>">
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <form action="<?= site_url('students/documents/delete/' . $document['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                                                    <?= csrf_field() ?>
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>                                
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h5>No Documents Found</h5>
                                    <p class="text-muted">Upload documents using the form above.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <!-- </div> -->
                </div> 
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Status change functionality
    $('.status_btn').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var table = $(this).data('tbl');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus == 0 ? 1 : 0;
        var button = $(this);
        
        if (confirm('Are you sure you want to change the status?')) {
            $.ajax({
                url: '<?= site_url('students/documents/update-status/') ?>' + id,
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        button.data('status', newStatus);
                        if (newStatus == 0) {
                            button.removeClass('status-inactive').addClass('status-active').text('Active');
                        } else {
                            button.removeClass('status-active').addClass('status-inactive').text('Inactive');
                        }
                    } else {
                        alert('Error updating status: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error updating status. Please try again.');
                }
            });
        }
    });
});
</script>