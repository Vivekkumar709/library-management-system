<?php
namespace App\Controllers;

use App\Models\ClassScheduleModel;

class ClassScheduleController extends BaseController
{
    protected $classScheduleModel;
    
    public function __construct()
    {
        $this->classScheduleModel = new ClassScheduleModel();
        $this->db = \Config\Database::connect();
    }    
    // List all class schedules
    public function list()
    {
        $filters = [];        
        // Add filter by financial year if needed
        $financialYearId = $this->request->getGet('financial_year_id')?? FINANCIAL_YEAR_ID;
        if ($financialYearId) {
            $filters['cs.financial_year_id'] = $financialYearId;
        }        
        $data['classSchedules'] = $this->classScheduleModel->getClassSchedules($filters);
        $this->title = 'Class Schedules';
        $this->content_view = 'classSchedule/list';
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [
               // ['title' => 'Class Schedules', 'url' => site_url('class-schedules')],
                ['title' => 'Class Schedules', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    }

    // Add/Edit class schedule form
    public function add($id = null)
    {
        $isEdit = !is_null($id);
        $this->title = $id ? 'Edit Class Schedule' : 'Add Class Schedule';
        $this->content_view = 'classSchedule/add';
        
        $data = ['isEdit' => $isEdit];
        
        if ($isEdit) {
            $data['classSchedule'] = $this->classScheduleModel->find($id);
            if (!$data['classSchedule']) {
                return redirect()->to('/class-schedules')->with('error', 'Class schedule not found.');
            }
        }     

        // Get dropdown data        
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Financial Year');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Class');        
        $data['teachers'] = get_dropdown('users', 'id', 'first_name', ['school_id'=>auth()->user()->school_id,'user_type_id'=>13], 'Teacher');        
        // Get subjects based on class level (if class is selected)
        $classId = $isEdit ? $data['classSchedule']['class_id'] : null;
        $data['subjects'] = [];
        
        if ($classId) {
            // Get class number to determine appropriate subjects
            $class = $this->db->table('m_classes')->where('id', $classId)->get()->getRowArray();
            if ($class) {
                $data['subjects'] = $this->classScheduleModel->getSubjectsByClassLevel($class['class_number']);
            }
        }
        
        // Days of week
        $data['days'] = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday'
        ];

        $this->thumbnails = [
                ['title' => 'Class Schedules', 'url' => site_url('class-schedules')],
                ['title' => 'Add Class Schedule', 'url' => '', 'active' => true]
        ];
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Class Schedules', 'url' => site_url('class-schedules')],
                ['title' => $isEdit ? 'Edit' : 'Add', 'url' => '', 'active' => true]
            ]
        ];
        
        return $this->render();
    }
    
    // Get sections by class for AJAX
    public function getSectionsByClass($classId)
    {
        $sections = get_dropdown('sections', 'id', 'section_id', ['class_id' => $classId, 'status' => 0], 'Empty');
        return $this->response->setJSON($sections);
    }
    
    // Get subjects by class for AJAX
    public function getSubjectsByClass($classId)
    {
        $class = $this->db->table('m_classes')->where('id', $classId)->get()->getRowArray();        
        $subjects = [];                
        if ($class) {
            $subjectsData = $this->classScheduleModel->getSubjectsByClassLevel($class['class_number']);            
            // Format for dropdown
            $subjects = [];//'' => 'Select Subject'
            foreach ($subjectsData as $subject) {
                $subjects[$subject['id']] = $subject['name'] . ' (' . $subject['category'] . ')';
            }
        }        
        return $this->response->setJSON($subjects);
    }
    
    // Get available teachers for time slot
    public function getAvailableTeachers()
    {
        $dayOfWeek = $this->request->getPost('day_of_week');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');
        $financialYearId = $this->request->getPost('financial_year_id');
        $excludeTeacherId = $this->request->getPost('exclude_teacher_id');
        
        $availableTeachers = $this->classScheduleModel->getAvailableTeachers(
            $dayOfWeek, $startTime, $endTime, $financialYearId, $excludeTeacherId
        );
        
        $options = '<option value="">Select Teacher</option>';
        foreach ($availableTeachers as $teacher) {
            $options .= '<option value="' . $teacher['id'] . '">' . $teacher['name'] . '</option>';
        }
        
        return $this->response->setJSON(['teachers' => $options]);
    }
    
    // Save class schedule
    public function save()
    {
        $id = $this->request->getPost('id');
        $validation = \Config\Services::validation();
        
        if ($id) {
            // EDIT MODE: Skip financial_year_id, class_id, section_id validation
            $validation->setRules([
                'subject_id' => 'required|integer',
                'teacher_id' => 'required|integer',           
                'day_of_week' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[7]',
                'start_time' => 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]',
                'end_time' => 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]',            
                'room_number' => 'permit_empty|max_length[20]',
                'status' => 'permit_empty|in_list[0,1]'
            ]);
        } else {
            // CREATE MODE: Validate all fields
            $validation->setRules([
                'financial_year_id' => 'required|integer',
                'class_id' => 'required|integer',
                'section_id' => 'required|integer',
                'subject_id' => 'required|integer',
                'teacher_id' => 'required|integer',           
                'day_of_week' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[7]',
                'start_time' => 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]',
                'end_time' => 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]',            
                'room_number' => 'permit_empty|max_length[20]',
                'status' => 'permit_empty|in_list[0,1]'
            ]);
        }
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        if ($id) {
            // EDIT MODE: Get existing record first
            $existingRecord = $this->classScheduleModel->find($id);                        
            $data = [
                'subject_id' => $this->request->getPost('subject_id'),
                'teacher_id' => $this->request->getPost('teacher_id'),
                'day_of_week' => $this->request->getPost('day_of_week'),
                'start_time' => $this->request->getPost('start_time'),
                'end_time' => $this->request->getPost('end_time'),
                'room_number' => $this->request->getPost('room_number'),
                // Use existing values for financial_year_id, class_id, section_id
                'financial_year_id' => $existingRecord['financial_year_id'],
                'class_id' => $existingRecord['class_id'],
                'section_id' => $existingRecord['section_id'],
            ];
        } else {
            // CREATE MODE: Get all values from POST
            $data = [
                'financial_year_id' => $this->request->getPost('financial_year_id'),
                'class_id' => $this->request->getPost('class_id'),
                'section_id' => $this->request->getPost('section_id'),
                'subject_id' => $this->request->getPost('subject_id'),
                'teacher_id' => $this->request->getPost('teacher_id'),
                'day_of_week' => $this->request->getPost('day_of_week'),
                'start_time' => $this->request->getPost('start_time'),
                'end_time' => $this->request->getPost('end_time'),
                'room_number' => $this->request->getPost('room_number'),
            ];
        }
        
        // Check if status is provided
        if ($this->request->getPost('status') !== null) {
            $data['status'] = $this->request->getPost('status');
        }
        
        // Validate time order
        if (strtotime($data['start_time']) >= strtotime($data['end_time'])) {
            return redirect()->back()->withInput()->with('error', 'End time must be after start time.');
        }
        
        // Check if time slot is already occupied
        if ($this->classScheduleModel->isTimeSlotOccupied(
            $data['class_id'], 
            $data['section_id'], 
            $data['day_of_week'], 
            $data['start_time'], 
            $data['end_time'], 
            $data['financial_year_id'],
            $id
        )) {
            return redirect()->back()->withInput()->with('error', 'This time slot is already occupied for the selected class and section.');
        }
        
        // Check if teacher is available
        $availableTeachers = $this->classScheduleModel->getAvailableTeachers(
            $data['day_of_week'], 
            $data['start_time'], 
            $data['end_time'], 
            $data['financial_year_id'],
            $id ? $data['teacher_id'] : null
        );
        
        $teacherAvailable = false;
        foreach ($availableTeachers as $teacher) {
            if ($teacher['id'] == $data['teacher_id']) {
                $teacherAvailable = true;
                break;
            }
        }
        
        if (!$teacherAvailable) {
            return redirect()->back()->withInput()->with('error', 'The selected teacher is not available during this time slot.');
        }
        
        if ($id) {
            // Update existing record
            $data['updated_by'] = auth()->id();
            $result = $this->classScheduleModel->update($id, $data);
            
            if ($result) {
                return redirect()->to('/class-schedules')->with('success', 'Class schedule updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update class schedule.');
            }
        } else {
            $data['created_by'] = auth()->id();
            $data['school_id'] = auth()->user()->school_id;
            $result = $this->classScheduleModel->insert($data);
            
            if ($result) {
                return redirect()->to('/class-schedules')->with('success', 'Class schedule created successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to create class schedule.');
            }
        }
    }    
    // Delete class schedule
    public function delete($id)
    {
        $classSchedule = $this->classScheduleModel->find($id);
        
        if (!$classSchedule) {
            return redirect()->to('/class-schedules')->with('error', 'Class schedule not found.');
        }
        
        if ($this->classScheduleModel->delete($id)) {
            return redirect()->to('/class-schedules')->with('success', 'Class schedule deleted successfully!');
        } else {
            return redirect()->to('/class-schedules')->with('error', 'Failed to delete class schedule.');
        }
    }
    
    // View timetable by class and section  
    public function timetable($classId = null, $sectionId = null)
    {
        $financialYearId = $this->request->getGet('financial_year_id') ?? FINANCIAL_YEAR_ID;        
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Empty');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Empty');
        $data['sections'] = get_dropdown('sections', 'id', 'section_id', ['class_id' => $classId, 'status' => 0], 'Section');

        // Pass classId and sectionId to the view data
        $classId = $this->request->getGet('class_id') ?? $classId;
        $sectionId = $this->request->getGet('section_id') ?? $sectionId;
    
        $data['classId'] = $classId;
        $data['sectionId'] = $sectionId;
        
        if ($classId && $sectionId) {
            
            $data['timetable'] = $this->classScheduleModel->getScheduleByClassSection($classId, $sectionId, $financialYearId);
            $data['selectedClass'] = $classId;
            $data['selectedSection'] = $sectionId;
            $data['selectedFinancialYear'] = $financialYearId;             

            $selectedSectionName = get_records('m_sections', [
                'joins' => [
                    ['table' => 'sections', 'condition' => 'm_sections.id = sections.section_id']
                ],
                'select' => ['m_sections.name AS name'],
                'filters' => ['sections.id' => $sectionId, 'sections.class_id' => $classId],
                'single' => true
            ]);
            
            $selectedSectionFor = get_records('m_section_for', [
                'joins' => [
                    ['table' => 'sections', 'condition' => 'sections.section_for = m_section_for.id']
                ],
                'select' => ['m_section_for.name AS name'],
                'filters' => ['sections.section_id' => $sectionId, 'sections.class_id' => $classId],
                'single' => true
            ]);           

            $data['selectedSectionName'] = $selectedSectionName['name']??null; 
            $data['selectedSectionFor'] = $selectedSectionFor['name']?? null;       
            // Get sections for the selected class
            // Format timetable by day and time
            $formattedTimetable = [];
            $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
            
            foreach ($days as $dayId => $dayName) {
                $formattedTimetable[$dayId] = [
                    'name' => $dayName,
                    'periods' => []
                ];
            }            
            foreach ($data['timetable'] as $schedule) {
                $formattedTimetable[$schedule['day_of_week']]['periods'][] = $schedule;
            }            
            $data['formattedTimetable'] = $formattedTimetable;
        }
        
        $this->title = 'Class Timetable';
        $this->content_view = 'classSchedule/timetable'; // Make sure this path is correct
        $this->thumbnails = [
                ['title' => 'Class Schedules', 'url' => site_url('class-schedules')],
                ['title' => 'Timetable', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    }
}