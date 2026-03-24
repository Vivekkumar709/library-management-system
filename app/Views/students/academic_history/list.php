<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Academic History - <?= esc($content_data['data']['student']['first_name'] . ' ' . $content_data['data']['student']['last_name']) ?></h3>
                        <p class="m-0 text-muted">Admission No: <?= esc($content_data['data']['student']['admission_no']) ?> | Current Class: <?= esc($content_data['data']['student']['class_name']) ?></p>
                    </div>
                    
                    <div class="box_right d-flex lms_block">                                    
                        <div class="add_button ms-2">
                            <a href="<?= site_url('students/academic-history/add/' . $content_data['data']['student']['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Academic Record
                            </a>                                        
                        </div>
                    </div>
                </div>
                
                <!-- Display messages -->
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <?= esc(session('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <?= esc(session('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="white_card_body">
                <?php if (!empty($content_data['data']['academicHistory'])): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Roll No</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Record Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($content_data['data']['academicHistory'] as $record): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($record['financial_year']) ?></strong>
                                        </td>
                                        <td><?= esc($record['class_name']) ?></td>
                                        <td><?= esc($record['section_name']) ?></td>
                                        <td><?= esc($record['roll_no'] ?: '-') ?></td>
                                        <td>
                                            <?php if ($record['percentage']): ?>
                                                <span class="badge bg-<?= $record['percentage'] >= 75 ? 'success' : ($record['percentage'] >= 50 ? 'warning' : 'danger') ?>">
                                                    <?= number_format($record['percentage'], 2) ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $record['status'] == 'Completed' ? 'success' : ($record['status'] == 'Promoted' ? 'info' : 'secondary') ?>">
                                                <?= esc($record['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($record['created_at'])) ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?= site_url('students/academic-history/edit/' . $record['id']) ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="<?= site_url('students/academic-history/delete/' . $record['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this academic record?')">
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
                    </div>
                    
                    <!-- Performance Summary -->
                    <?php
                    $performance = $content_data['data']['performance'];
                    if ($performance && $performance['total_records'] > 0):
                    ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Academic Performance Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-primary"><?= $performance['total_records'] ?></h4>
                                                <p class="mb-0 text-muted">Total Records</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-success"><?= number_format($performance['avg_percentage'], 2) ?>%</h4>
                                                <p class="mb-0 text-muted">Average Percentage</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-info"><?= number_format($performance['max_percentage'], 2) ?>%</h4>
                                                <p class="mb-0 text-muted">Highest Percentage</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-warning"><?= number_format($performance['min_percentage'], 2) ?>%</h4>
                                                <p class="mb-0 text-muted">Lowest Percentage</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h5>No Academic Records Found</h5>
                        <p class="text-muted">No academic history records found for this student.</p>
                        <a href="<?= site_url('students/academic-history/add/' . $content_data['data']['student']['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Academic Record
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>