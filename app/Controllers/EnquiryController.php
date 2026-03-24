<?php
namespace App\Controllers;
use App\Models\AdmissionEnquiryModel;
use App\Models\UserModel;

class EnquiryController extends BaseController {
    protected $enquiryModel;
    protected $userModel;

    public function __construct() {
        $this->enquiryModel = new AdmissionEnquiryModel();
        $this->userModel = new UserModel();
    }

    // public function addSection($id = null)
    // {   
    //     $isEdit = !is_null($id);        
    //     $this->title = $id ? 'Edit Section' : 'Add Section';
    //     $this->content_view = 'classes/add'; 
    //     $data = [            
    //         'isEdit' => $isEdit??null,             
    //     ];
    //     if($id){           
    //         $data['data'] = get_records('admission_enquiries', [            
    //             'orderBy' => 'id',
    //             'filters' => ['id'=>$id],//,'status'=>'0'         
    //         ]);
    //     }else{            
    //         $data['data'] = null;
    //     }
    //     $data['countries'] = get_dropdown('countries', 'id', 'name', ['status' => 0],'Empty');
    //     $data['states'] = get_dropdown('states', 'id', 'name', ['status' => 0],'Empty');
    //     $data['financial_year'] = get_dropdown('financial_year', 'id', 'name', [],'Empty');

