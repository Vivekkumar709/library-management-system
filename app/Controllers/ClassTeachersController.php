<?php
namespace App\Controllers;

use App\Models\ClassTeacherModel;

class ClassTeachersController extends BaseController
{
    protected $classTeacherModel;
    
    public function __construct()
    {
        $this->classTeacherModel = new ClassTeacherModel();
    }    
    // List all class teachers
    public function list()
    {   
        $data['classTeachers'] = $this->classTeacherModel->getClassTeachers();        
        $this->title = 'Class Teachers';
        $this->content_view = 'classTeachers/list';
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Class Teachers', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Add/Edit class teacher form
    public function add($id = null)
    {
        $isEdit = !is_null($id);
        $this->title = $id ? 'Edit Class Teacher' : 'Add Class Teacher';
        $this->content_view = 'classTeachers/add';
        
        $data = ['isEdit' => $isEdit];
        
        if ($isEdit) {
            $data['data'] = $this->classTeacherModel->find($id);
            if (!$data['data']) {
                return redirect()->to('/class-teachers')->with('error', 'Class teacher not found.');
            }
        }
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Financial Year');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Class');
        $data['teachers'] = get_dropdown('users', 'id', 'first_name', ['user_type_id'=>13], 'Teacher');
        $data['sections'] = get_dropdown('m_sections', 'id', 'name', ['status' => 0], 'Section');
        //'status' => 0
        $this->thumbnails = [
                ['title' => 'Class Teachers', 'url' => site_url('class-teachers')],
                ['title' => 'Add Class Teacher', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];       
        
        return $this->render();
    }
    
    // Get sections by class for AJAX
    public function getSectionsByClass($classId,$session_year)
    {              
        $sections = get_advanced_dropdown([
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
                's.class_id' => $classId,
                's.school_id' => auth()->user()->school_id,
                's.financial_year_id' => $session_year, 
            ],
            'orderBy' => 'ms.name ASC',
            'selectPostfix' => 'Empty'
        ]);                                       
        return $this->response->setJSON($sections);
    }
    
    // Save class teacher
    public function save()
    {
        $id = $this->request->getPost('id');
        
        // Different validation rules for create vs edit
        $validation = \Config\Services::validation();
        
        if ($id) {
            // EDIT MODE: Only validate teacher_id and status
            $validation->setRules([
                'teacher_id' => 'required|integer',
                'status' => 'permit_empty|in_list[0,1]'
            ]);
        } else {
            // CREATE MODE: Validate all required fields
            $validation->setRules([
                'financial_year_id' => 'required|integer',
                'class_id' => 'required|integer',
                'section_id' => 'required|integer',
                'teacher_id' => 'required|integer',
                'status' => 'permit_empty|in_list[0,1]'
            ]);
        }
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        if ($id) {
            // EDIT MODE: Only update teacher_id and status
            $data = [
                'teacher_id' => $this->request->getPost('teacher_id'),
                'updated_by' => auth()->id(),
                'updated_at' => date('Y-m-d H:i:s') // Add timestamp if your model doesn't handle it automatically
            ];
            
            // Check if status is provided
            if ($this->request->getPost('status') !== null) {
                $data['status'] = $this->request->getPost('status');
            }
            
            // Get existing record to use financial_year_id, class_id, section_id for validation
            $existingRecord = $this->classTeacherModel->find($id);
            
            // Check if teacher is already assigned to this class/section
            if ($this->classTeacherModel->isTeacherAssigned(
                $data['teacher_id'], 
                $existingRecord['class_id'], 
                $existingRecord['section_id'], 
                $existingRecord['financial_year_id'],
                $id
            )) {
                return redirect()->back()->withInput()->with('error', 'This teacher is already assigned to the selected class and section.');
            }
            
            $result = $this->classTeacherModel->update($id, $data);
            
            if ($result) {
                return redirect()->to('/class-teachers')->with('success', 'Class teacher updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update class teacher.');
            }
        } else {
            // CREATE MODE: Original logic for new records
            $data = [
                'financial_year_id' => $this->request->getPost('financial_year_id'),
                'class_id' => $this->request->getPost('class_id'),
                'section_id' => $this->request->getPost('section_id'),
                'teacher_id' => $this->request->getPost('teacher_id'),
                'created_by' => auth()->id()
            ];
            
            // Check if status is provided
            if ($this->request->getPost('status') !== null) {
                $data['status'] = $this->request->getPost('status');
            }
            
            // Check if teacher is already assigned to this class/section
            if ($this->classTeacherModel->isTeacherAssigned(
                $data['teacher_id'], 
                $data['class_id'], 
                $data['section_id'], 
                $data['financial_year_id'],
                $id
            )) {
                return redirect()->back()->withInput()->with('error', 'This teacher is already assigned to the selected class and section.');
            }
            
            $result = $this->classTeacherModel->insert($data);
            
            if ($result) {
                return redirect()->to('/class-teachers')->with('success', 'Class teacher assigned successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to assign class teacher.');
            }
        }
    }    
    
    // Delete class teacher
    public function delete($id)
    {
        $classTeacher = $this->classTeacherModel->find($id);
        
        if (!$classTeacher) {
            return redirect()->to('/class-teachers')->with('error', 'Class teacher not found.');
        }
        
        if ($this->classTeacherModel->delete($id)) {
            return redirect()->to('/class-teachers')->with('success', 'Class teacher deleted successfully!');
        } else {
            return redirect()->to('/class-teachers')->with('error', 'Failed to delete class teacher.');
        }
    }
}