<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;

class DevsController extends AppController {

	private $DmiRoOffices;
    private $DmiUserRoles;

	public function initialize(): void {

		parent::initialize();

		// added by shankhpal on 27-03-2024
        $components = [
            'Customfunctions',
            'Authentication',
            'AqcmsWrapper'
        ];

        foreach($components as $component){
            $this->loadComponent($component);
        }

		//Set Helpers
		$this->viewBuilder()->setHelpers(['Form', 'Html', 'Time']);

        // Call the loadAllModels method to load necessary models.
        $this->loadAllModels();

	}

    /**
     * Loads all necessary models for the current controller.
     * This method is responsible for loading models required for the controller's functionality.
     * Author: Shankhpal Shende
     * Date: 28-03-2024
     */
    private function loadAllModels(): void {

        $this->DmiRoOffices = $this->AqcmsWrapper->customeLoadModel('DmiRoOffices');
        $this->DmiUserRoles = $this->AqcmsWrapper->customeLoadModel('DmiUserRoles');
    }

	public function authenticateUser($username){

		if ($username == null) {
			return 0;
		} else {

			//checking primary applicant id pattern ex.102/2016
			if (preg_match("/^[0-9]+\/[0-9]+$/", $username, $matches) == 1) {
				return 'DmiCustomers';
			//checking secondary applicant id pattern ex.102/1/PUN/006
			} elseif (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $username, $matches) == 1) {
				return 'DmiFirms';
			//checking chemist user id pattern ex. CHM/21/1003
			} elseif (preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $username, $matches) == 1) {
				return 'DmiChemistRegistrations';
			// checking the if Email User
			} elseif (preg_match("/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username,$matches)==1) {
				return 'DmiUsers';
			} else {
				return 1;
			}
		}
	}

