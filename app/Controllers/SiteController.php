<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

class SiteController extends BaseController
{   
    use ResponseTrait;
    protected $template = 'layout/main.php';
    protected $title = 'Smart Education ERA';
    protected $content_view = '';
    protected $content_data = array();

    // public function __construct() {
    //     parent::__construct();
    // }

    protected function render(): string {
        return view($this->template, [
            'title' => $this->title,
            'content_view' => $this->content_view,
            'content_data' => $this->content_data
        ]);
    }

    
    public function index(): string
    {
        
        $this->title = 'Home - Smart Education ERA';
        $this->content_view = 'home';

        $html = '';//file_get_contents(APPPATH . 'Views/welcome_message.php');
        $this->content_data['data'] = $html;

        return $this->render();
    }
    public function submit()
    {        
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('Direct access not allowed');
        }
        $validation = \Config\Services::validation();
        $rules = [
            'name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'subject' => 'required|max_length[200]',
            'message' => 'required'
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($validation->getErrors());
        }
        // Get filtered input data
        $data = [
            'name' => esc($this->request->getPost('name', FILTER_SANITIZE_STRING)),
            'email' => esc($this->request->getPost('email', FILTER_SANITIZE_EMAIL)),
            'subject' => esc($this->request->getPost('subject', FILTER_SANITIZE_STRING)),
            'message' => esc($this->request->getPost('message', FILTER_SANITIZE_STRING)),
            'created_at' => date('Y-m-d H:i:s')
        ];
        // Insert data
        $db = \Config\Database::connect();        
        try {
            $db->table('contacts')->insert($data);
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Your message has been sent. Thank you!'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
            return $this->failServerError('There was a problem sending your message');
        }
    }
    //SUBSCRIBE NOW
    public function subscribeNow(): string
    {
        
        $this->title = 'Subscribe Now - Smart Education ERA';
        $this->content_view = 'subscribeNow';

        $html = '';//file_get_contents(APPPATH . 'Views/welcome_message.php');
        $this->content_data['data'] = $html;

        return $this->render();
    }

}
