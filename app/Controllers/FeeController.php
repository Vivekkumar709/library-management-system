<?php
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

class FeeController extends BaseController
{
    protected $feeModel;

    public function __construct()
    {
        $this->feeModel = model('FeeModel');
    }

    // ==================== LIST ALL FEES ====================
    public function list($student_id = null)
    {
        $data['fees'] = $this->feeModel->getAllFees($student_id);
        $data['students'] = model('StudentModel')->getStudents(); // for filter dropdown

        $this->title = $student_id ? 'Student Fee Details' : 'Fee Management';
        $this->content_view = 'fee/list';

        $this->thumbnails = [
            ['title' => 'Fee Management', 'url' => '', 'active' => true]
        ];

        $this->content_data = [
            'data' => $data,
            'distinctiveID' => 'fee_list',
            'loadResponsiveTable' => true,
            'student_id' => $student_id
        ];

        $this->breadcrumbs = [
            ['title' => 'Dashboard', 'url' => site_url('dashboard')],
            ['title' => 'Fee Management', 'url' => site_url('fee')],
            ['title' => $student_id ? 'Student Fees' : 'All', 'url' => '', 'active' => true]
        ];

        return $this->render();
    }

    // ==================== COLLECT FEE FORM ====================
    public function collect($student_id = null)
    {
        $this->title = 'Collect Fee';
        $this->content_view = 'fee/collect';

        $data['student'] = $student_id ? model('StudentModel')->getStudentWithDetails($student_id) : null;
        $data['fee_heads'] = $this->feeModel->getFeeStructures();

        $this->content_data = ['data' => $data];
        return $this->render();
    }

    // ==================== SAVE PAYMENT ====================
    public function savePayment()
    {
        $post = $this->request->getPost();
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert payment
            $paymentData = [
                'student_id'    => $post['student_id'],
                'amount'        => $post['amount'],
                'payment_date'  => date('Y-m-d'),
                'payment_mode'  => $post['payment_mode'] ?? 'Cash',
                'receipt_no'    => 'RCPT-' . time(),
                'created_by'    => auth()->id()
            ];

            $this->feeModel->insertPayment($paymentData);

            // Update student_fees paid_amount
            $this->feeModel->updatePaidAmount($post['student_id'], $post['fee_structure_id'], $post['amount']);

            $db->transComplete();

            return redirect()->to('/fee/list/' . $post['student_id'])
                ->with('success', 'Fee collected successfully! Receipt generated.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ==================== PRINT RECEIPT ====================
    public function receipt($payment_id)
    {
        $data['payment'] = $this->feeModel->getReceipt($payment_id);
        return view('fee/receipt_print', $data);   // A4 print
    }

    // AJAX for fee preview
    public function previewDue()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);
        $student_id = $this->request->getPost('student_id');
        $due = $this->feeModel->getTotalDue($student_id);
        return $this->response->setJSON(['due' => $due]);
    }
}