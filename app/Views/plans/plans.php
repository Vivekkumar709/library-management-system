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
                            <!-- <div class="white_box_tittle list_header">
                                <div class="box_right d-flex lms_block"> 
                                    <h4>Filters</h4>                                   
                                    <div class="add_button ms-2">
                                        <a href="<?= base_url('planAdd') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
                                    </div>
                                </div>                                
                            </div> -->
                            <div class="white_box_tittle list_header">
                                <h4> </h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                        <a href="<?= base_url('planAdd') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
                                    </div>
                                </div>
                            </div> 
                            <div class="QA_table mb_30">                                   
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.N</th>                                                
                                                <th scope="col">Plan</th> 
                                                <th scope="col">Type</th> 
                                                <th scope="col">Tenure</th> 
                                                <th scope="col">Services</th>                                                                                                                                                                                                                                           
                                                <th scope="col">Status</th> 
                                                <th scope="col" class="no-export">Action</th>                                               
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['data'])): ?>
                                            <?php $counter = 1;                                                
                                                foreach ($content_data['data']['data'] as $data): 
                                                   
                                                    $plan_services =  htmlspecialchars($data['plan_services'], ENT_COMPAT,'ISO-8859-1', true);
                                                ?>
                                            <tr>
                                                <td><?= $counter++ ?>                                                    
                                                </td>
                                                <td>
                                                    <?= esc($data['name']) ?>                                                    
                                                </td> 
                                                <td><?= esc($data['plan_types']) ?></td> 
                                                <td><?= esc($data['tenure']) ?><small class="d-block text-success fw-bold"><?= esc($data['price'] ?? '') ?></small></td> 
                                                
                                                <!-- <td><?= str_replace(['&lt;br&gt;', '&lt;br/&gt;'], '<br>', esc($data['plan_services'])) ?></td> -->
                                                <td><?= str_replace('<br>', '<br>✅ ', '✅ ' . str_replace(['&lt;br&gt;', '&lt;br/&gt;'], '<br>', esc($data['plan_services']))) ?></td>
                                                                                                                                                                                                                                                                                                                                  
                                                <td>
                                                    <a href="#" class="status_btn status-<?= $data['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($data['id']) ?>" data-tbl="plan_packages"  
                                                    data-status="<?= $data['status']?>">
                                                        <?= $data['status'] ? 'Active' : 'Inactive' ?>
                                                    </a>
                                                </td> 
                                                <td class="no-export">                                                    
                                                    <a href="plans/edit/<?= esc($data['id']) ?>" class="action_btn mr_10"> <i class="far fa-edit"></i></a>                                                
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





