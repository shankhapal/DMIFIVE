<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class DmiSentEmailLogsTable extends Table{

	var $name = "DmiSentEmailLogs";

	public function saveLog($message_id,$email_id,$email_message,$template_id){

		$entity = $this->newEntity(array(

			'message_id'=>$message_id,
			'destination_list'=>$email_id,
			'sent_date'=>date('Y-m-d H:i:s'),
			'message'=>$email_message,
			'created'=>date('Y-m-d H:i:s'),
			'template_id'=>$template_id
		));

		$this->save($entity);
	}

}
?>