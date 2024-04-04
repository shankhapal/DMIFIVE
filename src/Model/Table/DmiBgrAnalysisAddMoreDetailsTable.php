<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrAnalysisAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrAnalysisAddMoreDetails";
		
		public $validate = array(
		
			// 'machine_name'=>array(
			// 		'rule'=>array('maxLength',200),	
			// 		'allowEmpty'=>false,
			// 	),											
			// 'machine_type'=>array(
			// 		'rule1'=>array(
			// 			'rule'=>array('maxLength',50),
			// 			'allowEmpty'=>false,
			// 			'last'=>false),
			// 		'rule2'=>array(
			// 			'rule'=>'Numeric')
			// 	),
			// 'machine_no'=>array(
			// 		'rule'=>array('maxLength',100),
			// 		'allowEmpty'=>false,
			// 	),
			// 'machine_capacity'=>array(
			// 		'rule'=>array('maxLength',50),
			// 		'allowEmpty'=>false,
			// 	),
				
			);
		
		public function analysisDetails(){
		
      
     
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
		
			if(isset($_SESSION['edit_analysis_id']))
      { 
        $hide_edit_id = array('id !='=>$_SESSION['edit_analysis_id']); 
      }else{ 
        $hide_edit_id = array('id IS NOT NULL'); 
       }
      $added_analysis_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();
      
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
			$DmiBgrAnalysisAddMoreDetails = TableRegistry::getTableLocator()->get('DmiBgrAnalysisAddMoreDetails');
			$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
			$added_firm_field = $added_firms[0];	

			
			//taking id of multiple sub commodities	to show names in list	
			$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
			$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();
      

			$DmiChemicalParameters = TableRegistry::getTableLocator()->get('DmiChemicalParameters');
		  $chemical_parameters = $DmiChemicalParameters->find('list', array('valueField'=>'chemical_parameters','conditions'=>array('delete_status IS NULL'),'order'=>'id'))->toList();


      $abc = [];
			$xyz = [];
			return array($abc,$added_analysis_details,$sub_commodity_value,$chemical_parameters);
		}
		
		public function saveAnalysisDetails($date,$commodity,$batch_no,$quantity,$chemical_parameters,$analysis_grade,$analysis_date,$analysis_remark){
	
      $array = explode(',', $chemical_parameters);
			$chemical_parameters_value=implode(', ',$array);

			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
                        
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'report_date'=>$date,
				'commodity'=>$commodity,
        'batch_no'=>$batch_no,
        'quantity'=>$quantity,
        'chemical_parameters'=>$chemical_parameters,
        'analysis_grade'=>$analysis_grade,
        'analysis_date'=>$analysis_date,
        'analysis_remark'=>$analysis_remark,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
	
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}
		
		
		
		
		
		public function editAnalysisDetails($record_id,$report_date,$commodity,$batch_no,$quantity,$chemical_parameters,$analysis_grade,$analysis_date,$analysis_remark){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'report_date'=>$report_date,
				'commodity'=>$commodity,
				'batch_no'=>$batch_no,
				'quantity'=>$quantity,
				'chemical_parameters'=>$chemical_parameters,
				'analysis_grade'=>$analysis_grade,
				'analysis_date'=>$analysis_date,
				'analysis_remark'=>$analysis_remark,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){				
				return true;				
			}			
		}
		
		
		public function deleteAnalysisDetails($record_id){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}
				
		
}

?>