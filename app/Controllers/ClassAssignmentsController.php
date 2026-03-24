<?php
namespace App\Controllers;

use App\Models\ClassAssignmentModel;
use App\Models\ClassAssignmentSubmissionModel;

class ClassAssignmentsController extends BaseController
{
    protected $classAssignmentModel;
    protected $submissionModel;
    
    public function __construct()
    {
        $this->classAssignmentModel = new ClassAssignmentModel();
        $this->submissionModel = new ClassAssignmentSubmissionModel();
    }
    
    // List all assignments
    public function list()
    {   
        $filters = [];
        
        // Add filter by financial year if needed
        $financialYearId = $this->request->getGet('financial_year_id');
        if ($financialYearId) {
            $filters['ca.financial_year_id'] = $financialYearId;
        }
        
        $data['assignments'] = $this->classAssignmentModel->getAssignments($filters);
        
        $this->title = 'Class Assignments';
        $this->content_view = 'classAssignments/list';
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Class Assignments', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Add/Edit assignment form
    public function add($id = null)
    {
        $isEdit = !is_null($id);
        $this->title = $id ? 'Edit Assignment' : 'Add Assignment';
        $this->content_view = 'classAssignments/add';
        
        $data = ['isEdit' => $isEdit];
        
        if ($isEdit) {
            $data['data'] = $this->classAssignmentModel->getAssignmentById($id);
            if (!$data['data']) {
                return redirect()->to('/class-assignments')->with('error', 'Assignment not found.');
            }
        }
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Financial Year');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Class');
        $data['teachers'] = get_dropdown('users', 'id', 'first_name', ['user_type_id'=>13], 'Teacher');
        $data['subjects'] = get_dropdown('m_subjects', 'id', 'name,category', ['status' => 0], 'Subject');
        //echo "<pre>"; print_r($data['subjects']); die;
        $data['loadDatePicker'] = true;
        $this->thumbnails = [
                ['title' => 'Class Assignments', 'url' => site_url('class-assignments')],
                ['title' => 'Add Class Assignments', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];       
        
        return $this->render();
    }
    
    // Get sections by class for AJAX
    // public function getSectionsByClass($classId, $financialYearId = null)
    // {              
    //     if (!$financialYearId) {
    //         $financialYearId = FINANCIAL_YEAR_ID;
    //     }
        
    //     $sections = get_advanced_dropdown([
    //         'tables' => ['m_sections ms'],
    //         'joins' => [
    //             [
    //                 'table' => 'sections s', 
    //                 'condition' => 'ms.id = s.section_id',
    //                 'type' => 'left'
    //             ]
    //         ],
    //         'key' => 's.id', 
    //         'value' => 'ms.name as section_name', 
    //         'where' => [
    //             'ms.status' => 0, 
    //             's.status' => 0, 
    //             's.class_id' => $classId,
    //             's.school_id' => auth()->user()->school_id,
    //             's.financial_year_id' => $financialYearId, 
    //         ],
    //         'orderBy' => 'ms.name ASC',
    //         'selectPostfix' => 'Empty'
    //     ]);                                       
    //     return $this->response->setJSON($sections);
    // }
    
    // Save assignment    
    public function save()
    {
        $id = $this->request->getPost('id');        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'financial_year_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'teacher_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'due_date' => 'required|valid_date',
            'max_marks' => 'permit_empty|integer|greater_than_equal_to[0]',
            'status' => 'permit_empty|in_list[0,1]'
        ]);        
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $due_date = $this->request->getPost('due_date'); 
        $formattedDueDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $due_date)));
        $data = [
            'financial_year_id' => $this->request->getPost('financial_year_id'),
            'class_id' => $this->request->getPost('class_id'),
            'section_id' => $this->request->getPost('section_id'),
            'subject_id' => $this->request->getPost('subject_id'),
            'teacher_id' => $this->request->getPost('teacher_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'due_date' => $formattedDueDate,
            'max_marks' => $this->request->getPost('max_marks') ?: null,
        ];        
        
        // Handle file upload
        $attachment = $this->request->getFile('attachment');
        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            $newName = $attachment->getRandomName();
            
            // If editing and there's an existing attachment, delete it first
            if ($id) {
                // Get the existing record to check for old attachment
                $existingRecord = $this->classAssignmentModel->find($id);
                
                // Delete old attachment if it exists
                if ($existingRecord && !empty($existingRecord['attachment_path'])) {
                    $oldFilePath = ROOTPATH . 'public/' . $existingRecord['attachment_path'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath); // Delete the old file
                    }
                }
            }
            
            // Move the new file
            $attachment->move(ROOTPATH . 'public/uploads/assignments', $newName);
            $data['attachment_path'] = 'uploads/assignments/' . $newName;
        }        
        
        if ($id) {
            // Update existing record
            if ($this->request->getPost('status') !== null) {
                $data['status'] = $this->request->getPost('status');
            }
            $data['updated_by'] = auth()->id();            
            
            // Keep existing attachment if new one not uploaded
            if (empty($data['attachment_path'])) {
                unset($data['attachment_path']);
            }            
            
            $result = $this->classAssignmentModel->update($id, $data);            
            
            if ($result) {
                return redirect()->to('/class-assignments')->with('success', 'Assignment updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update assignment.');
            }
        } else {
            // Insert new record
            $data['created_by'] = auth()->id();
            $data['school_id'] = auth()->user()->school_id;
            
            $result = $this->classAssignmentModel->insert($data);
            
            if ($result) {
                return redirect()->to('/class-assignments')->with('success', 'Assignment created successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to create assignment.');
            }
        }
    }
    
    // View assignment details and submissions
    public function view($id)
    {
        $assignment = $this->classAssignmentModel->getAssignmentById($id);        
        if (!$assignment) {
            return redirect()->to('/class-assignments')->with('error', 'Assignment not found.');
        }        
        $data['assignment'] = $assignment;
        $data['submissions'] = $this->submissionModel->getSubmissionsByAssignment($id);
        $data['submissionCounts'] = $this->submissionModel->countSubmissionsByStatus($id);        
        $this->title = 'Assignment Details';
        $this->content_view = 'classAssignments/view';
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Class Assignments', 'url' => site_url('class-assignments')],
                ['title' => 'View Assignment', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }    
    // Grade submission
    public function gradeSubmission($submissionId)
    {
        $submission = $this->submissionModel->find($submissionId);        
        if (!$submission) {
            return redirect()->back()->with('error', 'Submission not found.');
        }
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'marks_obtained' => 'required|integer|greater_than_equal_to[0]',
            'teacher_remarks' => 'permit_empty'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }
        
        $data = [
            'marks_obtained' => $this->request->getPost('marks_obtained'),
            'teacher_remarks' => $this->request->getPost('teacher_remarks'),
            'status' => 2, // Graded
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->submissionModel->update($submissionId, $data)) {
            return redirect()->back()->with('success', 'Submission graded successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to grade submission.');
        }
    }
    
    // Delete assignment
    public function delete($id)
    {
        $assignment = $this->classAssignmentModel->find($id);
        
        if (!$assignment) {
            return redirect()->to('/class-assignments')->with('error', 'Assignment not found.');
        }
        
        if ($this->classAssignmentModel->delete($id)) {
            return redirect()->to('/class-assignments')->with('success', 'Assignment deleted successfully!');
        } else {
            return redirect()->to('/class-assignments')->with('error', 'Failed to delete assignment.');
        }
    }
    
    // Download attachment
    public function download($id)
    {
        $assignment = $this->classAssignmentModel->find($id);
        
        if (!$assignment || empty($assignment['attachment_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        $filePath = ROOTPATH . 'public/' . $assignment['attachment_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return $this->response->download($filePath, null);
    }
}