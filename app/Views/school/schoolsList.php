<?php helper('permission'); ?>
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
                                <h4>Filters</h4>
                                <div class="box_right d-flex lms_block">                                    
                                    <div class="add_button ms-2">
                                    <?php if (has_permission('/schools', PERMISSION_CREATE)): ?>
                                        <a href="<?= base_url('schoolAdd') ?>" class="btn btn-primary"><i class="fas fa-plus"> </i></a>                                        
                                    <?php endif; ?>                                    
                                    </div>
                                </div>
                            </div>                            
                            <!-- Filters Row -->                            
                            <!-- Change form method to GET and add action -->
                            <form method="post" id="filterForm-<?=$content_data['data']['distinctiveID']; ?>" action="/schools" class="dt-toolbar">
                                <div class="row dt-toolbar-footer">
                                    <div class="col-md-3">
                                        <div class="form-group dt-filter">
                                            <label class="dt-filter-label">School Type</label>
                                            <select class="form-control dt-filter-input" id="type-filter" name="school_type">                                                
                                                <?php $schoolTypes = get_dropdown('m_school_types', 'id', 'name', ['status' => 0]);
                                                if (!empty($schoolTypes)): ?>
                                                    <?php foreach ($schoolTypes as $id => $name): ?>
                                                    <option value="<?= $id ?>" <?= (isset($content_data['data']['current_filters']['school_type']) && $content_data['data']['current_filters']['school_type'] == $id) ? 'selected' : '' ?>>
                                                        <?= esc($name) ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group dt-filter">
                                            <label class="dt-filter-label">School Medium</label>
                                            <select class="form-control dt-filter-input" id="medium-filter" name="school_medium">                                                
                                                <?php $schoolMediums = get_dropdown('m_school_mediums', 'id', 'name', ['status' => 0]);
                                                if (!empty($schoolMediums)): ?>
                                                    <?php foreach ($schoolMediums as $mid => $mName): ?>
                                                    <option value="<?= $mid ?>" <?= (isset($content_data['data']['current_filters']['school_medium']) && $content_data['data']['current_filters']['school_medium'] == $mid) ? 'selected' : '' ?>>
                                                        <?= esc($mName) ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group dt-filter">
                                            <label class="dt-filter-label">Status</label>
                                            <select class="form-control dt-filter-input" id="status-filter" name="status">
                                                <option value="">Select</option>
                                                <option value="0" <?= (isset($content_data['data']['current_filters']['status']) && $content_data['data']['current_filters']['status'] === '0') ? 'selected' : '' ?>>Active</option>
                                                <option value="1" <?= (isset($content_data['data']['current_filters']['status']) && $content_data['data']['current_filters']['status'] === '1') ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group dt-filter">  
                                            <label class="dt-filter-label1"></label>
                                            <div class="d-flex w-100" style="gap: 15px;">
                                                <div class="position-relative flex-grow-1">
                                                    <i class="fas fa-search dt-icon position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 4;"></i>
                                                    <input type="submit" class="btn btn-secondary curvic-button w-100 pl-5" name="search" value="Search" style="padding-top: 8px; padding-bottom: 8px;">
                                                </div>
                                                <div class="position-relative flex-grow-1">
                                                    <i class="fas fa-undo dt-icon position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 4;"></i>
                                                    <input type="submit" class="btn btn-secondary curvic-button w-100 pl-5" name="reset" value="Reset" style="padding-top: 8px; padding-bottom: 8px;"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                            <?php if (session()->has('success')): ?>
                                    <div class="alert alert-success"><?= esc(session('success')) ?></div>
                                <?php endif ?>
                            <div class="QA_table mb_30">
                                    <!-- table-responsive -->
                                    <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.N</th>
                                                <th scope="col">Reg No</th>
                                                <th scope="col">School Name</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Medium</th>
                                                <th scope="col">Contact Person</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="no-export">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php if (!empty($content_data['data']['schools'])): ?>
                                            <?php $counter = 1;
                                                foreach ($content_data['data']['schools'] as $school): ?>
                                            <tr>
                                                <td><?= $counter++ ?>                                                    
                                                </td>
                                                <td><?= esc($school['school_registration_no']) ?></td>
                                                <td>
                                                    <?php if(!empty($school['school_logo'])){?>
                                                        <a href="<?= base_url('uploads/schools/' . $school['school_logo']) ?>" target="_blank" title="<?= esc($school['school_name']) ?>">
                                                            <img src="<?= base_url('uploads/schools/' . $school['school_logo']) ?>"  
                                                    class="img-thumbnail" style="max-height: 50px;" alt="<?= esc($school['school_name']) ?>"/></a>
                                                    <?php }?>
                                                    <a href="schools/edit/<?= esc($school['id']) ?>" class="question_content"><?= esc($school['school_name']) ?></a>                                                
                                                </td>
                                                <td><?= esc($school['school_type'] ?? 'N/A') ?></td>
                                                <td><?= esc($school['school_medium'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?= esc($school['contact_person_name'] ?? 'N/A') ?>
                                                    <small class="d-block"><?= esc($school['contact_person_mobile'] ?? '') ?></small>
                                                </td>
                                                
                                                <td>
                                                    <?php if (has_permission('/schools', PERMISSION_EDIT)): ?>                                                                                                
                                                    <a href="#" class="status_btn status-<?= $school['status'] ? 'active' : 'inactive' ?>" 
                                                    data-id="<?= esc($school['id']) ?>" data-tbl="schools"  
                                                    data-status="<?= $school['status'] ? 1 : 0 ?>">
                                                        <?= $school['status'] ? 'Active' : 'Inactive' ?>
                                                    </a>  
                                                    <?php endif; ?>                                                 
                                                </td>
                                                <td class="no-export">                                                    
                                                <?php if (has_permission('/schools', PERMISSION_EDIT)): ?><a href="schools/edit/<?= esc($school['id']) ?>" class="action_btn mr_10"> <i class="far fa-edit"></i></a><?php endif; ?>                                              
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







