<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

	class DmiChangeFieldListsTable extends Table{

			
		public function changeFieldList($selectfields){
			
			$app = new AppController;
			$CustomersController = new CustomersController;
			$customer_id = $CustomersController->Customfunctions->sessionCustomerID();
			$form_type = $CustomersController->Customfunctions->checkApplicantFormType($customer_id);
			
			$changefieldAarray = [];			
			$paymentforchange = 'NA';
			
			foreach($selectfields as $data){
				
				if(!ctype_digit($data)){						
					$app->invalidActivities();
				}
				$changeField = $this->find('all',array('valueField'=>array('c_filed_id','payment','sectionid'),'conditions'=>array('field_id IS'=>$data, 'form_type IS'=>'common')))->first();
     				if(!empty($changeField)){
				//commented this line as it throw deprecation error for null parameter, on 25-05-2023 by Amol
				//$explode = explode(',',$changeField['c_filed_id']);							
				$explode = array();
				$changefieldAarray = array_merge($changefieldAarray,$explode);				
				
				if($changeField['payment'] == 'paid'){
					$paymentforchange = 'available';
					//break;
					}
				}		
			}
			
			return array($changefieldAarray,$paymentforchange);
			
		} 	
		
	}

?>