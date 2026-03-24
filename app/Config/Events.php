<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;
helper('permission');
/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }

    // Events::on('login', static function ($user) {
    //     // $user is CodeIgniter\Shield\Entities\User
    
    //     // 1. Clear any stale permission cache for this user
    //     //clear_permission_cache($user->id);
    //     if ($user instanceof \CodeIgniter\Shield\Entities\User) {
    //         clear_permission_cache($user->id);
    //     }
    
    //     // 2. Optional: Immediately warm up / pre-load the permission cache
    //     //    (so first page load after login has zero delay)
    //     // load_user_permissions($user->id);   // uncomment if you want this
    
    //     // 3. Optional: Log the login (for audit/security)
    //     // log_message('info', "User logged in: ID={$user->id}, Email={$user->email}, IP=" . service('request')->getIPAddress());
    
    //     // 4. Optional: Set extra session data if needed
    //     // session()->set('last_login_at', date('Y-m-d H:i:s'));
    //     // session()->set('user_display_name', $user->first_name . ' ' . $user->last_name);
    
    //     // You can add more post-login setup here (e.g., load user profile, preferences, etc.)
    // });
    Events::on('login', static function ($user): void {
        helper('permission');   // ← this is the key line — load it right here
    
        if ($user instanceof \CodeIgniter\Shield\Entities\User) {
            clear_permission_cache($user->id);   // ← now plain name works
    
            if (function_exists('load_user_permissions')) {
                load_user_permissions($user->id);
            }
        }
    });
    
    Events::on('logout', static function ($user): void {
        helper('permission');   // ← also here, just in case
    
        if ($user instanceof \CodeIgniter\Shield\Entities\User) {
            clear_permission_cache($user->id);
        }
    });

});
