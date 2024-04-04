<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;

class DmiStatesTable extends Table{
	
	
	public $validate = array(
		
			'state_name'=>array(
					'rule'=>array('maxLength',100),		
					'allowEmpty'=>false,
				),	
		);


	//getDistrictNameById
	//Description: this function retrun the district name from the id given
	//Created : Akash [08-12-2022]
	public function getStateNameById($state_id){
		$fetch_state_name = $this->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$state_id, 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_state_name = $fetch_state_name['state_name'];
		return $firm_state_name;
	}

	
	

}

?>