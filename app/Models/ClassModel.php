<?php namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'classes';
    protected $primaryKey       = 'class_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['class_name', 'class_code', 'description', 'capacity', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'class_name' => 'required|min_length[3]|max_length[50]',
        'class_code' => 'required|min_length[2]|max_length[20]|is_unique[classes.class_code]',
        'capacity'   => 'permit_empty|integer|greater_than[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getClassesWithDetails()
    {
        $builder = $this->db->table('classes c');
        $builder->select('c.*, COUNT(DISTINCT cs.student_id) as student_count, COUNT(DISTINCT ct.teacher_id) as teacher_count');
        $builder->join('class_students cs', 'cs.class_id = c.class_id', 'left');
        $builder->join('class_teachers ct', 'ct.class_id = c.class_id', 'left');
        $builder->groupBy('c.class_id');
        return $builder->get()->getResultArray();
    }

    public function getClassSchedule($classId)
    {
        return $this->db->table('class_schedules')
                        ->where('class_id', $classId)
                        ->orderBy('day_of_week, start_time')
                        ->get()
                        ->getResultArray();
    }
}