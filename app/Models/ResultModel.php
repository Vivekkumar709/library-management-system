<?php
namespace App\Models;
use CodeIgniter\Model;

class ResultModel extends Model
{
    protected $table = 'student_marks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id','exam_type_id','subject_id','marks_obtained'];

    public function saveMark($data)
    {
        $exists = $this->where([
            'student_id'   => $data['student_id'],
            'exam_type_id' => $data['exam_type_id'],
            'subject_id'   => $data['subject_id']
        ])->first();

        if ($exists) {
            return $this->update($exists['id'], $data);
        }
        return $this->insert($data);
    }

    public function generateResultSummary($exam_id)
    {
        $db = $this->db;
        $db->query("INSERT INTO student_results (student_id, exam_type_id, total_marks, obtained_marks, percentage, grade, result_status)
            SELECT 
                sm.student_id, 
                sm.exam_type_id,
                SUM(cs.max_marks) as total,
                SUM(sm.marks_obtained) as obtained,
                ROUND(SUM(sm.marks_obtained)::numeric / SUM(cs.max_marks)::numeric * 100, 2) as perc,
                CASE WHEN ROUND(...) >= 90 THEN 'A+' ELSE ... END as grade,   -- you can customize
                CASE WHEN perc >= 40 THEN 'Pass' ELSE 'Fail' END
            FROM student_marks sm
            JOIN class_subjects cs ON cs.id = sm.subject_id
            WHERE sm.exam_type_id = ?
            GROUP BY sm.student_id, sm.exam_type_id
            ON CONFLICT (student_id, exam_type_id) DO UPDATE
            SET obtained_marks = EXCLUDED.obtained_marks;", [$exam_id]);
    }

    public function getAllResults($student_id = null)
    {
        $builder = $this->db->table('student_results r');
        $builder->select('r.*, s.admission_no, u.first_name, u.last_name, e.name as exam_name, c.class_name');
        $builder->join('students s', 's.id = r.student_id');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->join('exam_types e', 'e.id = r.exam_type_id');
        $builder->join('m_classes c', 'c.id = s.class_id');
        $builder->where('s.school_id', auth()->user()->school_id);

        if ($student_id) $builder->where('r.student_id', $student_id);

        return $builder->get()->getResultArray();
    }

    public function getStudentResult($student_id, $exam_id)
    {
        // detailed with subject-wise marks
        return $this->db->table('student_marks sm')
            ->select('sm.*, cs.subject_name, cs.max_marks, sr.percentage, sr.grade')
            ->join('class_subjects cs', 'cs.id = sm.subject_id')
            ->join('student_results sr', 'sr.student_id = sm.student_id AND sr.exam_type_id = sm.exam_type_id')
            ->where('sm.student_id', $student_id)
            ->where('sm.exam_type_id', $exam_id)
            ->get()->getResultArray();
    }
}