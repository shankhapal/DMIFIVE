<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;

class DmiDistrictsTable extends Table{
	
	
	public $validate = array(
		
			'district_name'=>array(
					'rule'=>array('maxLength',100),		
					'allowEmpty'=>true,
				),
			'state_id'=>array(
					'rule'=>'Numeric',	
					'allowEmpty'=>true,
				),
			'ro_id'=>array(
					'rule'=>'Numeric',	
					'allowEmpty'=>true,
				),
			'pao_id'=>array(
					'rule'=>'Numeric',
					'allowEmpty'=>true,
				),
		);


	// get ro and so id from district code by akash on 02-06-2022
	public function getRoIdFromDistrictId($district_id){

		$details = $this->find()->where(['id IS' => $district_id,'delete_status IS NULL'])->first();
		if (!empty($details)) {
			return array('ro_id' => $details['ro_id'], 'so_id' => $details['so_id']);
		}
		
	}

	//getDistrictNameById
	//Description: this function retrun the district name from the id given
	//Created : Akash [08-12-2022]
	public function getDistrictNameById($district_id){
		$fetch_district_name = $this->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$district_id, 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_district_name = $fetch_district_name['district_name'];
		return $firm_district_name;
	}

}

?>