<?php namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'grades';
    protected $primaryKey       = 'grade_id';
    protected $allowedFields    = ['assignment_id', 'student_id', 'score', 'comments', 'recorded_by'];
    protected $useTimestamps    = true;
    protected $createdField     = 'recorded_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'assignment_id' => 'required|numeric',
        'student_id'    => 'required|numeric',
        'score'         => 'permit_empty|decimal'
    ];

    public function getGradesByAssignment($assignmentId)
    {
        return $this->db->table('grades g')
                       ->select('g.*, s.name as student_name, s.student_code')
                       ->join('students s', 's.student_id = g.student_id')
                       ->where('g.assignment_id', $assignmentId)
                       ->orderBy('s.name')
                       ->get()
                       ->getResultArray();
    }

    public function getStudentGrades($studentId, $classId)
    {
        return $this->db->table('grades g')
                       ->select('g.*, a.title as assignment_title, a.max_score, gc.name as category_name, gc.weight')
                       ->join('assignments a', 'a.assignment_id = g.assignment_id')
                       ->join('grade_categories gc', 'gc.category_id = a.category_id')
                       ->where('g.student_id', $studentId)
                       ->where('gc.class_id', $classId)
                       ->orderBy('gc.name, a.due_date')
                       ->get()
                       ->getResultArray();
    }

    public function saveGrades($assignmentId, $grades)
    {
        $this->db->transStart();

        foreach ($grades as $studentId => $gradeData) {
            $data = [
                'assignment_id' => $assignmentId,
                'student_id'    => $studentId,
                'score'         => $gradeData['score'],
                'comments'      => $gradeData['comments'] ?? null,
                'recorded_by'   => session()->get('user_id')
            ];

            // Check if grade already exists
            $existing = $this->where('assignment_id', $assignmentId)
                            ->where('student_id', $studentId)
                            ->first();

            if ($existing) {
                $this->update($existing['grade_id'], $data);
            } else {
                $this->insert($data);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function calculateFinalGrade($studentId, $classId)
    {
        $grades = $this->getStudentGrades($studentId, $classId);

        $categories = [];
        foreach ($grades as $grade) {
            $categoryId = $grade['category_id'];

            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = [
                    'name' => $grade['category_name'],
                    'weight' => (float)$grade['weight'],
                    'total_score' => 0,
                    'max_score' => 0,
                    'count' => 0
                ];
            }

            if (!is_null($grade['score'])) {
                $categories[$categoryId]['total_score'] += (float)$grade['score'];
                $categories[$categoryId]['max_score'] += (float)$grade['max_score'];
                $categories[$categoryId]['count']++;
            }
        }

        $finalGrade = 0;
        foreach ($categories as $category) {
            if ($category['count'] > 0 && $category['max_score'] > 0) {
                $categoryPercentage = ($category['total_score'] / $category['max_score']) * 100;
                $finalGrade += $categoryPercentage * ($category['weight'] / 100);
            }
        }

        return [
            'final_grade' => round($finalGrade, 2),
            'categories' => $categories
        ];
    }
}