<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<?php 
// echo "<pre>";
// print_r($content_data);
// die;
$data = $content_data['data']['data'][0]?? null;
$isEdit = $content_data['data']['isEdit'];
$label = $isEdit ? 'Edit' : 'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?=$label;?> User Group</h3>
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
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success"><?= esc(session('message')) ?></div>
                    <?php endif ?>
                </div>
            </div>
            
            <div class="white_card_body">
                <form class="" autocomplete="off" method="post" action="<?= site_url('userGroups/create') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="_method" value="post">
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <?php endif; ?>
                    <div class="row">
                        <!-- Group Name -->
                        <div class="col-lg-6">
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Group Name" class="form-control" name="name" id="name" 
                                    value="<?= old('name', $data['name'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="common_input mb_15 select-required-asterisk1">
                                <input type="text" placeholder="Description" class="form-control" name="description" id="description" 
                                    value="<?= old('description', $data['description'] ?? '') ?>">
                            </div>
                        </div> 
                        <div class="col-lg-6">                            
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="use_for" id="use_for" required>
                                <option value="">Select Use For</option>
                                <option value="S" <?= set_select('use_for', 'S', isset($data['use_for']) && $data['use_for'] == 'S') ?>>
                                    School
                                </option>
                                <option value="A" <?= set_select('use_for', 'A', isset($data['use_for']) && $data['use_for'] == 'A') ?>>
                                    Admin
                                </option>
                                <option value="L" <?= set_select('use_for', 'L', isset($data['use_for']) && $data['use_for'] == 'L') ?>>
                                    Library
                                </option>
                            </select>                            
                        </div>
                        <div class="col-lg-6">                            
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="status" id="status" required>
                                <option value="">Select Status</option>
                                <option value="0" <?= set_select('status', '0', isset($data['status']) && $data['status'] == 0) ?>>
                                    Active
                                </option>
                                <option value="1" <?= set_select('status', '1', isset($data['status']) && $data['status'] == 1) ?>>
                                    Inactive
                                </option>
                            </select>                            
                        </div>
                        <div class="col-12">
                            <div class="create_report_btn mt_30">
                                <button type="submit" class="btn_1 d-block text-center"><?= $label ?></button>                            
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>

