<?php
namespace App\Models;
use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'class_subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = ['class_id', 'subject_name', 'subject_code', 'max_marks', 'school_id'];

    public function getAllSubjects()
    {
        $builder = $this->db->table('class_subjects cs');
        $builder->select('cs.*, c.class_name');
        $builder->join('m_classes c', 'c.id = cs.class_id');
        $builder->where('cs.school_id', auth()->user()->school_id);
        $builder->orderBy('c.class_name, cs.subject_name');

        return $builder->get()->getResultArray();
    }

    public function getClassSubjects($class_id = null)
    {
        $builder = $this->where('school_id', auth()->user()->school_id);
        if ($class_id) {
            $builder->where('class_id', $class_id);
        }
        return $builder->findAll();
    }
}