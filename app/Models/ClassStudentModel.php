<?php namespace App\Models;

use CodeIgniter\Model;

class ClassStudentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'class_students';
    protected $primaryKey       = ['class_id', 'student_id'];
    protected $allowedFields    = ['class_id', 'student_id', 'enrollment_date', 'status'];
    
    protected $validationRules = [
        'class_id' => 'required|numeric',
        'student_id' => 'required|numeric',
        'enrollment_date' => 'permit_empty|valid_date',
        'status' => 'permit_empty|in_list[active,inactive,transferred,graduated]'
    ];

    public function getStudentsByClass($classId)
    {
        return $this->db->table('class_students cs')
                        ->select('cs.*, s.name as student_name, s.email as student_email, s.phone as student_phone')
                        ->join('students s', 's.student_id = cs.student_id')
                        ->where('cs.class_id', $classId)
                        ->get()
                        ->getResultArray();
    }

    public function getClassesByStudent($studentId)
    {
        return $this->db->table('class_students cs')
                        ->select('cs.*, c.class_name, c.class_code')
                        ->join('classes c', 'c.class_id = cs.class_id')
                        ->where('cs.student_id', $studentId)
                        ->get()
                        ->getResultArray();
    }
}