<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;

class CommonController extends AppController {

	public $name = 'Common';

    private $DmiManuals;
    private $DmiUserActionLogs;
    private $DmiFirms;
	public function initialize(): void {

		parent::initialize();
		//Load Components
        // added by shankhpal on 27-03-2024
        $components = [
            'Customfunctions',
            'Createcaptcha',
            'Authentication',
            'Mastertablecontent',
            'Randomfunctions',
            'AqcmsWrapper',
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

        $this->DmiManuals = $this->AqcmsWrapper->customeLoadModel('DmiManuals');
        $this->DmiUserActionLogs = $this->AqcmsWrapper->customeLoadModel('DmiUserActionLogs');
        $this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
    }

	//Before Filter
	public function beforeFilter($event) {

		parent::beforeFilter($event);

		$username = $this->Session->read('username');
		$userType = $this->Customfunctions->getUserType($username);
		$this->set('userType', $userType);

		$customer_last_login = $this->Customfunctions->customerLastLogin();
		$this->set('customer_last_login', $customer_last_login);

		//Show button on Side menu
		if ($userType !== 'User') {
			$this->Beforepageload->showButtonOnSecondaryHome();

			//created and called function to check applicant is valid for renewal or not
			$show_renewal_btn = $this->Customfunctions->checkApplicantValidForRenewal($this->Session->read('username'));
			$this->set('show_renewal_btn', $show_renewal_btn);


			$is_already_granted = null;
			$get_is_already_granted = $this->DmiFirms->find('all', array('fields' => 'is_already_granted', 'conditions' => array('customer_id IS' => $this->Session->read('username'))))->first();

			if (!empty($get_is_already_granted)) {
				$is_already_granted = $get_is_already_granted['is_already_granted'];
			}

			$this->set('is_already_granted', $is_already_granted);
		}

	}



	// Authenticate User
	// Description : This is method will set the Layout for the specific user type , if user is not authorized it will show the message.
	// #Contributer : Akash Thakre
	// DATE : 19-04-2022

	public function authenticateUser(){

		if ($this->Session->read('username') == null) {
			$this->customAlertPage("Sorry You are not authorized to view this page..");
		} else {

			//checking primary applicant id pattern ex.102/2016
			if (preg_match("/^[0-9]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1) {

				$this->viewBuilder()->setLayout('corporate_customer');

			//checking secondary applicant id pattern ex.102/1/PUN/006
			} elseif (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1) {

				$this->viewBuilder()->setLayout('secondary_customer');

			//checking chemist user id pattern ex. CHM/21/1003
			} elseif (preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1) {

				$this->viewBuilder()->setLayout('chemist_home_layout');

			// checking the if Email User
			} elseif ($this->isBase64Encoded($this->Session->read('username'))==true) {

				$this->viewBuilder()->setLayout('admin_dashboard');

			} else {
				$this->customAlertPage("Sorry You are not authorized to view this page..");
			}
		}
	}



	// Change Password
	// Description : This is the common function to change the password for all users. This has the common template.
	// #Contributer : Akash Thakre
	// DATE : 19-04-2022

	public function changePassword() {

		// set variables to show popup messages from view file
		$this->authenticateUser();

		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$this->set('current_menu','change_password');

		if ($this->request->is('post')) {

			#Random Salt
			$randsalt = $this->Session->read('randSalt');

			#username
			$username = $this->Session->read('username');

			#get user type
			$user_type = $this->Customfunctions->getUserType($username);

			if (empty($user_type)) {

				$message = 'Sorry...User Id entered is not valid';
				$message_theme = 'failed';
				$redirect_to = 'change_password';

			} else {

				$oldpassdata = $this->request->getData('old_password');
				$newpassdata = $this->request->getData('new_password');
				$confpassdata = $this->request->getData('confirm_password');

				#get table
				$getTable = $this->getSpecificTable();
				$table = $getTable['password_table'];

				#calling library function
				$change_pass_result = $this->Authentication->changePasswordLib($table, $username, $oldpassdata, $newpassdata, $confpassdata, $randsalt);

				if ($change_pass_result == 1) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'Sorry...username not matched to save new password';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 2) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'Sorry...Please Check old password again';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 3) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'Sorry...please Check. Confirm password not matched';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 4) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'This password matched with your last three passwords, Please enter different password';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} else {

					$this->Customfunctions->saveActionPoint('Change Password','Success'); #save action log
					$message = 'Password Changed Successfully';
					$message_theme = 'success';
					$redirect_to = 'change_password';
				}
			}

