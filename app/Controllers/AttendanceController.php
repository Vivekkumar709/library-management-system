<?php
namespace App\Controllers;

class AttendanceController extends BaseController
{
    protected $attModel;

    public function __construct()
    {
        $this->attModel = model('AttendanceModel');
    }

    // ==================== MARK ATTENDANCE ====================
    public function mark()
    {
        $this->title = 'Mark Attendance';
        $this->content_view = 'attendance/mark';

        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0]);
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0]);

        $this->content_data = ['data' => $data];
        return $this->render();
    }

    // ==================== SAVE BULK ATTENDANCE ====================
    public function save()
    {
        $post = $this->request->getPost();
        $date = $post['attendance_date'] ?? date('Y-m-d');

        foreach ($post['status'] as $student_id => $status) {
            $this->attModel->insertOrUpdate([
                'student_id'      => $student_id,
                'class_id'        => $post['class_id'],
                'section_id'      => $post['section_id'],
                'financial_year_id'=> $post['financial_year_id'],
                'attendance_date' => $date,
                'status'          => $status,
                'remark'          => $post['remark'][$student_id] ?? '',
                'created_by'      => auth()->id()
            ]);
        }

        return redirect()->to('/attendance/mark')
            ->with('success', 'Attendance marked successfully for ' . date('d-m-Y', strtotime($date)));
    }

    // ==================== REPORT / VIEW ====================
    public function report($student_id = null)
    {
        $this->title = $student_id ? 'Student Attendance Report' : 'Attendance Report';
        $this->content_view = 'attendance/report';

        $data['report'] = $this->attModel->getMonthlySummary($student_id);
        $data['students'] = model('StudentModel')->getStudents();

        $this->content_data = ['data' => $data];
        $this->loadResponsiveTable = true;

        return $this->render();
    }

    // AJAX - Get students for selected class-section
    public function getStudents()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        $class_id = $this->request->getPost('class_id');
        $section_id = $this->request->getPost('section_id');
        $fy_id = $this->request->getPost('financial_year_id');

        $students = model('StudentModel')->getStudentsByClassSection($class_id, $section_id, $fy_id);

        return $this->response->setJSON($students);
    }
}