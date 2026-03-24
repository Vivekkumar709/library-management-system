<?php 
namespace App\Models;

use CodeIgniter\Model;

class StudentAcademicHistoryModel extends Model
{
    protected $table = 'student_academic_history';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'class_id', 'section_id', 'financial_year_id', 
        'roll_no', 'percentage', 'status', 'created_by', 'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get academic history by student ID with complete details
    public function getAcademicHistory($studentId)
    {
        $builder = $this->db->table('student_academic_history sah');
        $builder->select('sah.*, c.class_name, ms.name as section_name, fy.name as financial_year,
                         u.first_name, u.last_name, s.admission_no');
        $builder->join('students s', 's.id = sah.student_id');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->join('m_classes c', 'c.id = sah.class_id');
        $builder->join('sections sec', 'sec.id = sah.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->join('financial_year fy', 'fy.id = sah.financial_year_id');
        $builder->where('sah.student_id', $studentId);
        $builder->orderBy('fy.name DESC, c.class_number DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Get single record with complete details
    public function getRecordWithDetails($recordId)
    {
        $builder = $this->db->table('student_academic_history sah');
        $builder->select('sah.*, c.class_name, ms.name as section_name, fy.name as financial_year,
                         u.first_name, u.last_name, s.admission_no, s.school_id');
        $builder->join('students s', 's.id = sah.student_id');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->join('m_classes c', 'c.id = sah.class_id');
        $builder->join('sections sec', 'sec.id = sah.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->join('financial_year fy', 'fy.id = sah.financial_year_id');
        $builder->where('sah.id', $recordId);
        
        return $builder->get()->getRowArray();
    }
    
    // Check if academic record already exists
    public function isRecordExists($studentId, $classId, $financialYearId, $excludeId = null)
    {
        $builder = $this->db->table('student_academic_history');
        $builder->where('student_id', $studentId);
        $builder->where('class_id', $classId);
        $builder->where('financial_year_id', $financialYearId);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    // Get latest academic record for a student
    public function getLatestRecord($studentId)
    {
        $builder = $this->db->table('student_academic_history sah');
        $builder->select('sah.*, c.class_name, c.class_number, ms.name as section_name');
        $builder->join('m_classes c', 'c.id = sah.class_id');
        $builder->join('sections sec', 'sec.id = sah.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->where('sah.student_id', $studentId);
        $builder->orderBy('sah.financial_year_id DESC, c.class_number DESC');
        $builder->limit(1);
        
        return $builder->get()->getRowArray();
    }
    
    // Get academic performance summary
    public function getPerformanceSummary($studentId)
    {
        $builder = $this->db->table('student_academic_history sah');
        $builder->select('AVG(sah.percentage) as avg_percentage, 
                         MAX(sah.percentage) as max_percentage, 
                         MIN(sah.percentage) as min_percentage,
                         COUNT(sah.id) as total_records');
        $builder->where('sah.student_id', $studentId);
        $builder->where('sah.percentage IS NOT NULL');
        
        $result = $builder->get()->getRowArray();
        
        // Handle case when no records with percentage exist
        if (!$result || $result['total_records'] == 0) {
            return [
                'avg_percentage' => 0,
                'max_percentage' => 0,
                'min_percentage' => 0,
                'total_records' => 0
            ];
        }
        
        return $result;
    }
}