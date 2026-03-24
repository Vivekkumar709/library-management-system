<?php
namespace App\Models;
use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'student_attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id','class_id','section_id','financial_year_id','attendance_date','status','remark','created_by'];

    public function insertOrUpdate($data)
    {
        $exists = $this->where([
            'student_id' => $data['student_id'],
            'attendance_date' => $data['attendance_date']
        ])->first();

        if ($exists) {
            return $this->update($exists['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    public function getMonthlySummary($student_id = null)
    {
        $builder = $this->db->table('student_attendance a');
        $builder->select("a.*, s.admission_no, u.first_name, u.last_name, 
                         COUNT(CASE WHEN a.status='Present' THEN 1 END) as present,
                         COUNT(CASE WHEN a.status='Absent' THEN 1 END) as absent,
                         ROUND(COUNT(CASE WHEN a.status='Present' THEN 1 END)::numeric / 
                               NULLIF(COUNT(*),0)::numeric * 100, 2) as percentage");
        $builder->join('students s', 's.id = a.student_id');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->where('s.school_id', auth()->user()->school_id);
        $builder->groupBy('s.id, u.first_name, u.last_name, s.admission_no');

        if ($student_id) {
            $builder->where('a.student_id', $student_id);
        }

        return $builder->get()->getResultArray();
    }

    // For monthly calendar view (you can extend later)
    public function getAttendanceByMonth($student_id, $month, $year)
    {
        return $this->where('student_id', $student_id)
                    ->where("EXTRACT(MONTH FROM attendance_date)", $month)
                    ->where("EXTRACT(YEAR FROM attendance_date)", $year)
                    ->findAll();
    }
}