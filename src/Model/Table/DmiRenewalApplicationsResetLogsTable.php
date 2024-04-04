<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
	
class DmiRenewalApplicationsResetLogsTable extends Table{
	
	public function saveResetLog($customer_id,$pao_id,$user_id,$pao_email,$last_current_level,$last_current_position_email)
	{
	    $dataEntity = $this->newEntity(array(

            'customer_id'=>$customer_id,
            'pao_id'=>$pao_id,
            'user_id'=>$user_id,
            'pao_email'=>$pao_email,
            'last_current_level'=>$last_current_level,
            'last_current_position_email'=>$last_current_position_email,
            'done_by'=>$_SESSION['username'],
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s')
        ));

        $this->save($dataEntity);

        return true;
	}
	
}

?>