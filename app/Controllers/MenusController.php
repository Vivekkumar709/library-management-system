<?php namespace App\Controllers;

use App\Models\MenuModel;

class MenusController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new MenuModel();
    }

    public function index()
    {
        $this->title = 'Menus';
        $this->content_view = 'menus/menus'; 
        
        $data['data'] = $this->model->getMenuTree();        
        $data['statuses'] = ['0' => 'Active', '1' => 'Inactive']; 
        $data['loadResponsiveTable'] = true;
        $data['loadSortableJS'] = true; 
        $data['distinctiveID'] = $this->distinctive;
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Menus', 'url' => site_url('menus')],
                ['title' => 'List', 'url' => '', 'active' => true]
            ]
        ];        
        
        return $this->render();
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($id = null)
    {
        return $this->form($id);
    }

    protected function form($id = null)
    {
        $isEdit = !is_null($id);
        $menu = $isEdit ? $this->model->find($id) : null;
        
        if ($isEdit && !$menu) {
            return redirect()->to('/menus')->with('error', 'Menu not found');
        }

        $this->title = $isEdit ? 'Edit Menu' : 'Create Menu';
        $this->content_view = 'menus/add';
        
        $data = [
            'isEdit' => $isEdit,
            'menu' => $menu,
            'mainMenus' => $this->model->getParentMenus($id),
            'validation' => \Config\Services::validation(),
        ];
        
        // Get appropriate menus for priority selection
        if ($isEdit && $menu['menu_level'] == 1) {
            $data['priorityMenus'] = $this->model->where('menu_level', 1)
                                                //->where('id !=', $id)
                                                ->where('deleted_at', null)
                                                ->orderBy('priority', 'ASC')
                                                ->findAll();
            $data['maxPriority'] = $this->model->getMaxPriority(1) + 1;
        } elseif ($isEdit) {
            $data['priorityMenus'] = $this->model->where('parent_id', $menu['parent_id'])
                                               // ->where('id !=', $id)
                                                ->where('deleted_at', null)
                                                ->orderBy('priority', 'ASC')
                                                ->findAll();
            $data['maxPriority'] = $this->model->getMaxPriority(2, $menu['parent_id']) + 1;
        } else {
            $data['maxPriority'] = $this->model->getMaxPriority(1) + 1;
        }
        
        $this->content_data = [
            'data' => $data,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard')],
                ['title' => 'Menus', 'url' => site_url('menus')],
                ['title' => $isEdit ? 'Edit' : 'Create', 'url' => '', 'active' => true]
            ]
        ];
        
        return $this->render();
    }

    public function save($id = null)
    {
        $isEdit = !is_null($id);
        $menu = $isEdit ? $this->model->find($id) : null;
        
        if ($isEdit && !$menu) {
            return redirect()->to('/menus')->with('error', 'Menu not found');
        }

        $rules = [
            'name' => 'required|max_length[200]',
            'menu_level' => 'required|in_list[1,2]',
            //'parent_id' => 'permit_empty|numeric',
            'priority' => 'required|numeric',
            'url' => 'permit_empty|max_length[255]',
            //'icon_svg' => 'permit_empty',
            'icon_svg'    => 'permit_empty|uploaded[icon_svg]|mime_in[icon_svg,image/svg+xml]|ext_in[icon_svg,svg]',
            'plan_ids.*' => 'permit_empty|is_natural',
            'plan_ids' => 'required',
            'status' => 'required|in_list[0,1]',
            'show_menu' => 'required|in_list[0,1]'
        ];

        // Validate for submenus
        if ($this->request->getPost('menu_level') == 2) {
            $rules['parent_id'] = 'required|numeric';
        }
        //
        if ($this->request->getPost('menu_level') == 1) {
            $rules['menu_for'] = 'required';
        }
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check for duplicate menu names
        if ($this->model->checkMenuNameExists($this->request->getPost('name'), $id)) {
            return redirect()->back()->withInput()->with('error', 'Menu name already exists.');
        }

        // Handle file upload
        $file = $this->request->getFile('icon_svg');
        $iconSvg = $isEdit ? $menu['icon_svg'] : null;
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'assets/adminAssets/img/menu-icon/';
            $uploadPathFile = 'assets/adminAssets/img/menu-icon/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old file if it exists
            $existing_icon_svg = $menu['icon_svg'] ?? null;
            if ($existing_icon_svg && file_exists($uploadPath . $existing_icon_svg)) {
                unlink($uploadPath . $existing_icon_svg);
            }

            // Generate new filename and move uploaded file
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $iconSvg = $uploadPathFile.$newName;
        }
        // Process plan_ids array to comma-separated string
        $planIds = $this->request->getPost('plan_ids');
        $planIdsString = is_array($planIds) ? implode(',', $planIds) : '';

        $data = [
            'name' => $this->request->getPost('name'),
            'menu_level' => $this->request->getPost('menu_level'),
            'parent_id' => $this->request->getPost('menu_level') == 2 ? $this->request->getPost('parent_id') : null, 
            'menu_for' => $this->request->getPost('menu_level') == 1 ? $this->request->getPost('menu_for') : null,            
            'priority' => $this->request->getPost('priority'),
            'url' => $this->request->getPost('url'),
            'icon_svg' => $iconSvg,
            'plan_ids' => $planIdsString,
            'status' => $this->request->getPost('status'),
            'show_menu' => $this->request->getPost('show_menu')
        ];

        // Add created_by/updated_by
        if (!$isEdit) {
            $data['created_by'] = auth()->id();
        } else {
            $data['updated_by'] = auth()->id();
        }

        if ($this->model->saveWithPriority($data, $id)) {
            return redirect()->to('/menus')->with('message', 'Menu '.($isEdit ? 'updated' : 'created').' successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to '.($isEdit ? 'update' : 'create').' menu');
    }    
    public function get_priorities()
    {
        $menuLevel = $this->request->getPost('menu_level');
        $parentId = $this->request->getPost('parent_id');
        $currentId = $this->request->getPost('current_id');
        
        $builder = $this->model->where('deleted_at', null);
        
        if ($menuLevel == 1) {
            $builder->where('menu_level', 1);
        } else {
            $builder->where('menu_level', 2)
                ->where('parent_id', $parentId);
        }
        
        if ($currentId) {
            //$builder->where('id !=', $currentId);
        }
        
        $existingMenus = $builder->orderBy('priority', 'ASC')->findAll();
        $maxPriority = $this->model->getMaxPriority($menuLevel, $parentId) + 1;
        
        $options = '<option value="">Select Position</option>';
        
        // Add "First Menu" option (priority 1)
        $options .= '<option value="1">First Menu (Position: 1)</option>';
        
        // Add all existing menus with their current positions
        foreach ($existingMenus as $menu) {
            $options .= '<option value="'.$menu['priority'].'">'.
                        'Replace "'.$menu['name'].'" (Position: '.$menu['priority'].')'.
                        '</option>';
        }
        
        // Add "Add at the end" option
        $options .= '<option value="'.$maxPriority.'">Add at the end (New position: '.$maxPriority.')</option>';
        
        return $this->response->setBody($options);
    }

    public function delete($id = null)
    {
        // Validate menu exists
        if (!$menu = $this->model->find($id)) {
            return redirect()->to('/menus')->with('error', 'Menu not found');
        }
        $this->model->db->transStart();
        try {            
            if ($menu['menu_level'] == 1) {
                $this->deleteSubmenus($id);
            }           
            $this->model->delete($id);
            $this->reorderMenusAfterDelete($menu);
            $this->model->db->transComplete();
            return redirect()->to('/menus')->with('message', 'Menu deleted successfully');
        } catch (\Exception $e) {
            $this->model->db->transRollback();
            return redirect()->to('/menus')->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }   
    protected function deleteSubmenus($parentId)
    {
        $this->model->where('parent_id', $parentId)
                   ->where('deleted_at', null)
                   ->delete();
    }
    /* Reorder menus after deletion */
    protected function reorderMenusAfterDelete($deletedMenu)
    {
        $builder = $this->model->where('menu_level', $deletedMenu['menu_level'])
                             ->where('priority >', $deletedMenu['priority'])
                             ->where('deleted_at', null);
        if ($deletedMenu['menu_level'] == 2) {
            $builder->where('parent_id', $deletedMenu['parent_id']);
        }
        $menus = $builder->orderBy('priority', 'ASC')->findAll();
        foreach ($menus as $index => $menu) {
            $this->model->update($menu['id'], ['priority' => $deletedMenu['priority'] + $index]);
        }
    }

    //CHANGE MENU STATUS WITH SUB MENUS TOO
    public function updateStatus()
    {   
        // Validate request method
        if (!$this->request->isAJAX()) {
            return $this->fail('Method not allowed', 405);
        }
        // Get input data
        $table = $this->request->getPost('tbl');
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');        
        // Validate required fields
        if (empty($table) || empty($id) || (empty($status) && $status != 0)) {
            return $this->fail('Missing required parameters', 400);
        }
        $db = \Config\Database::connect();
        $db->transStart();
        try {
            // 1. Update the main menu status
            $updateData = ['status' => $status]; 
            $updateData['updated_at'] = date('Y-m-d H:i:s'); 
            $updateData['updated_by'] = auth()->id();          
            
            $db->table($table)
            ->where('id', $id)
            ->update($updateData);
            // 2. If this is a main menu, update all its submenus
            $menu = $db->table($table)
                    ->select('menu_level')
                    ->where('id', $id)
                    ->get()
                    ->getRow();
            if ($menu && $menu->menu_level == 1) {
                $db->table($table)
                ->where('parent_id', $id)
                ->update($updateData);
            }
            $db->transComplete();
            // Get updated status
            $updatedStatus = $db->table($table)
                            ->select('status')
                            ->where('id', $id)
                            ->get()
                            ->getRow()->status;

            return $this->respond([
                'success' => true,
                'new_status' => $updatedStatus,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', "Status update failed: {$e->getMessage()}");
            return $this->fail($e->getMessage(), 500);
        }
    }

}