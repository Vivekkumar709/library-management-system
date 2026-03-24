<?php namespace App\Controllers;

use App\Models\GradeCategoryModel;
use App\Models\ClassModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class GradeCategoriesController extends BaseController
{
    protected $categoryModel;
    protected $classModel;

    public function __construct()
    {
        $this->categoryModel = new GradeCategoryModel();
        $this->classModel = new ClassModel();
    }

    public function index($classId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Grade Categories - ' . $class['class_name'],
            'class' => $class,
            'categories' => $this->categoryModel->getCategoriesByClass($classId),
            'totalWeight' => $this->categoryModel->getTotalWeight($classId)
        ];

        return view('gradebook/categories', $data);
    }

    public function create($classId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Add Grade Category - ' . $class['class_name'],
            'class' => $class,
            'validation' => \Config\Services::validation()
        ];

        return view('gradebook/create_category', $data);
    }

    public function store($classId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate($this->categoryModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $totalWeight = $this->categoryModel->getTotalWeight($classId);
        $newWeight = (float)$this->request->getPost('weight');

        if (($totalWeight + $newWeight) > 100) {
            return redirect()->back()->withInput()->with('error', 'Total weight cannot exceed 100%');
        }

        $data = [
            'class_id' => $classId,
            'name' => $this->request->getPost('name'),
            'weight' => $newWeight,
            'max_score' => $this->request->getPost('max_score')
        ];

        if ($this->categoryModel->save($data)) {
            return redirect()->to("/classes/{$classId}/gradebook/categories")->with('message', 'Category added successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
    }

    public function delete($classId, $categoryId)
    {
        $class = $this->classModel->find($classId);
        if (!$class) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->categoryModel->delete($categoryId)) {
            return redirect()->to("/classes/{$classId}/gradebook/categories")->with('message', 'Category deleted successfully');
        }

        return redirect()->to("/classes/{$classId}/gradebook/categories")->with('errors', $this->categoryModel->errors());
    }
}