<?php 
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;

class DmiLxrFinalSubmitsTable extends Table{
	
	var $name = "DmiLxrFinalSubmits";

	public function checkIfSurrender($customer_id){

		$result = $this->find()->select('customer_id')->where(['customer_id IS'=>$customer_id])->order(['id'=>'ASC'])->first();
		if (!empty($result)) {
			return 'yes';
		}else{
			return 'no';
		}
	}
		
}

?>