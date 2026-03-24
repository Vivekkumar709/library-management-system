<?php 
// Initialize variables at the top
$data = $content_data['data']['data'] ?? null;
$isEdit = isset($data);
$label = $isEdit ? 'Edit' : 'Add';
$menuTree = $content_data['data']['menu'] ?? [];
$permissions = $content_data['data']['permissions'] ?? [];
$user_type = $content_data['user_type'] ?? null;
$groupId = $data['id'] ?? null;
?>
<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><?= $label ?> User Type: <i><font color="blue"><?=$user_type; ?></font></i></h3>
                    </div>
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                    <?php endif ?>
                </div>
            </div>
            <div class="white_card_body">
                <form method="post" action="<?= site_url('userGroups/user_type_menu_access/save') ?>">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="group_id" value="<?= $groupId ?>">
                    <?php endif; ?>
                    
                    <div class="permissions-container">
                        <?php 
                        $rowCounter = 1;
                        foreach ($menuTree as $mainMenu): 
                            $mainMenuPermissions = $isEdit ? ($data['permissions'][$mainMenu['id']] ?? []) : [];
                            $allMainPermissions = count($permissions) === count($mainMenuPermissions);
                        ?>
                        <!-- Main Menu Row -->
                        <div class="permission-row">
                            <div class="checkbox-label">
                                <input type="checkbox" id="all_<?= $rowCounter ?>" class="large-checkbox main-menu-all" 
                                    data-row="<?= $rowCounter ?>" 
                                    <?= $allMainPermissions ? 'checked' : '' ?>>
                                <label for="all_<?= $rowCounter ?>">All</label>
                            </div>

                            <div class="menu-name">
                                <?= $mainMenu['name'] ?>
                                <input type="hidden" name="menu_<?= $rowCounter ?>" value="<?= $mainMenu['id'] ?>">
                            </div>

                            <?php foreach ($permissions as $perm): ?>
                            <div class="checkbox-label">
                                <input type="checkbox" id="<?= $perm['id'] ?>_<?= $rowCounter ?>" 
                                    name="<?= $perm['id'] ?>_<?= $mainMenu['id'] ?>" 
                                    class="large-checkbox main-menu-checkbox" 
                                    data-row="<?= $rowCounter ?>"
                                    <?= isset($mainMenuPermissions[$perm['id']]) ? 'checked' : '' ?>>
                                <label for="<?= $perm['id'] ?>_<?= $rowCounter ?>"><?= $perm['display_name'] ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php  
                        // Submenus
                        $subRowCounter = 1;
                        foreach ($mainMenu['submenus'] as $subMenu): 
                            $subMenuPermissions = $isEdit ? ($data['permissions'][$subMenu['id']] ?? []) : [];
                            $allSubPermissions = count($permissions) === count($subMenuPermissions);
                        ?>
                        <div class="permission-row submenu-row">
                            <div class="menu-name">
                                <?= $subMenu['name'] ?>
                                <input type="hidden" name="menu_<?= $rowCounter ?>_<?= $subRowCounter ?>" value="<?= $subMenu['id'] ?>">
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" id="all_<?= $rowCounter ?>_<?= $subRowCounter ?>" 
                                    class="large-checkbox sub-menu-all" 
                                    data-row="<?= $rowCounter ?>" 
                                    data-subrow="<?= $subRowCounter ?>"
                                    <?= $allSubPermissions ? 'checked' : '' ?>>
                                <label for="all_<?= $rowCounter ?>_<?= $subRowCounter ?>">All</label>
                            </div>
                            <?php foreach ($permissions as $perm): ?>
                            <div class="checkbox-label">
                                <input type="checkbox" id="<?= $perm['id'] ?>_<?= $rowCounter ?>_<?= $subRowCounter ?>" 
                                    name="<?= $perm['id'] ?>_<?= $subMenu['id'] ?>" 
                                    class="large-checkbox sub-menu-checkbox" 
                                    data-row="<?= $rowCounter ?>" 
                                    data-subrow="<?= $subRowCounter ?>"
                                    <?= isset($subMenuPermissions[$perm['id']]) ? 'checked' : '' ?>>
                                <label for="<?= $perm['id'] ?>_<?= $rowCounter ?>_<?= $subRowCounter ?>"><?= $perm['display_name'] ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div> 
                        <?php 
                        $subRowCounter++;
                        endforeach;
                        
                        $rowCounter++;
                        endforeach; 
                        ?>
                        
                        <div class="create_report_btn mt-4">
                            <input type="submit" class="btn_1 d-block text-center" value="<?= $label ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<script>
