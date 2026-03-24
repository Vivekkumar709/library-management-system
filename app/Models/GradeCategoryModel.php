<?php namespace App\Models;

use CodeIgniter\Model;

class GradeCategoryModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'grade_categories';
    protected $primaryKey       = 'category_id';
    protected $allowedFields    = ['class_id', 'name', 'weight', 'max_score'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'class_id'  => 'required|numeric',
        'name'      => 'required|max_length[100]',
        'weight'    => 'required|decimal|less_than_equal_to[100]',
        'max_score' => 'required|decimal'
    ];

    public function getCategoriesByClass($classId)
    {
        return $this->where('class_id', $classId)
                   ->orderBy('name')
                   ->findAll();
    }

    public function getTotalWeight($classId)
    {
        $result = $this->selectSum('weight')
                      ->where('class_id', $classId)
                      ->first();

        return $result ? (float)$result['weight'] : 0;
    }
}