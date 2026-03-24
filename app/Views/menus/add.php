<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<?php 
$data = $content_data['data'];
$isEdit = $data['isEdit'];
$menu = $data['menu'] ?? null;
$label = $isEdit ? 'Edit' : 'Add';
?>
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?=$label;?> Menu</h3>
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
                <form class="" autocomplete="off" method="post" action="<?= site_url('menus/store'.($isEdit ? '/'.$menu['id'] : '')) ?>" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="_method" value="post">
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Menu Name -->
                        <div class="col-lg-6">
                            <div class="common_input mb_15 select-required-asterisk">
                                <input type="text" placeholder="Menu Name" class="form-control" name="name" id="name" 
                                    value="<?= old('name', $menu['name'] ?? '') ?>" required>
                            </div>
                        </div>
                                                
                        <!-- Menu Level -->
                        <div class="col-lg-6">                            
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="menu_level" id="menu_level" required 
                                onchange="toggleParentMenuField(this.value);">
                                <option value="">Select Menu Level</option>
                                <option value="1" <?= set_select('menu_level', '1', isset($menu['menu_level']) && $menu['menu_level'] == 1) ?>>
                                    Main Menu
                                </option>
                                <option value="2" <?= set_select('menu_level', '2', isset($menu['menu_level']) && $menu['menu_level'] == 2) ?>>
                                    Submenu
                                </option>
                            </select>                            
                        </div>
                        
                        <div class="col-lg-6" id="parent_id_div"> 
                        <!-- Parent Menu (only shown for submenus) -->
                            <?php $parentRequired = (isset($menu['menu_level']) && $menu['menu_level'] == 2) ? 'required' : ''; ?>
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="parent_id" id="parent_id" <?= $parentRequired ?>>
                                <option value="">Select Parent Menu</option>
                                <?php foreach ($data['mainMenus'] as $parent): ?>
                                    <option value="<?= $parent['id'] ?>" 
                                        <?= set_select('parent_id', $parent['id'], 
                                            isset($menu['parent_id']) && $menu['parent_id'] == $parent['id']) ?>>
                                        <?= esc($parent['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>                           
                        </div>

                        <div class="col-lg-6" id="parent_id_div_for">                            
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="menu_for" id="menu_for" required>
                                <option value="">Menu For</option>
                                <option value="A" <?= set_select('menu_for', 'A', isset($menu['menu_for']) && $menu['menu_for'] == A) ?>>Admin</option>
                                <option value="S" <?= set_select('menu_for', 'S', isset($menu['menu_for']) && $menu['menu_for'] == S) ?>>School</option>
                                <option value="L" <?= set_select('menu_for', 'L', isset($menu['menu_for']) && $menu['menu_for'] == L) ?>>Library</option>
                                <option value="M" <?= set_select('menu_for', 'M', isset($menu['menu_for']) && $menu['menu_for'] == M) ?>>All</option>
                            </select>                            
                        </div>
                        
                        <!-- Menu Priority -->                        
                        <div class="col-lg-6">
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" 
                                    name="priority" id="priority" required>
                                <option value="">Select Priority</option>
                                <?php if (!empty($data['priorityMenus'])): ?>
                                    <?php foreach ($data['priorityMenus'] as $item): ?>
                                        <option value="<?= $item['priority'] ?>"
                                            <?= (isset($menu['priority']) && (int)$menu['priority'] === (int)$item['priority']) ? 'selected' : '' ?>>
                                            <?= esc($item['name']) ?> (<?= $item['priority'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                    <!-- Add at the end option -->
                                    <option value="<?= $data['maxPriority'] ?>"
                                        <?= (isset($menu['priority']) && (int)$menu['priority'] === (int)$data['maxPriority']) ? 'selected' : '' ?>>
                                        Add at the end (<?= $data['maxPriority'] ?>)
                                    </option>
                                <?php else: ?>
                                    <!-- Default for new menus -->
                                    <option value="1" <?= !isset($menu['priority']) ? 'selected' : '' ?>>
                                        1 (First menu)
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- URL -->
                        <div class="col-lg-6">
                            <div class="common_input mb_15 select-required-asterisk1">
                                <input type="text" placeholder="URL" class="form-control" name="url" id="url" 
                                    value="<?= old('url', $menu['url'] ?? '') ?>">
                            </div>
                        </div> 
                        
                        <!-- Allowed Plans -->
                        <div class="col-lg-6">
                            <div class="wide mb_15 select-required-asterisk">                                
                                <select class="form-control select2-multi select-required-asterisk" id="plan_ids" name="plan_ids[]" placeholder="Select Plans" multiple required>
                                    <?php 
                                    $planDetails = get_records('plan_packages pp', [
                                        'joins' => [                                            
                                            [
                                                'table' => 'plan_tenure ptr',
                                                'condition' => 'pp.tenure_id = ptr.id',
                                                'type' => 'left'
                                            ],
                                            [
                                                'table' => 'plan_type pt',
                                                'condition' => 'pp.plan_type_id = pt.id',
                                                'type' => 'left'
                                            ]
                                        ],                                        
                                        'select' => [
                                            'pp.id',
                                            'pp.name',
                                            'pp.price',                                            
                                            'ptr.name as tenure',
                                            'pt.name as plan_types'                                                         
                                        ],   
                                        'filters'=>['pp.status' => 0],         
                                        'groupBy' => 'pp.id, ptr.name, pt.name' 
                                    ]);
                                    
                                    $selectedPlans = isset($menu['plan_ids']) ? explode(',', $menu['plan_ids']) : [];
                                    
                                    if (!empty($planDetails)): ?>
                                        <?php foreach ($planDetails as $mName): ?>
                                        <option value="<?= $mName['id'] ?>" data-price="<?= (int)$mName['price'] ?>" 
                                            <?= in_array($mName['id'], $selectedPlans) ? 'selected' : '' ?>>
                                            <?= $mName['name'].' / '.$mName['tenure'].' / &#8377;'.(int)$mName['price'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                                   
                                </select>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-lg-6">                            
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="status" id="status" required>
                                <option value="">Select Status</option>
                                <option value="0" <?= set_select('status', '0', isset($menu['status']) && $menu['status'] == 0) ?>>
                                    Active
                                </option>
                                <option value="1" <?= set_select('status', '1', isset($menu['status']) && $menu['status'] == 1) ?>>
                                    Inactive
                                </option>
                            </select>                            
                        </div>
                        
                        <!-- Show Menu -->
                        <div class="col-lg-6">                            
                            <select class="nice_Select2 nice_Select_line wide mb_15 select-required-asterisk" name="show_menu" id="show_menu" required>
                                <option value="">Show Menu</option>
                                <option value="0" <?= set_select('show_menu', '0', isset($menu['show_menu']) && $menu['show_menu'] == 0) ?>>
                                    Yes
                                </option>
                                <option value="1" <?= set_select('show_menu', '1', isset($menu['show_menu']) && $menu['show_menu'] == 1) ?>>
                                    No
                                </option>
                            </select>                            
                        </div>

                        <!-- Icon SVG -->
                        <div class="col-lg-6">
                            <div class="common_input mb_15">                                
                                <label for="icon_svg">SVG Icon</label>
                                <input type="file" name="icon_svg" id="icon_svg" class="form-control" accept=".svg,image/svg+xml">
                                
                                    <div class="mt-2">                                        
                                        <img id="imagePreview" class="img-fluid mt-2 d-none" style="max-height: 70px;">
                                        <?php if (isset($menu['icon_svg']) && !empty($menu['icon_svg'])): ?>
                                        <div class="svg-preview">                                            
                                            <img src="<?= base_url('/' . $menu['icon_svg']) ?>" class="img-thumbnail" style="max-height: 50px;" alt="<?= esc($menu['icon_svg']) ?>"/>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                
                            </div>
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
<script src="<?= base_url('assets/adminAssets/js/select2_content.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for plan_ids
    $('#plan_ids').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Plans',
        allowClear: true
    });

    // Initialize form based on current values
    const menuLevel = $('#menu_level').val();
    toggleParentMenuField(menuLevel);
    
    // If editing, set the current priority
    <?php if (isset($menu['priority'])): ?>
        $('#priority').val(<?= $menu['priority'] ?>);
    <?php endif; ?>

    // If editing and it's a submenu, load priorities for the current parent
    <?php if (isset($menu['menu_level']) && $menu['menu_level'] == 2 && isset($menu['parent_id'])): ?>
        loadPriorities(<?= $menu['menu_level'] ?>,<?= $menu['parent_id'] ?>, <?= $isEdit ? $menu['id'] : 'null' ?>);
    <?php endif; ?>

    function loadPriorities(menu_level, parent_id, currentMenuId = null) {
    if (menu_level === undefined) return;

    $.ajax({
        url: '<?= site_url('menus/get-priorities') ?>',
        type: 'POST',
        data: {
            menu_level: menu_level,
            parent_id: parent_id,
            current_id: currentMenuId,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        success: function(response) {
            console.log("Response received:", response);            
            // Get the select element
            const $select = $('#priority');
            
            // 1. Destroy any existing nice-select instance
            if ($select.next().hasClass('nice-select')) {
                $select.next().remove();
                $select.show();
            }
            
            // 2. Update the select options
            $select.html(response);
            
            // 3. Reinitialize nice-select
            $select.niceSelect();
            
            // 4. If using Select2, refresh it
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy').select2({
                    theme: 'bootstrap-5'
                });
            }
            
            // Set the current priority if editing
            <?php if (isset($menu['priority'])): ?>
                $select.val(<?= $menu['priority'] ?>);
                $select.niceSelect('update');
                $select.trigger('change.select2');
            <?php endif; ?>
            
            console.log("Updated dropdown content:", $select.html());
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $('#priority').html('<option value="">Error loading priorities</option>')
                         .niceSelect('update')
                         .trigger('change.select2');
        }
    });
}

    function toggleParentMenuField(menuLevel) { 
        const parentMenuContainer = $('#parent_id_div');
        const parentMenuContainerFor = $('#parent_id_div_for');
        const parentIdField = $('#parent_id');
        const menu_for = $('#menu_for');        
        
        if (menuLevel == '2') {
            parentMenuContainer.show();
            parentMenuContainerFor.hide();
            parentIdField.prop('required', true);
            menu_for.prop('required', false);
            menu_for.val('');
        } else {
            parentMenuContainer.hide();
            parentMenuContainerFor.show();
            parentIdField.prop('required', false);
            parentIdField.val('');
        }
    }

    // When parent menu changes (for submenus)
    $('#parent_id').change(function() {
        var menuLevelVal = $('#menu_level').val();
        loadPriorities(menuLevelVal,$(this).val(), <?= $isEdit ? $menu['id'] : 'null' ?>);
    });

    // When menu level changes
    $('#menu_level').change(function() {
        toggleParentMenuField($(this).val());
        if ($(this).val() == 1) {
            // For main menus
            loadPriorities($(this).val(),null, <?= $isEdit ? $menu['id'] : 'null' ?>);
        }
    });

    setupImagePreview('icon_svg', 'imagePreview');
});
</script>
