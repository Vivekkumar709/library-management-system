<?php
namespace App\Controllers;

class ResultController extends BaseController
{
    protected $resultModel;

    public function __construct()
    {
        $this->resultModel = model('ResultModel');
    }

    // ==================== ENTER MARKS ====================
    public function enter()
    {
        $this->title = 'Enter Student Marks';
        $this->content_view = 'result/enter';

        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Class');
        $data['exam_types'] = get_dropdown('exam_types', 'id', 'name', ['school_id' => auth()->user()->school_id], 'Exam');
        $data['subjects'] = model('SubjectModel')->getClassSubjects(); // we'll create this

        $this->content_data = ['data' => $data];
        return $this->render();
    }

    // ==================== SAVE MARKS ====================
    public function saveMarks()
    {
        $post = $this->request->getPost();
        $exam_id = $post['exam_type_id'];

        foreach ($post['marks'] as $student_id => $marks_data) {
            foreach ($marks_data as $subject_id => $obtained) {
                $this->resultModel->saveMark([
                    'student_id'     => $student_id,
                    'exam_type_id'   => $exam_id,
                    'subject_id'     => $subject_id,
                    'marks_obtained' => $obtained,
                ]);
            }
        }

        // Auto generate result summary
        $this->resultModel->generateResultSummary($exam_id);

        return redirect()->to('/result/enter')
            ->with('success', 'Marks saved & Result generated successfully!');
    }

    // ==================== VIEW / PUBLISH RESULT ====================
    public function report($student_id = null)
    {
        $this->title = $student_id ? 'Student Result' : 'Class Result';
        $this->content_view = 'result/report';

        $data['results'] = $this->resultModel->getAllResults($student_id);
        $data['students'] = model('StudentModel')->getStudents();

        $this->content_data = ['data' => $data];
        $this->loadResponsiveTable = true;

        return $this->render();
    }

    public function printReport($student_id, $exam_id)
    {
        $data['result'] = $this->resultModel->getStudentResult($student_id, $exam_id);
        return view('result/print', $data);   // A4 Marksheet
    }
}