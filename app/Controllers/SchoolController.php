<?php 
namespace App\Controllers;

use App\Models\SchoolModel;

class SchoolController extends BaseController
{
    protected $schoolModel;

    public function __construct()
    {
        $this->schoolModel = new \App\Models\SchoolModel();
    }

    public function getSchools()
    {
        $filters = [];
        if ($this->request->getMethod() === 'POST') {            
            $filters = [
                'school_type' => $this->request->getPost('school_type') ?? '',
                'school_medium' => $this->request->getPost('school_medium') ?? '',
                'status' => $this->request->getPost('status') ?? ''
            ];                   
        }  
        if ($this->request->getPost('reset')) {            
            $filters = [
                'school_type' => '',
                'school_medium' => '',
                'status' => ''
            ];
        }
        // helper('permission');
        $this->title = 'Schools Management';
        $this->content_view = 'school/schoolsList';      
        
        $data['schools'] = $this->schoolModel->getSchoolsWithDetails($filters);
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        
        // Pass current filters to view
        $data['current_filters'] = $filters;
        $this->thumbnails = [
                ['title' => 'Schools List', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    }

    public function addSchool($id = null)
    {
        $this->title = $id ? 'Edit School' : 'Add School';
        $this->content_view = 'school/add';
        $data = [
            'school' => $id ? $this->schoolModel->getSchoolWithDetails($id) : null,
            'schoolTypes' => get_dropdown('m_school_types', 'id', 'name', ['status' => 0],'School Type'),
            'schoolMediums' => get_dropdown('m_school_mediums', 'id', 'name', ['status' => 0],'School Medium'),            
            'affiliationBoards' => get_dropdown('m_affiliation_boards', 'id', 'name', ['status' => 0],'Affilation'),
            'educationLevels' => get_dropdown('m_education_levels', 'id', 'name', ['status' => 0],'Education Level'),
            'schoolTraditions' => get_dropdown('m_school_tradition', 'id', 'name', ['status' => 0],'School Tradition'),
            'states' => get_dropdown('states', 'id', 'name', ['status' => 0],'Empty'),
            'plans' => get_dropdown('plan_packages', 'id', 'name', ['status' => 0],'Plan'),
            'paymentModes' => get_dropdown('m_payment_mode', 'id', 'name', ['status' => 0],'Empty'),
            'planPackages' => get_dropdown('plan_packages', 'id', 'name', ['status' => 0],'Plans'),            
            //'branchOptions' => ['No' => 'No', 'Yes' => 'Yes']
        ];        
        $data['loadDatePicker'] = true;  
        $this->thumbnails = [           
                ['title' => 'Schools List', 'url' => site_url('schools')],
                ['title' => 'Add School', 'url' => '', 'active' => true]
        ];   
                  
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }

    public function saveSchool()
    {
        // Check if it's a POST request
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        // Define validation rules
        $rules = [
            'school_name' => 'required|max_length[255]',
            'school_type_id' => 'required|numeric',
            'school_medium_id' => 'required|numeric',
            'school_affiliation_id' => 'required|numeric',
            'school_education_level_id' => 'required|numeric',
            'school_tradition_id' => 'required|numeric',
            'total_no_staff' => 'required|numeric',
            'owner_name' => 'required|max_length[100]',
            'owner_mobile' => 'required|numeric|exact_length[10]',
            'school_registration_no' => 'required|max_length[50]',
            'school_branch' => 'required|in_list[Yes,No]',
            'contact_person_name' => 'required|max_length[100]',
            'contact_person_mobile' => 'required|numeric|exact_length[10]',
            'contact_person_email' => 'required|valid_email|max_length[100]',
            'contact_person_work_details' => 'permit_empty|max_length[255]',
            'contact_person_address' => 'required|max_length[500]',
            'school_address' => 'required|max_length[500]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'pincode' => 'required|numeric|exact_length[6]',
            'landmark' => 'permit_empty|max_length[255]',
            'plan_id' => 'required|numeric',
            'plan_payable_amount' => 'required|numeric',
            'payment_mode_id' => 'required|numeric',
            'valid_from' => 'required|valid_date',
            'school_logo' => [
                'if_exist',
                'uploaded[school_logo]',
                'mime_in[school_logo,image/jpg,image/jpeg,image/png]',
                'max_size[school_logo,2048]',
            ],
            'school_website' => 'valid_url|max_length[200]',
            'school_email_id' => 'valid_email|max_length[100]',
        ];
        
        // Run validation
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get existing logo if this is an update
        $schoolId = $this->request->getPost('id');
        $existingLogo = null;
        if ($schoolId) {
            $school = $this->schoolModel->find($schoolId);
            $existingLogo = $school['school_logo'] ?? null;
        }

        // Handle file upload
        $file = $this->request->getFile('school_logo');
        $schoolLogo = $existingLogo;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/schools/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old file if it exists
            if ($existingLogo && file_exists($uploadPath . $existingLogo)) {
                unlink($uploadPath . $existingLogo);
            }

            // Generate new filename and move uploaded file
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $schoolLogo = $newName;
        }
        $valid_from = $this->request->getPost('valid_from');
        if (!empty($valid_from)) {
            $valid_from_formatted = date('Y-m-d', strtotime(str_replace('/', '-', $valid_from)));
        }
        // Prepare data for saving
        $data = [
            'school_name' => $this->request->getPost('school_name'),
            'school_type_id' => $this->request->getPost('school_type_id'),
            'school_medium_id' => $this->request->getPost('school_medium_id'),
            'school_affiliation_id' => $this->request->getPost('school_affiliation_id'),
            'school_education_level_id' => $this->request->getPost('school_education_level_id'),
            'school_tradition_id' => $this->request->getPost('school_tradition_id'),
            'total_no_staff' => $this->request->getPost('total_no_staff'),
            'owner_name' => $this->request->getPost('owner_name'),
            'owner_mobile' => $this->request->getPost('owner_mobile'),
            'school_registration_no' => $this->request->getPost('school_registration_no'),
            'school_branch' => $this->request->getPost('school_branch'),
            'contact_person_name' => $this->request->getPost('contact_person_name'),
            'contact_person_mobile' => $this->request->getPost('contact_person_mobile'),
            'contact_person_email' => $this->request->getPost('contact_person_email'),
            'contact_person_work_details' => $this->request->getPost('contact_person_work_details'),
            'contact_person_address' => $this->request->getPost('contact_person_address'),
            'school_address' => $this->request->getPost('school_address'),
            'state_id' => $this->request->getPost('state_id'),
            'city_id' => $this->request->getPost('city_id'),
            'pincode' => $this->request->getPost('pincode'),
            'landmark' => $this->request->getPost('landmark'),
            'plan_id' => $this->request->getPost('plan_id'),
            'plan_payable_amount' => $this->request->getPost('plan_payable_amount'),
            'payment_mode_id' => $this->request->getPost('payment_mode_id'),
            'valid_from' => $valid_from_formatted,//$this->request->getPost('valid_from'),
            'school_logo' => $schoolLogo,
            'school_website' => $this->request->getPost('school_website'),
            'school_email_id' => $this->request->getPost('school_email_id'),

        ];   

        try {
            if ($schoolId) {           
                $this->schoolModel->update($schoolId, $data);
                $message = 'School updated successfully!';
            } else {            
                $this->schoolModel->insert($data);
                $message = 'School created successfully!';
            }

            return redirect()->to('/schools')->with('success', $message);
        } catch (\Exception $e) {
            // If there was an error, delete the uploaded file if it was uploaded
            if (isset($newName) && file_exists($uploadPath . $newName)) {
                unlink($uploadPath . $newName);
            }            
            log_message('error', 'School save error: ' . $e->getMessage());                        
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error saving school. Please try again.');
        }
    }          

    public function updateStatus()
    {    
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'school_id' => 'required|integer',
            'status' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        $schoolId = $this->request->getPost('school_id');
        $newStatus = $this->request->getPost('status');
        
        try {
            $updated = $this->schoolModel->update($schoolId, ['status' => $newStatus]);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Status updated successfully'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to update status'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
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
    
}