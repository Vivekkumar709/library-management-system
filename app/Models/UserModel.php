<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
class UserModel extends ShieldUserModel
{
    //protected $returnType = \App\Entities\User::class;

    protected $table = 'users';
    protected $primaryKey = 'id';  
    // Disable soft deletes if you want permanent deletion
    protected $useSoftDeletes = false;      
    protected $allowedFields = [
        'first_name', 
        'last_name', 
        'email_id', 
        'email', 
        'mobile', 
        'designation_id',          
        'about', 
        'profile_image', 
        'permanent_address',        
        'permanent_landmark',
        'permanent_state',
        'permanent_city',
        'permanent_pincode',
        'present_address',
        'present_landmark',
        'present_state',
        'present_city',
        'present_pincode',       
        'status',
        'updated_by',
        'deleted_by',
        'paswd',
        'user_type_id',
        'school_id',  
        'username',    
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getUserWithDetails($id)
    {   
        return $this->db->table('users u')
            ->select('u.*,agu.group_id as group_id,ai.type as auth_type,ai.secret as email,ai.secret2 as hashpassword')   
            ->join('auth_groups_users agu', 'u.id = agu.user_id', 'left')
            ->join('auth_identities ai', 'u.id = ai.user_id', 'left')            
            ->where('u.id', $id)
            ->get()
            ->getRowArray();
            
    }
    //TO GET TEACHERS INFORMATION
    public function getTeachersWithDetails($id)
    {   
        return $this->db->table('users u')
            ->select('u.*,agu.group_id AS group_id,ai.type AS auth_type,ai.secret AS email,ai.secret2 AS hashpassword,uit.service_start_from AS service_start_from,uit.employement_type_id,uit.specialization_subject_ids,uit.prefered_teaching_level_id,uit.highest_qualification_id,uit.approval_status')   
            ->join('auth_groups_users agu', 'u.id = agu.user_id', 'left')
            ->join('auth_identities ai', 'u.id = ai.user_id', 'left')  
            ->join('users_info_teachers uit', 'u.id = uit.user_id', 'left')
            ->where('u.id', $id)
            ->where('u.user_type_id', 13)
            ->get()
            ->getRowArray();
            
    }

    
    
}