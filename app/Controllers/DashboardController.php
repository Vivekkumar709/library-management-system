<?php 
namespace App\Controllers;
use CodeIgniter\Controller;


class DashboardController extends BaseController
{  
    public function dashboard(): string
    {   
        $this->title = 'Dashboard';
        $this->thumbnails = 'Dashboard';
        $this->content_view = 'dashboard';
        $html = "";//file_get_contents(APPPATH . 'Views/welcome_message.php');
        $this->content_data = [
            'data' => $html          
        ];
        return $this->render();
    }

    public function userProfile(): string
    {  
        
        $this->title = 'Profile';
        $this->thumbnails = 'Profile';
        $this->thumbnails = [                
                ['title' => 'Profile', 'url' => '', 'active' => true]
        ];
        $this->content_view = 'userProfile';
        $data['roles'] = get_dropdown('m_staff_roles', 'id', 'name', ['status' => 0]);        
        //$html = "Profile Page";//file_get_contents(APPPATH . 'Views/welcome_message.php');
        $data['otherData'] = "Profile Page";

        $this->content_data = [
            'data' => $data          
        ];
        return $this->render();
    }
    public function updateProfile()
    {
        // Initialize database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Request Validation
            if (!$this->request->isAJAX()) {
                throw new \RuntimeException('Direct script access not allowed');
            }

            $userId = auth()->id();
            if (!$userId) {
                throw new \RuntimeException('User not authenticated');
            }
            
            $validationRules = [
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|max_length[100]',
                    'errors' => [
                        'required' => 'Please enter your email address',
                        'valid_email' => 'Please provide a valid email address',
                        'max_length' => 'Email cannot exceed 100 characters'
                    ]
                ],
                'mobile' => [
                    'label' => 'Mobile',
                    'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]',
                    'errors' => [
                        'numeric' => 'Mobile must contain only numbers',
                        'min_length' => 'Mobile must be at least 10 digits',
                        'max_length' => 'Mobile cannot exceed 15 digits'
                    ]
                ],  
                'profile_image' => [
                    'label' => 'Profile Image',
                    'rules' => 'permit_empty|uploaded[profile_image]|max_size[profile_image,2048]|mime_in[profile_image,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'uploaded' => 'Please select an image file',
                        'max_size' => 'Image size cannot exceed 2MB',
                        'mime_in' => 'Only JPG, JPEG, and PNG images are allowed'
                    ]
                ],
            ];

            if (!$this->validate($validationRules)) {
                $errors = $this->validator->getErrors();
                throw new \RuntimeException(implode(" ", $errors));
            }

            // 2. Image Handling - Changed to public/uploads
            $imageFile = $this->request->getFile('profile_image');
            $imageUploadPath = FCPATH . 'uploads/profile_images/'; // FCPATH points to public folder
            $newImageName = null;
            $oldImagePath = null;

            // Get current user data
            $userModel = new \App\Models\UserModel();
            $currentUser = $userModel->asObject()->find($userId);           
            
            if (!$currentUser) {
                throw new \RuntimeException('User data not found');
            }

            // Check if new image was uploaded
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $newImageName = $imageFile->getRandomName();
                
                // Get current profile image path if exists
                if (!empty($currentUser->profile_image)) {  

                    $dbImagePath = str_replace('/', DIRECTORY_SEPARATOR, $currentUser->profile_image);
                    $oldImagePath = FCPATH . $dbImagePath;               
                     
                    // Verify old image exists before marking for deletion
                    if (!file_exists($oldImagePath)) {                        
                        $oldImagePath = null; // Skip deletion if file doesn't exist
                    }
                }      
                        
                // Ensure directory exists
                if (!is_dir($imageUploadPath)) {
                    mkdir($imageUploadPath, 0755, true);
                    // Add security file to prevent directory listing
                    file_put_contents($imageUploadPath . 'index.html', '<!DOCTYPE html><title>403 Forbidden</title><p>Directory access forbidden.</p>');
                }
            }
            // 3. Prepare Update Data
            $updateData = [            
                'email' => trim(htmlspecialchars(strip_tags($this->request->getPost('email')))),
                'mobile' => trim(htmlspecialchars(strip_tags($this->request->getPost('mobile')))),
                'about' => trim(htmlspecialchars(strip_tags($this->request->getPost('about')))),  
                'permanent_address' => implode(', ', array_filter([
                    trim(htmlspecialchars(strip_tags($this->request->getPost('permanent_address1')))),
                    trim(htmlspecialchars(strip_tags($this->request->getPost('permanent_address2'))))
                ])),            
                'permanent_landmark' => trim(htmlspecialchars(strip_tags($this->request->getPost('permanent_landmark')))),                    
                'permanent_state' => (int)trim($this->request->getPost('permanent_state')) ?: null,
                'permanent_city' => (int)trim($this->request->getPost('permanent_city')) ?: null,
                'permanent_pincode' => trim(htmlspecialchars(strip_tags($this->request->getPost('permanent_pincode')))),
                'present_address' => implode(', ', array_filter([
                    trim(htmlspecialchars(strip_tags($this->request->getPost('present_address1')))),
                    trim(htmlspecialchars(strip_tags($this->request->getPost('present_address2'))))
                ])),            
                'present_landmark' => trim(htmlspecialchars(strip_tags($this->request->getPost('present_landmark')))),            
                'present_state' => (int)trim($this->request->getPost('present_state')) ?: null,
                'present_city' => (int)trim($this->request->getPost('present_city')) ?: null,
                'present_pincode' => trim(htmlspecialchars(strip_tags($this->request->getPost('present_pincode')))),                      
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $userId
            ];

            // Only update image path if new image was uploaded
            if ($newImageName) {
                $updateData['profile_image'] = 'uploads/profile_images/' . $newImageName; // Public-relative path
            }

            // 4. Database Update
            if (!$userModel->update($userId, $updateData)) {
                throw new \RuntimeException('Failed to update profile in database');
            }

            // 5. File Operations (only after successful DB update)
            if ($newImageName) {
                // Move new image first
                if (!$imageFile->move($imageUploadPath, $newImageName)) {
                    throw new \RuntimeException('Failed to save uploaded image');
                }
    
                // Then delete old image if exists
                if ($oldImagePath && file_exists($oldImagePath)) { 
                    if (!unlink($oldImagePath)) {
                        log_message('error', "Failed to delete old profile image: {$oldImagePath}");
                        // Don't throw exception - the new image was saved successfully
                    }
                }
            }           
            // Commit transaction
            $db->transCommit();
    
            // 6. Prepare Success Response
            $responseData = [
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => [
                    'user_id' => $userId,
                    'updated_fields' => array_keys($updateData)
                ]
            ];
    
            if ($newImageName) {
                $responseData['data']['profile_image_url'] = base_url('uploads/profile_images/' . $newImageName);
                if ($oldImagePath) {
                    $responseData['data']['old_image_deleted'] = !file_exists($oldImagePath);
                }
            }
    
            return $this->respond($responseData);
    
        } catch (\RuntimeException $e) {
            $db->transRollback();
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
    
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Profile update failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->respond([
                'status' => 'error',
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }
    //TO GET CITIES BY STATE ID AND SEND IT INTO AJAX RESPONSE
    public function getCitiesByState()
    {
        helper('dropdown');
        if ($this->request->isAJAX()) {
            $stateId = $this->request->getJSON()->state_id;

            // Use helper instead of writing SQL here
            $cities = get_values_by_column('cities', 'state_id', $stateId, 'id', 'city');

            return $this->response->setJSON($cities);
        }

        return $this->response->setStatusCode(400)->setBody('Invalid request');
    }


    
}
