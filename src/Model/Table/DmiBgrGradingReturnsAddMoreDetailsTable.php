<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrGradingReturnsAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrGradingReturnsAddMoreDetails";
		
		public function gredingReturnsDetails(){
     
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
		
			if(isset($_SESSION['edit_greding_id']))
      { 
        $hide_edit_id = array('id !='=>$_SESSION['edit_greding_id']); 
      }else{ 
        $hide_edit_id = array('id IS NOT NULL'); 
       }
      $added_greding_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();


    $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
	  $DmiBgrAnalysisAddMoreDetails = TableRegistry::getTableLocator()->get('DmiBgrAnalysisAddMoreDetails');
		$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
		$added_firm_field = $added_firms[0];	
		
		//taking id of multiple sub commodities	to show names in list	
		$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
		$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();

			$xyz = [];
			return array($xyz,$added_greding_details,$sub_commodity_value);
		}
		
		public function saveGredingReturnsDetails($packing_date,$commodity,$lot_no,$grade,$size_of_packing,$total_no_of_pks,$replica_used,$total_weight,$total_qty,$estimated_value,$replica_charge){
	
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
        'packing_date'=>$packing_date,
        'commodity'=>$commodity,
        'lot_no'=>$lot_no,
				'grade'=>$grade,
        'size_of_packing'=>$size_of_packing,
        'total_no_of_pks'=>$total_no_of_pks,
        'replica_used'=>$replica_used,
        'total_weight'=>$total_weight,
        'total_qty'=>$total_qty,
        'estimated_value'=>$estimated_value,
        'replica_charge'=>$replica_charge,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
	
			if($this->save($newEntity)){
				
				return true;
				
			}		
			
		}
		
		public function editGredingDetails($record_id,$packing_date,$commodity,$lot_no,$grade,$size_of_packing,$total_no_of_pks,$replica_used,$total_weight,$total_qty,$estimated_value,$replica_charge){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'packing_date'=>$packing_date,
        'commodity'=>$commodity,
				'lot_no'=>$lot_no,
				'grade'=>$grade,
        'size_of_packing'=>$size_of_packing,
        'total_no_of_pks'=>$total_no_of_pks,
        'replica_used'=>$replica_used,
        'total_weight'=>$total_weight,
        'total_qty'=>$total_qty,
        'estimated_value'=>$estimated_value,
        'replica_charge'=>$replica_charge,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){				
				return true;				
			}			
		}
		
		
		public function deleteGredingDetails($record_id){
			
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