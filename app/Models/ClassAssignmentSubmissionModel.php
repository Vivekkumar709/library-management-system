<?php
namespace App\Models;

use CodeIgniter\Model;

class ClassAssignmentSubmissionModel extends Model
{
    protected $table = 'class_assignment_submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'assignment_id', 'student_id', 'submission_text', 
        'submission_file', 'marks_obtained', 'teacher_remarks', 
        'school_id', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get submissions for an assignment
    public function getSubmissionsByAssignment($assignmentId)
    {
        $builder = $this->db->table('class_assignment_submissions cas');
        $builder->select('cas.*, s.first_name, s.last_name, s.roll_number');
        $builder->join('students s', 's.id = cas.student_id');
        $builder->where('cas.assignment_id', $assignmentId);
        $builder->orderBy('s.roll_number, cas.submitted_at DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Get submission by student and assignment
    public function getSubmissionByStudent($assignmentId, $studentId)
    {
        return $this->where([
            'assignment_id' => $assignmentId,
            'student_id' => $studentId
        ])->first();
    }
    
    // Count submissions by status
    public function countSubmissionsByStatus($assignmentId)
    {
        $builder = $this->db->table('class_assignment_submissions');
        $builder->select('status, COUNT(*) as count');
        $builder->where('assignment_id', $assignmentId);
        $builder->groupBy('status');
        
        $result = $builder->get()->getResultArray();
        
        $counts = [
            'total' => 0,
            'submitted' => 0,
            'graded' => 0,
            'not_submitted' => 0
        ];
        
        foreach ($result as $row) {
            $counts['total'] += $row['count'];
            if ($row['status'] == 1) $counts['submitted'] = $row['count'];
            if ($row['status'] == 2) $counts['graded'] = $row['count'];
        }
        
        $counts['not_submitted'] = $counts['total'] - ($counts['submitted'] + $counts['graded']);
        
        return $counts;
    }
}