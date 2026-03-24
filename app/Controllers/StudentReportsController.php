<?php
namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\StudentAcademicHistoryModel;
use CodeIgniter\Database\BaseBuilder;

class StudentReportsController extends BaseController
{
    protected $studentModel;
    protected $academicHistoryModel;
    protected $db;
    
    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->academicHistoryModel = new StudentAcademicHistoryModel();
        $this->db = \Config\Database::connect();
    }    

    // Class-wise report
    public function classWise()
    {   
        $filters = [
            's.school_id' => auth()->user()->school_id,
            's.status' => 0
        ];        
        // Apply filters if provided
        $classId = $this->request->getGet('class_id');
        $sectionId = $this->request->getGet('section_id');
        $financialYearId = $this->request->getGet('financial_year_id') ?? FINANCIAL_YEAR_ID;
        
        if ($classId) {
            $filters['s.class_id'] = $classId;
        }
        if ($sectionId) {
            $filters['s.section_id'] = $sectionId;
        }
        if ($financialYearId) {
            $filters['s.financial_year_id'] = $financialYearId;
        }
        
        $data['students'] = $this->studentModel->getStudents($filters);
        $data['reportType'] = 'class_wise';
        $data['filters'] = [
            'class_id' => $classId,
            'section_id' => $sectionId,
            'financial_year_id' => $financialYearId
        ];
        
        // Get summary statistics
        $data['summary'] = $this->getClassWiseSummary($filters);
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Empty');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Empty');
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        
        $this->title = 'Class-wise Student Report';
        $this->content_view = 'students/reports/class_wise';  
        $this->thumbnails = [
                ['title' => 'Class-wise Student Report', 'url' => '', 'active' => true]
        ];      
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }

    // Section-wise report
    public function sectionWise()
    {   
        $filters = [
            's.school_id' => auth()->user()->school_id,
            's.status' => 0
        ];
        
        $classId = $this->request->getGet('class_id');
        $financialYearId = $this->request->getGet('financial_year_id') ?? FINANCIAL_YEAR_ID;
        
        if ($classId) {
            $filters['s.class_id'] = $classId;
        }
        if ($financialYearId) {
            $filters['s.financial_year_id'] = $financialYearId;
        }

        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $data['sections'] = $this->getSectionWiseData($filters);
        $data['reportType'] = 'section_wise';
        $data['filters'] = [
            'class_id' => $classId,
            'financial_year_id' => $financialYearId
        ];
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], '');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], '');
        
        $this->title = 'Section-wise Student Report';
        $this->content_view = 'students/reports/section_wise';
        $this->thumbnails = [
                ['title' => 'Section-wise Report', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Gender-wise report
    public function genderWise()
    {   
        $filters = [
            's.school_id' => auth()->user()->school_id,
            's.status' => 0
        ];
        
        $classId = $this->request->getGet('class_id');
        $financialYearId = $this->request->getGet('financial_year_id') ?? FINANCIAL_YEAR_ID;
        
        if ($classId) {
            $filters['s.class_id'] = $classId;
        }
        if ($financialYearId) {
            $filters['s.financial_year_id'] = $financialYearId;
        }
        
        $data['genderStats'] = $this->getGenderWiseStats($filters);
        $data['reportType'] = 'gender_wise';
        $data['filters'] = [
            'class_id' => $classId,
            'financial_year_id' => $financialYearId
        ];
        
        // Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Empty');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Empty');
        
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->title = 'Gender-wise Student Report';
        $this->content_view = 'students/reports/gender_wise';
        $this->thumbnails = [
                ['title' => 'Gender-wise Report', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Category-wise report
    public function categoryWise()
    {   
        $filters = [
            's.school_id' => auth()->user()->school_id,
            's.status' => 0
        ];

        $classId = $this->request->getGet('class_id');
        $financialYearId = $this->request->getGet('financial_year_id') ?? FINANCIAL_YEAR_ID;
        
        if ($classId) {
            $filters['s.class_id'] = $classId;
        }
        if ($financialYearId) {
            $filters['s.financial_year_id'] = $financialYearId;
        }

        $data['categoryStats'] = $this->getCategoryWiseStats($filters);
        $data['reportType'] = 'category_wise';
        $data['filters'] = [
                               'class_id' => $classId,
                               'financial_year_id' => $financialYearId
                           ]; 

        //Get dropdown data
        $data['financial_years'] = get_dropdown('financial_year', 'id', 'name', ['status' => 0], 'Empty');
        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Empty');

        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->title = 'Category-wise Student Report';
        $this->content_view = 'students/reports/category_wise';
        $this->thumbnails = [                
                ['title' => 'Category-wise Student Report', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data            
        ];        
        return $this->render();
    }
    
    // Birthday report
    public function birthday()
    {   
        $month = $this->request->getGet('month') ?? date('m');
        $filters = [
            's.school_id' => auth()->user()->school_id,
            's.status' => 0
        ];
        
        $data['students'] = $this->getBirthdayStudents($month, $filters);
        $data['reportType'] = 'birthday';
        $data['currentMonth'] = $month;
        $data['months'] = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->title = 'Student Birthday Report - ' . $data['months'][$month];
        $this->thumbnails = 'thumbnails';

        $this->content_view = 'students/reports/birthday';
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Birthday Report', 'url' => '', 'active' => true]
            ]
        ];        
        return $this->render();
    }
    
    // Export report
    public function export11($reportType)
    {
        $filters = [
            's.school_id' => auth()->user()->school_id,
            's.status' => 0
        ];
        
        switch ($reportType) {
            case 'class_wise':
                $data = $this->studentModel->getStudents($filters);
                $filename = 'class_wise_students_' . date('Y-m-d') . '.xlsx';
                break;
                
            case 'birthday':
                $month = $this->request->getGet('month') ?? date('m');
                $data = $this->getBirthdayStudents($month, $filters);
                $filename = 'birthday_students_' . date('Y-m-d') . '.xlsx';
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
        
        return $this->exportToExcel($data, $filename, $reportType);
    }
    
    // Private helper methods
    private function getClassWiseSummary($filters)
    {
        $builder = $this->db->table('students s');
        $builder->select("COUNT(s.id) as total_students, 
                         COUNT(CASE WHEN s.gender = 'Male' THEN 1 END) as male_count,
                         COUNT(CASE WHEN s.gender = 'Female' THEN 1 END) as female_count,
                         COUNT(CASE WHEN s.gender = 'Other' THEN 1 END) as other_count");
        $builder->where($filters);
        
        return $builder->get()->getRowArray();
    }    
    
    private function getSectionWiseData($filters)
    {
        $builder = $this->db->table('students s');
        $builder->select("c.class_name, ms.name as section_name, 
                         COUNT(s.id) as total_students,
                         COUNT(CASE WHEN s.gender = 'Male' THEN 1 END) as male_count,
                         COUNT(CASE WHEN s.gender = 'Female' THEN 1 END) as female_count");
        $builder->join('m_classes c', 'c.id = s.class_id');
        $builder->join('sections sec', 'sec.id = s.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->where($filters);
        // PostgreSQL requires all non-aggregated columns in GROUP BY
        $builder->groupBy('c.class_name, ms.name, c.class_number');
        $builder->orderBy('c.class_number, ms.name');
        
        return $builder->get()->getResultArray();
    } 

    private function getGenderWiseStats($filters)
    {
        $builder = $this->db->table('students s');
        $builder->select('s.gender, c.class_name, COUNT(s.id) as count');
        $builder->join('m_classes c', 'c.id = s.class_id');
        $builder->where($filters);
        // Add c.class_name to GROUP BY since it's in SELECT
        $builder->groupBy('s.gender, c.class_name, c.class_number');
        $builder->orderBy('c.class_number, s.gender');
        
        $results = $builder->get()->getResultArray();
        
        // Format data for better display
        $stats = [];
        foreach ($results as $row) {
            if (!isset($stats[$row['class_name']])) {
                $stats[$row['class_name']] = [
                    'Male' => 0,
                    'Female' => 0,
                    'Other' => 0,
                    'Total' => 0
                ];
            }
            $stats[$row['class_name']][$row['gender']] = $row['count'];
            $stats[$row['class_name']]['Total'] += $row['count'];
        }
        
        return $stats;
    }
    
    private function getCategoryWiseStats($filters)
    {
        $builder = $this->db->table('students s');
        $builder->select('cc.name AS caste, s.religion, c.class_name, COUNT(s.id) as count');
        $builder->join('m_classes c', 'c.id = s.class_id');
        //$builder->join('m_caste_categories cc', 'cc.id = s.caste');
        $builder->join('m_caste_categories cc', 'cc.id = CAST(s.caste AS INTEGER)');
        $builder->where($filters);
        $builder->where('s.caste IS NOT NULL');
        $builder->where("s.caste != ''");
        // PostgreSQL requires all non-aggregated columns in GROUP BY
        $builder->groupBy('cc.name, s.caste, s.religion, c.class_name, c.class_number');
        $builder->orderBy('c.class_number, s.caste, s.religion');
        
        $results = $builder->get()->getResultArray();
        
        // Format data for better display
        $stats = [];
        foreach ($results as $row) {
            $category = $row['caste'] . ' (' . $row['religion'] . ')';
            if (!isset($stats[$row['class_name']])) {
                $stats[$row['class_name']] = [];
            }
            $stats[$row['class_name']][$category] = $row['count'];
        }
        
        return $stats;
    }
    
    private function getBirthdayStudents($month, $filters)
    {
        $builder = $this->db->table('students s');
        $builder->select('s.*, c.class_name, ms.name as section_name, u.first_name, u.last_name');
        $builder->join('m_classes c', 'c.id = s.class_id');
        $builder->join('users u', 'u.id = s.user_id');        
        $builder->join('sections sec', 'sec.id = s.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->where($filters);
        $builder->where('EXTRACT(MONTH FROM s.date_of_birth) =', $month);
        $builder->orderBy('EXTRACT(DAY FROM s.date_of_birth), c.class_number, ms.name');
        
        return $builder->get()->getResultArray();
    }
    
    private function exportToExcel($data, $filename, $reportType)
    {
        // Simple CSV export implementation
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
        // Add headers based on report type
        switch ($reportType) {
            case 'class_wise':
                fputcsv($output, ['Admission No', 'Roll No', 'Student Name', 'Class', 'Section', 'Gender', 'Date of Birth', 'Father Name', 'Mobile']);
                foreach ($data as $row) {
                    fputcsv($output, [
                        $row['admission_no'],
                        $row['roll_no'],
                        $row['first_name'] . ' ' . $row['last_name'],
                        $row['class_name'],
                        $row['section_name'],
                        $row['gender'],
                        $row['date_of_birth'],
                        $row['father_name'],
                        $row['father_mobile'] ?: $row['mobile_no']
                    ]);
                }
                break;
                
            case 'birthday':
                fputcsv($output, ['Student Name', 'Class', 'Section', 'Date of Birth', 'Age']);
                foreach ($data as $row) {
                    $age = date_diff(date_create($row['date_of_birth']), date_create('today'))->y;
                    fputcsv($output, [
                        $row['first_name'] . ' ' . $row['last_name'],
                        $row['class_name'],
                        $row['section_name'],
                        $row['date_of_birth'],
                        $age
                    ]);
                }
                break;
        }
        
        fclose($output);
        exit;
    }
}