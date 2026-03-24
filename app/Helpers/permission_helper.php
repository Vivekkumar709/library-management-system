<?php

if (!function_exists('clear_permission_cache')) {
    function clear_permission_cache(int $userId): void
    {
        $cache = service('cache');
        $cache->delete("user_perms_{$userId}");
    }
}

if (!function_exists('load_user_permissions_with_group_emp')) {
    function load_user_permissions_with_group_emp(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }
        $db = db_connect();
        $perms = [];

        // Group-level permissions
        $groupPerms = $db->table('auth_groups_users agu')
            ->join('auth_groups_permissions agp', 'agu.group_id = agp.group_id')
            ->select('agp.menu_id, agp.permission_id')
            ->where('agu.user_id', $userId)
            ->where('agp.status', 0)
            ->get()->getResultArray();
       
        foreach ($groupPerms as $row) {
            $key = "{$row['menu_id']}.{$row['permission_id']}";
            $perms[$key] = true;
        }
        // User-level permissions
        $userPerms = $db->table('auth_groups_users_permissions ugp')
            ->select('ugp.menu_id, ugp.permission_id')
            ->where('ugp.user_id', $userId)
            ->where('ugp.status', 0)
            ->get()->getResultArray();        

        foreach ($userPerms as $row) {
            $key = "{$row['menu_id']}.{$row['permission_id']}";
            $perms[$key] = true;
        }        
        //log_message('debug', "Final permissions loaded: " . json_encode($perms));
        return $perms;
    }
}

if (!function_exists('load_user_permissions')) {
    function load_user_permissions(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $db = db_connect();
        $perms = [];

        // User-level permissions (check first)
        $userPerms = $db->table('auth_groups_users_permissions ugp')
            ->select('ugp.menu_id, ugp.permission_id')
            ->where('ugp.user_id', $userId)
            ->where('ugp.status', 0)
            ->get()->getResultArray();

        // If user-level permissions exist, use ONLY those
        if (!empty($userPerms)) {
            foreach ($userPerms as $row) {
                $key = "{$row['menu_id']}.{$row['permission_id']}";
                $perms[$key] = true;
            }
            return $perms; // ← early return, skip group-level entirely
        }

        // No user-level found, fall back to group-level permissions
        $groupPerms = $db->table('auth_groups_users agu')
            ->join('auth_groups_permissions agp', 'agu.group_id = agp.group_id')
            ->select('agp.menu_id, agp.permission_id')
            ->where('agu.user_id', $userId)
            ->where('agp.status', 0)
            ->get()->getResultArray();

        foreach ($groupPerms as $row) {
            $key = "{$row['menu_id']}.{$row['permission_id']}";
            $perms[$key] = true;
        }

        return $perms;
    }
}

function has_permission($menu, int $permId): bool
{
    // Get current user ID safely
    $userId = auth()->id();

    // If not logged in or invalid user → deny
    if (!$userId || $userId <= 0) {
        return false;
    }

    // Bypass: If user is in group ID 1 (admin/superadmin), allow ALL menus
    // This is very fast – one DB check or session cache per request
    //helper('auth');
    
    
    //$isAdminGroup = in_group(1);  // Shield helper: checks if user belongs to group id=1
    $user = auth()->user();     

    if ($user->user_type_id == 1) {
        log_message('debug', "User $userId is in admin group (id=1) → full menu access granted");
        return true;
    }

    // Normal permission check for non-admin users
    $cache = service('cache');
    $cacheKey = "user_perms_{$userId}";

    $perms = $cache->get($cacheKey);

    if ($perms === null) {
        $perms = load_user_permissions($userId);
        $cache->save($cacheKey, $perms, 3600*24*30); // 30 days TTL
    }

    $menuId = is_numeric($menu) ? (int)$menu : get_menu_id_from_url($menu);

    if (!$menuId) {
        log_message('warning', "Menu not found: $menu");
        return false;
    }

    $key = "{$menuId}.{$permId}";

    // Loose check to handle cached values (1/true/"1")
    return !empty($perms[$key]);
}

// Optional: if you use URL → menu_id mapping
if (!function_exists('get_menu_id_from_url')) {
    function get_menu_id_from_url(string $url): ?int
    {
        static $map = null;
        if ($map === null) {
            $cache = service('cache');
            $cacheKey = 'menu_url_to_id';
            $map = $cache->get($cacheKey);

            if ($map === null) {
                $rows = db_connect()->table('auth_menus')
                    ->select('id, url')
                    ->where('status', 0)
                    ->get()->getResultArray();

                $map = [];
                foreach ($rows as $row) {
                    $clean = trim($row['url'], '/');
                    $map[$clean] = (int)$row['id'];
                    $map[$row['url']] = (int)$row['id'];
                }
                $cache->save($cacheKey, $map, 86400 * 7); // 7 days
            }
        }
        $clean = trim($url, '/');
        return $map[$clean] ?? $map[$url] ?? null;
    }
}