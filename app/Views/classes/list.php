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
                                <h4>Class Management</h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                    <?php if (has_permission('/classes', PERMISSION_CREATE)): ?>
                                        <a href="<?= base_url('classes/addSection') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (session()->has('success')): ?>
                                <div class="alert alert-success"><?= esc(session('success')) ?></div>
                            <?php endif ?>
                            
                            <div class="QA_table mb_30">                                
                                <table class="table table-striped lms_table_active3" id="menuTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.N</th>                                                
                                            <th scope="col">Class</th>
                                            <!-- <th scope="col">Financial Year</th> -->
                                            <th scope="col">Section</th>
                                            <th scope="col">Section For</th>
                                            <th scope="col">Section Type</th>                                            
                                            <th scope="col">Capacity/Strength</th>
                                            <th scope="col">Status</th>
                                            <th scope="col"  class="no-export">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php //echo "<pre>"; print_r($content_data['data']['classes_data']); echo "</pre>";  die;
                                         foreach ($content_data['data']['classes_data'] as $classes): ?>                                            
                                            <tr class="main-menu" data-id="<?= $classes['id'] ?>">
                                                <td><?= $counter ?></td>
                                                <td>
                                                    <?php if(!empty($classes['sections'])): ?>
                                                        <span class="toggle-submenu" data-target="<?= $classes['id'] ?>"></span>
                                                    <?php else: ?>
                                                        <span style="display:inline-block; width:15px;"></span>
                                                    <?php endif; ?>
                                                    <?php if(!empty($classes['icon_svg'])): ?>
                                                        <img src="<?= base_url($classes['icon_svg']) ?>" class="menu-icon">
                                                    <?php endif; ?>
                                                    <a href="menus/edit/<?= esc($classes['id']) ?>" class="question_content"><?= esc($classes['class_name']) ?></a>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><span class="badge bg-primary"><?= esc($classes['total_max_capacity']) ?> / <?= esc($classes['total_current_strength']) ?></span></td>
                                                <td><?= $classes['status'] ? 'Inactive' : 'Active' ?>
                                                    <!-- <a href="#" class="status_btn1 status_change_btn status-<?= $classes['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($classes['id']) ?>" data-tbl="auth_menus"  
                                                    data-status="<?= $classes['status']?>">
                                                        <?= $classes['status'] ? 'Active' : 'Inactive' ?>
                                                    </a> -->
                                                </td>
                                                <td class="no-export">                                                    
                                                    <div class="btn-group mb-3">
                                                        
                                                        <button type="button" class="btn btn-primary btn-light dropdown-toggle btn-font" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                        </button>
                                                        
                                                        <div class="dropdown-menu compact-dropdown">                                                            
                                                            <?php if (has_permission('/class-teachers/', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="classes/teachers/<?= esc($classes['id']) ?>"> <i class="fas fa-users" style="color:#1357b0; font-size:17px;"></i> Class Teachers</a><?php endif; ?>                                                            
                                                            <?php if (has_permission('/class-schedules/', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="classes/classSchedule/<?= esc($classes['id']) ?>"><i class="fas fa-clock" style="color:#f39c12; font-size:17px;"></i> Class Schedule</a><?php endif; ?>
                                                            <?php if (has_permission('/class-teachers/', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="classes/classAttendance/<?= $classes['id']?>" ><i class="fas fa-calendar-alt" style="color:#007bff; font-size:17px;"></i> Attendance</a><?php endif; ?>
                                                            <?php if (has_permission('/class-teachers/', PERMISSION_EDIT)): ?><a class="dropdown-item btn-font" href="classes/classAssignments/<?= $classes['id']?>" ><i class="fas fa-tasks" style="color:#27ae60; font-size:17px;"></i> Assignments</a><?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <?php if (!empty($classes['sections'])): ?>
                                                <?php foreach ($classes['sections'] as $section): ?>
                                                    <tr class="sub-menu" data-parent="<?= $classes['id'] ?>">
                                                        <td></td>                                                        
                                                        <td><?= esc($section['financial_year_name']) ?></td>
                                                        <td><span class="badge bg-secondary">Section: <?= esc($section['section_name']) ?></span></td>  
                                                        <td><span class="badge bg-info"><?= esc($section['section_for_name']) ?></span></td>
                                                        <td><?= esc($section['section_type_name']) ?> <?= isset($section['special_section_name']) ? esc('('.$section['special_section_name'].')') : ''; ?></td>
                                                        <td><?= esc($section['max_capacity']) ?> / <?= esc($section['current_strength']) ?></td>                                                                                                              
                                                        <td><?php if (has_permission('/classes', PERMISSION_EDIT)): ?>
                                                            <a href="#" class="status_btn main_menu_id_<?= esc($classes['id']) ?> status-<?= $section['status'] ? 'active' : 'inactive' ?>" 
                                                            data-id="<?= esc($section['id']) ?>" data-tbl="sections"  
                                                            data-status="<?= $section['status']?>">
                                                                <?= $section['status'] ? 'Active' : 'Inactive' ?>
                                                            </a>
                                                            <?php endif; ?>
                                                        </td>                                                        
                                                        <td class="no-export">
                                                        <?php if (has_permission('/classes', PERMISSION_EDIT)): ?><a href="classes/edit/<?= esc($section['id']) ?>" class="action_btn mr_10"> <i class="far fa-edit"></i></a><?php endif; ?>
                                                            <!-- <a href="menus/delete/<?= esc($section['id']) ?>" class="action_btn mr_10" onclick="return confirm('Are you sure you want to delete this submenu?')"> <i class="far fa-trash-alt"></i></a>                                                        -->
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php $counter++; ?>
                                        <?php endforeach; ?>
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
<script>
$(document).ready(function() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#menuTable')) {
        $('#menuTable').DataTable().destroy();
    }    
    // Initialize toggle functionality first
    $('.toggle-submenu').on('click', function() {
        var targetId = $(this).data('target');
        $(this).toggleClass('collapsed');
        $('tr.sub-menu[data-parent="' + targetId + '"]').toggle();
    });
    // Initialize DataTable
    var table = $('#menuTable').DataTable({
        dom: '<"top"<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>>rt<"bottom"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"p>>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-info',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.pageOrientation = 'landscape';
                    doc.styles.tableHeader.fontSize = 9;
                }
            }
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        searching: true,
        ordering: false,
        info: true,
        autoWidth: false,
        initComplete: function(settings, json) {
            // Use the DataTable API properly
            var api = this.api();
            
            // Hide all submenus initially
            $('tr.sub-menu').hide();
            $('.dt-buttons').addClass('mb-3');
            
            // Add total records count
            $('.dataTables_length').append('<div class="total-records ml-2">Total: ' + api.rows().count() + '</div>');
        },        
        drawCallback: function(settings) {
            // Use the DataTable API properly
            var api = this.api();
            
            // Rebind toggle events after each draw
            $('.toggle-submenu').off('click').on('click', function() {
                var targetId = $(this).data('target');
                $(this).toggleClass('collapsed');
                $('tr.sub-menu[data-parent="' + targetId + '"]').toggle();                
                // Update total records count
                $('.total-records').text('Total: ' + api.rows().count());
            });
        }  
    });    
  
});

</script>