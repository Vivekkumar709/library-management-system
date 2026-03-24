<?php

if (!function_exists('get_user_group_data')) {
    /**
     * Get specific user group data by user ID
     * 
     * @param int $user_id User ID
     * @param string $column Column name to return (group_id, group_name, use_for, use_for_name)
     * @return mixed Column value or null if not found
     */
    function get_user_group_data($user_id, $column = 'group_name')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('auth_groups ag');
        
        $builder->select("ag.id AS group_id, ag.name AS group_name, 
                         ag.use_for AS use_for,
                         CASE
                             WHEN ag.use_for ='S' THEN 'School'
                             WHEN ag.use_for ='A' THEN 'Admin'
                             WHEN ag.use_for ='L' THEN 'Library'        
                             ELSE 'Undefine'
                         END AS use_for_name");
        
        $builder->join('auth_groups_users agu', 'ag.id = agu.group_id', 'left');
        $builder->where('agu.user_id', $user_id);
        $builder->groupBy('ag.id');
        
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            
            // Validate that the requested column exists
            $allowed_columns = ['group_id', 'group_name', 'use_for', 'use_for_name'];
            if (in_array($column, $allowed_columns) && property_exists($row, $column)) {
                return $row->$column;
            }
        }
        
        return null;
    }
}