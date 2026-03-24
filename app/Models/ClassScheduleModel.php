<?php
namespace App\Models;

use CodeIgniter\Model;

class ClassScheduleModel extends Model
{
    protected $table = 'class_schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class_id', 'section_id', 'subject_id', 'teacher_id', 
        'day_of_week', 'start_time', 'end_time', 'room_number',
        'financial_year_id', 'school_id', 'status', 'created_by', 'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get class schedules with joins for display
    public function getClassSchedules($filters = [])
    {
        $builder = $this->db->table('class_schedules cs');
        $builder->select('cs.*, c.class_name, c.class_number, ms.name as section_name, 
                         sub.name as subject_name, (u.first_name || \' \' || u.last_name) AS teacher_name,
                         fy.name as financial_year');
        $builder->join('m_classes c', 'c.id = cs.class_id');
        //$builder->join('sections s', 's.id = cs.section_id');
        $builder->join('m_sections ms', 'ms.id = cs.section_id');
        $builder->join('m_subjects sub', 'sub.id = cs.subject_id');        
        $builder->join('users u', 'u.id = cs.teacher_id AND u.user_type_id = 13');
        $builder->join('financial_year fy', 'fy.id = cs.financial_year_id');  
        $builder->where('cs.school_id = '.auth()->user()->school_id);       
        if (!empty($filters)) {
            $builder->where($filters);
        }        
        $builder->orderBy('c.class_number, ms.id, cs.day_of_week, cs.start_time');        
        return $builder->get()->getResultArray();
    }
    
    // Get subjects by class level
    public function getSubjectsByClassLevel($classNumber)
    {
        $builder = $this->db->table('m_subjects');
        $builder->where('status', 0);
        
        // Filter subjects based on class level
        if ($classNumber >= 11 && $classNumber <= 12) {
            // Senior Secondary (11-12)
            $builder->groupStart()
                ->like('category', 'Science', 'after')
                ->orLike('category', 'Commerce', 'after')
                ->orLike('category', 'Humanities', 'after')
                ->orLike('category', 'Art', 'after')
                ->orLike('category', 'Vocational', 'after')
            ->groupEnd();
        } elseif ($classNumber >= 9 && $classNumber <= 10) {
            // Secondary (9-10)
            $builder->groupStart()
                ->like('category', 'Core', 'after')
                ->orLike('category', 'Science', 'after')
                ->orLike('category', 'Humanities', 'after')
                ->orLike('category', 'Technology', 'after')
                ->orLike('category', 'Life Skills', 'after')
                ->orLike('category', 'Art', 'after')
            ->groupEnd();
        } elseif ($classNumber >= 6 && $classNumber <= 8) {
            // Middle School (6-8)
            $builder->groupStart()
                ->like('category', 'Core', 'after')
                ->orLike('category', 'Technology', 'after')
                ->orLike('category', 'Life Skills', 'after')
                ->orLike('category', 'Art', 'after')
                ->orLike('category', 'Health', 'after')
            ->groupEnd();
        } else {
            // Primary (1-5) and Pre-Primary (-4 to 0)
            $builder->groupStart()
                ->like('category', 'Core', 'after')
                ->orLike('category', 'Language', 'after')
                ->orLike('category', 'Art', 'after')
                ->orLike('category', 'Health', 'after')
                ->orLike('category', 'Life Skills', 'after')
            ->groupEnd();
        }
        
        $builder->orderBy('category, name');
        
        return $builder->get()->getResultArray();
    }
    
    // Check if time slot is already occupied
    public function isTimeSlotOccupied($classId, $sectionId, $dayOfWeek, $startTime, $endTime, $financialYearId, $excludeId = null)
    {
        $builder = $this->db->table('class_schedules');
        $builder->where('class_id', $classId);
        $builder->where('section_id', $sectionId);
        $builder->where('day_of_week', $dayOfWeek);
        $builder->where('financial_year_id', $financialYearId);
        $builder->groupStart()
            ->groupStart()
                ->where('start_time <=', $startTime)
                ->where('end_time >', $startTime)
            ->groupEnd()
            ->orGroupStart()
                ->where('start_time <', $endTime)
                ->where('end_time >=', $endTime)
            ->groupEnd()
            ->orGroupStart()
                ->where('start_time >=', $startTime)
                ->where('end_time <=', $endTime)
            ->groupEnd()
        ->groupEnd();
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    // Get schedule by class and section
    public function getScheduleByClassSection1($classId, $sectionId, $financialYearId)
    {
        return $this->where([
            'class_id' => $classId,
            'section_id' => $sectionId,
            'financial_year_id' => $financialYearId,
            'status' => 0
        ])->orderBy('day_of_week, start_time')->findAll();
    }

    public function getScheduleByClassSection($classId, $sectionId, $financialYearId)
    {
            return $this->select('class_schedules.*,                              
                        CONCAT_WS(\'\', m_subjects.name, \' [\', m_subjects.category, \']\') AS subject_name,
                        CONCAT_WS(\'\', users.first_name, \' \', users.last_name) AS teacher_name')
            ->join('m_subjects', 'm_subjects.id = class_schedules.subject_id', 'left')
            ->join('users', 'users.id = class_schedules.teacher_id AND users.user_type_id = 13', 'left')
            ->where([
                'class_schedules.class_id' => $classId,
                'class_schedules.section_id' => $sectionId,
                'class_schedules.financial_year_id' => $financialYearId,
                'class_schedules.status' => 0
            ])
            ->orderBy('class_schedules.day_of_week, class_schedules.start_time')
            ->findAll();
    }
    
    // Get teachers for dropdown based on selected filters
    public function getAvailableTeachers($dayOfWeek, $startTime, $endTime, $financialYearId, $excludeTeacherId = null)
    {
        $builder = $this->db->table('class_schedules cs');
        //$builder->select('DISTINCT cs.teacher_id');
        $builder->select('DISTINCT(cs.teacher_id)', false); 
        $builder->where('cs.day_of_week', $dayOfWeek);
        $builder->where('cs.financial_year_id', $financialYearId);
        $builder->where('cs.status', 0);
        $builder->where('cs.school_id', auth()->user()->school_id);
        $builder->groupStart()
            ->groupStart()
                ->where('cs.start_time <=', $startTime)
                ->where('cs.end_time >', $startTime)
            ->groupEnd()
            ->orGroupStart()
                ->where('cs.start_time <', $endTime)
                ->where('cs.end_time >=', $endTime)
            ->groupEnd()
            ->orGroupStart()
                ->where('cs.start_time >=', $startTime)
                ->where('cs.end_time <=', $endTime)
            ->groupEnd()
        ->groupEnd();
        
        if ($excludeTeacherId) {
            $builder->where('cs.teacher_id !=', $excludeTeacherId);
        }        
        $busyTeachers = $builder->get()->getResultArray();
        $busyTeacherIds = array_column($busyTeachers, 'teacher_id');        
        // Get all teachers excluding busy ones
        $teacherBuilder = $this->db->table('users');
        $teacherBuilder->select('id, (first_name || \' \' || last_name) AS teacher_name');
        $teacherBuilder->where('status', '0');
        $teacherBuilder->where('user_type_id', 13);
        
        if (!empty($busyTeacherIds)) {
            $teacherBuilder->whereNotIn('id', $busyTeacherIds);
        }        
        return $teacherBuilder->get()->getResultArray();
    }
}