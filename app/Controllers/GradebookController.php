<?php namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\ClassStudentModel;
use App\Models\GradeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class GradebookController extends BaseController
{
    protected $classModel;
    protected $classStudentModel;
    protected $gradeModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->classStudentModel = new ClassStudentModel();
        $this->gradeModel = new GradeModel();
    }

    public function index($classId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $students = $this->classStudentModel->getStudentsByClass($classId);

        $studentGrades = [];
        foreach ($students as $student) {
            $studentGrades[$student['student_id']] = [
                'student' => $student,
                'grades' => $this->gradeModel->getStudentGrades($student['student_id'], $classId),
                'final' => $this->gradeModel->calculateFinalGrade($student['student_id'], $classId)
            ];
        }

        $data = [
            'title' => 'Gradebook - ' . $class['class_name'],
            'class' => $class,
            'studentGrades' => $studentGrades
        ];

        return view('gradebook/index', $data);
    }

    public function studentReport($classId, $studentId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $studentModel = new \App\Models\StudentModel();
        $student = $studentModel->find($studentId);
        if (!$student) {
            throw PageNotFoundException::forPageNotFound();
        }

        $gradeData = $this->gradeModel->calculateFinalGrade($studentId, $classId);

        $data = [
            'title' => 'Grade Report - ' . $student['name'],
            'class' => $class,
            'student' => $student,
            'grades' => $this->gradeModel->getStudentGrades($studentId, $classId),
            'finalGrade' => $gradeData['final_grade'],
            'categories' => $gradeData['categories']
        ];

        return view('gradebook/student_report', $data);
    }
}