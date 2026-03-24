<?php
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

// use App\Models\StudentModel;
// use CodeIgniter\Shield\Entities\User;
// use CodeIgniter\Shield\Models\UserModel;
// use CodeIgniter\Shield\Models\UserIdentityModel;
// use CodeIgniter\Exceptions\PageNotFoundException;

class AdmitCardController extends BaseController
{
    public function generate($student_id)
    {
        $student = model('StudentModel')->getStudentWithDetails($student_id);
        //echo "<pre>"; print_r(auth()->user()->school_id); echo "</pre>";die;
        $examTypes = model('ExamModel')->where('school_id', auth()->user()->school_id)->findAll(); // create ExamModel

        $this->title = 'Generate Admit Card';
        $this->content_view = 'admit_card/generate';

        $this->content_data = [
            'data' => ['student' => $student, 'exams' => $examTypes]
        ];
        return $this->render();
    }

    public function print($student_id, $exam_id = null)
    {   
        $this->title = 'Print Admit Card';
        $this->content_view = 'admit_card/print';
        $student = model('StudentModel')->getStudentWithDetails($student_id);

        $this->content_data = [
            'data' => ['student' => $student]
        ];
        return $this->render();
        // A4 print layout
    }
}