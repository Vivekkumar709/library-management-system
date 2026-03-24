<?php 
namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    //'first_name', 'last_name', 'mobile_no', 'email', 
    protected $allowedFields = [
        'user_id', 'admission_no', 'roll_no', 'gender', 
        'date_of_birth', 'blood_group', 'religion', 'caste', 'nationality',
        'aadhaar_no', 'admission_date', 'class_id',
        'section_id', 'school_id', 'financial_year_id', 'father_name',
        'father_occupation', 'father_mobile', 'father_email', 'mother_name',
        'mother_occupation', 'mother_mobile', 'mother_email', 'guardian_name',
        'guardian_relation', 'guardian_occupation', 'guardian_mobile',
        'guardian_email', 'present_address', 'permanent_address', 'status',
        'created_by', 'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get students with joins for display
    public function getStudents($filters = [])
    {   
        $builder = $this->db->table('students s');
        $builder->select('s.*,c.class_name, ms.name as section_name, fy.name as financial_year, u.username, u.email, u.email_id, u.mobile, u.first_name, u.last_name, u.profile_image');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->join('m_classes c', 'c.id = s.class_id');
        $builder->join('sections sec', 'sec.id = s.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->join('financial_year fy', 'fy.id = s.financial_year_id');
        $builder->where('s.school_id', auth()->user()->school_id);

        if (!empty($filters)) {
            $builder->where($filters);
        }        
        $builder->orderBy('c.class_number, ms.name, s.roll_no');
        return $builder->get()->getResultArray();
    }
    
    // Check if admission number already exists
    public function isAdmissionNoExists($admissionNo, $excludeId = null)
    {
        $builder = $this->db->table('students');
        $builder->where('admission_no', $admissionNo);
        $builder->where('school_id', auth()->user()->school_id);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    // Check if roll number already exists in class-section
    public function isRollNoExists($rollNo, $classId, $sectionId, $financialYearId, $excludeId = null)
    {
        $builder = $this->db->table('students');
        $builder->where('roll_no', $rollNo);
        $builder->where('class_id', $classId);
        $builder->where('section_id', $sectionId);
        $builder->where('financial_year_id', $financialYearId);
        $builder->where('school_id', auth()->user()->school_id);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    // Get students by class and section
    public function getStudentsByClassSection($classId, $sectionId, $financialYearId)
    {
        return $this->where([
            'class_id' => $classId,
            'section_id' => $sectionId,
            'financial_year_id' => $financialYearId,
            'school_id' => auth()->user()->school_id,
            'status' => 0
        ])->orderBy('roll_no')->findAll();
    }

    // FIXED: Custom method to find student with user join
    public function findWithUser1($id)
    {
        $builder = $this->builder();
        
        return $builder->select('students.*, u.first_name, u.last_name, u.email, u.mobile, u.profile_image, u.about, 
                             u.permanent_address, u.permanent_state, u.permanent_city, u.permanent_pincode, u.permanent_landmark,
                             u.present_address, u.present_landmark, u.present_state, u.present_city, u.present_pincode')
                    ->join('users u', 'u.id = students.user_id', 'left')
                    ->where('students.id', $id)
                    ->get()
                    ->getRowArray();
    }
    // FIXED: Custom method to find student with user join
    public function findWithUser($id)
    {
        $builder = $this->db->table('students s');
        
        $result = $builder->select('s.*, u.first_name, u.last_name, u.email, u.mobile, u.profile_image, u.about, 
                            u.permanent_address, u.permanent_state, u.permanent_city, u.permanent_pincode, u.permanent_landmark,
                            u.present_address, u.present_landmark, u.present_state, u.present_city, u.present_pincode')
                    ->join('users u', 'u.id = s.user_id', 'left')
                    ->where('s.id', $id)
                    ->get()
                    ->getRowArray();
        
        // Return the result (will be null if not found, not false)
        return $result;
    }

    // Alternative method using direct query builder
    public function findStudentWithUser($id)
    {
        $builder = $this->db->table('students s');
        
        $result = $builder->select('s.*, u.first_name, u.last_name, u.email, u.mobile AS mobile_no, u.profile_image, u.about, 
                            u.permanent_address, u.permanent_state, u.permanent_city, u.permanent_pincode, u.permanent_landmark,
                            u.present_address, u.present_landmark, u.present_state, u.present_city, u.present_pincode, u.email_id AS email')
                    ->join('users u', 'u.id = s.user_id', 'left')
                    ->where('s.id', $id)
                    ->get()
                    ->getRowArray();
        
        return $result;
    }
    
    // Get student with complete details
    public function getStudentWithDetails($id)
    {
        $builder = $this->db->table('students s');
        $builder->select('s.*, c.class_name, ms.name as section_name, fy.name as financial_year,
                          u.first_name, u.last_name, u.username, u.user_type_id, u.profile_image,sch.school_name as school_name');
        $builder->join('m_classes c', 'c.id = s.class_id');
        $builder->join('sections sec', 'sec.id = s.section_id');
        $builder->join('m_sections ms', 'ms.id = sec.section_id');
        $builder->join('financial_year fy', 'fy.id = s.financial_year_id');
        $builder->join('users u', 'u.id = s.user_id', 'left');
        $builder->join('schools sch', 'u.school_id = sch.id', 'left');
        
        $builder->where('s.id', $id);
        $builder->where('s.school_id', auth()->user()->school_id);        
        return $builder->get()->getRowArray();
    }

    public function generateRollNumber($classId, $sectionId, $schoolId, $financialYearId, $previewMode = false)
    {
        $db = db_connect();
        
        log_message('debug', 'Generating roll number for class: ' . $classId . ', section: ' . $sectionId . ', preview: ' . ($previewMode ? 'true' : 'false'));
        
        try {
            // Call the stored procedure with preview mode parameter
            $query = $db->query("SELECT generate_student_roll_no(?, ?, ?, ?, ?) as roll_no", [
                $classId, $sectionId, $schoolId, $financialYearId, $previewMode
            ]);
            
            // Check if query failed
            if ($query === false) {
                $error = $db->error();
                log_message('error', 'Database query failed: ' . $error['message']);
                throw new \Exception('Database query failed: ' . $error['message']);
            }
            
            $result = $query->getRow();
            
            if ($result && !empty($result->roll_no)) {
                log_message('debug', 'Roll number generated via stored procedure: ' . $result->roll_no);
                return $result->roll_no;
            }
            
            log_message('debug', 'Stored procedure returned empty result, trying manual generation');
            // If stored procedure fails, use manual generation
            return $this->generateRollNumberManually($classId, $sectionId, $schoolId, $financialYearId, $previewMode);
            
        } catch (\Exception $e) {
            log_message('error', 'Roll number generation error: ' . $e->getMessage());
            // Fallback to manual generation
            return $this->generateRollNumberManually($classId, $sectionId, $schoolId, $financialYearId, $previewMode);
        }
    }  

    private function generateRollNumberManually($classId, $sectionId, $schoolId, $financialYearId, $previewMode = false)
    {
        $db = db_connect();
        
        try {
            // Get class information
            $classQuery = $db->table('m_classes')
                            ->select('class_name, class_number')
                            ->where('id', $classId)
                            ->get();
            $class = $classQuery->getRow();
            
            $classCode = 'CLS';
            if ($class) {
                if (!empty($class->class_number)) {
                    $classCode = 'C' . $class->class_number;
                } else {
                    $classCode = preg_replace('/[^A-Z0-9]/', '', strtoupper(substr($class->class_name, 0, 3))) ?: 'CLS';
                }
            }
            $classCode = $classCode . $classId;
            
            // Get section information
            $sectionQuery = $db->table('sections')
                            ->select('section_name, section_number')
                            ->where('id', $sectionId)
                            ->get();
            $section = $sectionQuery->getRow();
            
            $sectionCode = 'SEC';
            if ($section) {
                if (!empty($section->section_number)) {
                    $sectionCode = 'S' . $section->section_number;
                } else {
                    // Try to get section name from m_sections through join
                    $sectionNameQuery = $db->table('sections s')
                                        ->select('ms.name')
                                        ->join('m_sections ms', 'ms.id = s.section_id')
                                        ->where('s.id', $sectionId)
                                        ->get();
                    $sectionName = $sectionNameQuery->getRow();
                    
                    if (!empty($sectionName->name)) {
                        $sectionCode = preg_replace('/[^A-Z0-9]/', '', strtoupper(substr($sectionName->name, 0, 3))) ?: 'SEC';
                    }
                }
            }
            $sectionCode = $sectionCode . $sectionId;
            
            // Get the next roll number
            if ($previewMode) {
                // Preview mode: don't increment the counter
                $trackerQuery = $db->table('roll_number_tracker')
                                ->select('last_roll_number')
                                ->where('class_id', $classId)
                                ->where('section_id', $sectionId)
                                ->where('school_id', $schoolId)
                                ->where('financial_year_id', $financialYearId)
                                ->get();
                
                if ($trackerQuery->getNumRows() > 0) {
                    $tracker = $trackerQuery->getRow();
                    $nextNumber = $tracker->last_roll_number + 1;
                } else {
                    // Check existing students
                    $maxQuery = $db->table('students')
                                ->select('roll_no')
                                ->where('class_id', $classId)
                                ->where('section_id', $sectionId)
                                ->where('school_id', $schoolId)
                                ->where('financial_year_id', $financialYearId)
                                ->orderBy('id', 'DESC')
                                ->limit(1)
                                ->get();
                    
                    $nextNumber = 1;
                    if ($maxQuery->getNumRows() > 0) {
                        $student = $maxQuery->getRow();
                        if ($student->roll_no && preg_match('/-(\d+)$/', $student->roll_no, $matches)) {
                            $nextNumber = (int)$matches[1] + 1;
                        }
                    }
                }
            } else {
                // Actual insertion: increment the counter
                $trackerQuery = $db->table('roll_number_tracker')
                                ->select('last_roll_number')
                                ->where('class_id', $classId)
                                ->where('section_id', $sectionId)
                                ->where('school_id', $schoolId)
                                ->where('financial_year_id', $financialYearId)
                                ->get();
                
                if ($trackerQuery->getNumRows() > 0) {
                    $tracker = $trackerQuery->getRow();
                    $nextNumber = $tracker->last_roll_number + 1;
                    
                    // Update tracker
                    $db->table('roll_number_tracker')
                    ->where('class_id', $classId)
                    ->where('section_id', $sectionId)
                    ->where('school_id', $schoolId)
                    ->where('financial_year_id', $financialYearId)
                    ->update([
                        'last_roll_number' => $nextNumber,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Check existing students
                    $maxQuery = $db->table('students')
                                ->select('roll_no')
                                ->where('class_id', $classId)
                                ->where('section_id', $sectionId)
                                ->where('school_id', $schoolId)
                                ->where('financial_year_id', $financialYearId)
                                ->orderBy('id', 'DESC')
                                ->limit(1)
                                ->get();
                    
                    $nextNumber = 1;
                    if ($maxQuery->getNumRows() > 0) {
                        $student = $maxQuery->getRow();
                        if ($student->roll_no && preg_match('/-(\d+)$/', $student->roll_no, $matches)) {
                            $nextNumber = (int)$matches[1] + 1;
                        }
                    }
                    
                    // Create new tracker entry
                    $db->table('roll_number_tracker')->insert([
                        'class_id' => $classId,
                        'section_id' => $sectionId,
                        'school_id' => $schoolId,
                        'financial_year_id' => $financialYearId,
                        'last_roll_number' => $nextNumber,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            
            $rollNo = $classCode . '-' . $sectionCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            log_message('debug', 'Generated roll number: ' . $rollNo);
            
            return $rollNo;
            
        } catch (\Exception $e) {
            log_message('error', 'Manual roll number generation failed: ' . $e->getMessage());
            
            // Ultimate fallback
            return 'CLS' . $classId . '-SEC' . $sectionId . '-001';
        }
    } 

    // If you need to manually reassign roll numbers
    public function reassignRollNumbers($classId, $sectionId, $schoolId, $financialYearId)
    {
        $db = db_connect();
        
        // Reset the tracker
        $db->table('roll_number_tracker')
           ->where([
               'class_id' => $classId,
               'section_id' => $sectionId,
               'school_id' => $schoolId,
               'financial_year_id' => $financialYearId
           ])
           ->update(['last_roll_number' => 0]);
        
        // Get all students in the specified class/section
        $students = $this->where([
            'class_id' => $classId,
            'section_id' => $sectionId,
            'school_id' => $schoolId,
            'financial_year_id' => $financialYearId
        ])->orderBy('id', 'ASC')->findAll();
        
        // Update each student's roll number
        foreach ($students as $student) {
            $rollNo = $this->generateRollNumber($classId, $sectionId, $schoolId, $financialYearId);
            $this->update($student->id, ['roll_no' => $rollNo]);
        }
        
        return true;
    }

    public function generateAdmissionNumber($schoolId, $financialYearId, $regenerate = false)
    {
        $db = db_connect();
        
        log_message('debug', 'Generating admission number for school: ' . $schoolId . ', FY: ' . $financialYearId . ', regenerate: ' . ($regenerate ? 'true' : 'false'));
        
        try {
            // If regenerating, we need to increment the tracker
            if ($regenerate) {
                $this->incrementAdmissionTracker($schoolId, $financialYearId);
            }
            
            // First, let's test if the stored procedure exists
            $procedureCheck = $db->query("
                SELECT EXISTS (
                    SELECT 1 FROM pg_proc 
                    WHERE proname = 'generate_admission_no'
                ) as procedure_exists
            ")->getRow();
            
            if (!$procedureCheck || !$procedureCheck->procedure_exists) {
                log_message('error', 'Stored procedure generate_admission_no does not exist');
                return $this->generateAdmissionNumberManually($schoolId, $financialYearId, $regenerate);
            }
            
            // Call the stored procedure
            log_message('debug', 'Calling stored procedure generate_admission_no');
            $query = $db->query("SELECT generate_admission_no(?, ?) as admission_no", [
                $schoolId, $financialYearId
            ]);
            
            $result = $query->getRow();
            
            if ($result && !empty($result->admission_no)) {
                log_message('debug', 'Admission number generated via stored procedure: ' . $result->admission_no);
                return ($result->admission_n);
            }
            
            log_message('debug', 'Stored procedure returned empty result, trying manual generation');
            // If stored procedure fails, use manual generation
            return $this->generateAdmissionNumberManually($schoolId, $financialYearId, $regenerate);
            
        } catch (\Exception $e) {
            log_message('error', 'Admission number generation error: ' . $e->getMessage());
            log_message('error', 'Error details: ' . $e->getFile() . ':' . $e->getLine());
            // Fallback to manual generation
            return $this->generateAdmissionNumberManually($schoolId, $financialYearId, $regenerate);
        }
    }

    private function incrementAdmissionTracker($schoolId, $financialYearId)
    {
        $db = db_connect();
        
        // Increment the tracker for regeneration
        $db->table('admission_number_tracker')
        ->where('school_id', $schoolId)
        ->where('financial_year_id', $financialYearId)
        //->set('last_admission_number', 'last_admission_number + 1', false)
        ->set('last_admission_number', 'last_admission_number', false)
        ->update();
    }

    private function generateAdmissionNumberManually($schoolId, $financialYearId, $regenerate = false)
    {
        $db = db_connect();
        
        log_message('debug', 'Starting manual admission number generation');
        
        try {
            // Get financial year short format
            $yearQuery = $db->table('financial_year')
                        ->select('name')
                        ->where('id', $financialYearId)
                        ->get();
            $year = $yearQuery->getRow();
            
            $yearShort = date('y'); // Current year as fallback
            if ($year && !empty($year->name)) {
                // Extract last 2 digits from financial year name
                if (preg_match('/\d{2}$/', $year->name, $matches)) {
                    $yearShort = $matches[0];
                } elseif (preg_match('/\d{4}/', $year->name, $matches)) {
                    // Extract from 4-digit year
                    $yearShort = substr($matches[0], 2, 2);
                }
            }
            
            log_message('debug', 'Year short: ' . $yearShort);
            
            // Get school code or use school ID
            $schoolQuery = $db->table('schools')
                            ->select('school_identity, school_name')
                            ->where('id', $schoolId)
                            ->get();
            $school = $schoolQuery->getRow();
            
            $schoolCode = 'SCH' . $schoolId; // Default fallback
            if ($school) {
                if (!empty($school->school_identity)) {
                    $schoolCode = $school->school_identity;
                } elseif (!empty($school->school_name)) {
                    // Generate code from school name
                    $schoolCode = preg_replace('/[^A-Z0-9]/', '', strtoupper(substr($school->school_name, 0, 3)));
                    if (empty($schoolCode)) {
                        $schoolCode = 'SCH';
                    }
                    $schoolCode .= $schoolId;
                }
            }
            
            log_message('debug', 'School code: ' . $schoolCode);
            
            // Get the next admission number from tracker or existing students
            $trackerQuery = $db->table('admission_number_tracker')
                            ->select('last_admission_number')
                            ->where('school_id', $schoolId)
                            ->where('financial_year_id', $financialYearId)
                            ->get();
            
            $nextNumber = 1;
            if ($trackerQuery->getNumRows() > 0) {
                $tracker = $trackerQuery->getRow();
                $nextNumber = $tracker->last_admission_number + 1;
                
                // Update tracker
                $db->table('admission_number_tracker')
                ->where('school_id', $schoolId)
                ->where('financial_year_id', $financialYearId)
                ->update([
                    'last_admission_number' => $nextNumber,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Check existing students for this school and financial year
                $maxQuery = $db->table('students')
                            ->select('admission_no')
                            ->where('school_id', $schoolId)
                            ->where('financial_year_id', $financialYearId)
                            ->orderBy('id', 'DESC')
                            ->limit(1)
                            ->get();
                
                if ($maxQuery->getNumRows() > 0) {
                    $student = $maxQuery->getRow();
                    if ($student->admission_no && preg_match('/-(\d+)$/', $student->admission_no, $matches)) {
                        $nextNumber = (int)$matches[1] + 1;
                    }
                }
                
                // Create new tracker entry
                $db->table('admission_number_tracker')->insert([
                    'school_id' => $schoolId,
                    'financial_year_id' => $financialYearId,
                    'last_admission_number' => $nextNumber,
                    'prefix' => 'ADM',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            $admissionNo = 'ADM-' . $schoolCode . '-' . $yearShort . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            log_message('debug', 'Generated admission number: ' . $admissionNo);
            
            return $admissionNo;
            
        } catch (\Exception $e) {
            log_message('error', 'Manual admission number generation failed: ' . $e->getMessage());
            
            // Ultimate fallback - very simple format
            return 'ADM-' . $schoolId . '-' . date('y') . '-0001';
        }
    }
    //+++++++++++++++++++++++
    public function getStudentFullSummary($id)
    {
        return $this->db->table('students s')
            ->select("s.*, u.first_name, u.last_name, u.email, u.mobile, u.profile_image,
                    c.class_name, ms.name as section_name, fy.name as financial_year_name")
            ->join('users u', 'u.id = s.user_id')
            ->join('m_classes c', 'c.id = s.class_id')
            ->join('sections sec', 'sec.id = s.section_id')
            ->join('m_sections ms', 'ms.id = sec.section_id')
            ->join('financial_year fy', 'fy.id = s.financial_year_id')
            ->where('s.id', $id)
            ->where('s.school_id', auth()->user()->school_id)
            ->get()->getRowArray();
    }


}