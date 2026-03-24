<?php 
namespace App\Models;

use CodeIgniter\Model;

class SchoolModel extends Model
{
    protected $table = 'schools';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'school_registration_no',
        'school_name',
        'school_type_id',
        'school_medium_id',
        'school_affiliation_id',
        'school_education_level_id',
        'school_tradition_id',
        'total_no_staff',
        'school_branch',
        'school_logo',
        'owner_name',
        'owner_mobile',
        'state_id',
        'city_id',
        'landmark',
        'pincode',
        'contact_person_address',
        'contact_person_name',
        'contact_person_mobile',
        'contact_person_email',
        'contact_person_work_details',
        'plan_id',
        'valid_from',
        'plan_payable_amount',
        'payment_mode_id',
        'status',
        'school_address',
        'school_website',
        'school_email_id',
        'school_identity'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_on';
    protected $updatedField = 'updated_on';
    
    protected $beforeInsert = ['setInsertData'];
    protected $beforeUpdate = ['setUpdateData'];
    
    protected function setInsertData(array $data)
    {
        $data['data']['created_by'] = auth()->id();
        $school_identity = generate_school_id();
        $data['data']['school_identity'] = $school_identity;        
        return $data;
    }
    
    protected function setUpdateData(array $data)
    {
        $data['data']['updated_by'] = auth()->id();
        return $data;
    }
    
    public function getSchoolWithDetails($id)
    {
        return $this->db->table('schools s')
            ->select('s.*, st.name as school_type, sm.name as school_medium')
            ->join('m_school_types st', 'st.id = s.school_type_id', 'left')
            ->join('m_school_mediums sm', 'sm.id = s.school_medium_id', 'left')
            ->where('s.id', $id)
            ->get()
            ->getRowArray();
    }

    public function getSchoolsWithDetails(array $filters = [])
    {
        $builder = $this->db->table('schools s')
            ->select('s.*, st.name as school_type, sm.name as school_medium')
            ->join('m_school_types st', 'st.id = s.school_type_id', 'left')
            ->join('m_school_mediums sm', 'sm.id = s.school_medium_id', 'left')
            ->orderBy('id','DESC');
        
        // Apply filters if they exist
        if (!empty($filters['school_type'])) {
            $builder->where('s.school_type_id', $filters['school_type']);
        }
        
        if (!empty($filters['school_medium'])) {
            $builder->where('s.school_medium_id', $filters['school_medium']);
        }
        
        if (isset($filters['status']) && $filters['status'] !== '') {
            $builder->where('s.status', $filters['status']);
        }        
        return $builder->get()->getResultArray();
    }

}