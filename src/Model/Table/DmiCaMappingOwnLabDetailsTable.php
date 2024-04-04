<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;

class DmiCaMappingOwnLabDetailsTable extends Table{

		var $name = "DmiCaMappingOwnLabDetails";
		var $useTable = 'dmi_ca_mapping_own_lab_details';

		public function deleteLabDetails($lab_record_id,$lab_customer_id,$lab_id,$lab_map_type){
		     
			$Dmi_ca_pp_lab_action_Logs = TableRegistry::getTableLocator()->get('DmiCaPpLabActionLogs');
			$Dmi_ca_pp_lab_mappings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
			
			$current_ip = $_SERVER['REMOTE_ADDR'];
			
			if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

			$Dmi_ca_pp_lab_mappings->updateAll(array('delete_status' => "yes"),array('customer_id' => $lab_customer_id,'map_type'=>'lab','delete_status IS NULL'));

			$dmi_ca_pp_lab_action_logs_entity = $Dmi_ca_pp_lab_action_Logs->newEntity(
				
			['customer_id'=>$lab_customer_id,
			'ipaddress'=>$current_ip,
			'action_perform'=>'Laboratory (Removed)',
			'created'=>date('Y-m-d H:i:s'),
			'status'=>'Success']
			);
				
			if($Dmi_ca_pp_lab_action_Logs->save($dmi_ca_pp_lab_action_logs_entity)){

					$newEntity = $this->newEntity(array(
					'id'=>$lab_record_id,
					'customer_id'=>$lab_customer_id,
					'lab_id'=>$lab_id,
					'map_type'=>$lab_map_type,
					'delete_status'=>'yes',
					'modified'=>date('Y-m-d H:i:s')
					));
					
					if($this->save($newEntity)){						

						return true;
					}

			}



		}
										
}

?>
