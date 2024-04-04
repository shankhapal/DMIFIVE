<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrLaboratoryReportAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrLaboratoryReportAddMoreDetails";
		
		public function labReportDetails(){
     
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
		
			if(isset($_SESSION['edit_lab_id']))
      { 
        $hide_edit_id = array('id !='=>$_SESSION['edit_lab_id']); 
      }else{ 
        $hide_edit_id = array('id IS NOT NULL'); 
       }
      $added_lab_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();


    $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
	  $DmiBgrAnalysisAddMoreDetails = TableRegistry::getTableLocator()->get('DmiBgrAnalysisAddMoreDetails');
		$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
		$added_firm_field = $added_firms[0];	
		
		//taking id of multiple sub commodities	to show names in list	
		$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
		$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();

			$xyz = [];
			return array($xyz,$added_lab_details,$sub_commodity_value);
		}
		
		public function saveLabReportsDetails($commodity,$total_no_of_pack,$total_qty_graded,$total_e_value,$total_g_charge){
	
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
        'commodity'=>$commodity,
        'total_no_of_pack'=>$total_no_of_pack,
        'total_qty_graded'=>$total_qty_graded,
        'total_e_value'=>$total_e_value,
        'total_g_charge'=>$total_g_charge,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
	
			if($this->save($newEntity)){
				
				return true;
				
			}		
			
		}
		
		public function editLabReportDetails($record_id,$commodity,$total_no_of_pack,$total_qty_graded,$total_e_value,$total_g_charge){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'commodity'=>$commodity,
        'total_no_of_pack'=>$total_no_of_pack,
        'total_qty_graded'=>$total_qty_graded,
        'total_e_value'=>$total_e_value,
        'total_g_charge'=>$total_g_charge,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){				
				return true;				
			}			
		}
		
		
		public function deleteLabDetails($record_id){
			
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