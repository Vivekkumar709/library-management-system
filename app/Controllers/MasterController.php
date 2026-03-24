<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\MenuModel;

class MasterController extends BaseController
{   
    use ResponseTrait;
    public function getpaymentModes()
    {
               
        $this->title = 'Payment Modes';
        $this->content_view = 'masters/paymentModes'; 
        $data['data'] = get_records('m_payment_mode', [            
            'orderBy' => 'name ASC'            
        ]);  

        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [               
                ['title' => 'Payment Modes', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    } 
    public function getStaffType()
    {
               
        $this->title = 'Staff Type';
        $this->content_view = 'masters/staffType'; 
        $data['data'] = get_records('m_staff_types', [            
            'orderBy' => 'name ASC'            
        ]);  
                
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [               
                ['title' => 'Staff Type', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    } 
    public function getStaffRoles()
    {
               
        $this->title = 'Staff Roles';
        $this->content_view = 'masters/staffRoles'; 
        $data['data'] = get_records('m_staff_roles', [            
            'orderBy' => 'name ASC'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;  
        $this->thumbnails = [               
                ['title' => 'Staff Roles', 'url' => '', 'active' => true]
        ];      
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    } 
    public function getTeacherSecializationSubject()
    {
               
        $this->title = 'Teacher Secialization Subject';
        $this->content_view = 'masters/teacherSecializationSubject'; 
        $data['data'] = get_records('m_specialization', [            
            'orderBy' => 'name ASC'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;   
        $this->thumbnails = [               
                ['title' => 'Teacher Secialization Subject', 'url' => '', 'active' => true]
        ];      
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }  
    public function getClassSections()
    {
               
        $this->title = 'Class Sections';
        $this->content_view = 'masters/classSections'; 
        $data['data'] = get_records('m_sections', [            
            'orderBy' => 'name ASC'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;    
        $this->thumbnails = [               
                ['title' => 'Class Sections', 'url' => '', 'active' => true]
        ];     
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    } 
    public function getTypeSchool()
    {
               
        $this->title = 'School Type';
        $this->content_view = 'masters/typeSchool'; 
        $data['data'] = get_records('m_school_types', [            
            'orderBy' => 'name ASC'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;  
        $this->thumbnails = [               
                ['title' => 'School Type', 'url' => '', 'active' => true]
        ];      
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }   
    public function getSchoolTradition()
    {
               
        $this->title = 'School Tradition';
        $this->content_view = 'masters/schoolTradition'; 
        $data['data'] = get_records('m_school_tradition', [            
            'orderBy' => 'name ASC'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive; 
        $this->thumbnails = [               
                ['title' => 'School Tradition', 'url' => '', 'active' => true]
        ];         
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }  
    public function getSchoolMedium()
    {               
        $this->title = 'School Medium';
        $this->content_view = 'masters/schoolMedium'; 
        $data['data'] = get_records('m_school_mediums', [            
            'orderBy' => 'name ASC'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;   
        $this->thumbnails = [               
                ['title' => 'School Medium', 'url' => '', 'active' => true]
        ];      
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    } 
    public function getSchoolEducationLevel()
    {               
        $this->title = 'School Education Level';
        $this->content_view = 'masters/schoolEducationLevel'; 
        $data['data'] = get_records('m_education_levels', [            
            'orderBy' => 'id'            
        ]);                  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;   
        $this->thumbnails = [               
                ['title' => 'School Education Level', 'url' => '', 'active' => true]
        ];      
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    public function getTeachersDegree()
    {               
        $this->title = 'Teachers Degree';
        $this->content_view = 'masters/teachersDegree'; 
        // $data['data'] = get_records('m_education_levels', [            
        //     'orderBy' => 'id'            
        // ]);  
        $data['data'] = get_records('m_education', [
            'joins' => [
                ['table' => 'm_specialization', 'condition' => 'm_education.specialization_id = m_specialization.id']
            ],
            'select' => ['m_education.*', 'm_specialization.name AS specialization_stream'],   
            'orderBy' => 'm_education.name'          
        ]);                
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;  
        $this->thumbnails = [               
                ['title' => 'Teachers Degree', 'url' => '', 'active' => true]
        ];       
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    public function getSchoolAffilation()
    {               
        $this->title = 'School Affilation';
        $this->content_view = 'masters/schoolAffilation'; 
        $data['data'] = get_records('m_affiliation_boards', [            
            'orderBy' => 'id'            
        ]);                  
        //$data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive; 
        $this->thumbnails = [               
                ['title' => 'School Affilation', 'url' => '', 'active' => true]
        ];        
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }   

    public function getCities()
    {               
        $this->title = 'City';
        $this->content_view = 'masters/cities';         
        $data['data'] = get_records('cities', [
            'joins' => [
                ['table' => 'states', 'condition' => 'cities.state_id = states.id']
            ],
            'select' => ['cities.*', 'states.name AS state_name'],   
            'orderBy' => 'cities.city'          
        ]);                
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;  
        $this->thumbnails = [ 
                // ['title' => 'States', 'url' => site_url('plans')],              
                ['title' => 'City', 'url' => '', 'active' => true]
        ];       
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }

    public function updateStatus_OLD()
    {
         // Validate request method
         if (!$this->request->isAJAX()) {
            return $this->fail('Method not allowed', 405);
        }

        // Get input data
        $table = $this->request->getPost('tbl');
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        
        // Prepare tracking data
        $tracking = []; 
        
        if ($this->request->getPost('track_updates')) { 
            $tracking = ['track_updates' => 'Yes'];
        }

        // Validate required fields
        if (empty($table) || empty($id) || (empty($status) && $status != 0)) {
            return $this->fail('Missing required parameters'.$status, 400);
        }

        // Execute the toggle
        $result = $this->toggleStatusRecord($table, (int)$id, $status, $tracking);

        // Return response
        if ($result['success']) {
            return $this->respond($result);
        } else {
            return $this->fail($result['message'], 500);
        }

    }
    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Method not allowed', 405);
        }

        // Get input data
        $tables = $this->request->getPost('tbl');
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $primaryKey = $this->request->getPost('primary_key') ?? 'id';
        $relationsJson = $this->request->getPost('relations');
        $trackUpdates = $this->request->getPost('track_updates') ?? 'no'; // Default to 'no'

        // Validate required fields
        if (empty($tables) || empty($id) || (empty($status) && $status != 0)) {
            return $this->fail('Missing required parameters', 400);
        }

        // Parse relations
        $relations = [];
        if (!empty($relationsJson)) {
            $relations = json_decode($relationsJson, true) ?? [];
        }

        // Execute the update
        $result = $this->updateMultipleTablesStatus(
            $tables, 
            (int)$id, 
            (int)$status, 
            $primaryKey,
            $relations,
            $trackUpdates // Pass track_updates parameter
        );

        if ($result['success']) {
            return $this->respond($result);
        } else {
            return $this->fail($result['message'], 500);
        }
    }

     /**
     * Update status across multiple related tables
     */
    private function updateMultipleTablesStatus(
        string $tables, 
        int $id, 
        int $newStatus, 
        string $primaryKey,
        array $relations,
        string $trackUpdates = 'no' // Add track_updates parameter
    ): array {
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        try {
            $tableArray = array_map('trim', explode(',', $tables));
            $updatedTables = [];
            $primaryTable = $tableArray[0];
            
            // Validate all tables exist
            foreach ($tableArray as $table) {
                if (!$db->tableExists($table)) {
                    throw new \RuntimeException("Table '{$table}' does not exist");
                }
            }
            
            // Prepare update data with optional tracking
            $updateData = $this->prepareUpdateData($newStatus, $trackUpdates);
            
            // Step 1: Always update primary table
            $db->table($primaryTable)
               ->where($primaryKey, $id)
               ->update($updateData);
            $updatedTables[] = $primaryTable;
            
            // Step 2: Update related tables based on relationship configuration
            if (count($tableArray) > 1) {
                $relatedTables = array_slice($tableArray, 1);
                
                foreach ($relatedTables as $relatedTable) {
                    $relationConfig = $relations[$relatedTable] ?? null;
                    
                    if ($relationConfig) {
                        $this->updateRelatedTable(
                            $db, 
                            $relatedTable, 
                            $relationConfig, 
                            $id, 
                            $updateData
                        );
                        $updatedTables[] = $relatedTable;
                    }
                }
            }
            
            $db->transComplete();
            
            if (!$db->transStatus()) {
                throw new \RuntimeException('Transaction failed');
            }
            
            return [
                'success' => true,
                'new_status' => $newStatus,
                'updated_tables' => $updatedTables,
                'message' => 'Status updated successfully across ' . count($updatedTables) . ' table(s)'
            ];
            
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', "Multi-table update failed: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
     /**
     * Prepare update data with optional tracking fields
     */
    private function prepareUpdateData(int $newStatus, string $trackUpdates): array
    {
        $updateData = ['status' => $newStatus];
        
        // Add tracking fields if track_updates is enabled
        if ($trackUpdates === 'yes') {
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            
            // Add updated_by if user is logged in
            $userId = auth()->id();
            if ($userId) {
                $updateData['updated_by'] = $userId;
            }
        }        
        return $updateData;
    }
    /**
     * Update related table based on relationship configuration
     */
    private function updateRelatedTable($db, $relatedTable, $relationConfig, $primaryId, $updateData): bool
    {
        $foreignKey = $relationConfig['foreign_key'] ?? null;
        $referenceTable = $relationConfig['reference_table'] ?? null;
        $referenceColumn = $relationConfig['reference_column'] ?? null;
        
        if (!$foreignKey) {
            throw new \RuntimeException("Foreign key not defined for table: {$relatedTable}");
        }
        
        // If reference table and column are provided, we need to find the foreign key value
        if ($referenceTable && $referenceColumn) {
            // Get the foreign key value from the reference table
            $referenceRecord = $db->table($referenceTable)
                                ->select($referenceColumn)
                                ->where('id', $primaryId)
                                ->get()
                                ->getRow();
            
            if (!$referenceRecord) {
                throw new \RuntimeException("Reference record not found in {$referenceTable}");
            }
            
            $foreignKeyValue = $referenceRecord->$referenceColumn;
            
            // Update the related table using the foreign key value
            return $db->table($relatedTable)
                     ->where($foreignKey, $foreignKeyValue)
                     ->update($updateData);
                     
        } else {
            // Simple case: direct foreign key relationship
            return $db->table($relatedTable)
                     ->where($foreignKey, $primaryId)
                     ->update($updateData);
        }
    }    
    //=======================================================

     /**
     * Toggle status record
     * 
     * @param string $table
     * @param int $id
     * @param string $status
     * @param array $tracking
     * @return array
     */
    protected function toggleStatusRecord(
        string $table, 
        int $id,        
        int $newStatus,
        array $tracking = []
    ): array {
        $db = \Config\Database::connect();
        
        try {            
            if (!$db->tableExists($table)) {
                throw new \RuntimeException('Table does not exist');
            } 
            $update = ['status' => $newStatus]; 
            if (!empty($tracking['track_updates'])) {               
                $update['updated_at'] = date('Y-m-d H:i:s');                
                $update['updated_by'] = auth()->id() ?? null;                
            } 
            $updated = $db->table($table)
                         ->where('id', $id)
                         ->update($update);
    
            if (!$updated) {
                throw new \RuntimeException('No rows were updated');
            }    
            // Verify the update
            $row = $db->table($table)
                     ->select('status')
                     ->where('id', $id)
                     ->get()
                     ->getRow();    
            if (!$row) {
                throw new \RuntimeException('Failed to verify update');
            }    
            return [
                'success' => true,
                'new_status' => $row->status,
                'message' => 'Status updated successfully'
            ];
    
        } catch (\Throwable $e) {
            log_message('error', "Status toggle failed: {$e->getMessage()}");
            return [
                'success' => false,
                'new_status' => null,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getSchoolsWithDetails(array $filters = [])
    {
        $builder = $this->db->table('schools s')
            ->select('s.*, st.name as school_type, sm.name as school_medium')
            ->join('m_school_types st', 'st.id = s.school_type_id', 'left')
            ->join('m_school_mediums sm', 'sm.id = s.school_medium_id', 'left');
        
        // Apply filters if they exist
        if (!empty($filters['school_type'])) {
            $builder->where('s.school_type_id', $filters['school_type']);
        }
        
        if (!empty($filters['school_medium'])) {
            $builder->where('s.school_medium_id', $filters['school_medium']);
        }
        
        if (isset($filters['status']) && $filters['status'] !== '') {
            $builder->where('s.status', $filters['status']);
        }
        
        return $builder->get()->getResultArray();
    }

    public function getUserGroups()
    {               
        $this->title = 'User Type';
        $this->content_view = 'masters/userGroups/userGroup'; 
        // $data['data'] = get_records('auth_groups', [            
        //     'orderBy' => 'id'            
        // ]); 
        $filters = [
            'auth_groups.id' => ['operator' => 'NOT IN', 'value' => 1]
        ];
        $data['data'] = get_records('auth_groups', [
            'filters' => $filters,   
            'orderBy' => 'id'          
        ]);
                        
        //$data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive; 
        $this->thumbnails = [               
                ['title' => 'User Type', 'url' => '', 'active' => true]
        ];        
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    } 

    public function getSubjects()
    {               
        $this->title = 'Subject';
        $this->content_view = 'masters/subjects'; 
        $data['data'] = get_records('m_subjects', [            
            'orderBy' => 'id'            
        ]);                 
        
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;  
        $this->thumbnails = [               
                ['title' => 'Subject', 'url' => '', 'active' => true]
        ];        
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }     
    public function assign_user_type_menu_access($groupId = null)
    {               
        $this->title = 'Assign User Type Menu Access';
        $this->content_view = 'masters/assign_user_type_menu_access'; 
        $menuModel = new MenuModel();
        
        $data = [
            'menu' => $menuModel->getMenuTree(),
            'permissions' => get_records('auth_permissions', [            
                'orderBy' => 'id',
                'select' => ['id', 'name', 'display_name'],           
            ]),
            'loadResponsiveTable' => true,
            'distinctiveID' => $this->distinctive
        ];
        
        // If editing, fetch existing permissions
        if ($groupId) { 
            $findRecords = get_records('auth_groups', [
                'filters' => ['id' => $groupId,'status' => 0],
                'select' => ['id', 'name']
            ]); 
            if (empty($findRecords[0]['id'])) {
                return redirect()->to('/userTypes')->with('error', 'This record is not exists.');
            }                                     
            $existingPermissions = get_records('auth_groups_permissions', [
                'filters' => ['group_id' => $groupId],
                'select' => ['menu_id', 'permission_id']
            ]);           
            // Organize permissions by menu_id for easy access in view
            $permissionsByMenu = [];
            foreach ($existingPermissions as $perm) {
                $permissionsByMenu[$perm['menu_id']][$perm['permission_id']] = true;
            }   
            //echo $findRecords[0]['name']; die;         
            $data['data'] = [
                'id' => $groupId,
                'permissions' => $permissionsByMenu                
            ];
        }
        $this->thumbnails = [               
                ['title' => 'Assign User Type Menu Access', 'url' => '', 'active' => true]
        ];  
        $this->content_data = [
            'data' => $data,            
            'user_type' => $findRecords[0]['name']
        ];        
        return $this->render();
    }

    public function saveUserTypeMenuAccess()
    {   
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        if (!csrf_token() || !$this->request->getPost(csrf_token()) || 
            !hash_equals($this->request->getPost(csrf_token()), csrf_hash())) {
            return redirect()->back()->with('error', 'Invalid CSRF token.');
        }
        // Get the submitted data
        $postData = $this->request->getPost();
        $groupId = $this->request->getPost('group_id');
        $currentUserId = auth()->id(); // Assuming you have a way to get current user ID
        if (!$groupId) {
            return redirect()->back()->with('error', 'Group ID is required.');
        }
        // Initialize array to store permissions
        $permissionsToInsert = [];        
        foreach ($postData as $key => $value) {
            // Check if this is a permission checkbox (named like "permissionId_menuId")
            if (strpos($key, '_') !== false && $value == 'on') { // Checkboxes return 'on' when checked
                list($permissionId, $menuId) = explode('_', $key, 2);
                
                // Validate IDs are numeric
                if (!is_numeric($permissionId) || !is_numeric($menuId)) {
                    continue;
                }                
                $permissionsToInsert[] = [
                    'group_id' => $groupId,
                    'menu_id' => $menuId,
                    'permission_id' => $permissionId,
                    'status' => 0, 
                    'created_by' => $currentUserId,                    
                    'created_at' => date('Y-m-d H:i:s'),                    
                ];
            }
        }
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // First, HARD DELETE all existing permissions for this group
            $db->table('auth_groups_permissions')
                ->where('group_id', $groupId)
                ->delete(); // This permanently removes records

            // Only insert if there are permissions selected
            if (!empty($permissionsToInsert)) {
                $builder = $db->table('auth_groups_permissions');
                $builder->insertBatch($permissionsToInsert);
            }
            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to save permissions.');
            }
            return redirect()->to('userGroups') 
                ->with('success', 'Permissions updated successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Permission save error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error saving permissions. Please try again.');
        }
    }

    public function addUserGroup($id = null)
    {   
        $isEdit = !is_null($id);        
        $this->title = $id ? 'Edit User Group' : 'Add User Group';
        $this->content_view = 'masters/userGroups/add';
        $data = [            
            'isEdit' => $isEdit??null,             
        ];
        if($id){           
            $data['data'] = get_records('auth_groups', [            
                'orderBy' => 'id',
                'filters' => ['id'=>$id],//,'status'=>'0'         
            ]);
        }else{            
            $data['data'] = null;
        }

        $data['loadDatePicker'] = true; 
        $this->thumbnails = [ 
                ['title' => 'User Type', 'url' => site_url('userGroups')],              
                ['title' => $this->title, 'url' => '', 'active' => true]
        ];  
        $this->content_data = [
            'data' => $data,
            // 'breadcrumbs' => [                
            //     ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            // ]
        ];        
        return $this->render();
    }
    public function saveUserGroup()
    {
        // Check if it's a POST request
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        // Define validation rules
        $rules = [
            'name' => 'required|max_length[150]',
            'description' => 'required|max_length[200]',
            'use_for' => 'required|max_length[1]',                       
            'status' => 'integer',
        ];

        // Run validation
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get the database connection
        $db = \Config\Database::connect();
        $builder = $db->table('auth_groups');

        // Get form data
        $groupId = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name') ?? null,
            'description' => $this->request->getPost('description') ?? null, 
            'use_for' => $this->request->getPost('use_for') ?? null, 
            'status' => $this->request->getPost('status') ?? null,            
            'created_by' => auth()->id(),
        ];

        try {
            if ($groupId) {
                // Update existing group
                $builder->where('id', $groupId)->update($data);
                $message = 'User Group updated successfully!';
            } else {
                // Insert new group
                $builder->insert($data);
                $groupId = $db->insertID(); // Get the ID of the newly created group
                $message = 'User Group created successfully!';
            }

            return redirect()->to('/userGroups')->with('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'User Group save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error saving User Group: ' . $e->getMessage());
        }
    }

    public function getEmployementTypes()
    {
               
        $this->title = 'Employement Type';
        $this->content_view = 'masters/employementType'; 
        $data['data'] = get_records('m_employment_type', [            
            'orderBy' => 'name ASC'            
        ]);  
                
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                          
                ['title' => $this->title, 'url' => '', 'active' => true]
        ]; 
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    }

    public function getSectionType()
    {
               
        $this->title = 'Section Type';
        $this->content_view = 'masters/sectionType'; 
        $data['data'] = get_records('m_section_type', [            
            'orderBy' => 'name ASC'            
        ]);  
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive; 
        $this->thumbnails = [                          
                ['title' => $this->title, 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    } 

    public function getSpecialSectionCategory()
    {               
        $this->title = 'Special Section Category';
        $this->content_view = 'masters/specialSectionCategory'; 
        $data['data'] = get_records('m_special_section_category', [            
            'orderBy' => 'name ASC'            
        ]); 

        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                          
                ['title' => $this->title, 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    } 
    // SLOTS TIMING FOR ALL SCHOOLS
    public function getAllSchoolsTimeSlots()
    {          
        $this->title = 'Schools Periods Time Slots';
        $this->content_view = 'masters/allSchoolsTimeSlots'; 
        $data['data'] = get_records('schools_time_slots', [
            'joins' => [
                ['table' => 'schools', 'condition' => 'schools_time_slots.school_id = schools.id'],
                ['table' => 'm_schools_shift', 'condition' => 'schools_time_slots.shift_id = m_schools_shift.id']
            ],
            'select' => ['schools_time_slots.id','schools_time_slots.slot','schools_time_slots.status',
            'schools.school_name AS school_name','m_schools_shift.name as shift_name'],                       
            'filters' => ['schools_time_slots.status' => 0],
            'orderBy' => 'schools_time_slots.slot ASC'
        ]);                 
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [               
                ['title' => 'Schools Time Slots', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    // SHIFTS FOR ALL DEFAULT SHOULD BE 1
    public function getAllSchoolShift()
    {          
        $this->title = 'Schools Shift list';
        $this->content_view = 'masters/schoolsShifts';          
        $data['data'] = get_records('m_schools_shift', [            
            'orderBy' => 'id ASC'            
        ]);            
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [               
                ['title' => 'Schools Shift list', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    // SLOTS TIMING AS PER SCHOOLS
    public function getSchoolsTimeSlots()
    {          
        $this->title = 'Schools Periods Time Slots';
        $this->content_view = 'masters/schoolTimeSlots'; 
        $data['data'] = get_records('schools_time_slots', [
            'joins' => [
                ['table' => 'schools', 'condition' => 'schools_time_slots.school_id = schools.id'],
                ['table' => 'm_schools_shift', 'condition' => 'schools_time_slots.shift_id = m_schools_shift.id']
            ],
            'select' => ['schools_time_slots.id','schools_time_slots.slot','schools_time_slots.status',
            'schools.school_name AS school_name','m_schools_shift.name as shift_name'],                       
            'filters' => ['schools_time_slots.status' => 0,'schools_time_slots.school_id' => auth()->user()->school_id],
            'orderBy' => 'schools_time_slots.slot ASC'
        ]);                 
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Schools List', 'url' => site_url('schools')],
                ['title' => 'School Time Slots', 'url' => '', 'active' => true]
        ];
        
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    //CASTE CATEGORY LIST
    public function getCasteCategory()
    {               
        $this->title = 'Caste Category';
        $this->content_view = 'masters/casteCategory'; 
        $data['data'] = get_records('m_caste_categories', [            
            'orderBy' => 'id ASC'            
        ]); 

        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                          
                ['title' => $this->title, 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    } 
        
}