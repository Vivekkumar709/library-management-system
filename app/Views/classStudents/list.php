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
                                <h4>Class Students</h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                        <a href="<?= base_url('classStudents/add') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
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
                                            <th scope="col">User Group</th>
                                            <th scope="col">Used For</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" class="no-export">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($content_data['data']['data'])): ?>
                                            <?php $counter = 1; foreach ($content_data['data']['data'] as $data): ?>
                                                <tr>
                                                    <td><?= $counter++ ?></td>
                                                    <td><?= esc($data['name']) ?></td>
                                                    <td><?php 
                                                        if (!empty($data['use_for']) && $data['use_for']=='L') {
                                                            echo "Library";
                                                        } elseif (!empty($data['use_for']) && $data['use_for']=='S') {
                                                            echo "Software";
                                                        } else {
                                                            echo "School";
                                                        }
                                                    ?></td>
                                                    <td><?= esc($data['description']) ?></td>
                                                    <td>
                                                        <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>" data-id="<?= esc($data['id']) ?>" data-tbl="auth_groups" data-status="<?= $data['status']?>">
                                                            <?= $data['status'] ? 'Active' : 'Inactive' ?>
                                                        </a>
                                                    </td>
                                                    <td class="no-export">
                                                        <div class="btn-group mb-3">
                                                            <button type="button" class="btn btn-primary btn-light dropdown-toggle btn-font" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                                            <div class="dropdown-menu compact-dropdown">
                                                                <a class="dropdown-item btn-font" href="userGroups/edit/<?= esc($data['id']) ?>"> <i class="fas fa-edit"></i> Edit</a>
                                                                <a class="dropdown-item btn-font" href="/user_type_menu_access/<?= $data['id']?>" ><i class="fab fa-gg"></i> Assign Access</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No Records Found!!!</td>
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

<script>
$(document).ready(function() {
    var tableId = '#table-<?= $content_data['data']['distinctiveID']; ?>';
    if ($.fn.DataTable && $.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }
    $(tableId).DataTable({
        dom: '<"top"<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>>rt<"bottom"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"p>>>',
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-success', exportOptions: { columns: ':not(.no-export)' } },
            { extend: 'csvHtml5', text: '<i class="fas fa-file-csv"></i> CSV', className: 'btn btn-info', exportOptions: { columns: ':not(.no-export)' } },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-danger', exportOptions: { columns: ':not(.no-export)' }, customize: function(doc){ doc.defaultStyle.fontSize = 8; doc.pageOrientation = 'landscape'; doc.styles.tableHeader.fontSize = 9; } }
        ],
        paging: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        searching: true,
        ordering: false,
        info: true,
        autoWidth: false,
        initComplete: function(){
            $('.dt-buttons').addClass('mb-3');
        }
    });
});
</script>





