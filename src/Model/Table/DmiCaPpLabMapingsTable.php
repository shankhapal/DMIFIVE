<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;

class DmiCaPpLabMapingsTable extends Table{

	var $name = "DmiCaPpLabMapings";
	var $useTable = 'dmi_ca_pp_lab_mapings';

		 
		
	// Description : The getReplicaAllotmentDetails function are use to get replica  allotment details for attached printing press,lab 
	// Author : Shankhpal Shende
	// Date : 10/05/2023
	// For Module : attached pp/lab/wonlab
	public function getReplicaAllotmentDetails($record_id){
		
		$DmiReplicaAllotmentDetails = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentDetails');
		$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');

		$find_details = $DmiCaPpLabMapings->find('all', array('conditions' => array('OR' => array('pp_id' => $record_id,'lab_id' => $record_id),'delete_status IS NULL'),'limit' => 1))->first();
	
		$pp_id = $find_details['pp_id'];
		$lab_id = $find_details['lab_id'];
		
		$replica_appl_list = array(); // Initialize as an empty array
		if ($pp_id != null || $lab_id != null) {
			$conditions = array(
					'delete_status IS NULL'
			);

			if ($pp_id != null) {
					$conditions['authorized_printer'] = $pp_id;
			} elseif ($lab_id != null) {
					$conditions['grading_lab'] = $lab_id;
			}
			// for replica allotment details
			$replica_appl_list = $DmiReplicaAllotmentDetails->find('all', array(
					'fields' => array('customer_id', 'alloted_rep_from', 'alloted_rep_to', 'created'),
					'conditions' => $conditions
			))->toArray();
			
}

		if(!empty($replica_appl_list)){

			$replica_alloted_resultArray = [];
			$i=0;
			foreach ($replica_appl_list as $resultArray) {
				
				$delimiter = " ";
				$replica_alloted_resultArray[$i]['alloted_rep_from'] = $resultArray['alloted_rep_from'];
				$replica_alloted_resultArray[$i]['alloted_rep_to'] = $resultArray['alloted_rep_to'];
				$replica_alloted_resultArray[$i]['created'] = $resultArray['created'];
				$i++;
				
			}
			
			return $replica_alloted_resultArray;
		}else{
			return null;
		}
	}

	// Description : The deletePpDetails function are use to delete attached printing press, 
	// Author : Shankhpal Shende
	// Date : 04/05/2023
	// For Module : attached pp/lab/wonlab
	public function deletePpDetails($pp_record_id,$pp_customer_id,$pp_id,$pp_map_type,$remark){


				$current_ip = $_SERVER['REMOTE_ADDR'];
				if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }
	
				$Dmi_ca_pp_lab_action_Logs = TableRegistry::getTableLocator()->get('DmiCaPpLabActionLogs');
				
				$newEntity = $this->newEntity(array(

					'id'=>$pp_record_id,
					'customer_id'=>$pp_customer_id,
					'pp_id'=>$pp_id,
					'map_type'=>$pp_map_type,
					'delete_status'=>'yes',
					'$remark'=>$remark,
					'modified'=>date('Y-m-d H:i:s')
				)); 

				//Save printing delete Logs Status
				$dmi_ca_pp_lab_action_logs_entity = $Dmi_ca_pp_lab_action_Logs->newEntity(
					['customer_id'=>$pp_customer_id,
					'ipaddress'=>$current_ip,
					'action_perform'=>'Printing Press (Removed)',
					'created'=>date('Y-m-d H:i:s'),
					'status'=>'Success']
				);
			
				$Dmi_ca_pp_lab_action_Logs->save($dmi_ca_pp_lab_action_logs_entity);	
			

				if ($this->save($newEntity)) {
					
					return true;
				}

}

		// Description : The deleteLabDetails function are use to delete attached domestic labaratory
		// Author : Shankhpal Shende
		// Date : 04/05/2023
		// For Module : attached pp/lab/wonlab
		public function deleteLabDetails($lab_record_id,$lab_customer_id,$lab_id,$lab_map_type){
		       
						$Dmi_ca_pp_lab_action_Logs = TableRegistry::getTableLocator()->get('DmiCaPpLabActionLogs');
						
						$current_ip = $_SERVER['REMOTE_ADDR'];
						
						if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

						$newEntity = $this->newEntity(array(

							'id'=>$lab_record_id,
							'customer_id'=>$lab_customer_id,
							'lab_id'=>$lab_id,
							'map_type'=>$lab_map_type,
							'delete_status'=>'yes',
							'modified'=>date('Y-m-d H:i:s')
						)); 

						//Save printing delete Logs Status
						$dmi_ca_pp_lab_action_logs_entity = $Dmi_ca_pp_lab_action_Logs->newEntity(
							['customer_id'=>$lab_customer_id,
							'ipaddress'=>$current_ip,
							'action_perform'=>'Laboratory (Removed)',
							'created'=>date('Y-m-d H:i:s'),
							'status'=>'Success']
						);
					
						$Dmi_ca_pp_lab_action_Logs->save($dmi_ca_pp_lab_action_logs_entity);	

						if ($this->save($newEntity)) {
							
							return true;
						}

		}

		public function pplabDetails($customer_id,$record_id){
			
			$DmiCaMappingOwnLabDetails = TableRegistry::getTableLocator()->get('DmiCaMappingOwnLabDetails');

			if (strpos($record_id, "/Own") !== false) {
				$recordidArray = explode("/", $record_id);
				$ownlabId = $recordidArray[0];
			
				$cappdetails = $DmiCaMappingOwnLabDetails->find('all', array(
						'conditions' => array(
								'id' => $ownlabId
						)
				))->first();
		
				$lab_id = $cappdetails['own_lab_id'];
			
			} else {
				$cappdetails = $this->find('all',array('conditions'=>array('id'=>$record_id,'customer_id IS'=>$customer_id)))->first();
				
			}
			return $cappdetails;		

	}

}
?>
