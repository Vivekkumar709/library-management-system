<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\API\ResponseTrait;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    //DEFINE VARIABLES FOR RENDERING
    protected $template = 'adminlayout/main.php';
    protected $title = 'Smart Education ERA';
    protected $distinctive;
    protected $thumbnails = '';
    protected $content_view = '';
    protected $content_data = [];
    use ResponseTrait;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        helper('permission');

        $this->response->setHeader('Cache-Control', 'no-store');

        $this->distinctive = $this->generateRandomString(12);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        $this->session = service('session');   

        //TO GET EXTRA LOGGED IN USER DATA       
        if (auth()->loggedIn()) {
            $user = auth()->user();            
            // Load data using your helper
            $user_group = get_value_by_id('auth_groups', 'id', $user->user_type_id);
            //$designationName = get_value_by_id('m_staff_roles', 'id', $user->designation_id);
            $arrayD = array_map('intval', explode(',', $user->designation_id));
            $designationNameArray = get_values_by_ids('m_staff_roles', 'id', $arrayD);
            $designationName = implode(', ', array_values($designationNameArray));
            
            $permanentStateName = get_value_by_id('states', 'id', $user->permanent_state);
            $permanentStateCode = get_value_by_id('states', 'id', $user->permanent_state, 'state_code');
            $permanentCityName = get_value_by_id('cities', 'id', $user->permanent_city, 'city');
            $presentStateName = get_value_by_id('states', 'id', $user->present_state);
    
            $permanentAddress = $user->permanent_address;
            $permanentAddressfull = explode(',', $permanentAddress, 2)? :[];
            $permanentAddressLine1 = trim($permanentAddressfull[0]);
            $permanentAddressLine2 = isset($permanentAddressfull[1]) ? trim($permanentAddressfull[1]) : '';
    
            $presentAddress = $user->present_address;
            $presentAddressfull = explode(',', $presentAddress, 2)? :[];
            $presentAddressLine1 = trim($presentAddressfull[0]);
            $presentAddressLine2 = isset($presentAddressfull[1]) ? trim($presentAddressfull[1]) : '';
    
            // Make them available globally in views
            $renderer = service('renderer');
            $renderer->setVar('LoggedInUserGroup', $user_group);
            $renderer->setVar('LoggedInUserDesignation', $designationName);
            $renderer->setVar('LoggedInUserPermanentStateName', $permanentStateName);
            $renderer->setVar('LoggedInUserPermanentStateCode', $permanentStateCode);
            $renderer->setVar('LoggedInUserPermanentCityName', $permanentCityName);
            $renderer->setVar('LoggedInUserPresentStateName', $presentStateName);
            $renderer->setVar('LoggedInUserPermanentAddressLine1', $permanentAddressLine1);
            $renderer->setVar('LoggedInUserPermanentAddressLine2', $permanentAddressLine2);
            $renderer->setVar('LoggedInUserPresentAddressLine1', $presentAddressLine1);
            $renderer->setVar('LoggedInUserPresentAddressLine2', $presentAddressLine2);
        }



    } 
    
    /**
     * Renders the view with common template
     */
    protected function render(): string
    {   
        if (!is_file(APPPATH.'Views/'.$this->template)) {
            throw new \RuntimeException('Template not found');
        }
        $menuModel = new \App\Models\MenuModel();
        $menuTree = $menuModel->getMenuTree();

        return view($this->template, [
            'title' => $this->title,
            'thumbnails' => $this->thumbnails,
            'content_view' => $this->content_view,
            'content_data' => $this->content_data,
            'menuTree' => $menuTree
        ]);
    }

    protected function generateRandomString($length = 12): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
