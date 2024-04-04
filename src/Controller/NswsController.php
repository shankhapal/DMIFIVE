<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;

class NswsController extends AppController {

    private $DmiNswsApplMappings;

    public function initialize(): void {

        parent::initialize();
         //Load Components
        // added by shankhpal on 27-03-2024
         $components = [
            'Createcaptcha',
            'Customfunctions',
            'Authentication',
            'AqcmsWrapper'
        ];

        foreach($components as $component){
            $this->loadComponent($component);
        }

        //Set Helpers
        $this->viewBuilder()->setHelpers(['Form', 'Html', 'Time']);

        // Call the loadAllModels method to load necessary models.
        $this->loadAllModels();
    }




    /**
     * Loads all necessary models for the current controller.
     * This method is responsible for loading models required for the controller's functionality.
     * Author: Shankhpal Shende
     * Date: 28-03-2024
     */
    private function loadAllModels(): void {

        $this->DmiNswsApplMappings = $this->AqcmsWrapper->customeLoadModel('DmiNswsApplMappings');

    }


    //Before Filter
    public function beforeFilter($event) {

        parent::beforeFilter($event);
	}

	public function primApplRegViaNsws(){

		$this->layout = false;
		$this->autoRender = false;

		if($this->request->is('post')){

			$reqdata = $this->request->getData();
			if(!empty($reqdata['InvestorSWSId']) && !empty($reqdata['f_name'])
				&& !empty($reqdata['l_name']) && !empty($reqdata['email'])
				&& !empty($reqdata['mobile'])){

				$investorId = $reqdata['InvestorSWSId'];

				//check if investor id is already present, if not register new primary id

				$getRecord = $this->DmiNswsApplMappings->find('all',array('fields'=>'id','conditions'=>array('investor_id'=>$investorId)))->first();
				if(empty($getRecord)){

					//create a primary applicant id

				}

				$response = array('200'=>'Success');
			}else{
				$response = array('401'=>'Unauthorized');
			}

			echo json_encode($response);
		}


	}

}

?>