			// set variables to show popup messages from view file
			$this->set('message_theme', $message_theme);
			$this->set('message', $message);
			$this->set('redirect_to', $redirect_to);

			if ($message != null) {
				$this->render('/element/message_boxes');
			}
		}
	}




	// USER ACTION HISTORY
	// Description : This is the common function to display the user action logs for all users. This has the common template.
	// @AUTHOR : PRAVIN BHAKARE
	// #Contributer : Akash Thakre
	// DATE : 19-04-2022

	public function userActionHistory() {

		$this->authenticateUser();
		$username = $this->Session->read('username');
		$this->set('current_menu','user_action_history');

		#get the specific action log table
		$getTable = $this->getSpecificTable();
		$actionLogTable = $getTable['action_log_table'];

		#load the model
        $actionLogTable = $this->AqcmsWrapper->customeLoadModel($actionLogTable);

		$get_user_actions = $this->$actionLogTable->getActionLogs();

		//this is if the result is not found on the new tables created for saving the action log , then look into the old Action Table
		if (empty($get_user_actions)) {

			$get_user_actions = $this->DmiUserActionLogs->find('all', array('conditions' => array('user_id IS' => $username,'action_perform IS NOT NULL'),'order' => array('id desc'), 'limit' => '100'))->toArray();
		}

		$this->set('get_user_actions', $get_user_actions);

	}





	// User Logs
	// Description : This is the common function to display the user logs for all users. This has the common template.
	// @Author : Amol Choudhari
	// #Contributer : Akash Thakre
	// Date : 19-04-2022

	public function currentUserLogs() {

		$this->authenticateUser();
		$this->set('current_menu','current_user_logs');

		$username = $this->getRequest()->getSession()->read('username');

		#check the user type
		$userType = $this->Customfunctions->getUserType($username);

		#get the specific log table
		$getTable = $this->getSpecificTable();
		$logsTable = $getTable['log_table'];

		#load the model
        $logsTable = $this->AqcmsWrapper->customeLoadModel($logsTable);

		#condition for user
		if ($userType == 'User') {
			$condition = array('email_id IS'=>$this->Session->read('username'));
		} else {
			$condition = array('customer_id IS'=>$this->Session->read('username'));
		}

		$currentLogs = $this-> $logsTable->find('all', array('conditions'=> $condition,'order' => 'id DESC'))->toArray();

		//to hide current session logout time.
		$currentLogs[0]['time_out'] = null;
		$this->set('currentLogs',$currentLogs);
	}



	// Logout
	// Description : This common logout function is created for the user,chemist and customer customer
	// @Author : Amol Choudhari
	// #Contributer : Akash Thakre
	// Date : 19-04-2022

	public function logout() {

		$this->authenticateUser();
		$username = $this->getRequest()->getSession()->read('username');

		if (!empty($username)) {

			#check the user type
			$userType = $this->Customfunctions->getUserType($username);

			#get the specific log table
			$getTable = $this->getSpecificTable();
			$logsTable = $getTable['log_table'];

			#load the model
            $logsTable = $this->AqcmsWrapper->customeLoadModel($logsTable);

			if ($userType == 'User') {
				$condition = array('email_id IS'=>$username);
			} else {
				$condition = array('customer_id IS'=>$username);
			}

			$list_id = $this->$logsTable->find('list', array('valueField' => 'id', 'conditions' => $condition))->toList();

			if (!empty($list_id)) {

				$fetch_last_id_query = $this->$logsTable->find('all', array('fields' => 'id', 'conditions' => array('id' => max($list_id), 'remark' => 'Success')))->first();
				$fetch_last_id = $fetch_last_id_query['id'];

				$UserLogsEntity = $this->$logsTable->newEntity(array('id' => $fetch_last_id,'time_out' => date('H:i:s')));

				$this->$logsTable->save($UserLogsEntity);
				$this->Authentication->browserLoginStatus($username,null);
				$this->Session->destroy();
				$this->redirect('/');

			} else {
				$this->customAlertPage("Sorry You are not authorized to view this page..");
			}

		} else {
			$this->redirect('/');
		}
	}




	//Get Specific Table
	//Description: Returns the table needed for all the methods those are common flow.
	//@Author : Akash Thakre
	//Date : 14-09-2022

	public function getSpecificTable(){

		//get user type
		$userType = $this->Customfunctions->getUserType($this->Session->read('username'));

		if ($userType == 'Primary') {

			$log_table = 'DmiCustomerLogs';					#user log table
			$action_log_table = 'DmiCustomerActionLogs';	#action log table
			$password_table = 'DmiCustomers';				#password saved table

		} elseif ($userType == 'Chemist') {

			$log_table = 'DmiChemistLogs';					#user log table
			$action_log_table = 'DmiChemistActionLogs';		#action log table
			$password_table = 'DmiChemistRegistrations';	#password saved table

		} elseif ($userType == 'Secondary') {

			$log_table = 'DmiCustomerLogs';					#user log table
			$action_log_table = 'DmiFirmActionLogs'; 		#action log table
			$password_table = 'DmiFirms';					#password saved table

		} elseif ($userType == 'User') {

			$log_table = 'DmiUserLogs';						#user log table
			$action_log_table = 'DmiUserActionLogs'; 		#action log table
			$password_table = 'DmiUsers';					#password saved table
		}

		return array('log_table' => $log_table, 'action_log_table' => $action_log_table,'password_table' => $password_table);
	}



    //Is Base 64 Encoded
	//Description: Returns the true or false based on the email encoded or not.
	//@Author : Amol Choudhari
	//Date : 14-09-2022

	public function isBase64Encoded($data){
		if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
		return TRUE;
		} else {
		return FALSE;
		}
	}

	// Calling logout on session expired
	// DESCRIPTION : ON USER'S SESSION EXPIRED, CALL THIS FUNCTION TO BYPASS THE 'ALREADY LOGGEDIN' MODAL ALERT ON THE TIME OF LOGIN
	// @AUTHOR : ANIKET GANVIR
	// DATE : 13-10-2022

	public function sessionExpiredLogout() {

		$this->autoRender = false;
		$username = $_POST['session_username'];

		$this->Session->write('username', $username);
		$this->authenticateUser();

		if (!empty($username)) {

			//check the user type
			$userType = $this->Customfunctions->getUserType($username);

			#get the specific log table
			$getTable = $this->getSpecificTable();
			$logsTable = $getTable['log_table'];
			#load the model
            $logsTable = $this->AqcmsWrapper->customeLoadModel($logsTable);

			if ($userType == 'User') {
				$condition = array('email_id IS'=>$username);
			} else {
				$condition = array('customer_id IS'=>$username);
			}

			$list_id = $this->$logsTable->find('list', array('valueField' => 'id', 'conditions' => $condition))->toList();

			if (!empty($list_id)) {

				$fetch_last_id_query = $this->$logsTable->find('all', array('fields' => 'id', 'conditions' => array('id' => max($list_id), 'remark' => 'Success')))->first();
				$fetch_last_id = $fetch_last_id_query['id'];

				$UserLogsEntity = $this->$logsTable->newEntity(array('id' => $fetch_last_id,'time_out' => date('H:i:s')));
				$this->$logsTable->save($UserLogsEntity);
				$this->Authentication->browserLoginStatus($username,null);
				$this->Session->destroy();
				echo 'success'; exit;

			} else {
				echo 'Unauthorized request'; exit;
			}

		} else {
			echo 'Invalid username'; exit;
		}

	}


	//This function is refactored and modified dynamically fetch the manuals form the database
	//rather than hard quoted : Akash Thakre [08-12-2023]
	public function allManuals(){



		$this->authenticateUser();
		$this->set('current_menu','menu_manual');

		$getUserManuals = $this->DmiManuals->find()->where(['delete_status IS NULL','for_user' => 'users'])->order('id ASC')->toArray();
		$getApplicantManuals = $this->DmiManuals->find()->where(['delete_status IS NULL','for_user' => 'applicant'])->order('id ASC')->toArray();

		$this->set(compact('getUserManuals','getApplicantManuals'));
	}


}
?>