$(document).ready(function() {

    // Helper: check if ALL given checkboxes are checked
    function areAllChecked($checkboxes) {
        if ($checkboxes.length === 0) return false;
        return $checkboxes.length === $checkboxes.filter(':checked').length;
    }

    // Helper: update "All" checkbox state
    function updateAllState($allCheckbox, $relatedCheckboxes) {
        $allCheckbox.prop('checked', areAllChecked($relatedCheckboxes));
    }
    
    // ───────────────────────────────────────────────
    // Main "All" → controls main row + all submenus completely
    // ───────────────────────────────────────────────
    $('.main-menu-all').on('change', function() {
        const row = $(this).data('row');
        const checked = this.checked;

        // Main menu permissions
        $(`.main-menu-checkbox[data-row="${row}"]`).prop('checked', checked);

        // All submenus + their "All" checkboxes
        $(`.sub-menu-checkbox[data-row="${row}"]`).prop('checked', checked);
        $(`.sub-menu-all[data-row="${row}"]`).prop('checked', checked);
    });

    // ───────────────────────────────────────────────
    // Submenu "All" → only this submenu
    // ───────────────────────────────────────────────
    $('.sub-menu-all').on('change', function() {
        const row    = $(this).data('row');
        const subrow = $(this).data('subrow');
        const checked = this.checked;

        $(`.sub-menu-checkbox[data-row="${row}"][data-subrow="${subrow}"]`)
            .prop('checked', checked);
    });

    // ───────────────────────────────────────────────
    // Main menu individual permission changed → cascade SAME permission to submenus
    // ───────────────────────────────────────────────
    $('.main-menu-checkbox').on('change', function() {
        const row = $(this).data('row');
        const isChecked = this.checked;

        // Get permission ID from name (example: name="3_15" → "3")
        const permId = this.name.split('_')[0];

        // 1. Update this main row's "All"
        const $mainPerms = $(`.main-menu-checkbox[data-row="${row}"]`);
        updateAllState($(`#all_${row}`), $mainPerms);

        // 2. Apply the same change to ALL submenus for this exact permission
        $(`.sub-menu-checkbox[name^="${permId}_"][data-row="${row}"]`)
            .prop('checked', isChecked);

        // 3. After changing submenus → update every submenu's "All" checkbox
        $(`.sub-menu-all[data-row="${row}"]`).each(function() {
            const subrow = $(this).data('subrow');
            const $subPerms = $(`.sub-menu-checkbox[data-row="${row}"][data-subrow="${subrow}"]`);
            updateAllState($(this), $subPerms);
        });
    });

    // ───────────────────────────────────────────────
    // Submenu individual permission changed
    // ───────────────────────────────────────────────
    $('.sub-menu-checkbox').on('change', function() {
        const row    = $(this).data('row');
        const subrow = $(this).data('subrow');

        // Update this submenu's "All"
        const $subPerms = $(`.sub-menu-checkbox[data-row="${row}"][data-subrow="${subrow}"]`);
        updateAllState($(`#all_${row}_${subrow}`), $subPerms);

        // Note: we do NOT automatically update main "All" here
        // (most permission UIs keep main "All" independent of submenus)
    });

    // ───────────────────────────────────────────────
    // Initial state sync – very important for edit mode
    // ───────────────────────────────────────────────
    $('.main-menu-all').each(function() {
        const row = $(this).data('row');
        const $perms = $(`.main-menu-checkbox[data-row="${row}"]`);
        updateAllState($(this), $perms);
    });

    $('.sub-menu-all').each(function() {
        const row    = $(this).data('row');
        const subrow = $(this).data('subrow');
        const $perms = $(`.sub-menu-checkbox[data-row="${row}"][data-subrow="${subrow}"]`);
        updateAllState($(this), $perms);
    });
});
</script>