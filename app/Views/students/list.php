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
                                <h4>Student Management</h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                        <a href="<?= base_url('students/add/') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i> Add Student</a>                                        
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
                                                <th scope="col">Photo</th>
                                                <th>Admission No</th>
                                                <th>Roll No</th>
                                                <th>Student Name</th>
                                                <th>Class</th>
                                                <!-- <th>Section</th> -->
                                                <!-- <th>Gender</th> -->
                                                <th>Contact</th>
                                                <!-- <th>Financial Year</th> -->
                                                <th>Status</th>
                                                <th scope="col" class="no-export">Action</th>                                               
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['students'])): ?>
                                            <?php $counter = 1;                                                
                                                foreach ($content_data['data']['students'] as $student): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td>
                                                    <?php if (!empty($student['profile_image'])): ?>                                                        
                                                        <a href="<?= base_url('/' . $student['profile_image']) ?>" target="_blank" title="<?= esc($student['profile_image']) ?>">
                                                            <img src="<?= base_url('/' . $student['profile_image']) ?>"  
                                                    class="img-thumbnail" style="max-height: 50px;" alt="<?= esc($student['profile_image']) ?>"/></a>
                                                    <?php else: ?>
                                                        <img src="<?= base_url('/uploads/profile_images/default_user.png') ?>" alt="No Image" width="30" height="30" style="object-fit:cover; border-radius:50%;">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= esc($student['admission_no']) ?>
                                                    <br><small><strong>Financial Year:</strong> <?= esc($student['financial_year']) ?></small>
                                                </td>
                                                <td>
                                                    <?= esc($student['roll_no']) ?>
                                                    <br><small><strong>Section:</strong> <?= esc($student['section_name']) ?></small>
                                                </td>
                                                <td>
                                                    <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
                                                    <br><small><strong>Gender:</strong> <?= esc($student['gender']) ?></small>
                                                </td>
                                                <td>
                                                    <?= esc($student['class_name']) ?>                                                   
                                                </td>
                                                <!-- <td>
                                                    
                                                </td> -->
                                                <!-- <td>
                                                    <?= esc($student['gender']) ?>
                                                </td> -->
                                                <td>
                                                    <?= !empty($student['mobile_no']) ? esc($student['mobile_no']) : (!empty($student['father_mobile']) ? esc($student['father_mobile']) : '-') ?>
                                                </td>
                                                <!-- <td>
                                                    
                                                </td> -->
                                                <!-- users, data-primary-key="id" data-relations='{"students": "user_id"}' -->
                                                <td> 
                                                    <a href="#" class="status_btn status-<?= $student['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($student['id']) ?>" data-tbl="students,users"
                                                    data-primary-key="id" data-relations='{"users": {"foreign_key": "id", "reference_table": "students", "reference_column": "user_id"}}'   
                                                    data-status="<?= $student['status']?>">
                                                        <?= $student['status'] ? 'Active' : 'Inactive' ?>
                                                    </a>
                                                </td>
                                                <td class="no-export">
                                                    <div class="btn-group mb-3">
                                                        <button type="button" class="btn btn-primary btn-light dropdown-toggle btn-font" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                        </button>
                                                        <div class="dropdown-menu compact-dropdown">  

                                                            <a class="dropdown-item btn-font" href="<?= site_url('students/view/' . $student['id']) ?>"> <i class="fas fa-eye text-primary"></i> View</a>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('students/add/' . $student['id']) ?>"> <i class="fas fa-edit text-primary"></i> Edit</a>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('students/documents/' . $student['id']) ?>"> <i class="fas fa-file text-primary"></i> Documents</a>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('students/academic-history/' . $student['id']) ?>"> <i class="fas fa-history text-primary"></i> Academic History</a>
                                                            
                                                            <a class="dropdown-item btn-font" href="<?= site_url('fee/list/' . $student['id']) ?>">
                                                                <i class="fas fa-rupee-sign text-success"></i> Fees
                                                            </a>

                                                            <a class="dropdown-item btn-font" href="<?= site_url('attendance/report/' . $student['id']) ?>">
                                                                <i class="fas fa-calendar-check text-primary"></i> Attendance
                                                            </a>

                                                            <a class="dropdown-item btn-font" href="<?= site_url('result/report/' . $student['id']) ?>">
                                                                <i class="fas fa-chart-bar text-primary"></i> Result
                                                            </a>
                                                            <!--  -->
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('certificate/generate/' . $student['id'] . '/bonafide') ?>">
                                                                <i class="fas fa-certificate text-primary"></i> Bonafide Certificate
                                                            </a>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('certificate/generate/' . $student['id'] . '/tc') ?>">
                                                                <i class="fas fa-file-export text-primary"></i> Transfer Certificate
                                                            </a>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('certificate/generate/' . $student['id'] . '/character') ?>">
                                                                <i class="fas fa-id-card text-primary"></i> Character Certificate
                                                            </a>
                                                            <a class="dropdown-item btn-font" href="<?= site_url('academics/subjects') ?>">
                                                                    <i class="fas fa-book-open text-primary"></i> Manage Academics
                                                            </a>
                                                            <!--  -->
                                                            <div class="dropdown-divider"></div>
                                                                <form action="<?= site_url('students/delete/' . $student['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                                                    <?= csrf_field() ?>
                                                                    <button type="submit" class="dropdown-item btn-font text-danger"><i class="fas fa-trash"></i> Delete</button>
                                                                </form>
                                                            </div>

                                                            <!-- -->
                                                                
                                                            </div> 
                                                                
                                                                
                                                </td>                                               
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" class="text-center">No Data found.</td>
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

<!-- JavaScript for DataTable initialization -->
<script>
$(document).ready(function() {
    // Initialize DataTable with responsive feature
    // $('#table-<?=$content_data['data']['distinctiveID']; ?>').DataTable({
    //     responsive: true,
    //     dom: 'Bfrtip',
    //     buttons: [
    //         {
    //             extend: 'excel',
    //             text: 'Excel',
    //             className: 'btn btn-primary btn-sm',
    //             exportOptions: {
    //                 columns: ':not(.no-export)'
    //             }
    //         },
    //         {
    //             extend: 'pdf',
    //             text: 'PDF',
    //             className: 'btn btn-primary btn-sm',
    //             exportOptions: {
    //                 columns: ':not(.no-export)'
    //             }
    //         },
    //         {
    //             extend: 'print',
    //             text: 'Print',
    //             className: 'btn btn-primary btn-sm',
    //             exportOptions: {
    //                 columns: ':not(.no-export)'
    //             }
    //         }
    //     ],
    //     "pageLength": 25,
    //     "order": [[0, "asc"]],
    //     "language": {
    //         "search": "Search:",
    //         "lengthMenu": "Show _MENU_ entries",
    //         "info": "Showing _START_ to _END_ of _TOTAL_ entries",
    //         "infoEmpty": "Showing 0 to 0 of 0 entries",
    //         "infoFiltered": "(filtered from _MAX_ total entries)",
    //         "paginate": {
    //             "first": "First",
    //             "last": "Last",
    //             "next": "Next",
    //             "previous": "Previous"
    //         }
    //     }
    // });
});
</script>
