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
                                        <!-- <a href="<?= base_url('userGroups/userGroupAdd') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a> -->
                                        <?php if (has_permission('/class-assignments', PERMISSION_CREATE)): ?><a href="<?= site_url('class-schedules/add/') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a><?php endif; ?>
                                        <?php if (has_permission('/class-assignments', PERMISSION_VIEW)): ?><a href="<?= site_url('class-schedules/timetable') ?>" class="btn btn-primary">View Timetable</a><?php endif; ?>
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
                                                <th>#</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Subject</th>
                                                <th>Teacher</th>
                                                <th>Day</th>
                                                <th>Time</th>
                                                <th>Room</th>
                                                <th>Financial Year</th>                                             
                                                <th scope="col">Status</th> 
                                                <th scope="col" class="no-export">Action</th>                                               
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['classSchedules'])): ?>
                                            <?php $counter = 1;
                                                foreach ($content_data['data']['classSchedules'] as $data):
                                                ?>
                                            <tr>
                                                <td><?= $counter++ ?>                                                    
                                                </td>
                                                <td><?= esc($data['class_name']) ?></td>
                                                <td>Section <?= esc($data['section_name']) ?></td>
                                                <td><?= esc($data['subject_name']) ?></td>
                                                <td><?= esc($data['teacher_name']) ?></td>
                                                <td>
                                                    <?php 
                                                    $days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];
                                                    echo $days[$data['day_of_week']] ?? 'N/A'; 
                                                    ?>
                                                </td>
                                                <td><?= date('h:i A', strtotime($data['start_time'])) . ' - ' . date('h:i A', strtotime($data['end_time'])) ?></td>
                                                <td><?= esc($data['room_number'] ?? 'N/A') ?></td>
                                                <td><?= esc($data['financial_year']) ?></td>                                                                                            
                                                <td><?php if (has_permission('/class-assignments', PERMISSION_EDIT)): ?>
                                                    <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($data['id']) ?>" data-tbl="class_schedules"  
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
                                                        <?php if (has_permission('/class-assignments', PERMISSION_EDIT)): ?><a href="<?= site_url('class-schedules/add/' . $data['id']) ?>" class="dropdown-item btn-font"> <i class="fas fa-edit"></i> Edit</a><?php endif; ?>
                                                        <?php if (has_permission('/class-assignments', PERMISSION_DELETE)): ?><a href="<?= site_url('class-schedules/delete/' . $data['id']) ?>" class="dropdown-item btn-font" onclick="return confirm('Are you sure you want to delete this schedule?')"> <i class="fas fa-trash"></i> Delete</a><?php endif; ?>
                                                                                    
                                                            <!-- <a class="dropdown-item btn-font" href="userGroups/edit/<?= esc($data['id']) ?>"> <i class="fas fa-edit"></i> Edit</a>
                                                            <a class="dropdown-item btn-font" href="/user_type_menu_access/<?= $data['id']?>" ><i class="fab fa-gg"></i> Assign Access</a> -->
                                                            <!-- <form action="<?= site_url('userGroup/delete/' . $data['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="dropdown-item btn-font"><i class="fas fa-trash"></i> Delete</button>
                                                            </form>                                                             -->
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" class="text-center">No Records Found!!!</td>
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





