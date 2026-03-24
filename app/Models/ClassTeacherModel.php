<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClassTeacherModel extends Model
{
    protected $table = 'class_teachers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class_id', 'section_id', 'teacher_id', 'financial_year_id', 
        'status', 'created_by', 'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get class teachers with joins for display
    public function getClassTeachers($filters = [])
    {   
        $builder = $this->db->table('class_teachers ct');
        $builder->select('ct.*, c.class_name, ms.name as section_name,
                        (u.first_name || \' \' || u.last_name) AS teacher_name,
                        fy.name as financial_year');
        $builder->join('m_classes c', 'c.id = ct.class_id');
        //$builder->join('sections s', 's.id = ct.section_id');
        $builder->join('m_sections ms', 'ms.id = ct.section_id');
        //$builder->join('users_info_teachers t', 't.id = ct.teacher_id'); 
        $builder->join('users u', 'u.id = ct.teacher_id'); 
        $builder->join('financial_year fy', 'fy.id = ct.financial_year_id');
        $builder->where('ct.school_id = '.auth()->user()->school_id);
        
        if (!empty($filters)) {
            $builder->where($filters);
        }        
        $builder->orderBy('c.class_number, ms.id');
        return $builder->get()->getResultArray();
    }
    
    // Check if a teacher is already assigned to a class/section
    public function isTeacherAssigned($teacherId, $classId, $sectionId, $financialYearId, $excludeId = null)
    {
        $builder = $this->db->table('class_teachers');
        $builder->where('teacher_id', $teacherId);
        $builder->where('class_id', $classId);
        $builder->where('section_id', $sectionId);
        $builder->where('financial_year_id', $financialYearId);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    // Get teacher by class and section
    public function getTeacherByClassSection($classId, $sectionId, $financialYearId)
    {
        return $this->where([
            'class_id' => $classId,
            'section_id' => $sectionId,
            'financial_year_id' => $financialYearId,
            'status' => 0
        ])->first();
    }
}