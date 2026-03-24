<?php
if (!function_exists('get_dropdown')) {
    function get_dropdown(
        string $table,
        string $key = 'id',
        string $value = 'name',
        array $where = [],
        string $selectPostfix = '',
        bool $useCache = true,
        int $ttl = 3600
    ): array {
        // Generate cache key only if caching is enabled
        $cacheKey = null;
        if ($useCache) {
            $whereHash = md5(json_encode($where));
            $cacheKey = "dropdown_{$table}_{$key}_{$value}_{$whereHash}_{$selectPostfix}";
            $cached = cache($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        $db = \Config\Database::connect();
        $valueColumns = array_map('trim', explode(',', $value));
        $selectColumns = array_merge([$key], $valueColumns);
        $builder = $db->table($table)->select($selectColumns);
        //$builder = $db->table($table)->select("$key, $value");
        
        // Process where conditions
        foreach ($where as $field => $condition) {
            if (is_array($condition) && isset($condition['operator'])) {
                // Handle special operators like NOT IN, IN, etc.
                switch (strtoupper($condition['operator'])) {
                    case 'NOT IN':
                        $builder->whereNotIn($field, $condition['value']);
                        break;
                    case 'IN':
                        $builder->whereIn($field, $condition['value']);
                        break;
                    // Add other operators as needed
                    default:
                        $builder->where($field . ' ' . $condition['operator'], $condition['value']);
                }
            } else {
                // Simple where condition
                $builder->where($field, $condition);
            }
        }
        
        $results = $builder->get()->getResult();
        if($selectPostfix == 'Empty'){ 
            $options = [];
        }else{
            $options = ['' => 'Select' . ($selectPostfix ? ' ' . $selectPostfix : '')];
        }    
        
        foreach ($results as $row) {
            $displayValue = [];
            foreach ($valueColumns as $col) {
                $displayValue[] = $row->$col ?? '';
            }
            $options[$row->$key] = implode(',', array_filter($displayValue));
            //$options[$row->$key] = $row->$value;
        }
        
        if ($useCache && $cacheKey) {
            cache()->save($cacheKey, $options, $ttl);
        }
        
        return $options;
    }
}

//$options = get_dropdown('users', 'id', 'name');
//$options = get_dropdown('users', 'id', 'name', ['status' => 1], 'User');
//$options = get_dropdown('users', 'id', 'name', [], '', false);
//$options = get_dropdown('users', 'id', 'name', [], 'Member', true, 86400);
//$options = get_dropdown('auth_groups', 'id', 'name', ['id' => 13], 'name');
// $options = get_dropdown('auth_groups', 'id', 'name,use_for', [
//     'status' => 0,
//     'id' => [
//         'operator' => 'IN',
//         'value' => [13]
//     ]
//     ], 'User Type');
if (!function_exists('get_value_by_id')) {
    function get_value_by_id(string $table, string $key, $id, string $value = 'name'): ?string
    {
        $db = \Config\Database::connect();
        $row = $db->table($table)
                  ->select($value)
                  ->where($key, $id)
                  ->get()
                  ->getRow();
        return $row ? $row->$value : null;
    }
}
//$roleName = get_values_by_ids('m_staff_roles', 'id', [1,3,5]);
// $productIds = [17];
// $productPrices = get_values_by_ids('schools', 'id', $productIds, 'school_name');

if (!function_exists('get_values_by_ids')) {
    function get_values_by_ids(string $table, string $key, array $ids, string $value = 'name'): array
        {
            $db = \Config\Database::connect();
            $rows = $db->table($table)
                    ->select("$key, $value")
                    ->whereIn($key, $ids)
                    ->get()
                    ->getResult();

            $output = [];
            foreach ($rows as $row) {
                $output[$row->$key] = $row->$value;
            }

            return $output;
        }
}
//get result passing id into it for ajax purpose

if (!function_exists('get_values_by_column')) {
    function get_values_by_column(string $table, string $whereColumn, $whereValue, string $key = 'id', string $value = 'name'): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table)->select("$key, $value");
        $builder->where($whereColumn, $whereValue);
        $builder->where('status', 0);
        $rows = $builder->get()->getResult();

        $output = [];
        foreach ($rows as $row) {
            $output[] = [
                'id' => $row->$key,
                'name' => $row->$value
            ];
        }

        return $output;
    }
}


