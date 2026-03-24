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
                                <h4>Menu Management</h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                        <a href="<?= base_url('menus/create') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
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
                                            <th scope="col">Menu Name</th>
                                            <th scope="col">Menu For</th>
                                            <th scope="col">URL</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Status</th>                                            
                                            <th scope="col" class="no-export">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php foreach ($content_data['data']['data'] as $menu): ?>
                                            <!-- Main Menu Row -->
                                            <tr class="main-menu" data-id="<?= $menu['id'] ?>">
                                                <td><?= $counter ?></td>
                                                <td>
                                                    <?php if(!empty($menu['submenus'])): ?>
                                                        <span class="toggle-submenu" data-target="<?= $menu['id'] ?>"></span>
                                                    <?php else: ?>
                                                        <span style="display:inline-block; width:15px;"></span>
                                                    <?php endif; ?>
                                                    <?php if(!empty($menu['icon_svg'])): ?>
                                                        <img src="<?= base_url($menu['icon_svg']) ?>" class="menu-icon">
                                                    <?php endif; ?>
                                                    <a href="menus/edit/<?= esc($menu['id']) ?>" class="question_content"><?= esc($menu['name']) ?></a>
                                                </td>
                                                <!-- <td><?= !empty($menu['menu_for']) ? esc($menu['menu_for']) : '-' ?></td> -->
                                                <td><?= esc(['A'=>'Admin','S'=>'School','L'=>'Library','SL'=>'School & Library','M'=>'All'][$menu['menu_for']] ?? '-') ?></td>
                                                <td><?= !empty($menu['url']) ? esc($menu['url']) : '-' ?></td>
                                                <td><span class="badge bg-primary">Main</span></td>
                                                <td>
                                                    <a href="#" class="status_btn1 status_change_btn status-<?= $menu['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($menu['id']) ?>" data-tbl="auth_menus"  
                                                    data-status="<?= $menu['status']?>">
                                                        <?= $menu['status'] ? 'Active' : 'Inactive' ?>
                                                    </a>
                                                </td>                                                   
                                                <td class="no-export">
                                                    <a href="menus/edit/<?= esc($menu['id']) ?>" class="action_btn mr_10"> <i class="far fa-edit"></i></a>
                                                    <a href="menus/delete/<?= esc($menu['id']) ?>" class="action_btn mr_10" onclick="return confirm('Are you sure you want to delete this menu?')"> <i class="far fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                            <!-- Submenus - Placed immediately after their parent -->
                                            <?php if (!empty($menu['submenus'])): ?>
                                                <?php foreach ($menu['submenus'] as $submenu): ?>
                                                    <tr class="sub-menu" data-parent="<?= $menu['id'] ?>">
                                                        <td></td>
                                                        <td><?= esc($submenu['name']) ?></td>
                                                        <td></td>
                                                        <td><?= !empty($submenu['url']) ? esc($submenu['url']) : '-' ?></td>
                                                        <td><span class="badge bg-secondary">Sub</span></td>                                                        
                                                        <td>
                                                            <a href="#" class="status_btn main_menu_id_<?= esc($menu['id']) ?> status-<?= $submenu['status'] ? 'active' : 'inactive' ?>" 
                                                            data-id="<?= esc($submenu['id']) ?>" data-tbl="auth_menus"  
                                                            data-status="<?= $submenu['status']?>">
                                                                <?= $submenu['status'] ? 'Active' : 'Inactive' ?>
                                                            </a>
                                                        </td>                                                        
                                                        <td class="no-export">
                                                            <a href="menus/edit/<?= esc($submenu['id']) ?>" class="action_btn mr_10"> <i class="far fa-edit"></i></a>
                                                            <a href="menus/delete/<?= esc($submenu['id']) ?>" class="action_btn mr_10" onclick="return confirm('Are you sure you want to delete this submenu?')"> <i class="far fa-trash-alt"></i></a>                                                       
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
    
    $(document).on('click', '.status_change_btn', function(e) {
        // Configure toastr
            e.preventDefault();        
            var button = $(this);
            var id = button.data('id');
            var tbl = button.data('tbl');
            var currentStatus = button.data('status');
            var newStatus = currentStatus ? 0 : 1;   
            var trackUpdates = button.data('track-updates') || null;

            var isSubmenu = button.hasClass('status_btn'); // Check if it's a submenu button
            var mainMenuId = isSubmenu ? button.attr('class').match(/main_menu_id_(\d+)/)[1] : null;

            if (!confirm('Are you sure you want to change the status?')) {
                return; 
            }
            button.text('Updating...');

            $.ajax({
                url: '<?= site_url('menus/changeStatus') ?>',
                method: 'POST',
                data: {
                    id: id,
                    status: newStatus,
                    tbl: tbl,
                    track_updates: trackUpdates,                
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        button.toggleClass('status-active status-inactive');
                        button.data('status', newStatus);
                        button.text(newStatus ? 'Active' : 'Inactive');

                        // If this is a main menu button, update all its submenus
                        if (!isSubmenu) {
                            $('.main_menu_id_' + id + '.status_btn')
                                .toggleClass('status-active status-inactive')
                                .data('status', newStatus)
                                .text(newStatus ? 'Active' : 'Inactive');
                        } 
                        //toastr.success('Status updated successfully');
                        
                         // If you're using DataTables, you might need to redraw
                        if ($.fn.DataTable.isDataTable('#menuTable')) {
                            $('#menuTable').DataTable().draw(false); // false to maintain paging
                        }
                    } else {
                        alert(response.message || 'Failed to update status');
                        button.text(currentStatus ? 'Active' : 'Inactive');
                    }
                },
                error: function() {
                    alert('An error occurred while updating status');
                    button.text(currentStatus ? 'Active' : 'Inactive');
                }
            });
    });  
});

</script>