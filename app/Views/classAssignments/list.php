<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<!-- Main content -->
<section class="content">
<div class="main_content_iner1 ">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30">

                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="white_box_tittle list_header"> 
                                <h4>Class Assignments</h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                        <a href="<?= base_url('class-assignments/add/') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i> Add Assignment</a>                                        
                                    </div>
                                </div>                                                             
                            </div>
                            <?php if (session()->has('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= esc(session('success')) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?> 
                            <div class="QA_table mb_30">                                   
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.N</th>
                                            <th>Title</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th>Teacher</th>
                                            <th>Due Date</th>
                                            <th>Submissions</th>
                                            <th>Status</th>
                                            <th scope="col" class="no-export">Action</th>                                               
                                        </tr>
                                    </thead>
                                    <tbody>                                            
                                        <?php if (!empty($content_data['data']['assignments'])): ?>
                                        <?php $counter = 1;                                                
                                            foreach ($content_data['data']['assignments'] as $data): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>                                                                                              
                                            <td><?= esc($data['title']) ?></td>
                                            <td><?= esc($data['class_name']) ?></td>
                                            <td><?= esc($data['section_number']) ?></td>
                                            <td><?= esc($data['subject_name']) ?></td>
                                            <td><?= esc($data['teacher_name']) ?></td>
                                            <td><?= date('M j, Y', strtotime($data['due_date'])) ?></td>
                                            <td><?= $data['submission_count'] ?> submitted</td>
                                            <td>
                                                <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>" 
                                                data-id="<?= esc($data['id']) ?>" data-tbl="class_assignments"  
                                                data-status="<?= $data['status']?>">
                                                    <?= $data['status'] ? 'Active' : 'Inactive' ?>
                                                </a>
                                            </td>
                                            <td class="no-export">
                                                <div class="btn-group mb-3">
                                                    <button type="button" class="btn btn-primary btn-light dropdown-toggle btn-font" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                    </button>
                                                    <div class="dropdown-menu compact-dropdown">                                                            
                                                        <a class="dropdown-item btn-font" href="<?= site_url('class-assignments/view/' . $data['id']) ?>"> <i class="fas fa-eye"></i> View</a>
                                                        <a class="dropdown-item btn-font" href="<?= site_url('class-assignments/add/' . $data['id']) ?>"> <i class="fas fa-edit"></i> Edit</a>
                                                        <?php if (!empty($data['attachment_path'])): ?>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('class-assignments/download/' . $data['id']) ?>"> <i class="fas fa-download"></i> Download</a>
                                                        <?php endif; ?>
                                                        <form action="<?= site_url('class-assignments/delete/' . $data['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="dropdown-item btn-font"><i class="fas fa-trash"></i> Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>                                               
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No assignments found.</td>
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