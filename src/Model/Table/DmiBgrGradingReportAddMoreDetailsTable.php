<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiBgrGradingReportAddMoreDetailsTable extends Table{
		
		var $name = "DmiBgrGradingReportAddMoreDetails";
		
		public function gredingReportDetails(){
     
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


			$xyz = [];
			return array($xyz,$added_greding_details);
		}
		
		public function saveGredingReportDetails($name_of_packer,$packing_date,$lot_no,$moisture,$color,$total_ash,$acidity,$specific_gravity,$sucrose,$trs,$fg_ratio,$anilene_chloride_test,$fleche_test,$grade){
	
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
        'name_of_packer'=>$name_of_packer,
        'packing_date'=>$packing_date,
        'lot_no'=>$lot_no,
        'moisture'=>$moisture,
        'color'=>$color,
        'total_ash'=>$total_ash,
        'acidity'=>$acidity,
        'specific_gravity'=>$specific_gravity,
        'sucrose'=>$sucrose,
        'trs'=>$trs,
        'fg_ratio'=>$fg_ratio,
        'anilene_chloride_test'=>$anilene_chloride_test,
        'fleche_test'=>$fleche_test,
				'grade'=>$grade,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
	
			if($this->save($newEntity)){
				
				return true;
				
			}		
			
		}
		
		public function editGredingDetails($record_id,$name_of_packer,$packing_date,$lot_no,$moisture,$color,$total_ash,$acidity,$specific_gravity,$sucrose,$trs,$fg_ratio,$anilene_chloride_test,$fleche_test,$grade){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'name_of_packer'=>$name_of_packer,
				'packing_date'=>$packing_date,
				'lot_no'=>$lot_no,
				'moisture'=>$moisture,
				'color'=>$color,
				'total_ash'=>$total_ash,
				'acidity'=>$acidity,
				'specific_gravity'=>$specific_gravity,
				'sucrose'=>$sucrose,
				'trs'=>$trs,
				'fg_ratio'=>$fg_ratio,
				'anilene_chloride_test'=>$anilene_chloride_test,
				'fleche_test'=>$fleche_test,
				'grade'=>$grade,
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