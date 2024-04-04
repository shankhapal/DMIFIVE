<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrAnalysisFoodSafetyAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrAnalysisFoodSafetyAddMoreDetails";
		
		public function foodSafetyDetails(){
		
      
     
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


			$xyz = [];
			return array($xyz,$added_analysis_details);
		}
		
		public function saveAnalysisFoodDetails($lot_no,$packing_date,$name_of_lab,$report_no_date,$remarks){
	
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
                        
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'lot_no'=>$lot_no,
				'packing_date'=>$packing_date,
        'name_of_lab'=>$name_of_lab,
        'report_no_date'=>$report_no_date,
        'remarks'=>$remarks,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
	
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}
		
		public function editAnalysisDetails($record_id,$lot_no,$packing_date,$name_of_lab,$report_no_date,$remarks){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'lot_no'=>$lot_no,
				'packing_date'=>$packing_date,
				'name_of_lab'=>$name_of_lab,
				'report_no_date'=>$report_no_date,
				'remarks'=>$remarks,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){				
				return true;				
			}			
		}
		
		
		public function deleteFoodDetails($record_id){
			
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