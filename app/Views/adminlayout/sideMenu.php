<body class="crm_body_bg">  
<!-- main content part here --> 
 <!-- sidebar  -->
<nav class="sidebar dark_sidebar1"  id="sidebarNav">
    <div class="logo d-flex justify-content-between" style="background-color: white;">
        <a class="large_logo" href="/dashboard"><img src="<?= base_url('images/logo.png') ?>" width="170" height="85" alt="logo" alt="logo"></a>
        <a class="small_logo" href="/dashboard"><img src="<?= base_url('images/logoSmall.png') ?>" width="60" height="70" alt="logo"></a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    <?php 
    // Get current URL segment for active state comparison
    $currentUrl = current_url(); 
    ?>
    <ul id="sidebar_menu">
        
<!-- //+++++++++++++++ DYNAMIC MENUS CODE START FROM HERE +++++++ --> 
<?php if (!empty($menuTree)): ?>

<?php
// Local cache for permission checks (fast lookup per request)
$permissionCache = [];

// Helper function (closure) – avoids globals
$hasAccess = function (int $menuId) use (&$permissionCache): bool {
    if (array_key_exists($menuId, $permissionCache)) {
        return $permissionCache[$menuId];
    }

    $has = has_permission($menuId, PERMISSION_VIEW); // your permission function
    $permissionCache[$menuId] = $has;
    return $has;
};
?>
<?php foreach ($menuTree as $menu): ?>
    <?php
    $menuId   = $menu['id'] ?? 0;
    $hasPerm  = $hasAccess($menuId);
    $submenus = $menu['submenus'] ?? [];

    // Check if any submenu is accessible
    $anySubHasAccess = false;
    foreach ($submenus as $sub) {
        if ($hasAccess($sub['id'] ?? 0)) {
            $anySubHasAccess = true;
            break;
        }
    }

    // Show this menu item only if parent OR at least one child is allowed
    if (!$hasPerm && !$anySubHasAccess) {
        continue;
    }

    $hasChildren = !empty($submenus);
    $isLink      = !$hasChildren;
    $href        = $isLink ? site_url($menu['url'] ?? '#') : '#';
    $arrowClass  = $hasChildren ? 'has-arrow' : '';
    ?>

    <li class="<?= esc($menu['class'] ?? $menuId) ?>">
        <a class="<?= esc($arrowClass) ?>" 
           href="<?= esc($href) ?>" 
           aria-expanded="false">

            <div class="nav_icon_small">
                <?php if ($menu['name'] === 'Transport Management'): ?>
                    <i class="ti-car" style="color: #94c0d4; font-size: 18px;"></i>
                <?php elseif ($menu['name'] === 'Announcements'): ?>
                    <img src="<?= base_url('assets/adminAssets/img/menu-icon/announcements.svg') ?>" alt="Announcements">
                <?php else: ?>
                    <?php 
                    $iconPath = $menu['icon_svg'] ?? 'assets/adminAssets/img/menu-icon/default.svg';
                    ?>
                    <img src="<?= base_url($iconPath) ?>" alt="<?= esc($menu['name']) ?>">
                <?php endif; ?>
            </div>

            <div class="nav_title">
                <span><?= esc($menu['name'] ?? 'Menu') ?></span>
            </div>
        </a>

        <?php if ($hasChildren): ?>
            <ul>
                <?php foreach ($submenus as $submenu): ?>
                    <?php if ($hasAccess($submenu['id'] ?? 0)): ?>
                        <li>
                            <a href="<?= esc(site_url($submenu['url'] ?? '#')) ?>">
                                <?= esc($submenu['name'] ?? 'Submenu') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
<?php endif; ?>
 
<!-- //+++++++++++++++ DYNAMIC MENUS CODE ENDED HERE ++++++++++++ -->
    </ul>
</nav>
 <!--/ sidebar  -->