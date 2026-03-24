<?php
namespace App\Models;
use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table = 'certificate_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['certificate_type','template_content','school_id'];

    public function getTemplate($type, $school_id)
    {
        return $this->where('certificate_type', $type)
                    ->where('school_id', $school_id)
                    ->first();
    }

    // Optional: method to create default templates (run once via migration or seeder)
    public function createDefaultTemplates($school_id)
    {
        $defaults = [
            [
                'certificate_type' => 'bonafide',
                'template_content' => '<div style="text-align:center; font-family:Arial;">
                    <h2>{school_name}</h2>
                    <h3>Bonafide Certificate</h3>
                    <p>This is to certify that <strong>{student_name}</strong> (Admission No: {admission_no}, Roll No: {roll_no}) 
                    son/daughter of <strong>{father_name}</strong> is a bonafide student of this school studying in Class {class_name} 
                    during the academic year {financial_year}.</p>
                    <p>Date: {issue_date} &nbsp;&nbsp;&nbsp; Principal</p>
                </div>',
                'school_id' => $school_id
            ],
            [
                'certificate_type' => 'tc',
                'template_content' => '<div style="text-align:center;">
                    <h2>Transfer Certificate</h2>
                    <p>Certified that <strong>{student_name}</strong> was studying in this school in Class {class_name} 
                    during the session {financial_year}. His/Her conduct was good.</p>
                    <p>Date: {issue_date} &nbsp;&nbsp;&nbsp; Principal</p>
                </div>'
            ],
            // Add more types: character, leaving, etc.
        ];

        foreach ($defaults as $tpl) {
            if (!$this->where('certificate_type', $tpl['certificate_type'])->where('school_id', $school_id)->first()) {
                $this->insert($tpl);
            }
        }
    }
}