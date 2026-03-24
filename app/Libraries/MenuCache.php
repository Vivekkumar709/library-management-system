<?php

namespace App\Libraries;

class MenuCache
{
    private static $urlToId = null;

    public static function getMenuIdByUrl(string $url): ?int
    {
        if (self::$urlToId === null) {
            $cache = service('cache');
            $key   = 'global_menu_url_to_id';

            self::$urlToId = $cache->get($key);

            if (self::$urlToId === null) {
                $rows = db_connect()->table('auth_menus')
                    ->select('id, url')
                    ->where('status', 0)
                    ->get()->getResultArray();

                self::$urlToId = [];
                foreach ($rows as $row) {
                    $clean = trim($row['url'], '/');
                    self::$urlToId[$clean] = (int)$row['id'];
                    self::$urlToId[$row['url']] = (int)$row['id'];
                }

                $cache->save($key, self::$urlToId, 86400 * 30); // 30 days
            }
        }

        $clean = trim($url, '/');
        return self::$urlToId[$clean] ?? self::$urlToId[$url] ?? null;
    }

    public static function clear(): void
    {
        service('cache')->delete('global_menu_url_to_id');
        self::$urlToId = null;
    }
}