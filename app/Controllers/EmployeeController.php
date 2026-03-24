<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\MenuModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EmployeeController extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function addEmployee($id = null){

        $this->title = $id ? 'Edit Employee' : 'Add Employee';
        $this->content_view = 'employee/addEmployee';
        $users = model(UserModel::class);         
        $data = [
            'data' => $id ? $this->userModel->getUserWithDetails($id) : null, 
            'planType' => get_dropdown('plan_type', 'id', 'name', ['status' => 0],'Plan Type'),
            'planTenure' => get_dropdown('plan_tenure', 'id', 'name', ['status' => 0],'Tenure'),            
            'planServices' => get_dropdown('plan_services', 'id', 'name', ['status' => 0],'Empty'),//Services     
        ];
        $this->thumbnails = [
                ['title' => 'Employees List', 'url' => site_url('employeesList')],
                ['title' => 'Add Employee', 'url' => '', 'active' => true]
        ];
        $data['loadDatePicker'] = true; 
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Employees List', 'url' => site_url('/employeesList')],
                ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }   

    public function getEmplyees()
    {
               
        $this->title = 'Employees List';
        $this->content_view = 'employee/employeeList';
        //echo auth()->user()->school_id; die;

        $filters = [
            'users.designation_id' => ['operator' => 'NOT IN', 'value' => FACULTY_ID],
            'users.user_type_id' => ['operator' => 'NOT IN', 'value' => FACULTY_GROUP_ID]
        ];
        
        // Only add custom condition if it's not null
        if(isset(auth()->user()->school_id) && auth()->user()->school_id != null && auth()->user()->school_id != '') {            
            $condition = "(users.school_id = " . auth()->user()->school_id . ")";
            $filters['custom'] = $condition;
        }        
        elseif(auth()->id() == 1 || (auth()->user()->user_type_id == 1)) {
        } 
        else {            
            $condition = "(users.id = " . auth()->id() . ")";
            $filters['custom'] = $condition;
        }
        $data['data'] = get_records('users', [
            'joins' => [
                ['table' => 'schools', 'condition' => 'users.school_id = schools.id'],
                ['table' => 'auth_groups', 'condition' => 'users.user_type_id = auth_groups.id'],
                ['table' => 'auth_identities', 'condition' => 'users.id = auth_identities.user_id']                
            ],              
            'filters' => $filters,              
            'select' => [
                'users.*', 
                'schools.school_name AS school_name',               
                'auth_groups.name AS user_type',
                'auth_groups.use_for AS use_for',
                'auth_identities.secret as emailId',                
                "(SELECT STRING_AGG(msr.name, ', ') 
                 FROM m_staff_roles msr
                 WHERE msr.id::TEXT = ANY(STRING_TO_ARRAY(users.designation_id, ','))
                ) AS designation"
            ],   
            'orderBy' => 'users.id'          
        ]);         
        
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Employees List', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }

    public function create()
    {
        try {
            $users = model(UserModel::class);
            $data = $this->request->getPost();
            $id = $data['id'] ?? null;
            $db = \Config\Database::connect();

            // ==================== VALIDATION ====================
            $rules = [                
                'first_name'          => 'required|max_length[100]',
                'last_name'           => 'permit_empty|max_length[100]',                
                'mobile'              => 'required|numeric|exact_length[10]',                
                'about'               => 'permit_empty|max_length[255]',                           
                'permanent_address'   => 'required|max_length[255]',
                'permanent_state'     => 'required|numeric',
                'permanent_city'      => 'required|numeric',
                'permanent_pincode'   => 'required|numeric|exact_length[6]',
                'permanent_landmark'  => 'required|max_length[100]',
                'present_address'     => 'required|max_length[255]',
                'present_landmark'    => 'required|max_length[100]',
                'present_state'       => 'required|numeric',
                'present_city'        => 'required|numeric',
                'present_pincode'     => 'required|numeric|exact_length[6]',
                'user_type_id'        => 'required|numeric',
                'school_id'           => 'permit_empty|numeric',
                'designation_id'      => 'permit_empty|numeric',
            ];

            if (empty($id)) {
                $rules['password'] = 'required|min_length[8]';
            }           

            $messages = [
                'email' => [
                    'required' => 'Email address is required',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email is already registered',
                    'max_length' => 'Email cannot exceed 254 characters'
                ],
                'username' => [
                    'required' => 'Username is required',
                    'is_unique' => 'Username already exists'
                ],
                'first_name' => [
                    'required' => 'First name is required',
                    'max_length' => 'First name cannot exceed 100 characters'
                ],
                'last_name' => [                    
                    'max_length' => 'Last name cannot exceed 100 characters'
                ],
                'mobile' => [
                    'required' => 'Mobile number is required',
                    'numeric' => 'Mobile number must contain only numbers',
                    'exact_length' => 'Mobile number must be 10 digits'
                ],                
                'about' => [
                    'max_length' => 'About cannot exceed 255 characters'
                ],
                'profile_image' => [
                    'uploaded' => 'Please select an image to upload',
                    'mime_in' => 'Only JPG, JPEG, PNG or WEBP images are allowed',
                    'max_size' => 'Image size must be less than 2MB',
                    'is_image' => 'The file must be a valid image'
                ],
                'permanent_pincode' => [
                    'numeric' => 'Pincode must contain only numbers',
                    'exact_length' => 'Pincode must be 6 digits'
                ],
                'present_pincode' => [
                    'numeric' => 'Pincode must contain only numbers',
                    'exact_length' => 'Pincode must be 6 digits'
                ],
                'password' => [
                    'min_length' => 'Password must be at least 8 characters long'
                ],
                'user_type_id' => [
                    'required' => 'User type is required',
                    'numeric' => 'Invalid user type selected'
                ],
                'school_id' => [                    
                    'numeric' => 'Invalid school selected'
                ],
                'designation_id' => [                    
                    'numeric' => 'Invalid designation selected'
                ],
            ];
            if ($id) {
                $rules['email'] = "required|valid_email|max_length[254]|is_unique[users.email,id,{$id}]";
            } else {
                $rules['email'] = "required|valid_email|max_length[254]|is_unique[users.email]";
            }
            // Password rules
            if (!empty($data['password'])) {                
                $rules['username'] = 'required' . ($id ? '' : '|is_unique[users.username]');
                $rules['profile_image'] = [
                                            'if_exist',
                                            ($id ? 'permit_empty' : 'uploaded[profile_image]'),
                                            'mime_in[profile_image,image/jpg,image/jpeg,image/png,image/webp]',
                                            'max_size[profile_image,2048]',
                                            'is_image[profile_image]',
                                          ];
                $rules['password'] = 'required|min_length[8]';
                $rules['password_confirm'] = 'required|matches[password]';
            }
            if (!$this->validate($rules, $messages)) {
                throw new \RuntimeException(implode('<br>', $this->validator->getErrors()));
            }
            // ==================== FILE UPLOAD HANDLING ====================
            $file = $this->request->getFile('profile_image');
            $profileImage = null;

            if ($id) {
                $user = $users->find($id);
                if (!$user) {
                    throw new \RuntimeException('User not found');
                }
                $profileImage = $user->profile_image;
            }

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/profile_images/';
                $filePath   =          'uploads/profile_images/';
                
                if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
                    throw new \RuntimeException('Failed to create upload directory');
                }

                if ($profileImage && file_exists($uploadPath . $profileImage)) {
                    unlink($uploadPath . $profileImage);
                }

                $newName = $file->getRandomName();
                if (!$file->move($uploadPath, $newName)) {
                    throw new \RuntimeException('Failed to upload profile image');
                }
                $profileImage = $filePath.$newName;
            }

            // ==================== USER DATA PREPARATION ====================
            $userData = [               
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],                
                'mobile' => $data['mobile'], 
                'email_id'=> $data['email'],                           
                'permanent_address' => $data['permanent_address'] ?? null,
                'permanent_state' => $data['permanent_state'] ?? null,
                'permanent_city' => $data['permanent_city'] ?? null,
                'permanent_pincode' => $data['permanent_pincode'] ?? null,
                'permanent_landmark' => $data['permanent_landmark'] ?? null,
                'present_address' => $data['present_address'] ?? null,
                'present_landmark' => $data['present_landmark'] ?? null,
                'present_state' => $data['present_state'] ?? null,
                'present_city' => $data['present_city'] ?? null,
                'present_pincode' => $data['present_pincode'] ?? null,
                'user_type_id' => $data['user_type_id'],
                'school_id' => trim($data['school_id']) ?: null,
                'designation_id' => trim($data['designation_id']) ?: null,
                'about' => $data['about'] ?? null,                   
                'profile_image' => $profileImage,

            ];
            
            if(empty($id)){
                $userData['username'] = $data['username'];
                $userData['email'] = $data['email'];
                $userData['email_id'] = $data['email'];
                $userData['password'] = $data['password']; 
                $userData['paswd'] = $data['password'];
                $userData['status'] = 0;
            }

            // ==================== SHIELD AUTHENTICATION HANDLING ====================
            $db->transStart();
            if ($id) {
                // UPDATE EXISTING USER
                $user = $users->find($id);
                if (!$user) {
                    throw new \RuntimeException('User not found');
                }
                // Update basic user data
                $userData['updated_by'] = auth()->id(); 
                $user->fill($userData);                
                
                // Save user
                $users->protect(false);                
                if (!$users->save($user)) {
                    throw new \RuntimeException('Failed to update user');
                } 
                $users->protect(true); 
                // Update group association

                $db->table('auth_groups_users')
                ->where('user_id', $id)
                ->set('group_id', $data['user_type_id'])
                ->update();
                //=====================================
                // Update email in auth_identities table
                $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
                $identity = $identityModel->where('user_id', $id)
                                        ->where('type', 'email_password')
                                        ->first();                                        

                if ($identity) {
                    $identityModel->update($identity->id, [
                        'secret' => $data['email']
                    ]);
                } else {
                    // If no identity exists (shouldn't happen for existing users), create one
                    $identityModel->insert([
                        'user_id' => $id,
                        'type' => 'email_password',
                        'secret' => $data['email'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                //=====================================
                $message = 'User updated successfully!';
                
            } else {
                // CREATE NEW USER
                $userData['created_by'] = auth()->id();
                $user = new User($userData);
                $user->password = $data['password'];
                $users->protect(false);
                
                if (!$users->save($user)) {
                    throw new \RuntimeException('Failed to create user');
                }

                // Now $user->id should be set (or get it from $users->getInsertID())
                $userId = $user->id ?? $users->getInsertID();
                if (!$userId) {
                    throw new \RuntimeException('User was not saved properly, no ID returned.');
                }
                $user->id = $userId;

                // Now you can safely call these methods                
                // $user->createEmailIdentity([
                //     'email' => $data['email'],
                //     'password' => $data['password']
                // ]);
                //$user->addGroup('user');
                // Add to group based on user_type_id
                $db->table('auth_groups_users')->insert([
                    'user_id' => $userId,
                    'group_id' => $data['user_type_id'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $message = 'User created successfully!';
                $users->protect(true);
            }
            $db->transComplete(); // Commit transaction
            return redirect()->to('employeesList')->with('success', $message);

        } catch (\RuntimeException $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            log_message('error', 'User create error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An unexpected error occurred'.$e->getMessage());
        }
    }

    public function resetPassword()
    {
        try {
            $data = $this->request->getJSON(true);            
            if (!$data) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
            }            
            $userId = $data['user_id'] ?? null;
            $newPassword = $data['new_password'] ?? null;            
            if (!$userId || !$newPassword) {
                return $this->response->setJSON(['success' => false, 'message' => 'Missing required data']);
            }            
            $users = model(UserModel::class);
            $user = $users->find($userId);            
            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
            }
            // Get current timestamp
            $currentTime = date('Y-m-d H:i:s');
            $currentUserId = auth()->id(); // Get logged-in user ID
            
            // Start database transaction
            $db = \Config\Database::connect();
            $db->transStart();
            
            try {
                // Update user password and timestamps
                $users->protect(false);
                $user->password = $newPassword;
                $user->paswd = $newPassword;
                $user->updated_at = $currentTime;
                $user->updated_by = $currentUserId;
                
                if (!$users->save($user)) {
                    throw new \RuntimeException('Failed to update user password');
                }
                $users->protect(true);
                
                // Update auth_identities table
                $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
                $identityModel->where('user_id', $userId)
                            ->where('type', 'email_password')
                            ->set([
                                'secret2' => password_hash($newPassword, PASSWORD_DEFAULT),
                                'updated_at' => $currentTime
                            ])
                            ->update();
                
                // Commit transaction
                $db->transComplete();
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Password reset successfully'
                ]);                
                
            } catch (\Exception $e) {
                $db->transRollback();
                log_message('error', 'Password reset error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Failed to update password: ' . $e->getMessage()
                ]);
            }
            // // Update password  
            // $users->protect(false);         
            // $user->password = $newPassword;
            // $user->paswd = $newPassword;            
            // if ($users->save($user)) { 
            //     $users->protect(true); 
            //     return $this->response->setJSON(['success' => true, 'message' => 'Password reset successfully']);                
            // } else {
            //     return $this->response->setJSON(['success' => false, 'message' => 'Failed to update password']);
            // }            
        } catch (\Exception $e) {
            log_message('error', 'Password reset error: ' . $e->getMessage());            
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred']);
        }
    }
    public function deleteUser($userId)
    {         
        // 1. Load all required models
        $userModel = model('UserModel');
        $identityModel = model('UserIdentityModel');
        $loginModel = model('LoginModel');
        
        // 2. Start transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // First get user data before deletion
            $user = $userModel->find($userId);
            if (!$user) {
                throw new \Exception('User not found');
            }

            // 3. Delete profile image if exists
            if (!empty($user->profile_image)) {
                $imagePath = FCPATH . '/' . $user->profile_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the physical file
                }
            }

            // 4. Delete from related tables
            $identityModel->where('user_id', $userId)->delete();
            $loginModel->where('user_id', $userId)->delete();
            $db->table('auth_groups_users')->where('user_id', $userId)->delete();
            
            // 5. Finally delete from users table
            $userModel->delete($userId);
            
            // 6. Commit if all successful
            $db->transComplete();
            
            // Set flashdata with success message
            session()->setFlashdata('success', 'User deleted successfully');
            //return redirect()->to('/users'); // Use explicit redirect path
            return redirect()->back();
            
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Deletion failed: '.$e->getMessage());
            return redirect()->back();
        }
    }
    public function assign_user_menu_access($userId = null)
    {               
        $this->title = 'Assign User Type Menu Access';
        $this->content_view = 'employee/assign_user_menu_access'; 
        $menuModel = new MenuModel();
        
        $data = [
            'menu' => $menuModel->getMenuTreeForUser(),
            'permissions' => get_records('auth_permissions', [            
                'orderBy' => 'id',
                'select' => ['id', 'name', 'display_name'],           
            ]),
            'loadResponsiveTable' => true,
            'distinctiveID' => $this->distinctive
        ];
        
        // If editing, fetch existing permissions
        if ($userId) {              
            $findRecords = get_records('users', [
                'joins' => [
                    ['table' => 'auth_groups', 'condition' => 'users.user_type_id = auth_groups.id']
                ],
                'select' => ['users.id','users.user_type_id','users.first_name','users.last_name','auth_groups.name AS user_type'],
                'filters' => [
                    'users.id' => $userId,
                    'users.status' => '0'
                ],                               
            ]);
            if (empty($findRecords[0]['id'])) {
                return redirect()->to('/employeesList')->with('error', 'This record is not exists.');
            }                                     
            $existingPermissions = get_records('auth_groups_users_permissions', [
                'filters' => ['user_id' => $userId],
                'select' => ['menu_id', 'permission_id']
            ]);           
            // Organize permissions by menu_id for easy access in view
            $permissionsByMenu = [];
            foreach ($existingPermissions as $perm) {
                $permissionsByMenu[$perm['menu_id']][$perm['permission_id']] = true;
            }                       
            $data['data'] = [
                'id' => $userId,
                'permissions' => $permissionsByMenu                
            ];
        }
        $this->thumbnails = [
                // ['title' => 'Teachers List', 'url' => site_url('teachers')],
                ['title' => 'Edit User Type', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
            'user_type' => $findRecords[0]['user_type'],
            'user_full_name' => $findRecords[0]['first_name'].' '.$findRecords[0]['last_name'],
            'user_type_id' => $findRecords[0]['user_type_id'],
            
        ];        
        return $this->render();
    }
    public function saveUserMenuAccess()
    {   
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        
        // CSRF validation
        if (!hash_equals($this->request->getPost(csrf_token()), csrf_hash())) {
            return redirect()->back()->with('error', 'Invalid CSRF token.');
        }
        
        // Get the submitted data
        $postData = $this->request->getPost();
        $groupId = $this->request->getPost('group_id');
        $userId = $this->request->getPost('user_id');
        
        if (!$userId || !$groupId) {
            return redirect()->back()->with('error', 'User ID and Group ID are required.');
        }
        
        $currentUserId = auth()->id();
        $permissionsToInsert = [];        
        
        foreach ($postData as $key => $value) {
            if (strpos($key, '_') !== false && $value == 'on') {
                list($permissionId, $menuId) = explode('_', $key, 2);               
                
                if (!is_numeric($permissionId) || !is_numeric($menuId)) {
                    continue;
                }                
                
                $permissionsToInsert[] = [
                    'user_id' => $userId,
                    'group_id' => $groupId,
                    'menu_id' => $menuId,
                    'permission_id' => $permissionId,
                    'status' => 0, 
                    'created_by' => $currentUserId,                    
                    'created_at' => date('Y-m-d H:i:s'),                    
                ];
                
            }
           
        }        
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete existing permissions
            $deleteResult = $db->table('auth_groups_users_permissions') // Make sure this matches your actual table
                ->where('group_id', $groupId)
                ->where('user_id', $userId)
                ->delete();
            
            // Insert new permissions if any
            if (!empty($permissionsToInsert)) {
                $insertResult = $db->table('auth_groups_users_permissions')
                    ->insertBatch($permissionsToInsert);
                    
                if (!$insertResult) {
                    throw new \Exception('Insert batch failed');
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed');
                return redirect()->back()->with('error', 'Failed to save permissions.');
            }
            
            return redirect()->to('employeesList') 
                ->with('success', 'Permissions updated successfully.');

        } catch (\Exception $e) {            
            $db->transRollback();
            $error = $db->error(); 
            log_message('error', 'Database error: '.print_r($error, true));
            log_message('error', 'Permission save error: ' .$e->getMessage());
            log_message('debug', 'Post data: ' . print_r($postData, true));
            log_message('debug', 'Permissions to insert: ' . print_r($permissionsToInsert, true));
            return redirect()->back()->with('error', 'Error saving permissions1: '. ($error['message'] ?? $e->getMessage()));
        }
    }
    //GET TEACHERS LIST
    public function getTeachers()
    {               
        $this->title = 'Teachers List';
        $this->content_view = 'employee/teachersList'; 
        $filters = [];
        
        if(isset(auth()->user()->school_id) && auth()->user()->school_id != null && auth()->user()->school_id != ''){
            $filters['users.school_id'] = auth()->user()->school_id;
        }
        elseif (auth()->id() == 1 && auth()->user()->user_type_id == 1) {

        }else{
            $filters['users.id'] = auth()->id();             
        }
        $filters['users.user_type_id'] = FACULTY_GROUP_ID;        
        $data['data'] = get_records('users', [
            'joins' => [
                ['table' => 'schools', 'condition' => 'users.school_id = schools.id'],				
                ['table' => 'auth_groups', 'condition' => 'users.user_type_id = auth_groups.id'],
                ['table' => 'auth_identities', 'condition' => 'users.id = auth_identities.user_id'],                              
            ],              
           'select' => [
                        'users.*', 
                        'schools.school_name AS school_name',
                        'auth_groups.use_for AS use_for',
                        'auth_groups.name AS user_type',
                        'auth_identities.secret as emailId',
                        "(SELECT STRING_AGG(m_staff_roles.name, ', ') 
                        FROM m_staff_roles 
                        WHERE m_staff_roles.id::text = ANY(STRING_TO_ARRAY(users.designation_id, ','))) AS designation"
                    ],    
            'orderBy' => 'users.id',
            'filters' => $filters 
            //'filters'=>['users.school_id'=>auth()->user()->school_id,'users.user_type_id'=>FACULTY_GROUP_ID]         
        ]);
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [               
                ['title' => 'Teachers List', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,           
        ];        
        return $this->render();
    }
    public function addTeachers($id = null){
        $user = auth()->user();
        $schoolIds = [$user->school_id];

        if(empty($user->school_id) || $user->school_id == null){
            throw PageNotFoundException::forPageNotFound('You are not registered with any school!!!');
        }        
        $this->title = $id ? 'Edit Teacher' : 'Add Teacher';
        $this->content_view = 'employee/addTeacher';
        $users = model(UserModel::class);                
        $data = [
            'data' => $id ? $this->userModel->getTeachersWithDetails($id) : null, 
            'permanentState' => get_dropdown('states', 'id', 'name', ['status' => 0],'Permanent State'),
            'presentState' => get_dropdown('states', 'id', 'name', ['status' => 0],'Present State'),
            'userType' => get_dropdown('auth_groups', 'id', 'name', ['id' => FACULTY_GROUP_ID], 'User Type'),
            'employementType' => get_dropdown('m_employment_type', 'id', 'name', ['status' => 0], 'Employement Type'),
            'specializationSubjects' => get_dropdown('m_specialization', 'id', 'name', ['status' => 0], 'Empty'),
            'preferedTeachingLevel' => get_dropdown('m_education_levels', 'id', 'name', ['status' => 0], 'Prefered Teaching Level'),
            'highestQualification' => get_dropdown('m_education', 'id', 'name', ['status' => 0], 'Highest Qualification'),            
            'designations' => get_dropdown('m_staff_roles', 'id', 'name',             
            [
            'status' => 0,
            'id' => [
                        'operator' => 'IN',
                        'value' => FACULTY_ID,
                    ]
            ],
            'Empty'), 
            'schools' => get_values_by_ids('schools', 'id', $schoolIds, 'school_name'),     
        ];        
        $data['loadDatePicker'] = true; 
        $this->thumbnails = [
                ['title' => 'Teachers List', 'url' => site_url('teachers')],
                ['title' => 'Add Teacher', 'url' => '', 'active' => true]
        ];

        // $this->thumbnails = [
        //         ['title' => 'Teachers List', 'url' => site_url('teachers')],
        //         ['title' => 'Add Teacher', 'url' => '', 'active' => true]
        // ];

        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }     
    public function createTeacher()
    {
        try {
            $users = model(UserModel::class);
            $data = $this->request->getPost();
            
            $id = $data['id'] ?? null;            
            $db = \Config\Database::connect();
            // ==================== VALIDATION ====================
            $rules = [                
                'first_name'          => 'required|max_length[100]',
                'last_name'           => 'permit_empty|max_length[100]',                
                'mobile'              => 'required|numeric|exact_length[10]',                
                'about'               => 'permit_empty|max_length[255]',                           
                'permanent_address'   => 'required|max_length[255]',
                'permanent_state'     => 'required|numeric',
                'permanent_city'      => 'required|numeric',
                'permanent_pincode'   => 'required|numeric|exact_length[6]',
                'permanent_landmark'  => 'required|max_length[100]',
                'present_address'     => 'required|max_length[255]',
                'present_landmark'    => 'required|max_length[100]',
                'present_state'       => 'required|numeric',
                'present_city'        => 'required|numeric',
                'present_pincode'     => 'required|numeric|exact_length[6]',
                'user_type_id'        => 'required|numeric',
                'school_id'           => 'permit_empty|numeric',
                'designation_id'      => 'permit_empty', 
                'designation_id.*'    => 'integer',                  
                'employement_type_id'            => 'required|numeric',             
                'specialization_subject_ids'     => 'required',
                'specialization_subject_ids.*'   => 'integer',
                'prefered_teaching_level_id'     => 'required|numeric',
                'highest_qualification_id'       => 'required|numeric',
                'service_start_from'             => 'required|date',
                'approval_status'                => 'required|max_length[70]',                
            ];

            if (empty($id)) {
                $rules['password'] = 'required|min_length[8]';
            }
            $messages = [
                'email' => [
                    'required' => 'Email address is required',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email is already registered',
                    'max_length' => 'Email cannot exceed 254 characters'
                ],
                'username' => [
                    'required' => 'Username is required',
                    'is_unique' => 'Username already exists'
                ],
                'first_name' => [
                    'required' => 'First name is required',
                    'max_length' => 'First name cannot exceed 100 characters'
                ],
                'last_name' => [                    
                    'max_length' => 'Last name cannot exceed 100 characters'
                ],
                'mobile' => [
                    'required' => 'Mobile number is required',
                    'numeric' => 'Mobile number must contain only numbers',
                    'exact_length' => 'Mobile number must be 10 digits'
                ],                
                'about' => [
                    'max_length' => 'About cannot exceed 255 characters'
                ],
                'profile_image' => [
                    'uploaded' => 'Please select an image to upload',
                    'mime_in' => 'Only JPG, JPEG, PNG or WEBP images are allowed',
                    'max_size' => 'Image size must be less than 2MB',
                    'is_image' => 'The file must be a valid image'
                ],
                'permanent_pincode' => [
                    'numeric' => 'Pincode must contain only numbers',
                    'exact_length' => 'Pincode must be 6 digits'
                ],
                'present_pincode' => [
                    'numeric' => 'Pincode must contain only numbers',
                    'exact_length' => 'Pincode must be 6 digits'
                ],
                'password' => [
                    'min_length' => 'Password must be at least 8 characters long'
                ],
                'user_type_id' => [
                    'required' => 'User type is required',
                    'numeric' => 'Invalid user type selected'
                ],
                'school_id' => [                    
                    'numeric' => 'Invalid school selected'
                ],
                'designation_id' => [                    
                    'numeric' => 'Invalid designation selected'
                ],
                'employement_type_id' => [
                    'required' => 'Employment type is required',
                    'numeric' => 'Invalid employment type selected'
                ],
                'specialization_subject_ids' => [
                    'required' => 'Specialization subjects are required',
                ],
                'specialization_subject_ids.*' => [
                    'integer' => 'Invalid specialization subject selected'
                ],
                'prefered_teaching_level_id' => [
                    'required' => 'Preferred teaching level is required',
                    'numeric' => 'Invalid teaching level selected'
                ],
                'highest_qualification_id' => [
                    'required' => 'Highest qualification is required',
                    'numeric' => 'Invalid qualification selected'
                ],
                'service_start_from' => [
                    'required' => 'Service start date is required',
                    'date' => 'Invalid date format for service start date'
                ],
                'approval_status' => [
                    'required' => 'Approval status is required',
                    'max_length' => 'Approval status cannot exceed 70 characters'
                ],
            ];
            if ($id) {
                $rules['email'] = "required|valid_email|max_length[254]|is_unique[users.email,id,{$id}]";
            } else {
                $rules['email'] = "required|valid_email|max_length[254]|is_unique[users.email]";
            }
            // Password rules
            if (!empty($data['password'])) {                
                $rules['username'] = 'required' . ($id ? '' : '|is_unique[users.username]');
                $rules['profile_image'] = [
                                            'if_exist',
                                            ($id ? 'permit_empty' : 'uploaded[profile_image]'),
                                            'mime_in[profile_image,image/jpg,image/jpeg,image/png,image/webp]',
                                            'max_size[profile_image,2048]',
                                            'is_image[profile_image]',
                                        ];
                $rules['password'] = 'required|min_length[8]';
                $rules['password_confirm'] = 'required|matches[password]';
            }
            if (!$this->validate($rules, $messages)) {
                throw new \RuntimeException(implode('<br>', $this->validator->getErrors()));
            }
            // ==================== FILE UPLOAD HANDLING ====================
            $file = $this->request->getFile('profile_image');
            $profileImage = null;
            if ($id) {
                $user = $users->find($id);
                if (!$user) {
                    throw new \RuntimeException('User not found');
                }
                $profileImage = $user->profile_image;
            }

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/profile_images/';
                $filePath   =          'uploads/profile_images/';
                
                if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
                    throw new \RuntimeException('Failed to create upload directory');
                }

                if ($profileImage && file_exists($uploadPath . $profileImage)) {
                    unlink($uploadPath . $profileImage);
                }

                $newName = $file->getRandomName();
                if (!$file->move($uploadPath, $newName)) {
                    throw new \RuntimeException('Failed to upload profile image');
                }
                $profileImage = $filePath.$newName;
            }
            $designation_ids = $this->request->getPost('designation_id'); 
            $designationIdsString = implode(',', $designation_ids);
            // ==================== USER DATA PREPARATION ====================
            $userData = [               
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],                
                'mobile' => $data['mobile'], 
                'email_id'=> $data['email'],                           
                'permanent_address' => $data['permanent_address'] ?? null,
                'permanent_state' => $data['permanent_state'] ?? null,
                'permanent_city' => $data['permanent_city'] ?? null,
                'permanent_pincode' => $data['permanent_pincode'] ?? null,
                'permanent_landmark' => $data['permanent_landmark'] ?? null,
                'present_address' => $data['present_address'] ?? null,
                'present_landmark' => $data['present_landmark'] ?? null,
                'present_state' => $data['present_state'] ?? null,
                'present_city' => $data['present_city'] ?? null,
                'present_pincode' => $data['present_pincode'] ?? null,
                'user_type_id' => $data['user_type_id'],
                'school_id' => trim($data['school_id'] ?? '') ?: null,
                'designation_id' => trim($designationIdsString) ?: null,
                'about' => $data['about'] ?? null,                   
                'profile_image' => $profileImage,

            ];            
            if(empty($id)){
                $userData['username'] = $data['username'];
                $userData['email'] = $data['email'];
                $userData['email_id'] = $data['email'];
                $userData['password'] = $data['password']; 
                $userData['paswd'] = $data['password'];
                $userData['status'] = 0;
            }
            $specializationSubjectIds = $this->request->getPost('specialization_subject_ids'); 
            $specializationSubjectIdString = implode(',', $specializationSubjectIds);
            
            //==================== TEACHER-SPECIFIC DATA PREPARATION ====================
            // Convert date format for PostgreSQL (DD-MM-YYYY to YYYY-MM-DD)
            $serviceStartFrom = $data['service_start_from'];
            if (!empty($serviceStartFrom)) {
                $serviceStartFrom = date('Y-m-d', strtotime($serviceStartFrom));
                if ($serviceStartFrom === false) {
                    throw new \RuntimeException('Invalid service start date format');
                }
            }
            
            $teacherData = [
                'employement_type_id' => $data['employement_type_id'],
                'specialization_subject_ids' => $specializationSubjectIdString,
                'prefered_teaching_level_id' => $data['prefered_teaching_level_id'],
                'highest_qualification_id' => $data['highest_qualification_id'],
                'service_start_from' => $serviceStartFrom,
                'approval_status' => $data['approval_status'],
                'status' => 0,
            ];

            // ==================== SHIELD AUTHENTICATION HANDLING ====================
            $db->transStart();
            
            if ($id) {                
                // UPDATE EXISTING USER
                $user = $users->find($id);
                if (!$user) {
                    throw new \RuntimeException('User not found');
                }
                
                // Update basic user data
                $userData['updated_by'] = auth()->id(); 
                
                // Handle null values before saving
                foreach ($userData as $key => $value) {
                    if ($value === null) {
                        $userData[$key] = ''; // Convert null to empty string
                    }
                }
                
                $user->fill($userData);
                
                // Save user
                $users->protect(false);                
                if (!$users->save($user)) {
                    throw new \RuntimeException('Failed to update user: ' . implode(', ', $users->errors()));
                }                 
                
                // Update teacher-specific data
                $teacherInfoModel = $db->table('users_info_teachers');
                $existingTeacherInfo = $teacherInfoModel->where('user_id', $id)->get()->getRow();
                
                if ($existingTeacherInfo) {
                    // Update existing record - check if columns exist
                    $updateData = [
                        'employement_type_id' => $teacherData['employement_type_id'],
                        'specialization_subject_ids' => $teacherData['specialization_subject_ids'],
                        'prefered_teaching_level_id' => $teacherData['prefered_teaching_level_id'],
                        'highest_qualification_id' => $teacherData['highest_qualification_id'],
                        'service_start_from' => $teacherData['service_start_from'],
                        'approval_status' => $teacherData['approval_status'],
                        'status' => $teacherData['status'],
                        'updated_by' => auth()->id()
                    ];                    
                    // Only add updated_at if column exists
                    $columns = $db->getFieldNames('users_info_teachers');
                    if (in_array('updated_at', $columns)) {
                        $updateData['updated_at'] = date('Y-m-d H:i:s');
                    }                                        
                    $teacherInfoModel->where('user_id', $id)->update($updateData);                    
                } else {
                    // Insert new record
                    $insertData = [
                        'user_id' => $id,
                        'employement_type_id' => $teacherData['employement_type_id'],
                        'specialization_subject_ids' => $teacherData['specialization_subject_ids'],
                        'prefered_teaching_level_id' => $teacherData['prefered_teaching_level_id'],
                        'highest_qualification_id' => $teacherData['highest_qualification_id'],
                        'service_start_from' => $teacherData['service_start_from'],
                        'approval_status' => $teacherData['approval_status'],
                        'status' => $teacherData['status'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => auth()->id()
                    ];
                    $teacherInfoModel->insert($insertData);
                }
                
                // Update group association                
                $db->table('auth_groups_users')
                    ->where('user_id', $id)
                    ->set('group_id', $data['user_type_id'])
                    ->update();
                
                // Update email in auth_identities table 
                $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
                $identity = $identityModel->where('user_id', $id)
                                        ->where('type', 'email_password')
                                        ->first();                                                                  
                
                if ($identity) {
                    // Update the existing identity
                    $identityModel->update($identity->id, [
                        'secret' => $data['email'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Create a new identity if none exists
                    $identityModel->insert([
                        'user_id' => $id,
                        'type' => 'email_password',
                        'secret' => $data['email'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                } 
                
                $users->protect(true);                           
                $message = 'User updated successfully!';                

            } else {
                // CREATE NEW USER
                $userData['created_by'] = auth()->id();                
                $user = new User($userData);
                $user->password = $data['password'];
                $users->protect(false);
                
                // Handle null values before saving
                foreach ($userData as $key => $value) {
                    if ($value === null) {
                        $userData[$key] = ''; // Convert null to empty string
                    }
                }
                
                if (!$users->save($user)) {
                    throw new \RuntimeException('Failed to create user: ' . implode(', ', $users->errors()));
                }
                
                // Get the new user ID
                $userId = $user->id ?? $users->getInsertID();
                if (!$userId) {
                    throw new \RuntimeException('User was not saved properly, no ID returned.');
                }
                
                // Save teacher-specific data
                $insertData = [
                    'user_id' => $userId,
                    'employement_type_id' => $teacherData['employement_type_id'],
                    'specialization_subject_ids' => $teacherData['specialization_subject_ids'],
                    'prefered_teaching_level_id' => $teacherData['prefered_teaching_level_id'],
                    'highest_qualification_id' => $teacherData['highest_qualification_id'],
                    'service_start_from' => $teacherData['service_start_from'],
                    'approval_status' => $teacherData['approval_status'],
                    'status' => $teacherData['status'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->id()
                ];
                
                $teacherInfoModel = $db->table('users_info_teachers');
                $teacherInfoModel->insert($insertData);

                $db->table('auth_groups_users')->insert([
                    'user_id' => $userId,
                    'group_id' => $data['user_type_id'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $message = 'User created successfully!';
                $users->protect(true);
            }
            
            $db->transComplete(); // Commit transaction
            
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaction failed');
            }
            
            return redirect()->to('teachers')->with('success', $message);

        } catch (\RuntimeException $e) {
            if (isset($db) && $db->transStatus() !== false) {
                $db->transRollback();
            }
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            if (isset($db) && $db->transStatus() !== false) {
                $db->transRollback();
            }
            log_message('error', 'User create error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }    

}
