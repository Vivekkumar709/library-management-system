<?php
namespace App\Controllers;

use App\Models\StudentDocumentModel;
use App\Models\StudentModel;

class StudentDocumentsController extends BaseController
{
    protected $studentDocumentModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->studentDocumentModel = new StudentDocumentModel();
        $this->studentModel = new StudentModel();
    }
    
    // List documents for a student
    public function list($studentId)
    {   
        // Get student details
        $student = $this->studentModel->getStudentWithDetails($studentId);
        
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }
        
        $data['documents'] = $this->studentDocumentModel->getDocumentsByStudent($studentId);        
        $data['student'] = $student;
        $data['documentTypes'] = [
            'birth_certificate' => 'Birth Certificate',
            'aadhaar_card' => 'Aadhaar Card',
            'transfer_certificate' => 'Transfer Certificate',
            'marksheet' => 'Marksheet',
            'photo' => 'Photograph',
            'medical_certificate' => 'Medical Certificate',
            'caste_certificate' => 'Caste Certificate',
            'income_certificate' => 'Income Certificate',
            'other' => 'Other'
        ];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        
        $this->title = 'Student Documents - ' . $student['first_name'] . ' ' . $student['last_name'];
        $this->content_view = 'students/documents/list';
        $this->thumbnails = [
                ['title' => 'Students', 'url' => site_url('students')],
                ['title' => 'Student Documents', 'url' => '', 'active' => true]
        ];

        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }
    
    // Upload document
    public function upload($studentId)
    {
        // Check if student exists
        $student = $this->studentModel->find($studentId);
        if (!$student) {
            return redirect()->to('/students')->with('error', 'Student not found.');
        }
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'document_type' => 'required',
            'document_name' => 'required|max_length[255]',
            'document_file' => 'uploaded[document_file]|max_size[document_file,5120]|mime_in[document_file,image/jpeg,image/png,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $file = $this->request->getFile('document_file');
        
        if ($file->isValid() && !$file->hasMoved()) {
            // Create upload directory if not exists
            $uploadPath = WRITEPATH . 'uploads/students/' . $studentId . '/documents/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Generate unique filename
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            
            $data = [
                'student_id' => $studentId,
                'document_type' => $this->request->getPost('document_type'),
                'document_name' => $this->request->getPost('document_name'),
                'document_path' => 'students/' . $studentId . '/documents/' . $newName,
                'created_by' => auth()->id()
            ];
            
            if ($this->studentDocumentModel->insert($data)) {
                return redirect()->to('/students/documents/' . $studentId)->with('success', 'Document uploaded successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to upload document.');
            }
        }
        
        return redirect()->back()->with('error', 'File upload failed.');
    }
    
    // Download document
    public function download($documentId)
    {
        $document = $this->studentDocumentModel->find($documentId);
        
        if (!$document) {
            return redirect()->back()->with('error', 'Document not found.');
        }
        
        $filePath = WRITEPATH . 'uploads/' . $document['document_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return $this->response->download($filePath, null)->setFileName($document['document_name'] . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
    
    // View document
    public function view($documentId)
    {
        $document = $this->studentDocumentModel->find($documentId);
        
        if (!$document) {
            return redirect()->back()->with('error', 'Document not found.');
        }
        
        $filePath = WRITEPATH . 'uploads/' . $document['document_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $document['document_name'] . '"');
        readfile($filePath);
        exit;
    }
    
    // Delete document
    public function delete($documentId)
    {
        $document = $this->studentDocumentModel->find($documentId);
        
        if (!$document) {
            return redirect()->back()->with('error', 'Document not found.');
        }
        
        $filePath = WRITEPATH . 'uploads/' . $document['document_path'];
        
        // Delete file from server
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        if ($this->studentDocumentModel->delete($documentId)) {
            return redirect()->back()->with('success', 'Document deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete document.');
        }
    }
    
    // Update document status
    public function updateStatus($documentId)
    {
        $document = $this->studentDocumentModel->find($documentId);
        
        if (!$document) {
            return $this->response->setJSON(['success' => false, 'message' => 'Document not found.']);
        }
        
        $newStatus = $document['status'] == 0 ? 1 : 0;
        
        if ($this->studentDocumentModel->update($documentId, ['status' => $newStatus])) {
            return $this->response->setJSON(['success' => true, 'new_status' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status.']);
        }
    }
}