<?php namespace App\Controllers;

use App\Models\ClassStudentModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ClassStudents extends BaseController
{
    protected $classStudentModel;
    protected $classModel;
    protected $studentModel;

    public function __construct()
    {
        $this->classStudentModel = new ClassStudentModel();
        $this->classModel = new ClassModel();
        $this->studentModel = new StudentModel(); // Assuming you have a StudentModel
    }

    public function index($classId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Class Students - ' . $class['class_name'],
            'class' => $class,
            'students' => $this->classStudentModel->getStudentsByClass($classId),
            'availableStudents' => $this->studentModel->findAll()
        ];

        return view('class_students/index', $data);
    }

    public function enroll($classId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'student_id' => 'required|numeric',
            'status' => 'permit_empty|in_list[active,inactive,transferred,graduated]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'class_id' => $classId,
            'student_id' => $this->request->getPost('student_id'),
            'enrollment_date' => date('Y-m-d'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->classStudentModel->save($data)) {
            return redirect()->to("/classes/{$classId}/students")->with('message', 'Student enrolled successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->classStudentModel->errors());
    }

    public function unenroll($classId, $studentId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->classStudentModel->delete([$classId, $studentId])) {
            return redirect()->to("/classes/{$classId}/students")->with('message', 'Student unenrolled successfully');
        }

        return redirect()->to("/classes/{$classId}/students")->with('errors', $this->classStudentModel->errors());
    }

    public function updateStatus($classId, $studentId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();
        $validation->setRule('status', 'Status', 'required|in_list[active,inactive,transferred,graduated]');

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->classStudentModel->where('class_id', $classId)
                               ->where('student_id', $studentId)
                               ->set(['status' => $this->request->getPost('status')])
                               ->update();

        return redirect()->to("/classes/{$classId}/students")->with('message', 'Student status updated successfully');
    }
}