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
                            </div>
                            <div class="QA_table mb_30">                                   
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.N</th> 
                                                <th scope="col">Slot</th>                                               
                                                <!-- <th scope="col">School</th> -->
                                                <th scope="col">Shift</th>
                                                                                                                                               
                                                <th scope="col">Status</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['data'])): ?>
                                            <?php $counter = 1;                                                
                                                foreach ($content_data['data']['data'] as $data): ?>
                                            <tr>
                                                <td><?= $counter++ ?>                                                    
                                                </td>
                                                <td><?= esc($data['slot']) ?></td>
                                                <!-- <td><?= esc($data['school_name']) ?></td>  -->
                                                <td><?= esc($data['shift_name']) ?></td>                                                                                                                                                                                            
                                                <td>
                                                    <?php if (has_permission('/school-time-slots', PERMISSION_EDIT)): ?>
                                                    <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($data['id']) ?>" data-tbl="schools_time_slots"  
                                                    data-status="<?= $data['status']?>">
                                                        <?= $data['status'] ? 'Active' : 'Inactive' ?>
                                                    </a>
                                                    <?php endif; ?>
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
</section





