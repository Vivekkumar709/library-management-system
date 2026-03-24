<?php
namespace App\Models;
use CodeIgniter\Model;

class FeeModel extends Model
{
    protected $table = 'student_fees';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id','fee_structure_id','total_amount','paid_amount','status'];

    public function getAllFees($student_id = null)
    {
        $builder = $this->db->table('student_fees sf');
        $builder->select('sf.*, s.admission_no, u.first_name, u.last_name, fs.fee_head, c.class_name');
        $builder->join('students s', 's.id = sf.student_id');
        $builder->join('users u', 'u.id = s.user_id');
        $builder->join('fee_structures fs', 'fs.id = sf.fee_structure_id');
        $builder->join('m_classes c', 'c.id = fs.class_id', 'left');
        $builder->where('s.school_id', auth()->user()->school_id);

        if ($student_id) {
            $builder->where('sf.student_id', $student_id);
        }

        $builder->orderBy('sf.id', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getFeeStructures()
    {
        return $this->db->table('fee_structures')
            ->where('school_id', auth()->user()->school_id)
            ->where('status', 0)
            ->get()->getResultArray();
    }

    public function insertPayment($data)
    {
        return $this->db->table('fee_payments')->insert($data);
    }

    public function updatePaidAmount($student_id, $fee_structure_id, $amount)
    {
        $this->db->table('student_fees')
            ->where('student_id', $student_id)
            ->where('fee_structure_id', $fee_structure_id)
            ->set('paid_amount', 'paid_amount + ' . (float)$amount, false)
            ->update();
    }

    public function getTotalDue($student_id)
    {
        $row = $this->db->table('student_fees')
            ->select('SUM(due_amount) as total_due')
            ->where('student_id', $student_id)
            ->get()->getRow();
        return $row->total_due ?? 0;
    }

    public function getReceipt($payment_id)
    {
        return $this->db->table('fee_payments fp')
            ->select('fp.*, s.admission_no, u.first_name, u.last_name, u.mobile')
            ->join('students s', 's.id = fp.student_id')
            ->join('users u', 'u.id = s.user_id')
            ->where('fp.id', $payment_id)
            ->get()->getRowArray();
    }

    public function getStudentFeeSummary($student_id)
    {
        return $this->getAllFees($student_id);
    }
}