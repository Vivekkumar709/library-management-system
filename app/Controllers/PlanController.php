<?php 
namespace App\Controllers;
use App\Models\PlanModel;
use CodeIgniter\API\ResponseTrait;

class PlanController extends BaseController
{
    protected $planModel;

    public function __construct()
    {
        $this->planModel = new \App\Models\PlanModel();
    }

    public function getPlans()
    {
               
        $this->title = 'Plans';
        $this->content_view = 'plans/plans'; 

        $data['data'] = get_records('plan_packages pp', [
            'joins' => [
                [
                    'table' => 'plan_services ps',
                    'condition' => 'ps.id = ANY(SELECT trim(unnest(string_to_array(pp.service_id, \',\')))::INTEGER)',
                    'type' => 'left'
                ],
                [
                    'table' => 'plan_tenure ptr',
                    'condition' => 'pp.tenure_id = ptr.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'plan_type pt',
                    'condition' => 'pp.plan_type_id = pt.id',
                    'type' => 'left'
                ]
            ],
            'select' => [
                'pp.id',
                'pp.name',
                'pp.price',
                'pp.details',
                'pp.status',
                'ptr.name as tenure',
                'pt.name as plan_types',
                'STRING_AGG(ps.name, \'<br>\') as plan_services'                
            ],            
            'groupBy' => 'pp.id, ptr.name, pt.name' 
        ]);
       
        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Plans', 'url' => '', 'active' => true]
        ];
        
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    } 
    public function addPlans($id = null)
    {
        $this->title = $id ? 'Edit Plan' : 'Add Plan';
        $this->content_view = 'plans/add';

        $data = [
            'data' => $id ? $this->planModel->getPlanWithDetails($id) : null,
            'planType' => get_dropdown('plan_type', 'id', 'name', ['status' => 0],'Plan Type'),
            'planTenure' => get_dropdown('plan_tenure', 'id', 'name', ['status' => 0],'Tenure'),            
            'planServices' => get_dropdown('plan_services', 'id', 'name', ['status' => 0],'Empty'),//Services     
        ];
        $this->thumbnails = [
                ['title' => 'Plans', 'url' => site_url('plans')],
                ['title' => 'Add Plans', 'url' => '', 'active' => true]
        ];

        $data['loadDatePicker'] = true; 
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    }  
    public function savePlan()
    {
        // Check if it's a POST request
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        // Define validation rules
        $rules = [
            'name' => 'required|max_length[255]',
            'price' => 'required|numeric',
            'plan_type_id' => 'required|numeric',
            'tenure_id' => 'required|numeric',
            'service_id' => 'required',
            'service_id.*' => 'integer',                       
            'details' => 'required|max_length[7000]',            
        ];

        // Run validation
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        // Get existing logo if this is an update
        $planId = $this->request->getPost('id');        
        // Prepare data for saving    
        $serviceIds = $this->request->getPost('service_id'); 
        $serviceString = implode(',', $serviceIds);
        $data = [
            'name' => $this->request->getPost('name'),
            'service_id' => $serviceString,
            'tenure_id' => $this->request->getPost('tenure_id'),
            'plan_type_id' => $this->request->getPost('plan_type_id'),
            'price' => $this->request->getPost('price'),
            'details' => $this->request->getPost('details'),           
        ];   
        try {
            if ($planId) {           
                $this->planModel->update($planId, $data);
                $message = 'Plan updated successfully!';
            } else {            
                $this->planModel->insert($data);
                $message = 'Plan created successfully!';
            }
            return redirect()->to('/plans')->with('success', $message);
        } catch (\Exception $e) {                        
            log_message('error', 'Plan save error: ' . $e->getMessage()); 
            echo $e->getMessage(); die;                        
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error saving plan. Please try again.');
        }
    } 
    public function getPlanType()
    {
               
        $this->title = 'Plan Type';
        $this->content_view = 'plans/planType'; 
        $data['data'] = get_records('plan_type', [            
            'orderBy' => 'name ASC'            
        ]);  

        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Plan Type', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    } 
    public function getPlanTenure()
    {
               
        $this->title = 'Plan Tenure';
        $this->content_view = 'plans/planTenure'; 
        $data['data'] = get_records('plan_tenure', [            
            'orderBy' => 'name ASC'            
        ]);  

        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;
        $this->thumbnails = [                
                ['title' => 'Plan Tenure', 'url' => '', 'active' => true]
        ];
        $this->content_data = [
            'data' => $data,            
        ];        
        return $this->render();
    }   
    public function getPlanServices()
    {               
        $this->title = 'Plan Services';
        $this->content_view = 'plans/planServices'; 
        $data['data'] = get_records('plan_services', [            
            'orderBy' => 'name ASC'            
        ]);  

        $data['statuses'] = ['1' => 'Active', '0' => 'Inactive'];
        $data['loadResponsiveTable'] = true;
        $data['distinctiveID'] = $this->distinctive;

        $this->thumbnails = [                
                ['title' => 'Plan Services', 'url' => '', 'active' => true]
        ];

        $this->content_data = [
            'data' => $data,            
        ];
        
        return $this->render();
    }
    
}