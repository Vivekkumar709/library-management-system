<?php 
namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'plan_packages';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'service_id',
        'tenure_id', 
        'plan_type_id',
        'price', 
        'details',                 
        'status',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_on';
    protected $updatedField = 'updated_on';
    
    protected $beforeInsert = ['setInsertData'];
    protected $beforeUpdate = ['setUpdateData'];
    
    protected function setInsertData(array $data)
    {
        $data['data']['created_by'] = auth()->id();
        return $data;
    }
    
    protected function setUpdateData(array $data)
    {
        $data['data']['updated_by'] = auth()->id();
        return $data;
    }
    
    public function getPlanWithDetails($id)
    {   
        return $this->db->table('plan_packages pp')
            ->select('pp.*')           
            ->join('plan_services ps', 'ps.id = ANY(SELECT trim(unnest(string_to_array(pp.service_id, \',\')))::INTEGER)', 'left')
            ->join('plan_tenure ptr', 'pp.tenure_id = ptr.id', 'left')
            ->join('plan_type pt', 'pp.plan_type_id = pt.id', 'left')
            ->where('pp.id', $id)
            ->get()
            ->getRowArray();
            
    }

    public function getSchoolsWithDetails(array $filters = [])
    {
        $builder = $this->db->table('schools s')
            ->select('s.*, st.name as school_type, sm.name as school_medium')
            ->join('m_school_types st', 'st.id = s.school_type_id', 'left')
            ->join('m_school_mediums sm', 'sm.id = s.school_medium_id', 'left');
        
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
        //echo "<pre>";
        //print_r($filters);
        //echo $builder->getCompiledSelect();         
        //print_r($builder->get()->getResultArray());
        //die;
        return $builder->get()->getResultArray();
    }

}