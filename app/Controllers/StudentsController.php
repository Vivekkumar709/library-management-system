<?php
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

use App\Models\StudentModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Exceptions\PageNotFoundException;


class StudentsController extends BaseController
{
    protected $studentModel;
    protected $userModel;
    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->userModel = new \App\Models\UserModel();
    }    
    // List all students
    public function list()
    {   
        $data['students'] = $this->studentModel->getStudents();        
        $this->title = 'Students';
        $this->content_view = 'students/list';
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Students', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Add/Edit student form
    public function add($id = null)
    {
        $isEdit = !is_null($id);
        $this->title = $id ? 'Edit Student' : 'Add Student';
        $this->content_view = 'students/add';
        
        $data = ['isEdit' => $isEdit];
        
        if ($isEdit) {
            //$data['data'] = $this->studentModel->find($id);
            //$data['data'] = $this->studentModel->findWithUser($id);
            $data['data'] = $this->studentModel->findStudentWithUser($id);
           
            if (!$data['data']) {
                log_message('error', "Student not found with ID: {$id}");
                return redirect()->to('/students')->with('error', 'Student not found.');
            }
            // Additional check to make sure we have valid data
            if (!isset($data['data']['id'])) {
                log_message('error', "Invalid student data for ID: {$id}");
                return redirect()->to('/students')->with('error', 'Invalid student data.');
            }
        }
        if($isEdit && (!$data['data'] || ($data['data']['class_id']))){
            $data['sections'] = get_advanced_dropdown([
                'tables' => ['m_sections ms'],
                'joins' => [
                    [
                        'table' => 'sections s', 
                        'condition' => 'ms.id = s.section_id',
                        'type' => 'left'
                    ]
                ],
                'key' => 's.id', 
                'value' => 'ms.name as section_name', 
                'where' => [
                    'ms.status' => 0, 
                    's.status' => 0, 
                    's.class_id' => $data['data']['class_id'],
                    's.school_id' => auth()->user()->school_id,
                    's.financial_year_id' => $data['data']['financial_year_id'], 
                ],
                'orderBy' => 'ms.name ASC',
                'selectPostfix' => 'Empty'
            ]);
        }else {
            $data['sections'] = [];
        } 
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Financial Year');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Class');
        $data['blood_groups'] = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $data['genders'] = ['Male', 'Female', 'Other'];
        $data['caste_categories'] = get_dropdown('m_caste_categories', 'id', 'name', ['status' => 0], 'Caste');
        $data['sections'] = get_dropdown('m_sections', 'id', 'name', ['status' => 0], 'Section');
        $data['permanentState'] = get_dropdown('states', 'id', 'name', ['status' => 0],'Permanent State');    
        $data['presentState'] = get_dropdown('states', 'id', 'name', ['status' => 0],'Present State');
        
        $data['loadDatePicker'] = true;
        $this->thumbnails = [
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Add Students', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => $isEdit ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            ]
        ];       
        
        return $this->render();
    }
        
    // Generate admission number
    public function generateAdmissionNo()
    {
        $schoolId = auth()->user()->school_id;
        $year = date('y');
        
        // Get last admission number for this school
        $lastAdmission = $this->studentModel
            ->where('school_id', $schoolId)
            ->orderBy('id', 'DESC')
            ->first();
            
        if ($lastAdmission && preg_match('/^SCH' . $schoolId . $year . '(\d+)$/', $lastAdmission['admission_no'], $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        
        $admissionNo = 'SCH' . $schoolId . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        return $this->response->setJSON(['admission_no' => $admissionNo]);
    } 

    public function save()
    {
        $dataPost = $this->request->getPost();
        $users = model(UserModel::class);
        $id = $this->request->getPost('id'); 
        
        if(isset($id)){

            // echo "<pre>"; 
            // print_r($dataPost);
            // echo "</pre>"; 
            // die;
            $getUserId = get_records('students', [
                'joins' => [
                    ['table' => 'users', 'condition' => 'students.user_id = users.id']
                ],
                'select' => ['students.user_id'],
                'filters' => ['students.id' => $id],
                'single' => true
            ]);
            $userId = $getUserId['user_id'];  
        }    
        $validation = \Config\Services::validation();

        $validation->setRules([
            // 'admission_no' => [
            //     'required',
            //     'regex_match[/^ADM-[A-Z]{3}\d{2}-\d{2}-\d{4}$/]'
            // ],  
            'admission_no' => 'required',          
            //'roll_no' => 'permit_empty|alpha_numeric',
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]',
            'gender' => 'required|in_list[Male,Female,Other]',
            'date_of_birth' => 'required|valid_date',
            'admission_date' => 'required|valid_date',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'financial_year_id' => 'required|integer',
            'father_name' => 'permit_empty|max_length[100]',
            'mother_name' => 'permit_empty|max_length[100]',
            'mobile_no' => 'permit_empty|max_length[15]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'status' => 'permit_empty|in_list[0,1]',
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
        ]);
        if (!empty($dataPost['profile_image'])) { 
            $rules['profile_image'] = [
                'if_exist',
                ($userId ? 'permit_empty' : 'uploaded[profile_image]'),
                'mime_in[profile_image,image/jpg,image/jpeg,image/png,image/webp]',
                'max_size[profile_image,2048]',
                'is_image[profile_image]',
            ];
        }
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // ==================== FILE UPLOAD HANDLING ====================
        $file = $this->request->getFile('profile_image');
        $profileImage = null;
        if ($id) {
            $user = $users->find($userId);
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
        
        $dob = $this->request->getPost('date_of_birth');
        if (!empty($dob)) {
            $dob = date('Y-m-d', strtotime(str_replace('/', '-', $dob)));
        }
        $admission_date = $this->request->getPost('admission_date');
        if (!empty($admission_date)) {
            $admission_date = date('Y-m-d', strtotime(str_replace('/', '-', $admission_date)));
        }
        $random_number = mt_rand(100, 999);
        $classId = $this->request->getPost('class_id');
        
        $studentName = strtolower(
            str_replace(' ', '', 
                $this->request->getPost('first_name') . $this->request->getPost('last_name')
            )
        );
        $genratedUserName = $studentName.'@'.$classId.auth()->user()->school_id.$random_number;
        helper('password');
        $password = generate_random_password(12);

        $data = [
            'admission_no' => $this->request->getPost('admission_no'),
            'gender' => $this->request->getPost('gender'),
            'date_of_birth' => $dob,
            'blood_group' => $this->request->getPost('blood_group'),
            'religion' => $this->request->getPost('religion'),
            'caste' => $this->request->getPost('caste'),
            'nationality' => $this->request->getPost('nationality'),
            'aadhaar_no' => $this->request->getPost('aadhaar_no'),            
            'admission_date' => $admission_date,
            'class_id' => $this->request->getPost('class_id'),
            'section_id' => $this->request->getPost('section_id'),
            'financial_year_id' => $this->request->getPost('financial_year_id'),
            'father_name' => $this->request->getPost('father_name'),
            'father_occupation' => $this->request->getPost('father_occupation'),
            'father_mobile' => $this->request->getPost('father_mobile'),
            'father_email' => $this->request->getPost('father_email'),
            'mother_name' => $this->request->getPost('mother_name'),
            'mother_occupation' => $this->request->getPost('mother_occupation'),
            'mother_mobile' => $this->request->getPost('mother_mobile'),
            'mother_email' => $this->request->getPost('mother_email'),
            'guardian_name' => $this->request->getPost('guardian_name'),
            'guardian_relation' => $this->request->getPost('guardian_relation'),
            'guardian_occupation' => $this->request->getPost('guardian_occupation'),
            'guardian_mobile' => $this->request->getPost('guardian_mobile'),
            'guardian_email' => $this->request->getPost('guardian_email'),            
            'school_id' => auth()->user()->school_id,                        
        ];

        $userData = [               
            'first_name' => $dataPost['first_name'],
            'last_name' => $dataPost['last_name'],                
            'mobile' => $dataPost['mobile_no'], 
            'email_id'=> $dataPost['email'],                           
            'permanent_address' => $dataPost['permanent_address'] ?? null,
            'permanent_state' => $dataPost['permanent_state'] ?? null,
            'permanent_city' => $dataPost['permanent_city'] ?? null,
            'permanent_pincode' => $dataPost['permanent_pincode'] ?? null,
            'permanent_landmark' => $dataPost['permanent_landmark'] ?? null,
            'present_address' => $dataPost['present_address'] ?? null,
            'present_landmark' => $dataPost['present_landmark'] ?? null,
            'present_state' => $dataPost['present_state'] ?? null,
            'present_city' => $dataPost['present_city'] ?? null,
            'present_pincode' => $dataPost['present_pincode'] ?? null,
            'user_type_id' => 4,
            'school_id' => auth()->user()->school_id,
            'designation_id' => 38, 
            'about' => $dataPost['about'] ?? null,                   
            'profile_image' => $profileImage,
            'email' => $dataPost['email'],
            'email_id' => $dataPost['email'],
        ];

        //echo "<pre>"; print_r($userData); echo "</pre>"; die;

        if(empty($id)){
            $userData['username'] = $genratedUserName;
            //$userData['email'] = $dataPost['email'];
           // $userData['email_id'] = $dataPost['email'];
            $userData['password'] = $password; 
            $userData['paswd'] = $password;
            $userData['status'] = 0;
        }

        //echo "<pre>";
        //print_r($dataPost);
        //print_r($data);
        //print_r($userData);
        //die;

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Validation for Unique Admission Numbers 
            if ($this->studentModel->isAdmissionNoExists($data['admission_no'], $id)) {
                // If it's a new student and admission number already exists, regenerate
                if (!$id) {
                    $data['admission_no'] = $this->studentModel->generateAdmissionNumber(
                        $data['school_id'], 
                        $data['financial_year_id'],
                        true // Force regeneration
                    );
                } else {
                    throw new \Exception('Admission number already exists.');
                }
            }

            if (!$id) {               
                // Generate roll number for actual insertion (will increment counter)
                $data['roll_no'] = $this->studentModel->generateRollNumber(
                    $data['class_id'], 
                    $data['section_id'], 
                    $data['school_id'], 
                    $data['financial_year_id'],
                    false 
                );
                
                // Check if the generated roll number already exists in the same class-section
                if (!empty($data['roll_no'])) {
                    if ($this->studentModel->isRollNoExists(
                        $data['roll_no'], 
                        $data['class_id'], 
                        $data['section_id'], 
                        $data['financial_year_id'],
                        $id
                    )) {
                        // If there's a conflict, regenerate the roll number
                        $data['roll_no'] = $this->studentModel->generateRollNumber(
                            $data['class_id'], 
                            $data['section_id'], 
                            $data['school_id'], 
                            $data['financial_year_id'],
                            false // Actual insertion, not preview
                        );
                    }
                }
            } else {
                // For edit mode, use the provided roll number if any
                $providedRollNo = $this->request->getPost('roll_no');
                if (!empty($providedRollNo)) {
                    $data['roll_no'] = $providedRollNo;                    
                    // Check if roll number already exists in the same class-section (excluding current student)
                    if ($this->studentModel->isRollNoExists(
                        $data['roll_no'], 
                        $data['class_id'], 
                        $data['section_id'], 
                        $data['financial_year_id'],
                        $id
                    )) {
                        throw new \Exception('Roll number already exists in the selected class and section.');
                    }
                }
            }
            
            // Check if admission number already exists (again after regeneration)
            if ($this->studentModel->isAdmissionNoExists($data['admission_no'], $id)) {
                throw new \Exception('Admission number already exists.');
            }
            
            if ($id) {
                //+++++++++
                $user = $users->find($userId);
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
                ->where('user_id', $userId)
                ->set('group_id', 4) //$data['user_type_id']
                ->update();
                //=====================================
                // Update email in auth_identities table
                $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
                $identity = $identityModel->where('user_id', $userId)
                                        ->where('type', 'email_password')
                                        ->first();                                        

                if ($identity) {
                    $identityModel->update($identity->id, [
                        'secret' => $dataPost['email']
                    ]);
                } else {
                    // If no identity exists (shouldn't happen for existing users), create one
                    $identityModel->insert([
                        'user_id' => $userId,
                        'type' => 'email_password',
                        'secret' => $dataPost['email'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                //+++++++++
                // Edit mode
                $data['updated_by'] = auth()->id();            
                if ($this->request->getPost('status') !== null) {
                    $data['status'] = $this->request->getPost('status');
                }            
                //$result = $this->studentModel->update($id, $data);     
                $result = $this->studentModel->builder()
                        ->where('user_id', $userId)
                        ->update($data);                                      
                if (!$result) {
                    throw new \Exception('Failed to update student.');
                }
            } else {
                //+++++++++
                // CREATE NEW USER
                $userData['created_by'] = auth()->id();
                $user = new User($userData);
                $user->password = $userData['password'];//$data['password'];
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
                $db->table('auth_groups_users')->insert([
                    'user_id' => $userId,
                    'group_id' => 4, //$data['user_type_id']
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $message = 'User created successfully!';
                $users->protect(true);
                //+++++++++
                // Create mode
                $data['created_by'] = auth()->id(); 
                $data['user_id'] = $userId;            
                if ($this->request->getPost('status') !== null) {
                    $data['status'] = $this->request->getPost('status');
                }else{
                    $data['status'] = 0;                    
                }           
                $result = $this->studentModel->insert($data);            
                if (!$result) {
                    throw new \Exception('Failed to add student.');
                }
            }            
            // Commit transaction if everything is successful
            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed.');
            }
            
            return redirect()->to('/students')->with('success', $id ? 'Student updated successfully!' : 'Student added successfully!');
            
        } catch (\Exception $e) {
            // Roll back transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
    // Delete student
    public function delete($id)
    {
        $student = $this->studentModel->find($id);
        
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }
        
        if ($this->studentModel->delete($id)) {
            return redirect()->to('/students')->with('success', 'Student deleted successfully!');
        } else {
            return redirect()->to('/students')->with('error', 'Failed to delete student.');
        }
    }
        
    // View student details
    public function view($id)
    {
        $student = $this->studentModel->getStudents(['s.id' => $id]);
        
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }
        
        $this->title = 'Student Details';
        $this->content_view = 'students/view';
        
        $this->content_data = [
            'data' => ['student' => $student[0]],
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Details', 'url' => '', 'active' => true]
            ]
        ];       
        
        return $this->render();
    }

    public function previewRollNo()
    {
        // Check if it's an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ])->setStatusCode(400);
        }
        
        $classId = $this->request->getPost('class_id');
        $sectionId = $this->request->getPost('section_id');
        $financialYearId = $this->request->getPost('financial_year_id');
        $schoolId = auth()->user()->school_id;
        
        // Validate required fields
        if (empty($classId) || empty($sectionId) || empty($financialYearId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Class, section, and financial year are required'
            ]);
        }
        
        try {
            // Generate roll number in preview mode (won't increment counter)
            $rollNo = $this->studentModel->generateRollNumber($classId, $sectionId, $schoolId, $financialYearId, true);
            
            if ($rollNo) {
                return $this->response->setJSON([
                    'success' => true,
                    'roll_no' => $rollNo,
                    'message' => 'Roll number preview generated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to generate roll number preview'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Roll number preview error: ' . $e->getMessage());
            
            // Try one more time with manual generation
            try {
                $rollNo = $this->studentModel->generateRollNumberManually($classId, $sectionId, $schoolId, $financialYearId, true);
                
                return $this->response->setJSON([
                    'success' => true,
                    'roll_no' => $rollNo,
                    'message' => 'Roll number preview generated (fallback method)'
                ]);
            } catch (\Exception $e2) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error generating roll number preview. Please contact administrator.'
                ]);
            }
        }
    }

    public function previewAdmissionNo()
    {
        // Check if it's an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ])->setStatusCode(400);
        }
        
        $schoolId = auth()->user()->school_id;
        $financialYearId = $this->request->getPost('financial_year_id');
        $regenerate = $this->request->getPost('regenerate') === 'true';
        
        // Validate required fields
        if (empty($financialYearId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Financial year is required'
            ]);
        }
        
        try {
            // Generate admission number
            $admissionNo = $this->studentModel->generateAdmissionNumber($schoolId, $financialYearId, $regenerate);
            
            if ($admissionNo) {
                return $this->response->setJSON([
                    'success' => true,
                    'admission_no' => $admissionNo,
                    'message' => 'Admission number generated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to generate admission number. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admission number preview error: ' . $e->getMessage());
            
            // Try one more time with manual generation
            try {
                $admissionNo = $this->studentModel->generateAdmissionNumberManually($schoolId, $financialYearId, $regenerate);
                
                return $this->response->setJSON([
                    'success' => true,
                    'admission_no' => $admissionNo,
                    'message' => 'Admission number generated (fallback method)'
                ]);
            } catch (\Exception $e2) {
                // Final fallback - very simple format
                $admissionNo = 'ADM-' . $schoolId . '-' . date('y') . '-0001';
                
                return $this->response->setJSON([
                    'success' => true,
                    'admission_no' => $admissionNo,
                    'message' => 'Admission number generated (emergency fallback)'
                ]);
            }
        }
    }      
    //FOR STUDENT SAVE CODE END HERE 

    // ==================== STUDENT SUMMARY ====================
    public function summary($id)
    {
        $studentModel = model('StudentModel');
        $docModel     = model('StudentDocumentModel');   // create this model
        $acModel      = model('StudentAcademicModel');   // create this model
        $feeModel     = model('FeeModel');               // create this model
        $attModel     = model('AttendanceModel');        // create this model
        $resModel     = model('ResultModel');            // create this model

        $data['student']     = $studentModel->getStudentFullSummary($id);
        $data['documents']   = $docModel->where('student_id', $id)->findAll();
        $data['academic']    = $acModel->where('student_id', $id)->findAll();
        $data['fees']        = $feeModel->getStudentFeeSummary($id);
        $data['attendance']  = $attModel->getMonthlySummary($id);
        $data['results']     = $resModel->getStudentResultSummary($id);

        $this->title = 'Student Summary - ' . ($data['student']['first_name'] ?? '');
        $this->content_view = 'students/summary';

        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Summary', 'url' => '', 'active' => true]
            ]
        ];

        $this->thumbnails = [
            ['title' => 'Students', 'url' => site_url('students')],
            ['title' => 'Summary', 'url' => '', 'active' => true]
        ];

        return $this->render();
    }


    
}