/*
	public function getPending(){
		$this->loadModel('DmiFlowWiseTablesLists');
		$this->loadModel('DmiApplicationTypes');

		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all', array(
			'conditions' => array('application_type IN' => $this->Session->read('applTypeArray')),
			'order' => 'id ASC'
		))->toArray();
		$level_arr = array('level_1', 'level_2', 'level_3', 'level_4', 'level_4_ro', 'level_4_mo', 'pao');

		$finalRecords = array(); // Empty array to store final records
		$this->loadModel('DmiRejectedApplLogs');
		foreach ($flow_wise_tables as $eachflow) {
			//get application type
			$getApplType = $this->DmiApplicationTypes->find('all', array(
				'fields' => 'application_type',
				'conditions' => array('id IS' => $eachflow['application_type'])
			))->first();

			//flow wise appl tables
			$applPosTable = $eachflow['appl_current_pos'];
			$this->loadModel($applPosTable);

			$finalSubmitTable = $eachflow['application_form'];
			$this->loadModel($finalSubmitTable);

			$grantCertTable = $eachflow['grant_pdf'];
			$this->loadModel($grantCertTable);

			$allocationTable = $eachflow['allocation'];
			$this->loadModel($allocationTable);


			$appl_c = $this->$applPosTable->find('all')->toArray();

			foreach ($appl_c as $record) {

				$currentLevel = $record->current_level;
				$currentUserEmail = $record->current_user_email_id;


				if ($currentLevel !== 'applicant' && $currentLevel !== 'pao' && $currentLevel !== 'level_4') {
					// Query dmi_allocations table based on current_level column

					$allocationRecord = $this->$allocationTable->find('all')->where([$currentLevel . ' !=' => $currentUserEmail,'customer_id IS' => $record->customer_id])->first();



					if ($allocationRecord) {

						//check entry in rejected/junked table
						$checkIfRejected = $this->DmiRejectedApplLogs->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$allocationRecord['customer_id'],'appl_type IS'=>$eachflow['application_type'])))->first();

						if(empty($checkIfRejected)){
							//check if appl submission and granted
							$checkLastStatus = $this->$finalSubmitTable->find('all',array('conditions'=>array('customer_id IS'=>$allocationRecord['customer_id']),'order'=>'id desc'))->first();
							if(!empty($checkLastStatus) && (($checkLastStatus['status']=='approved' && ($checkLastStatus['current_level']=='level_3' || $checkLastStatus['current_level']=='level_4')) || ($eachflow['application_type'] == 4 && $checkLastStatus['status']=='approved' &&
								($checkLastStatus['current_level']=='level_3' || $checkLastStatus['current_level']=='level_1')))){
								//nothing
							}else{

								$finalRecords[] = [
									'id'=>$record->id,
									'customer_id' => $allocationRecord['customer_id'],
									'current_lvl_in_curr_pos' => $currentLevel,
									'email_in_current_tbl' => base64_decode($currentUserEmail),
									'email_in_alloc_tbl' => base64_decode($allocationRecord[$currentLevel]),
									'date_in_curr_pos_tbl' =>$record->modified,
									'date_in_alloc_tbl'=>$allocationRecord['modified']
								];

							}
						}
					}
				}
			}
		}

		foreach ($finalRecords as $value) {
			echo "ID ". $value['customer_id']."  |  " ."Current Position Date". $value['date_in_curr_pos_tbl'] ."  |  " ."Allocation Table Date". $value['date_in_alloc_tbl']  ."  |  " ."curr_email::: ". $value['email_in_current_tbl']   ."  |  " ."alloc_email::: ". $value['email_in_alloc_tbl']; echo "<br>";
		}

		exit;
	}
*/


	//Login Customer function start
    public function login() {


		//for checking the appl with payment confirmed but not entry in grant to resolved issue.
		/*$mdate = date('2023-01-10 00:00:00');
		$this->loadModel('DmiRenewalApplicantPaymentDetails');
		$getLastPayConfirmationDate = $this->DmiRenewalApplicantPaymentDetails->find('all',array('fields'=>array('id','created','payment_confirmation','customer_id'),'conditions'=>array('date(created) > '=>$mdate,'payment_confirmation'=>'confirmed'),'order'=>'id asc'))->toArray();
		$checkentry = array();
		foreach($getLastPayConfirmationDate as $each){
			$this->loadModel('DmiGrantCertificatesPdfs');
			$getGrantRecordAfterConf = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>array('id','customer_id','created'),'conditions'=>array('customer_id IS'=>$each['customer_id']),'order'=>'id desc'))->first();

			if(strtotime(str_replace('/','-',$getGrantRecordAfterConf['created'])) > strtotime(str_replace('/','-',$each['created']))){

			}else{
				$checkentry[] = $each['customer_id'];
			}
		}
		print_r($checkentry);exit;*/


		//print_r($this->Customfunctions->getCertificateValidUptoDate('191/3/PUN/022','19/07/2021 00:00:00')); exit;
        //Set the Layout
        $this->viewBuilder()->setLayout('devs_layout');

        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';


		if ($this->request->is('post')) {

			$postData = $this->request->getData();
			$passcode = $postData['passcode'];

			$validUser = $this->authenticateUser($postData['username']);

			if($validUser == 0){
				$message = 'Enter the Customer ID / User Email or Chemist ID".';
				$message_theme = 'failed';
				$redirect_to = 'login';

			} elseif ($validUser == 1) {

				$message = 'Customer ID / User Email or Chemist ID is not valid';
				$message_theme = 'failed';
				$redirect_to = 'login';

			} else {

				$loginpro = $this->proceedLogin($postData['username'],$validUser,$passcode);

				if($loginpro==0){

					$message = 'Sorry... It seems you are LMIS module user. Please use "LMIS Login".';
					$message_theme = 'failed';
					$redirect_to = 'login';

				} elseif ($loginpro == 2){

					$message = 'Wrong Passcode Entered.';
					$message_theme = 'failed';
					$redirect_to = 'login';

				}
			}
		}

        // set variables to show popup messages from view file
        $this->set('message', $message);
        $this->set('message_theme', $message_theme);
        $this->set('redirect_to', $redirect_to);
    }



	// Customer Proceed Login
	// Description : this function contains the login logic for Authorized  user & on for multiple logged in check security updates for customers
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : Akash Thakre (u) (m)
	// DATE : 25-06-2021

	public function proceedLogin($username,$table,$passcode) {

		$this->Session->destroy();
		Session_start();
		$defPasscode = '123';
		if ($passcode == $defPasscode) {

			$this->Session->write('username',$username);
			$this->Session->write('last_login_time_value',time());
			$this->Session->write('ip_address',$this->request->clientIp());

            $table = $this->AqcmsWrapper->customeLoadModel($table);

			if ($table == 'DmiCustomers') {

				$customer_data_query = $this->$table->find('all', array('conditions'=> array('customer_id IS' => $username)))->first();
				$customer_f_name = $customer_data_query['f_name'];
				$this->Session->write('f_name',$customer_f_name);
				$customer_l_name = $customer_data_query['l_name'];
				$this->Session->write('l_name',$customer_l_name);
				$this->redirect(array('controller'=>'customers', 'action'=>'primary_home'));

			} elseif ($table == 'DmiFirms') {

				$customer_data_query = $this->$table->find('all', array('conditions'=> array('customer_id IS' => $username)))->first();
				$firm_name = $customer_data_query['firm_name'];
				$this->Session->write('firm_name',$firm_name);
				$this->redirect(array('controller'=>'customers', 'action'=>'secondary_home'));

			} elseif ($table == 'DmiChemistRegistrations') {

				$customer_data_query = $this->$table->find('all', array('conditions'=> array('chemist_id IS' => $username)))->first();
				$customer_f_name = $customer_data_query['chemist_fname'];
				$this->Session->write('f_name',$customer_f_name);
				$customer_l_name = $customer_data_query['chemist_lname'];
				$this->Session->write('l_name',$customer_l_name);
				$this->redirect(array('controller'=>'chemist', 'action'=>'home'));

			} elseif ($table == 'DmiUsers') {

				$user_data_query = $this->$table->find('all', array('conditions'=> array('email IS' => base64_encode($username))))->first();


				$user_roles = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>base64_encode($username))))->first();


				if ($user_data_query['division'] == 'DMI' || $user_data_query['division'] == 'BOTH') {

					$userProceedLogin = 'yes';

				} elseif ($user_data_query['division'] == 'LMIS' && !empty($user_roles)) {

					if ($user_roles['set_roles']=='yes') {

						$userProceedLogin = 'yes';
					}
				}

				if ($userProceedLogin == 'yes') {



					$customer_data_query = $this->$table->find('all', array('conditions'=> array('email IS' => base64_encode($username))))->first();
					$f_name = $customer_data_query['f_name'];
					$l_name = $customer_data_query['l_name'];
					$once_card_no = '000000000000';
					$division = $user_data_query['division'];
					$role = $user_data_query['role'];

					$this->Session->write('username',base64_encode($username));
					$this->Session->write('division',$division);
					$this->Session->write('f_name',$f_name);
					$this->Session->write('l_name',$l_name);
					$this->Session->write('role',$role);
					$this->redirect('/dashboard/home');
				} else {
					return 0;
				}

			}

			//$_SESSION['browser_session_d'] = 123;
			$this->Session->write('profile_pic',$customer_data_query['profile_pic']);
			$once_card_no = null;
			$this->Session->write('once_card_no',$once_card_no);
			$this->Session->write('userloggedin','yes');

		}else{
			return 2;
		}
	}



	// Logout
	// Description : This common logout function is created for the user,chemist and customer customer
	// @Author : Amol Choudhari
	// #Contributer : Akash Thakre
	// Date : 19-04-2022

	public function logout() {

		$this->Session->destroy();
		$this->redirect(array('controller'=>'devs', 'action'=>'login'));
	}




}
?>
