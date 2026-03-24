<?php
if (!function_exists('get_records')) {
    /**
     * Generic function to get records with optional joins and advanced filtering
     * 
     * @param string $mainTable Main table name
     * @param array $options Configuration options:
     *   - 'joins' => array of join configs (optional)
     *   - 'filters' => array of where conditions with operators (optional)
     *   - 'select' => array of columns to select (default: all)
     *   - 'groupBy' => string or array for GROUP BY (optional)
     *   - 'orderBy' => string or array for ORDER BY (optional)
     *   - 'limit' => int for LIMIT (optional)
     *   - 'offset' => int for OFFSET (optional)
     * @return array Result array
     */
    function get_records(string $mainTable, array $options = []): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($mainTable);
        
        // Apply joins if provided
        if (!empty($options['joins'])) {
            foreach ($options['joins'] as $join) {
                $type = $join['type'] ?? 'left';
                $builder->join($join['table'], $join['condition'], $type);
            }
        }
       
        if (!empty($options['filters'])) {
            $builder->groupStart(); // Start a group for default AND conditions
            
            foreach ($options['filters'] as $field => $value) {
                // Handle OR group conditions
                if ($field === 'OR') {
                    $builder->orGroupStart();
                    foreach ($value as $orField => $orValue) {
                        process_where_condition($builder, $orField, $orValue);
                    }
                    $builder->groupEnd(); 
                }
                // Handle AND group conditions
                elseif ($field === 'AND') {
                    $builder->groupStart();
                    foreach ($value as $andField => $andValue) {
                        process_where_condition($builder, $andField, $andValue);
                    }
                    $builder->groupEnd();
                    //echo $builder->getCompiledSelect();                    
                }
                // Handle individual conditions with operators
                else {
                    process_where_condition($builder, $field, $value);
                }
            }            
            $builder->groupEnd(); // Close the main group
        }
        
        // Set select columns
        $select = $options['select'] ?? ['*'];
        $builder->select($select);
        
        // Optional GROUP BY
        if (!empty($options['groupBy'])) {
            $builder->groupBy($options['groupBy']);
        }
        
        // Optional ORDER BY
        if (!empty($options['orderBy'])) {
            $builder->orderBy($options['orderBy']);
        }

        // Optional LIMIT and OFFSET
        if (isset($options['limit'])) {
            $builder->limit($options['limit'], $options['offset'] ?? 0);
        }
        $results = $builder->get()->getResultArray();
        if (!empty($options['single'])) {
            return $results[0] ?? null;
        }
        return $results;
        //return $builder->get()->getResultArray();
    }
}

if (!function_exists('process_where_condition')) {
    /**
     * Process individual where conditions with various operators
     * 
     * @param object $builder CodeIgniter query builder
     * @param string $field Field name
     * @param mixed $value Condition value or array with operator
     */
    function process_where_condition($builder, $field, $value)
    {
        if ($field === 'custom' && is_string($value)) {
            $builder->where($value);
            return;
        }
        
        // Handle custom SQL conditions (when value is null but field contains SQL operators)
        if ($value === null && (strpos($field, '(') !== false || stripos($field, ' OR ') !== false || stripos($field, ' AND ') !== false || stripos($field, ' = ') !== false)) {
            $builder->where($field);
            return;
        }
        // If value is an array with operator specification
        if (is_array($value) && isset($value['operator'])) {
            $operator = strtoupper($value['operator']);
            $val = $value['value'] ?? null;
            
            switch ($operator) {
                case 'IN':
                    $builder->whereIn($field, (array)$val);
                    break;
                case 'NOT IN':
                    $builder->whereNotIn($field, (array)$val);                    
                    break;
                case 'BETWEEN':
                    $builder->where("$field BETWEEN", $val[0] . " AND " . $val[1]);
                    break;
                case 'NOT BETWEEN':
                    $builder->where("$field NOT BETWEEN", $val[0] . " AND " . $val[1]);
                    break;
                case 'IS NULL':
                    $builder->where("$field IS NULL");
                    break;
                case 'IS NOT NULL':
                    $builder->where("$field IS NOT NULL");
                    break;
                case 'LIKE':
                    $builder->like($field, $val);
                    break;
                case 'NOT LIKE':
                    $builder->notLike($field, $val);
                    break;
                default:
                    // Standard operators: =, !=, <, >, <=, >=
                    $builder->where("$field $operator", $val);
                    break;
            }
        }
        // Simple array value (treated as IN condition for backward compatibility)
        elseif (is_array($value)) {
            $builder->whereIn($field, $value);
            
        }
        // Simple value (equals condition)
        else {
            $builder->where($field, $value);
        }
    }
}

/*
Simple query without joins:
// Get all active schools
$schools = get_records('schools', [
    'filters' => ['status' => 'active'],
    'orderBy' => 'name ASC',
    'limit' => 10
]);

Query with joins:
// Get schools with their details
$schools = get_records('schools', [
    'joins' => [
        ['table' => 'school_details', 'condition' => 'schools.id = school_details.school_id']
    ],
    'select' => ['schools.*', 'school_details.address', 'school_details.phone'],
    'filters' => ['schools.status' => 'active']
]);

Complex query with multiple joins:
// Get schools with details and district information
$schools = get_records('schools', [
    'joins' => [
        ['table' => 'school_details', 'condition' => 'schools.id = school_details.school_id'],
        ['table' => 'districts', 'condition' => 'schools.district_id = districts.id', 'type' => 'inner']
    ],
    'select' => ['schools.name', 'school_details.address', 'districts.name AS district_name'],
    'filters' => [
        'schools.type' => 'public',
        'districts.region_id' => 5
    ],
    'orderBy' => 'schools.name ASC'
]);

Count records:
// Count active schools
$count = get_records('schools', [
    'select' => ['COUNT(*) AS total'],
    'filters' => ['status' => 'active']
])[0]['total'];
*/

/* 
$filters = [
    'status' => ['operator' => 'IN', 'value' => ['active', 'pending']]
];
$filters = [
    'category_id' => ['operator' => 'NOT IN', 'value' => [1, 2, 3]]
];
$filters = [
    'OR' => [
        'status' => ['operator' => 'IN', 'value' => ['active', 'pending']],
        'created_at' => ['operator' => '>=', 'value' => '2023-01-01']
    ]
];
$filters = [
    'type' => 'product',
    'price' => ['operator' => '>=', 'value' => 100],
    'category_id' => ['operator' => 'NOT IN', 'value' => [5, 6]],
    'OR' => [
        'status' => 'active',
        'is_featured' => 1
    ]
];
$filters = [
    'AND' => [
        'status' => 'active',
        'OR' => [
            'category_id' => ['operator' => 'IN', 'value' => [1, 2]],
            'tags' => ['operator' => 'LIKE', 'value' => '%sale%']
        ]
    ]
];

*/
