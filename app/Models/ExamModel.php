<?php
namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table            = 'exam_types';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'name',
        'school_id',
        'status'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Get all active exam types for the current school
     */
    public function getActiveExams()
    {
        return $this->where('school_id', auth()->user()->school_id)
                    ->where('status', 0)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get dropdown-friendly array (id => name)
     * Useful for your get_dropdown() helper style
     */
    public function getDropdown()
    {
        $exams = $this->getActiveExams();
        $dropdown = [];
        foreach ($exams as $exam) {
            $dropdown[$exam['id']] = $exam['name'];
        }
        return $dropdown;
    }
}