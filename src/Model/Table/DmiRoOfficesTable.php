<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;


class DmiRoOfficesTable extends Table{

	var $name = "DmiRoOffices";

	public $validate = array(

			'ro_office'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty'=>false,
				),
			'ro_office_address'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty'=>false,
				),
			'short_code'=>array(
					'rule'=>array('maxLength',10),
					'allowEmpty'=>false,
				),
			'ro_email_id'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty'=>false,
				),
			'delete_status'=>array(
					'rule'=>array('maxLength',10),
				),
			'user_email_id'=>array(
					'rule'=>array('maxLength',200),
				),
			'ro_office_phone'=>array(
					'rule1'=>array(
							'rule'=>array('lengthBetween', 6, 15),
							'allowEmpty'=>false,
							'last'=>false,
						),
					'rule2'=>array(
							'rule'=>'Numeric',
						)
				),

	);


	// getRoOfficeEmail
	// Author : Akash Thakre
	// Description : This function will get the email by table id
	// Date : 30-05-2022

	public function getRoOfficeEmail($id) {

		$get_user_details = $this->find()->select(['ro_email_id'])->where(['id' => $id])->first();
		$userEmail = $get_user_details['ro_email_id'];
		return $userEmail;
	}


	// getOfficeDetails
	// Author : Akash Thakre
	// Description : This function will get the array of (a) Office Name, (b) Office Type, (c) Incharge Email, (d) RO table ID for SO by Username.
	// Date : 30-05-2022

	public function getOfficeDetails($username) {

		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');

		$getUser = $DmiUsers->find('all')->where(['email' => $username,'status !='=>'disactive'])->first();
		$posted_ro_office = $getUser['posted_ro_office'];

		$officeDetails = $this->find('all',array('conditions'=>array('id IS' => $posted_ro_office, 'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first();

		$office_name = $officeDetails['ro_office'];
		$office_type = $officeDetails['office_type'];
		$office_email = $officeDetails['ro_email_id'];
		$ro_id_for_so = $officeDetails['ro_id_for_so'];

		return array($office_name,$office_type,$office_email,$ro_id_for_so);
	}



	// getIoForCurrentOffice
	// Author : Akash Thakre
	// Description : This function will get the array of (a) Name of user, (b) Email, for Inspection officer by Username for current office of that username.
	// Date : 30-05-2022

	public function getIoForCurrentOffice() {

		$io_user_list = array();
		$customer_id='';
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiAllocations = TableRegistry::getTableLocator()->get('DmiAllocations');

		if (isset($_POST['search_applicant_id'])) {
			$customer_id = $_POST['search_applicant_id'];
		}
		
		if (!empty($customer_id)) {
			
			$getAllocatedUser = $DmiAllocations->getAllocatedIo($customer_id);
			
			if (empty($getAllocatedUser)) {
				$io_user_list = $this->getIoAllocatedUsersDirectly();
			} else {
				$getName = $DmiUsers->getFullName($getAllocatedUser);
				$io_user_list[0] = array('io_name' => $getName, 'io_email' => $getAllocatedUser);
			}

		} else {
	
			$io_user_list = $this->getIoAllocatedUsersDirectly();
		}

		return $io_user_list;
	}



	// getScrutinizerForCurrentOffice
	// Author : Akash Thakre
	// Description : This function will get the array of (a) Name of user, (b) Email, for Inspection officer by Username for current office of that username.
	// Date : 30-05-2022

	public function getScrutinizerForCurrentOffice() {
        
		$scrutinizer_list = array();
		$customer_id='';
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiAllocations = TableRegistry::getTableLocator()->get('DmiAllocations');
		
		if (isset($_POST['search_applicant_id'])) {
			$customer_id = $_POST['search_applicant_id'];
		}
		
		if (!empty($customer_id)) {
			
			$getAllocatedUser = $DmiAllocations->getAllocatedIo($customer_id);
			
			if (empty($getAllocatedUser)) {
				$scrutinizer_list = $this->getAllocatedScrutinizerDirectly();
			} else {
				$getName = $DmiUsers->getFullName($getAllocatedUser);
				$scrutinizer_list[0] = array('scrutinizers_name' => $getName, 'scrutinizers_email' => $getAllocatedUser);
			}

		} else {
			
			$scrutinizer_list = $this->getAllocatedScrutinizerDirectly();
		}

		return $scrutinizer_list;		
	}





	public function getAllocatedScrutinizerDirectly(){

		$scrutinizer_list = array();
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');

		$officeID = $DmiUsers->getPostedOffId($_SESSION['username']);
		$get_user_details = $DmiUsers->find('all')->where(['posted_ro_office IS' => $officeID, 'status !=' => 'disactive'])->toArray();

		if(!empty($get_user_details)){

			$i = 0;
			foreach($get_user_details as $each){

				$check_user_role = $DmiUserRoles->find('all')->select(['user_email_id','mo_smo_inspection'])->where(['user_email_id IS'=> $each['email']])->first();

				if(!empty($check_user_role)){

					if($check_user_role['mo_smo_inspection'] == 'yes') {

						$scrutinizers = $DmiUsers->find('all',array('conditions'=>array('email IS'=> $each['email'], 'status !=' =>'disactive')))->first();

						$scrutinizer_list[$i] = array('scrutinizers_name' => $scrutinizers['f_name'].' '.$scrutinizers['l_name'], 'scrutinizers_email' => $scrutinizers['email']);

						$i = $i + 1;
					}
				}
			}

		}else{

			$scrutinizer_list = array();
		}
	
		return $scrutinizer_list;
	
	}


	public function getIoAllocatedUsersDirectly(){

		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');

		//get posted ro office id
		$posted_ro_office = $DmiUsers->getPostedOffId($_SESSION['username']);
		
		$get_user_details = $DmiUsers->find('all')->where(['posted_ro_office IS' => $posted_ro_office, 'status !=' => 'disactive'])->toArray();
		
		if(!empty($get_user_details)){

			$i = 0;
			foreach($get_user_details as $each){

				$check_user_role = $DmiUserRoles->find('all')->select(['user_email_id','io_inspection'])->where(['user_email_id IS'=> $each['email']])->first();

				if(!empty($check_user_role)){

					if($check_user_role['io_inspection'] == 'yes') {

						$io_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=> $each['email'], 'status !=' =>'disactive')))->first();

						$io_user_list[$i] = array('io_name' => $io_details['f_name'].' '.$io_details['l_name'], 'io_email' => $io_details['email']);

						$i = $i + 1;
					}
				}
			}

		}else{

			$io_user_list = array();
		}
	
		return $io_user_list;
	}


	public function getOic($id) {

		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$getEmail = $this->find('all')->select(['ro_email_id'])->where(['id IS' => $id,'delete_status IS NULL'])->first();
		$get_user_details = $DmiUsers->find('all')->select(['id'])->where(['email IS'=>$getEmail['ro_email_id'],'role' =>'RAL/CAL OIC','status !='=>'disactive'])->first();
		if(!empty($get_user_details)){
			$oic = $get_user_details['id'];
		}else{
			$oic=null;
		}
		return $oic;
	}

	 public function getOfficeDetailsById($id) {

        $officeDetails = $this->find('all',array('conditions'=>array('id IS' => $id, 'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first();
        $office_name = $officeDetails['ro_office'];
        $office_type = $officeDetails['office_type'];
        $office_email = $officeDetails['ro_email_id'];
        return array($office_name,$office_type,$office_email);
    }

    public function getAllOffices(){
    	return $this->find('list', ['keyField' => 'id','valueField' => 'ro_office'])
    	->where(['delete_status IS NULL','office_type !=' => 'RAL'])
    	->order(['ro_office' => 'ASC'])
    	->toArray();
    }
}

?>
