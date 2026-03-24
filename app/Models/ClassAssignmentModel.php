<?php
namespace App\Models;

use CodeIgniter\Model;

class ClassAssignmentModel extends Model
{
    protected $table = 'class_assignments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class_id', 'section_id', 'subject_id', 'teacher_id', 
        'title', 'description', 'due_date', 'attachment_path',
        'max_marks', 'financial_year_id', 'school_id', 'status', 
        'created_by', 'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get assignments with joins for display
    public function getAssignments($filters = [])
    {
        $builder = $this->db->table('class_assignments ca');
        $builder->select('ca.*, c.class_name, s.section_id as section_number, 
                         sub.name as subject_name, t.first_name as teacher_name, 
                         fy.name as financial_year, COUNT(cas.id) as submission_count');
        $builder->join('m_classes c', 'c.id = ca.class_id');
        $builder->join('sections s', 's.id = ca.section_id');
        $builder->join('m_subjects sub', 'sub.id = ca.subject_id');
        $builder->join('users t', 't.id = ca.teacher_id');
        $builder->join('financial_year fy', 'fy.id = ca.financial_year_id');
        $builder->join('class_assignment_submissions cas', 'cas.assignment_id = ca.id', 'left');
        
        if (!empty($filters)) {
            $builder->where($filters);
        }
        
        $builder->groupBy('ca.id, c.class_name, s.section_id, sub.name, t.first_name, fy.name');
        $builder->orderBy('ca.due_date DESC, ca.created_at DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Get assignment by ID with details
    public function getAssignmentById($id)
    {
        $builder = $this->db->table('class_assignments ca');        
        $builder->select("ca.*, 
                  TO_CHAR(ca.due_date, 'DD-MM-YYYY HH24:MI') as due_date_formatted, 
                  c.class_name, 
                  s.section_id as section_number, 
                  sub.name as subject_name, 
                  t.first_name as teacher_name, 
                  fy.name as financial_year", false);
        $builder->join('m_classes c', 'c.id = ca.class_id');
        $builder->join('sections s', 's.id = ca.section_id');
        $builder->join('m_subjects sub', 'sub.id = ca.subject_id');
        $builder->join('users t', 't.id = ca.teacher_id');
        $builder->join('financial_year fy', 'fy.id = ca.financial_year_id');
        $builder->where('ca.id', $id);
        
        return $builder->get()->getRowArray();
    }
    
    // Get assignments for a specific class and section
    public function getAssignmentsByClassSection($classId, $sectionId, $financialYearId)
    {
        return $this->where([
            'class_id' => $classId,
            'section_id' => $sectionId,
            'financial_year_id' => $financialYearId,
            'status' => 0
        ])->orderBy('due_date DESC')->findAll();
    }
}