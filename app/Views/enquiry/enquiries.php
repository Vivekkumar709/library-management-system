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
                                <h4></h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">                                    
                                        <?php if (has_permission('enquiry/list', PERMISSION_CREATE)): ?>
                                            <a href="<?= base_url('enquiry') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
                                        <?php endif; ?>
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
                                                <th scope="col">Student Name</th>
                                                <th scope="col">Parent Name</th>
                                                <th scope="col">Contact</th>
                                                <th scope="col">Course</th>
                                                <th scope="col">Registered</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="no-export">Action</th>                                               
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['data'])): ?>
                                            <?php $counter = 1;                                                
                                                foreach ($content_data['data']['data'] as $data): ?>
                                            <tr>
                                                <td><?= $counter++ ?>                                                    
                                                </td>                                                                                              
                                                <td>
                                                    <?= esc($data['student_name']) ?><br>
                                                    <small class="text-muted">DOB: <?= date('d/m/Y', strtotime($data['date_of_birth'])) ?></small>
                                                </td>
                                                <td>
                                                    <?= esc($data['father_name']) ?><br>
                                                    <small class="text-muted"><?= esc($data['mother_name']) ?></small>
                                                </td>
                                                <td>
                                                    <?= esc($data['mobile']) ?><br>
                                                    <small class="text-muted"><?= esc($data['email']) ?></small>
                                                </td>
                                                <td>
                                                    <?= esc($data['course_applying']) ?><br>
                                                    <small class="text-muted"><?= esc($data['academic_year']) ?></small>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($data['created_at'])) ?></td>                                                                                            
                                                <td>
                                                <?php if (has_permission('/enquiry', PERMISSION_EDIT)): ?>
                                                    <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($data['id']) ?>" data-tbl="admission_enquiries"  
                                                    data-status="<?= $data['status']?>">
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
                                                            <?php if (has_permission('/enquiry', PERMISSION_EDIT)): ?>                                                           
                                                            <a class="dropdown-item btn-font" href="edit/<?= esc($data['id']) ?>" target="_blank"> <i class="fas fa-edit"></i> Edit</a>                                                            
                                                            <?php endif; ?>
                                                            <!-- <form action="<?= site_url('userGroup/delete/' . $data['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="dropdown-item btn-font"><i class="fas fa-trash"></i> Delete</button>
                                                            </form> -->
                                                        </div>
                                                    </div>
                                                </td>                                               
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No Record Found!</td>
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
</section





