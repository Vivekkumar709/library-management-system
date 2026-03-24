<?php
namespace App\Controllers;

use App\Models\StudentAcademicHistoryModel;
use App\Models\StudentModel;

class StudentAcademicHistoryController extends BaseController
{
    protected $academicHistoryModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->academicHistoryModel = new StudentAcademicHistoryModel();
        $this->studentModel = new StudentModel();
    }
    
    // List academic history for a student
    public function list($studentId)
    {   
        // Get student details
        $student = $this->studentModel->getStudentWithDetails($studentId);
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }
        
        $data['academicHistory'] = $this->academicHistoryModel->getAcademicHistory($studentId);
        $data['student'] = $student;
        $data['performance'] = $this->academicHistoryModel->getPerformanceSummary($studentId);
        
        $this->title = 'Academic History - ' . $student['first_name'] . ' ' . $student['last_name'];
        $this->content_view = 'students/academic_history/list';
        $this->thumbnails = [
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Student Academic History', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Add academic record form
    public function add($studentId)
    {
        // Get student details
        $student = $this->studentModel->getStudentWithDetails($studentId);
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }
        
        $data['student'] = $student;
        $data['isEdit'] = false;
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Empty');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Empty');
        
        $this->title = 'Add Academic Record - ' . $student['first_name'] . ' ' . $student['last_name'];
        $this->content_view = 'students/academic_history/add';
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Academic History', 'url' => site_url('students/academic-history/' . $studentId)],
                ['title' => 'Add Record', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }
    
    // Edit academic record form
    public function edit($recordId)
    {
        $record = $this->academicHistoryModel->getRecordWithDetails($recordId);
        if (!$record) {
            return redirect()->to('/students')->with('error', 'Academic record not found.');
        }
        
        $data['record'] = $record;
        $data['isEdit'] = true;
        $data['student'] = $this->studentModel->getStudentWithDetails($record['student_id']);
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Financial Year');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Class');
        
        $this->title = 'Edit Academic Record - ' . $data['student']['first_name'] . ' ' . $data['student']['last_name'];
        $this->content_view = 'students/academic_history/add';
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Academic History', 'url' => site_url('students/academic-history/' . $record['student_id'])],
                ['title' => 'Edit Record', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }
    
    // Save academic record
    public function save()
    {
        $id = $this->request->getPost('id');
        $studentId = $this->request->getPost('student_id');
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'student_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'financial_year_id' => 'required|integer',
            'roll_no' => 'permit_empty|max_length[50]',
            'percentage' => 'permit_empty|decimal',
            'status' => 'permit_empty|max_length[20]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $data = [
            'student_id' => $studentId,
            'class_id' => $this->request->getPost('class_id'),
            'section_id' => $this->request->getPost('section_id'),
            'financial_year_id' => $this->request->getPost('financial_year_id'),
            'roll_no' => $this->request->getPost('roll_no'),
            'percentage' => $this->request->getPost('percentage'),
            'status' => $this->request->getPost('status') ?? 'Completed'
        ];
        
        // Check if academic record already exists for this student, class, and financial year
        if ($this->academicHistoryModel->isRecordExists(
            $studentId, 
            $data['class_id'], 
            $data['financial_year_id'],
            $id
        )) {
            return redirect()->back()->withInput()->with('error', 'Academic record already exists for this class and financial year.');
        }
        
        if ($id) {
            // Edit mode
            $data['updated_by'] = auth()->id();
            $result = $this->academicHistoryModel->update($id, $data);
            
            if ($result) {
                return redirect()->to('/students/academic-history/' . $studentId)->with('success', 'Academic record updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update academic record.');
            }
        } else {
            // Create mode
            $data['created_by'] = auth()->id();
            $result = $this->academicHistoryModel->insert($data);
            
            if ($result) {
                return redirect()->to('/students/academic-history/' . $studentId)->with('success', 'Academic record added successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to add academic record.');
            }
        }
    }
    
    // Delete academic record
    public function delete($recordId)
    {
        $record = $this->academicHistoryModel->find($recordId);
        
        if (!$record) {
            return redirect()->back()->with('error', 'Academic record not found.');
        }
        
        $studentId = $record['student_id'];
        
        if ($this->academicHistoryModel->delete($recordId)) {
            return redirect()->to('/students/academic-history/' . $studentId)->with('success', 'Academic record deleted successfully!');
        } else {
            return redirect()->to('/students/academic-history/' . $studentId)->with('error', 'Failed to delete academic record.');
        }
    }
    
    // Get sections by class for AJAX
    public function getSectionsByClass($classId, $financialYearId)
    {              
        $sections = get_advanced_dropdown([
            'tables' => ['sections s'],
            'joins' => [
                [
                    'table' => 'm_sections ms', 
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
                's.financial_year_id' => $financialYearId, 
            ],
            'orderBy' => 'ms.name ASC',
            'selectPostfix' => 'Empty'
        ]);                                       
        return $this->response->setJSON($sections);
    }
}