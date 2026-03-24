<?php
namespace App\Controllers;

class CertificateController extends BaseController
{
    protected $certModel;

    public function __construct()
    {
        $this->certModel = model('CertificateModel');
    }

    // ==================== GENERATE CERTIFICATE ====================
    public function generate($student_id, $type = 'bonafide') // bonafide, tc, character, etc.
    {
        $student = model('StudentModel')->getStudentWithDetails($student_id);
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found');
        }

        $template = $this->certModel->getTemplate($type, auth()->user()->school_id);
        if (!$template) {
            return redirect()->back()->with('error', 'No template found for this certificate type');
        }

        $this->title = ucfirst($type) . ' Certificate - ' . $student['first_name'];
        $this->content_view = 'certificate/generate';

        $this->content_data = [
            'data' => [
                'student'   => $student,
                'template'  => $template,
                'type'      => $type,
                'issue_date'=> date('d-m-Y')
            ]
        ];

        $this->thumbnails = [
            ['title' => 'Students', 'url' => site_url('students')],
            ['title' => 'Certificates', 'url' => '', 'active' => true]
        ];

        return $this->render();
    }

    // ==================== PRINT / DOWNLOAD ====================
    public function print($student_id, $type)
    {
        $data = $this->content_data['data']; // reuse same data logic
        $student = model('StudentModel')->getStudentWithDetails($student_id);
        $template = $this->certModel->getTemplate($type, auth()->user()->school_id);

        return view('certificate/print', [
            'student'   => $student,
            'template'  => $template,
            'type'      => $type,
            'issue_date'=> date('d-m-Y')
        ]);
    }
}