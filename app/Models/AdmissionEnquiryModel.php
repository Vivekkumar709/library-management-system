<?php
namespace App\Models;
use CodeIgniter\Model;

class AdmissionEnquiryModel extends Model {
    protected $DBGroup          = 'default';
    protected $table            = 'admission_enquiries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_name', 'date_of_birth', 'gender', 'nationality',
        'current_institution', 'father_name', 'mother_name',
        'mobile', 'email', 'address', 'country_id', 'state_id',
        'city_id','school_id','address_pincode', 'course_applying', 'academic_year',
        'preferred_campus', 'heard_from', 'special_requirements', 'questions'
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'student_name'      => 'required|max_length[100]',
        'date_of_birth'     => 'required|valid_date',
        'gender'            => 'required|in_list[Male,Female,Other]',
        'nationality'       => 'required|max_length[50]',
        'father_name'       => 'required|max_length[100]',
        'mother_name'       => 'required|max_length[100]',
        'mobile'            => 'required|max_length[20]',
        'email'             => 'required|valid_email|max_length[100]',
        'address'           => 'required|max_length[255]',        
        'country_id'        => 'required|is_natural_no_zero',
        'state_id'          => 'required|is_natural_no_zero',
        'city_id'           => 'required|is_natural_no_zero',
        //'school_id'         => 'is_natural_no_zero',
        'address_pincode'   => 'required|integer',
        'course_applying'   => 'required|max_length[100]',
        'academic_year'     => 'required|max_length[20]',
        'heard_from'        => 'required|max_length[50]',
    ];

    protected $validationMessages = [
        'school_id' => [
            'required' => 'The school ID field is required',
            'is_natural_no_zero' => 'The school ID must be a valid positive number'
        ],
        'email' => ['valid_email' => 'Please enter a valid email address.']
    ];
}