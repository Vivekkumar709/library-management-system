<?php

if (!function_exists('get_advanced_dropdown')) {
    /**
     * Advanced dropdown helper for complex queries with multiple tables
     */
    function get_advanced_dropdown(array $config): array
    {
        // Validate required parameters
        if (!isset($config['tables']) || empty($config['tables'])) {
            throw new InvalidArgumentException('Tables parameter is required');
        }

        $key = $config['key'] ?? 'id';
        $value = $config['value'] ?? 'name';
        $where = $config['where'] ?? [];
        $joins = $config['joins'] ?? [];
        $groupBy = $config['groupBy'] ?? null;
        $orderBy = $config['orderBy'] ?? null;
        $selectPostfix = $config['selectPostfix'] ?? '';
        $useCache = $config['useCache'] ?? true;
        $ttl = $config['ttl'] ?? 3600;

        // Generate cache key
        $cacheKey = null;
        if ($useCache) {
            $configHash = md5(json_encode($config));
            $cacheKey = "advanced_dropdown_{$configHash}";
            $cached = cache($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        $db = \Config\Database::connect();
        
        // Parse the first table
        $firstTable = $config['tables'][0];
        $tableParts = explode(' ', $firstTable);
        $mainTable = $tableParts[0];
        
        $builder = $db->table($mainTable);
        
        // If we have an alias, use it
        if (count($tableParts) > 1) {
            $builder->from($firstTable);
        }
        
        // Add joins if specified
        foreach ($joins as $join) {
            $joinTable = $join['table'];
            $joinCondition = $join['condition'];
            $joinType = $join['type'] ?? 'left';
            
            switch (strtoupper($joinType)) {
                case 'INNER':
                    $builder->join($joinTable, $joinCondition, 'inner');
                    break;
                case 'RIGHT':
                    $builder->join($joinTable, $joinCondition, 'right');
                    break;
                case 'OUTER':
                    $builder->join($joinTable, $joinCondition, 'outer');
                    break;
                default:
                    $builder->join($joinTable, $joinCondition, 'left');
                    break;
            }
        }
        
        // MANUALLY build the select statement to avoid CodeIgniter's field parsing issues
        $selectFields = [];
        
        // Process key field
        if (strpos($key, '.') !== false) {
            $selectFields[] = $key; // Keep as is for table.column format
        } else {
            $selectFields[] = $key;
        }
        
        // Process value field(s)
        $valueFields = array_map('trim', explode(',', $value));
        foreach ($valueFields as $field) {
            if (strpos($field, ' as ') !== false) {
                $selectFields[] = $field; // Keep aliases as is
            } elseif (strpos($field, '.') !== false) {
                $selectFields[] = $field; // Keep table.column format
            } else {
                $selectFields[] = $field;
            }
        }
        
        // Use raw select to avoid CodeIgniter's field parsing
        $builder->select(implode(', ', $selectFields), false);
        
        // Process where conditions
        foreach ($where as $field => $condition) {
            if (is_array($condition) && isset($condition['operator'])) {
                switch (strtoupper($condition['operator'])) {
                    case 'NOT IN':
                        $builder->whereNotIn($field, $condition['value']);
                        break;
                    case 'IN':
                        $builder->whereIn($field, $condition['value']);
                        break;
                    case 'BETWEEN':
                        $builder->whereBetween($field, $condition['value']);
                        break;
                    case 'NOT BETWEEN':
                        $builder->whereNotBetween($field, $condition['value']);
                        break;
                    default:
                        $builder->where($field . ' ' . $condition['operator'], $condition['value']);
                }
            } else {
                $builder->where($field, $condition);
            }
        }
        
        // Add group by if specified
        if ($groupBy) {
            $builder->groupBy($groupBy);
        }
        
        // Add order by if specified
        if ($orderBy) {
            $builder->orderBy($orderBy);
        }
        
        $results = $builder->get()->getResult();
        
        // Build options array
        if ($selectPostfix == 'Empty') {
            $options = [];
        } else {
            $options = ['' => 'Select' . ($selectPostfix ? ' ' . $selectPostfix : '')];
        }
        
        // Process the key field to extract just the column name
        $keyParts = explode('.', $key);
        $keyField = count($keyParts) > 1 ? $keyParts[1] : $keyParts[0];
        
        foreach ($results as $row) {
            $displayValues = [];
            
            // Process each value field
            foreach ($valueFields as $field) {
                // Extract the actual field name from possible aliases
                if (strpos($field, ' as ') !== false) {
                    $parts = explode(' as ', $field);
                    $fieldName = trim($parts[1]);
                } else {
                    $fieldParts = explode('.', $field);
                    $fieldName = count($fieldParts) > 1 ? $fieldParts[1] : $fieldParts[0];
                }
                
                if (isset($row->$fieldName)) {
                    $displayValues[] = trim($row->$fieldName);
                }
            }
            
            // Combine values if multiple fields were requested
            $displayValue = implode(' ', array_filter($displayValues));
            $options[$row->$keyField] = $displayValue;
        }
        
        // Cache the results if enabled
        if ($useCache && $cacheKey) {
            cache()->save($cacheKey, $options, $ttl);
        }
        
        return $options;
    }
}
// Example usage:
/*
$dropdownData = get_advanced_dropdown([
    'tables' => ['m_sections'],
    'joins' => [
        [
            'table' => 'sections',
            'condition' => 'm_sections.id = sections.section_id',
            'type' => 'left'
        ]
    ],
    'key' => 's.id',
    'value' => 'ms.name as section_name',
    'where' => [
        'ms.status' => 0,
        's.status' => 0,
        'class_id' => 15
    ],
    'orderBy' => 'ms.name ASC',
    'selectPostfix' => 'Section'
]);

//Multiple joins with complex conditions:

$users = get_advanced_dropdown([
    'tables' => ['users u'],
    'joins' => [
        [
            'table' => 'departments d',
            'condition' => 'u.department_id = d.id',
            'type' => 'left'
        ],
        [
            'table' => 'roles r',
            'condition' => 'u.role_id = r.id',
            'type' => 'inner'
        ]
    ],
    'key' => 'u.id',
    'value' => 'u.name, d.name as department, r.name as role',
    'where' => [
        'u.active' => 1,
        'd.status' => ['operator' => 'IN', 'value' => [1, 2, 3]],
        'r.level' => ['operator' => '>=', 'value' => 2]
    ],
    'orderBy' => 'u.name ASC'
]);

Using grouped conditions:

$products = get_advanced_dropdown([
    'tables' => ['products p'],
    'joins' => [
        [
            'table' => 'categories c',
            'condition' => 'p.category_id = c.id',
            'type' => 'left'
        ]
    ],
    'key' => 'p.id',
    'value' => 'p.name, c.name as category, p.price',
    'where' => [
        'p.status' => 1,
        'grouped_conditions' => [
            [
                'field' => 'p.price',
                'operator' => '>=',
                'value' => 50
            ],
            [
                'field' => 'p.stock',
                'operator' => '>',
                'value' => 0
            ]
        ]
    ],
    'orderBy' => 'c.name, p.name'
]);

*/