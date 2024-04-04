<?php 
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;

class DmiUserLogsTable extends Table{

	public function saveLog($username,$status){

		if($username==null){ $username = $_SESSION['username']; }

		$current_ip = $_SERVER['REMOTE_ADDR'];
	
		if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

		$entity = $this->newEntity([
			'email_id'=>$username,
			'ip_address'=>$current_ip,
			'date'=>date('Y-m-d'),
			'time_in'=>date('H:i:s'),
			'time_out'=>date('H:i:s'),
			'remark'=>$status]);

		$this->save($entity);
	}
} 
?>