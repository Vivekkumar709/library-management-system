<?php namespace App\Controllers;

// use App\Models\ClassModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;

class ClassesController extends BaseController
{
   // protected $classModel;
    public function __construct()
    {
       // $this->classModel = new ClassModel();
    }

    // LISTING    
    public function classesList()
    {              
        $this->title = 'Classes List';
        $this->content_view = 'classes/list'; 
        
        // Get all classes
        $classes = get_records('m_classes', [            
            'orderBy' => 'class_number ASC'            
        ]);
        
        // Get all sections
        $db = Database::connect();        
        $sections = $db->table('sections s')
                    ->select('s.*, ms.name AS section_name, 
                    mst.name AS section_type_name, 
                    mss.name AS special_section_name, 
                    msf.name AS section_for_name,
                    fy.name AS financial_year_name') 
                    ->join('m_sections ms', 'ms.id = s.section_id', 'left') 
                    ->join('m_section_type mst', 'mst.id = s.section_type', 'left')
                    ->join('m_special_section_category mss', 's.special_section = mss.id', 'left')
                    ->join('m_section_for msf', 'msf.id = s.section_for', 'left')
                    ->join('financial_year fy', 'fy.id = s.financial_year_id', 'left')                     
                    ->where('s.school_id', auth()->user()->school_id)
                    ->where('s.financial_year_id', FINANCIAL_YEAR_ID)
                    ->orderBy('s.class_id, s.section_id')
                    ->get()
                    ->getResultArray();
        
        // Group sections by class_id
        $sectionsByClass = [];
        if(count($sections) > 0){
            foreach ($sections as $section) {
                $sectionsByClass[$section['class_id']][] = $section;
            }
        }

        // Add sections to each class
        $data['classes_data'] = [];
        if (!empty($classes)) {
            foreach ($classes as $class) {
                $classData = $class;
                $classData['sections'] = $sectionsByClass[$class['id']] ?? [];
                $classData['total_max_capacity'] = 0;
                $classData['total_current_strength'] = 0;
                
                if (!empty($classData['sections'])) {
                    foreach ($classData['sections'] as $section) {
                        $classData['total_max_capacity'] += (int)$section['max_capacity'];
                        $classData['total_current_strength'] += (int)$section['current_strength'];
                    }
                }                
                $data['classes_data'][] = $classData;
            }
        }
        
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;     
        $this->thumbnails = [ 
                ['title' => 'Classes List', 'url' => '', 'active' => true]
        ];   
        $this->content_data = [
            'data' => $data,            
        ];           
        return $this->render();
    }


    public function addSection($id = null)
        {   
            $isEdit = !is_null($id);        
            $this->title = $id ? 'Edit Section' : 'Add Section';
            $this->content_view = 'classes/addSection'; 
            $data = [            
                'isEdit' => $isEdit??null,             
            ];
            if($id){ 
                $result = get_records('sections', [            
                    'orderBy' => 'id',
                    'filters' => ['id'=>$id]         
                ]);
                if (!empty($result) && is_array($result)) {
                    $data['data'] = $result[0]; 
                } else {
                    $data['data'] = null;
                }
            }else{            
                $data['data'] = null;
            } 
            $data['financial_year'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0],'Session');
            $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0],'Classes');
            $data['sections'] = get_dropdown('m_sections', 'id', 'name', ['status' => 0],'Section');
            $data['section_type'] = get_dropdown('m_section_type', 'id', 'name', ['status' => 0],'Section Type');
            $data['special_section'] = get_dropdown('m_special_section_category', 'id', 'name', ['status' => 0],'Special Section');
            $data['section_for'] = get_dropdown('m_section_for', 'id', 'name', ['status' => 0],'Section For');
            $data['loadDatePicker'] = true; 
            $this->thumbnails = [
                    ['title' => 'Classes List', 'url' => site_url('classes')],
                    ['title' => 'Add Section', 'url' => '', 'active' => true]
            ];
            $this->content_data = [
                'data' => $data,                
            ];        
            return $this->render();
        }
    //TO ADD/EDIT SECTION
    public function saveSectionDetails()
    {
        // Validate the input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'financial_year_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'max_capacity' => 'required|integer|greater_than[0]',
            'current_strength' => 'required|integer|greater_than_equal_to[0]',
            'section_for' => 'required|integer',            
            'section_type' => 'required|integer',            
            'special_section' => 'permit_empty|integer',
            'status' => 'permit_empty|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = Database::connect();
        $builder = $db->table('sections');

        $data = [
            'class_id' => $this->request->getPost('class_id'),
            'section_id' => $this->request->getPost('section_id'),
            'max_capacity' => $this->request->getPost('max_capacity'),
            'current_strength' => $this->request->getPost('current_strength'),  
            'section_for' => $this->request->getPost('section_for'),  
            'section_type' => $this->request->getPost('section_type'), 
            'financial_year_id' => $this->request->getPost('financial_year_id'),             
        ];

        if($data['section_type'] == 8){
            $data['special_section'] = $this->request->getPost('special_section');
        }

        // Check if status is provided (for edit)
        if ($this->request->getPost('status') !== null) {
            $data['status'] = $this->request->getPost('status');
        }
        // Check if it's an edit or new
        if ($this->request->getPost('id')) {
            // Update existing record
            $builder->where('id', $this->request->getPost('id'));            
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = auth()->id();
            $result = $builder->update($data);

            if ($result) {
                return redirect()->to('/classes')->with('success', 'Section updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update section.');
            }

        } else {
            // Insert new record
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = auth()->id();
            $data['school_id'] = auth()->user()->school_id;
            
            // Check if section already exists for this class
            $exists = $db->table('sections')
                ->where('class_id', $data['class_id'])
                ->where('section_id', $data['section_id'])
                ->where('school_id', auth()->user()->school_id)
                ->countAllResults();
                
            if ($exists > 0) {                
                return redirect()->back()->withInput()->with('error', 'This section already exists for the selected class.');
            }                        
            $result = $builder->insert($data);
            
            if ($result) {
                return redirect()->to('/classes')->with('success', 'Section created successfully!'); 
            } else {
                return redirect()->back()->with('error', 'Failed to create section.');
            }
        }
    } 
    /* CLASS TEACHERS CODE START */
    //GET CLASS TEACHERS LIST
    public function getClassTeachersList()
    {               
        $this->title = 'Class Teachers List';
        $this->content_view = 'classes/classTeachersList'; 
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
                // ['title' => 'Classes List', 'url' => site_url('classes')],
                ['title' => 'Teachers List', 'url' => '', 'active' => true]
        ];
        
        $this->content_data = [
            'data' => $data,
            // 'breadcrumbs' => [
            //     ['title' => 'Dashboard', 'url' => site_url('dashboard')],
            //     ['title' => 'Teachers List', 'url' => site_url('teachersList')],
            //     ['title' => 'List', 'url' => '', 'active' => true]
            // ]
        ];        
        return $this->render();
    }
    //ADD-EDIT MODE VIEW FOR CLASS TEACHERS
    public function addClassTeachers($id = null){
        $user = auth()->user();
        $schoolIds = [$user->school_id];

        if(empty($user->school_id) || $user->school_id == null){
            throw PageNotFoundException::forPageNotFound('You are not registered with any school!!!');
        }        
        $this->title = $id ? 'Edit Class Teacher' : 'Add Class Teacher';
        $this->content_view = 'employee/addTeacher';
        $users = model(UserModel::class);                
        $data = [
            'data' => null,//$id ? $this->userModel->getTeachersWithDetails($id) : null, 
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
        
        $this->content_data = [
            'data' => $data,
            // 'breadcrumbs' => [
            //     ['title' => 'Dashboard', 'url' => site_url('dashboard')],
            //     ['title' => 'Teachers List', 'url' => site_url('/teachers')],
            //     ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            // ]
        ];        
        return $this->render();
    } 
    //CREATE-UPDATE MODE VIEW FOR CLASS TEACHERS    
    public function createClassTeacher()
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
    /* CLASS TEACHERS CODE ENDED */

    /* CLASS SCHEDULE CODE START */
    //GET CLASS SCHEDULE LIST
    public function getClassScheduleList()
    {               
        $this->title = 'Class Schedule List';
        $this->content_view = 'classes/classScheduleList'; 
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
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Teachers List', 'url' => site_url('teachersList')],
                ['title' => 'List', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }
    //ADD-EDIT MODE VIEW FOR CLASS TEACHERS
    public function addClassSchedule($id = null){
        $user = auth()->user();
        $schoolIds = [$user->school_id];

        if(empty($user->school_id) || $user->school_id == null){
            throw PageNotFoundException::forPageNotFound('You are not registered with any school!!!');
        }        
        $this->title = $id ? 'Edit Class Schedule' : 'Add Class Schedule';
        $this->content_view = 'classes/addClassSchedule';
        $users = model(UserModel::class);                
        $data = [
            'data' => null,//$id ? $this->userModel->getTeachersWithDetails($id) : null, 
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
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Teachers List', 'url' => site_url('/teachers')],
                ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    } 
    //CREATE-UPDATE MODE VIEW FOR CLASS TEACHERS    
    public function createClassSchedule()
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
    /* CLASS SCHEDULE CODE ENDED */

    /* CLASS ASSIGNMENTS  CODE START */
    //GET CLASS ASSIGNMENTS LIST
    public function getClassAttendanceList()
    {               
        $this->title = 'Class Attendance List';
        $this->content_view = 'classes/classAttendanceList'; 
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
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Class Attendence List', 'url' => site_url('classes/classList')],
                ['title' => 'List', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }
    //ADD-EDIT MODE VIEW FOR CLASS TEACHERS
    public function addClassAttendance($id = null){
        $user = auth()->user();
        $schoolIds = [$user->school_id];

        if(empty($user->school_id) || $user->school_id == null){
            throw PageNotFoundException::forPageNotFound('You are not registered with any school!!!');
        }        
        $this->title = $id ? 'Edit Class Attendance' : 'Add Class Attendance';
        $this->content_view = 'classes/addClassAttendance';
        $users = model(UserModel::class);                
        $data = [
            'data' => null,//$id ? $this->userModel->getTeachersWithDetails($id) : null, 
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
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Teachers List', 'url' => site_url('/teachers')],
                ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    } 
    //CREATE-UPDATE MODE VIEW FOR CLASS TEACHERS    
    public function createClassAttendance()
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
    /* CLASS ASSIGNMENTS CODE ENDED */


    /* CLASS ASSIGNMENTS  CODE START */
    //GET CLASS ASSIGNMENTS LIST
    public function getClassAssignmentsList()
    {               
        $this->title = 'Class Assignments List';
        $this->content_view = 'classes/classAssignmentsList'; 
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
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Class Attendence List', 'url' => site_url('classes/classList')],
                ['title' => 'List', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }
    //ADD-EDIT MODE VIEW FOR CLASS TEACHERS
    public function addClassAssignments($id = null){
        $user = auth()->user();
        $schoolIds = [$user->school_id];

        if(empty($user->school_id) || $user->school_id == null){
            throw PageNotFoundException::forPageNotFound('You are not registered with any school!!!');
        }        
        $this->title = $id ? 'Edit Class Assignments' : 'Add Class Assignments';
        $this->content_view = 'classes/addClassAssignments';
        $users = model(UserModel::class);                
        $data = [
            'data' => null,//$id ? $this->userModel->getTeachersWithDetails($id) : null, 
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
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Teachers List', 'url' => site_url('/teachers')],
                ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    } 
    //CREATE-UPDATE MODE VIEW FOR CLASS TEACHERS    
    public function createClassAssignments()
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
    /* CLASS Assignments ASSIGNMENTS CODE ENDED */
    
}