<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<?php 
$data = $content_data['data']['data'] ?? null;

$isEdit = isset($data);
$label = $isEdit?'Edit':'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?=$label;?> Plan </h3>
                    </div>
                    <!-- Display validation errors -->
                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>

                        <!-- Display success/error messages -->
                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                        <?php endif ?>                         
                        <form class="" autocomplete="off" method="post" action="<?= site_url('plans/save') ?>">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                        <?php endif; ?>
                        <!--  -->
                </div>
            </div>
            
            <div class="white_card_body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="common_input mb_15 required-asterisk">
                        <input type="text" placeholder="Plan Name" class="form-control" name="name" id="name" value="<?= old('name', $data['name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="common_input mb_15 required-asterisk">
                        <input type="text" placeholder="Plan Price" class="form-control" oninput="validateDigitsOnly(this)" name="price" id="price" value="<?= old('price', isset($data['price']) ? (int)$data['price'] : '') ?>" required>
                        </div>
                    </div>  
                    <div class="col-lg-6">                        
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="plan_type_id" name="plan_type_id" required>
                                <?php foreach ($content_data['data']['planType'] as $id => $name): ?>
                                <option value="<?= $id ?>" 
                                    <?= set_select('plan_type_id', $id, 
                                        isset($data['plan_type_id']) && $data['plan_type_id'] == $id) ?>>
                                    <?= esc($name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>                        
                    </div>
                    <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" id="tenure_id" name="tenure_id" required>
                                    <?php 
                                        foreach ($content_data['data']['planTenure'] as $id => $name): ?>
                                        <option value="<?= $id ?>" 
                                            <?= set_select('tenure_id', $id, 
                                                isset($data['tenure_id']) && $data['tenure_id'] == $id) ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                            </select>                        
                    </div>
                    
                    <div class="col-lg-6">  
                            <div class="wide mb_15 required-asterisk">                   
                                <select class="form-control select2-multi" id="service_id" name="service_id[]" placeholder="Select Services" required multiple>                                        
                                        <?php $service_id_array = isset( $data['service_id']) ?  explode(',', $data['service_id']) : []; 
                                            foreach ($content_data['data']['planServices'] as $id => $name): ?>
                                            <option value="<?= $id ?>"
                                                <?= (empty($id) || !isset($id)) ? 'disabled' : '' ?>
                                                <?= in_array($id, $service_id_array) ? 'selected' : '' ?>>
                                                <?= esc($name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                </select>  
                            </div>                      
                    </div> 
                                                     
                    <div class="col-lg-12">
                        <div class="common_input mb_15 required-asterisk">
                            <textarea class="form-control" placeholder="Plan Details" id="details" name="details" rows="7" required><?= old('details', $data['details'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="create_report_btn mt_30">
                            <input type="submit" class="btn_1 d-block text-center" value="<?=$label;?>">                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<script src="<?= base_url('assets/adminAssets/js/select2_content.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>





