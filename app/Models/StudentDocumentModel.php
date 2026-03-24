<?php 
namespace App\Models;

use CodeIgniter\Model;

class StudentDocumentModel extends Model
{
    protected $table = 'student_documents';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'document_type', 'document_name', 'document_path', 
        'status'
    ];
    //, 'created_by', 'updated_by'
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get documents by student ID with student details
    public function getDocumentsByStudent($studentId)
    {
        $builder = $this->db->table('student_documents sd');
        $builder->select('sd.*, u.first_name, u.last_name, s.admission_no');
        $builder->join('students s', 's.id = sd.student_id');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->where('sd.student_id', $studentId);
        $builder->orderBy('sd.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Get document with student details
    public function getDocumentWithStudent($documentId)
    {
        $builder = $this->db->table('student_documents sd');
        $builder->select('sd.*, s.first_name, s.last_name, s.admission_no, s.school_id');
        $builder->join('students s', 's.id = sd.student_id');
        $builder->where('sd.id', $documentId);
        
        return $builder->get()->getRowArray();
    }
    
    // Count documents by type for a student
    public function countDocumentsByType($studentId)
    {
        $builder = $this->db->table('student_documents');
        $builder->select('document_type, COUNT(*) as count');
        $builder->where('student_id', $studentId);
        $builder->where('status', 0);
        $builder->groupBy('document_type');
        
        return $builder->get()->getResultArray();
    }
}