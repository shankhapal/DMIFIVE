<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

	class DmiRtiPackerDetailsTable extends Table{

		var $name = "DmiRtiPackerDetails";
			   
	
		public function savePackageingDetails($packer_id,$indent,$supplied,$balance,$tbl_name){
			
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}

			$CustomersController = new CustomersController;
			// added version for inserting version in this table by shankhpal on 08/06/2023
			$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);

												
			$customer_once_no = $_SESSION['once_card_no'];
			
			$newEntity = $this->newEntity(array(

				'customer_id'=>$customer_id,
				'packer_id'=>$packer_id,
				'indent'=>$indent,
				'supplied'=>$supplied,
				'balance'=>$balance,
				'tbl'=>$tbl_name,
				'version'=>$current_version,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}

	public function packagingDetails(){
		
		$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			
		if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
			$customer_id = $_SESSION['customer_id'];
		}else{
			$customer_id = $_SESSION['username'];
		}
		if(isset($_SESSION['edit_packer_id'])){ 
			$hide_edit_id = array('id !='=>$_SESSION['edit_machine_id']); 
		}else{ 
			$hide_edit_id = array('id IS NOT NULL');  
			$edit_id = '';
		}

		$CustomersController = new CustomersController;
		// added version for inserting version in this table by shankhpal on 08/06/2023
		$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);
		$added_sample_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id, 'customer_id IS'=>$customer_id,'delete_status IS NULL','version'=>$current_version),'order'=>'id'))->toArray();
				
		$find_packers_details = $this->find('all',array('conditions'=>array('id IS'=>$edit_id)))->first();
			
		$firm_data = $DmiFirms->find('all',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('customer_id IN'=> $customer_id)))->toArray();
    
		$firm_id = $firm_data[0]['id'];
		$find_ca_list = $DmiCaPpLabMapings->find('list',array('keyField'=>'customer_id','valueField'=>'customer_id', 'conditions'=>array('pp_id'=>$firm_id)))->toArray();

		return array($find_packers_details,$added_sample_details,$find_ca_list);
	}

			
		public function editPackerDetails($record_id,$packer_id,$indent,$supplied,$balance,$tbl){
	
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'packer_id'=>$packer_id,
				'indent'=>$indent,
				'supplied'=>$supplied,
				'balance'=>$balance,
				'tbl'=>$tbl,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){				
				return true;				
			}			
		}

		public function deletePackDetails($record_id){
			
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
