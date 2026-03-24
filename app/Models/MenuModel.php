<?php namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'auth_menus';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'menu_level','menu_for','parent_id', 'priority', 'url',
        'icon_svg', 'plan_ids', 'status', 'show_menu',
        'created_by', 'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function getParentMenus($excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('menu_level', 1)
                ->where('deleted_at', null);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->orderBy('priority', 'ASC')->get()->getResultArray();
    }

    public function getSubmenusByParent($parentId, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('parent_id', $parentId)
                ->where('deleted_at', null);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->orderBy('priority', 'ASC')->get()->getResultArray();
    }

    public function checkMenuNameExists($name, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('name', $name)
                ->where('deleted_at', null);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    public function saveWithPriority($data, $id = null)
    {
        $isUpdate = !is_null($id);
        $oldData = $isUpdate ? $this->find($id) : null;

        // For new records
        if (!$isUpdate) {
            // For main menus
            if ($data['menu_level'] == 1) {
                $this->where('priority >=', $data['priority'])
                     ->where('menu_level', 1)
                     ->where('deleted_at', null)
                     ->set('priority', 'priority+1', false)
                     ->update();
            } 
            // For submenus
            else {
                $this->where('priority >=', $data['priority'])
                     ->where('parent_id', $data['parent_id'])
                     ->where('deleted_at', null)
                     ->set('priority', 'priority+1', false)
                     ->update();
            }
            return $this->insert($data);
        }
        // For updates
        else {
            $newPriority = $data['priority'];
            $oldPriority = $oldData['priority'];
            $parentChanged = isset($data['parent_id']) && $data['parent_id'] != $oldData['parent_id'];
            $levelChanged = isset($data['menu_level']) && $data['menu_level'] != $oldData['menu_level'];

            // If parent or level changed, adjust priorities in both old and new locations
            if ($parentChanged || $levelChanged) {
                // Remove from old position
                if ($oldData['menu_level'] == 1) {
                    $this->where('priority >', $oldPriority)
                         ->where('menu_level', 1)
                         ->where('deleted_at', null)
                         ->set('priority', 'priority-1', false)
                         ->update();
                } else {
                    $this->where('priority >', $oldPriority)
                         ->where('parent_id', $oldData['parent_id'])
                         ->where('deleted_at', null)
                         ->set('priority', 'priority-1', false)
                         ->update();
                }

                // Insert to new position
                if ($data['menu_level'] == 1) {
                    $this->where('priority >=', $newPriority)
                         ->where('menu_level', 1)
                         ->where('deleted_at', null)
                         ->set('priority', 'priority+1', false)
                         ->update();
                } else {
                    $this->where('priority >=', $newPriority)
                         ->where('parent_id', $data['parent_id'])
                         ->where('deleted_at', null)
                         ->set('priority', 'priority+1', false)
                         ->update();
                }
            } 
            // If just priority changed within same parent/level
            else {
                if ($newPriority > $oldPriority) {
                    // Moving down in priority
                    $this->where('priority >', $oldPriority)
                         ->where('priority <=', $newPriority)
                         ->where($oldData['menu_level'] == 1 ? 'menu_level = 1' : 'parent_id = '.$oldData['parent_id'])
                         ->where('deleted_at', null)
                         ->set('priority', 'priority-1', false)
                         ->update();
                } else {
                    // Moving up in priority
                    $this->where('priority >=', $newPriority)
                         ->where('priority <', $oldPriority)
                         ->where($oldData['menu_level'] == 1 ? 'menu_level = 1' : 'parent_id = '.$oldData['parent_id'])
                         ->where('deleted_at', null)
                         ->set('priority', 'priority+1', false)
                         ->update();
                }
            }

            return $this->update($id, $data);
        }
    }

    public function getMaxPriority($menuLevel, $parentId = null)
    {
        $builder = $this->builder();
        $builder->where('menu_level', $menuLevel)
                ->where('deleted_at', null);
        
        if ($menuLevel == 2) {
            $builder->where('parent_id', $parentId);
        }
        
        $builder->selectMax('priority');
        $result = $builder->get()->getRowArray();
        return $result['priority'] ?? 0;
    }

    public function getMenuTree()
    {
        // Get all menus ordered by level and priority
        $menus = $this->where('deleted_at', null)
                      ->where('status', 0) 
                      //->whereIN('menu_for', ['S','SL','M','null'])                       
                      ->orderBy('menu_level', 'ASC')
                      ->orderBy('parent_id', 'ASC')
                      ->orderBy('priority', 'ASC')
                      ->findAll();

        $tree = [];
        foreach ($menus as $menu) {
            if ($menu['menu_level'] == 1) {
                // Main menu
                $tree[$menu['id']] = $menu;
                $tree[$menu['id']]['submenus'] = [];
            } else {
                // Submenu - add to parent's submenus array
                if (isset($tree[$menu['parent_id']])) {
                    $tree[$menu['parent_id']]['submenus'][] = $menu;
                }
            }
        }

        return array_values($tree);
    }

    public function getMenuTreeForUser()
    {
        // Get all menus ordered by level and priority
        // $menus = $this->where('deleted_at', null)
        //               ->where('status', 0) 
        //               ->whereIN('menu_for', ['S','SL','M','null'])                       
        //               ->orderBy('menu_level', 'ASC')
        //               ->orderBy('parent_id', 'ASC')
        //               ->orderBy('priority', 'ASC')
        //               ->findAll();

        $menus = $this->where('deleted_at', null)
              ->where('status', 0) 
              ->groupStart()
                  ->whereIn('menu_for', ['S', 'SL', 'M'])
                  ->orWhere('menu_for', null)
              ->groupEnd()
              ->orderBy('menu_level', 'ASC')
              ->orderBy('parent_id', 'ASC')
              ->orderBy('priority', 'ASC')
              ->findAll();


        $tree = [];
        foreach ($menus as $menu) {
            if ($menu['menu_level'] == 1) {
                // Main menu
                $tree[$menu['id']] = $menu;
                $tree[$menu['id']]['submenus'] = [];
            } else {
                // Submenu - add to parent's submenus array
                if (isset($tree[$menu['parent_id']])) {
                    $tree[$menu['parent_id']]['submenus'][] = $menu;
                }
            }
        }

        return array_values($tree);
    }
}