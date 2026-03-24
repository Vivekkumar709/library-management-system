<?php
namespace App\Controllers;

class AcademicsController extends BaseController
{
    protected $subjectModel;

    public function __construct()
    {
        $this->subjectModel = model('SubjectModel');
    }

    // ==================== MANAGE SUBJECTS ====================
    public function subjects()
    {
        $this->title = 'Manage Class Subjects';
        $this->content_view = 'academics/subjects';

        $data['classes'] = get_dropdown('m_classes', 'id', 'class_name', ['status' => 0], 'Select Class');
        $data['subjects'] = $this->subjectModel->getAllSubjects();

        $this->content_data = ['data' => $data];
        $this->loadResponsiveTable = true;

        $this->thumbnails = [
            ['title' => 'Manage Academics', 'url' => '', 'active' => true]
        ];

        return $this->render();
    }

    // ==================== SAVE / UPDATE SUBJECT ====================
    public function saveSubject()
    {
        $post = $this->request->getPost();

        $data = [
            'class_id'      => $post['class_id'],
            'subject_name'  => $post['subject_name'],
            'subject_code'  => $post['subject_code'] ?? '',
            'max_marks'     => $post['max_marks'] ?? 100,
            'school_id'     => auth()->user()->school_id
        ];

        if (!empty($post['id'])) {
            $this->subjectModel->update($post['id'], $data);
            $msg = 'Subject updated successfully!';
        } else {
            $this->subjectModel->insert($data);
            $msg = 'Subject added successfully!';
        }

        return redirect()->to('/academics/subjects')
            ->with('success', $msg);
    }

    // ==================== DELETE SUBJECT ====================
    public function delete($id)
    {
        $this->subjectModel->delete($id);
        return redirect()->to('/academics/subjects')
            ->with('success', 'Subject deleted successfully!');
    }

    // AJAX - Get subjects for a class (for dropdowns or preview)
    public function getClassSubjects()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        $class_id = $this->request->getPost('class_id');
        $subjects = $this->subjectModel->where('class_id', $class_id)
                                       ->where('school_id', auth()->user()->school_id)
                                       ->findAll();

        return $this->response->setJSON($subjects);
    }
}