    //     $data['loadDatePicker'] = true; 
    //     $this->content_data = [
    //         'data' => $data,
    //         'breadcrumbs' => [
    //             ['title' => 'Dashboard', 'url' => site_url('dashboard')],
    //             ['title' => 'Admission Enquiry', 'url' => site_url('AdmissionEnquiry')],
    //             ['title' => $id ? 'Edit' : 'Add', 'url' => '', 'active' => true]
    //         ]
    //     ];        
    //     return $this->render();
    // }
    public function addEnquiry($id = null)
    {   
        $isEdit = !is_null($id);        
        $this->title = $id ? 'Edit Admission Enquiry' : 'Add Admission Enquiry';
        $this->content_view = 'enquiry/enquiry_form'; 
        $data = [            
            'isEdit' => $isEdit??null,             
        ];
        if($id){           
            $data['data'] = get_records('admission_enquiries', [            
                'orderBy' => 'id',
                'filters' => ['id'=>$id],//,'status'=>'0'         
            ]);
        }else{            
            $data['data'] = null;
        }
        $data['countries'] = get_dropdown('countries', 'id', 'name', ['status' => 0],'Empty');
        $data['states'] = get_dropdown('states', 'id', 'name', ['status' => 0],'Empty');
        $data['financial_year'] = get_dropdown('financial_year', 'id', 'name', [],'Empty');

        $data['loadDatePicker'] = true; 
        $this->thumbnails = [                
                ['title' => 'Enquiries List', 'url' => site_url('enquiry/list')],
                ['title' => 'Add Student Enquiry', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }

    public function saveEnquiry()
    {   // Check if it's a POST request
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        // Get the current user's school_id
        $schoolId = 0;
        $userId = auth()->id();
        $user = $this->userModel->find($userId);        
        $schoolId = $user->school_id ?? null;
        if (!$schoolId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to determine your school. Please contact administrator.');
        }
        // Get form data
        $enquiryId = $this->request->getPost('id');
        $isEdit = !empty($enquiryId);
        $dob = $this->request->getPost('date_of_birth');
        if (!empty($dob)) {
            $dob = date('Y-m-d', strtotime(str_replace('/', '-', $dob)));
        }
        // Prepare data for validation and saving
        $data = [
            'student_name' => $this->request->getPost('student_name'),
            'date_of_birth' => $dob,
            'gender' => $this->request->getPost('gender'),
            'nationality' => $this->request->getPost('nationality'),
            'current_institution' => $this->request->getPost('current_institution'),
            'father_name' => $this->request->getPost('father_name'),
            'mother_name' => $this->request->getPost('mother_name'),
            'mobile' => $this->request->getPost('mobile'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'country_id' => (int)$this->request->getPost('country_id'),
            'state_id' => (int)$this->request->getPost('state_id'),
            'city_id' => (int)$this->request->getPost('city_id'),
            'school_id' =>(int)trim($schoolId),
            'address_pincode' => $this->request->getPost('address_pincode'),
            'course_applying' => $this->request->getPost('course_applying'),
            'academic_year' => $this->request->getPost('academic_year'),
            'preferred_campus' => $this->request->getPost('preferred_campus'),
            'heard_from' => $this->request->getPost('heard_from'),
            'special_requirements' => $this->request->getPost('special_requirements'),
            'questions' => $this->request->getPost('questions'),
            'updated_by' => $userId,
        ];
        // Only add created_by for new records
        if (!$isEdit) {
            $data['created_by'] = $userId;
        }
        // Add status fields for edit mode
        if ($isEdit) {
            $data['status'] = $this->request->getPost('status') ?? 0;
            $data['status_note'] = $this->request->getPost('status_note');
        }        
        try {
            // Run validation
            if (!$this->validate($this->enquiryModel->validationRules, $this->enquiryModel->validationMessages)) {                
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }
            // Save or update the enquiry
            if ($isEdit) {
                $this->enquiryModel->update($enquiryId, $data);
                $message = 'Enquiry updated successfully!';
            } else {
                $this->enquiryModel->insert($data);
                $enquiryId = $this->enquiryModel->getInsertID();
                $message = 'Enquiry created successfully!';
            }

            return redirect()->to('/enquiry/list')
                ->with('message', $message);

        } catch (\Exception $e) {
            log_message('error', 'Enquiry save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error saving enquiry: ' . $e->getMessage());
        }
    }   

    // public function submitEnquiry() {
    //     if (!$this->validate($this->enquiryModel->validationRules, $this->enquiryModel->validationMessages)) {
    //         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    //     }

    //     $data = [
    //         'student_name'      => $this->request->getPost('student_name'),
    //         'date_of_birth'     => $this->request->getPost('date_of_birth'),
    //         'gender'            => $this->request->getPost('gender'),
    //         'nationality'       => $this->request->getPost('nationality'),
    //         'current_institution' => $this->request->getPost('current_institution'),
    //         'father_name'       => $this->request->getPost('father_name'),
    //         'mother_name'       => $this->request->getPost('mother_name'),
    //         'mobile'            => $this->request->getPost('mobile'),
    //         'email'            => $this->request->getPost('email'),
    //         'address'          => $this->request->getPost('address'),
    //         'address_country'   => $this->request->getPost('address_country'),
    //         'address_state'     => $this->request->getPost('address_state'),
    //         'address_city'     => $this->request->getPost('address_city'),
    //         'address_pincode'  => $this->request->getPost('address_pincode'),
    //         'course_applying'  => $this->request->getPost('course_applying'),
    //         'academic_year'    => $this->request->getPost('academic_year'),
    //         'preferred_campus' => $this->request->getPost('preferred_campus'),
    //         'heard_from'       => $this->request->getPost('heard_from'),
    //         'special_requirements' => $this->request->getPost('special_requirements'),
    //         'questions'        => $this->request->getPost('questions'),
    //     ];

    //     if ($this->enquiryModel->save($data)) {
    //         return redirect()->to('/enquiry/success');
    //     } else {
    //         return redirect()->back()->withInput()->with('error', 'Failed to submit the enquiry.');
    //     }
    // }  
      
    // LISTING
    public function listEnquiries()
    {          
        $this->title = 'Enquiries List';
        $this->content_view = 'enquiry/enquiries'; 
        $data['data'] = $this->enquiryModel->orderBy('id', 'DESC')->findAll();  
        // $data['data'] = get_records('admission_enquiries', [            
        //     'orderBy' => 'id'            
        // ]);
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive; 
        $this->thumbnails = [                
                ['title' => 'Enquiries List', 'url' => '', 'active' => true]
        ];       
        $this->content_data = [
            'data' => $data,            
        ];  
        return $this->render();
    }
   

    public function delete($id)
    {
        if ($this->enquiryModel->delete($id)) {
            return redirect()->to('/enquiry/list')->with('success', 'Enquiry deleted successfully');
        } else {
            return redirect()->to('/enquiry/list')->with('error', 'Failed to delete enquiry');
        }
    }
    // END ADDED
}