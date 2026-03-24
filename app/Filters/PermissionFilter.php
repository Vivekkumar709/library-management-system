<?php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Load your helper (change 'permission' to your actual helper name if different)        
        helper('permission');
        
        // Handle the arguments - they come as comma-separated values
        if (empty($arguments)) {            
            return redirect()->to('/')->with('error', 'Invalid permission configuration.');
        }        
        // The first argument might contain comma-separated values
        $params = $arguments;
        
        // If there's only one argument and it contains a comma, split it
        if (count($arguments) === 1 && strpos($arguments[0], ',') !== false) {
            $params = explode(',', $arguments[0]);
        }        

        if (count($params) < 2) {            
            return redirect()->to('/2')->with('error', 'Invalid permission configuration.');
        }

        $menu   = trim($params[0]); // e.g. '/schools' or menu_id number        
        $permId = (int) trim($params[1]);

        if (!has_permission($menu, $permId)) {
           
            if (auth()->loggedIn()) {
                // Logged in but no permission
                return redirect()->back()
                    ->with('error', 'You do not have permission to access this module.');
            }

            // Not logged in
            return redirect()->to(route_to('login') ?? '/login')
                ->with('error', 'Please login first.');
        }
        //log_message('debug', "Permission GRANTED for {$menu}.{$permId}");
        return null; // permission granted → continue
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed
    }
}