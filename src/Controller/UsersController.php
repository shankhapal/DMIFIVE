<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Controller\Component;
use Cake\Utility\Hash;
use Cake\ORM\Entity;
use Cake\View\ViewBuilder;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Type;

class UsersController extends AppController {

	private $DmiPaoDetails;
    private $DmiAdvPaymentDetails;
    private $DmiFirms;
    private $DmiAdvPaymentTransactions;
    private $DmiUserLogs;
    private $DmiRoOffices;
    private $UserRole;
    private $DmiUserHistoryLogs;
    private $DmiUserRoles;
    private $DmiUsers;
    public string $name = 'Users'; // Ensure $name is declared as a string added by shankhpal on 26-03-2024
	//Initialize the Methods
	public function initialize(): void {

		parent::initialize();

        // added by shankhpal on 27-03-2024
        $components = [
            'Createcaptcha',
            'Customfunctions',
            'Authentication',
            'AqcmsWrapper'
        ];

        foreach($components as $component){
            $this->loadComponent($component);
        }



		//Set Helpers
		$this->viewBuilder()->setHelpers(['Form', 'Html', 'Time']);
		//Set Sessions
		$this->Session = $this->getRequest()->getSession();

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


        //load model
		$this->DmiPaoDetails = $this->AqcmsWrapper->customeLoadModel('DmiPaoDetails');
		$this->DmiAdvPaymentDetails = $this->AqcmsWrapper->customeLoadModel('DmiAdvPaymentDetails');
		$this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
		$this->DmiAdvPaymentTransactions = $this->AqcmsWrapper->customeLoadModel('DmiAdvPaymentTransactions');
        $this->DmiUserLogs = $this->AqcmsWrapper->customeLoadModel('DmiUserLogs');
        $this->DmiRoOffices = $this->AqcmsWrapper->customeLoadModel('DmiRoOffices');
        $this->UserRole = $this->AqcmsWrapper->customeLoadModel('UserRole');
        $this->DmiUserHistoryLogs = $this->AqcmsWrapper->customeLoadModel('DmiUserHistoryLogs');
        $this->DmiUserRoles = $this->AqcmsWrapper->customeLoadModel('DmiUserRoles');
        $this->DmiUsers = $this->AqcmsWrapper->customeLoadModel('DmiUsers');

    }
	// CREATE CAPTCHA
	public function createCaptcha() {
		$this->autoRender = false;
		$this->Createcaptcha->createCaptcha();
	}



	// REFRESH CAPTCHA CODE
	public function refreshCaptchaCode() {
		$this->autoRender = false;
		$this->Createcaptcha->refreshCaptchaCode();
		exit;
	}


	// BEFORE FILTER
	public function beforeFilter($event) {
		parent::beforeFilter($event);
	}


	// HOME
	public function home() {

		if ($this->request->getSession()->read('username') != null) {
			//Set the Layout
			$this->viewBuilder()->setLayout('admin_dashboard');
			//Set the session
			$username = $this->request->getSession()->read('username');
			$this->set('username', $username);
		} else {
			echo "Sorry.. No direct access";
			exit;
		}
	}


	// LOGIN USER
	public function loginUser() {


		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';
		$already_loggedin_msg = 'no';

		$this->viewBuilder()->setLayout('form_layout');

		if ($this->request->is('post')) {

			//check login lockout status, applied on 24-04-2018 by Amol
			$lockout_status = $this->Customfunctions->checkLoginLockout('DmiUserLogs', base64_encode($this->request->getData('email')));//for email encoding

			if ($lockout_status == 'yes') {

				$message = 'Sorry... Your account is disabled for today, on account of 3 login failure.';
				$message_theme = 'failed';
				$redirect_to = $this->getRequest()->getAttribute('webroot');

			} else {

				$randsalt = $this->getRequest()->getSession()->read('randSalt');
				$captchacode1 = $this->getRequest()->getSession()->read('code');
				$logindata = $this->request->getData();

				$table = 'DmiUsers';
				$username = $this->request->getData('email');
				$password = $this->request->getData('password');
				$captcharequest = $this->request->getData('captcha');


				$login_result = $this->Authentication->userLoginLib($table, $username, $password, $randsalt); // calling login library function

				// show user login failed messages (by pravin 27/05/2017)
				if ($login_result == 0) {

					$message = 'Sorry... It seems you are LMIS module user. Please use "LMIS Login".';
					$message_theme = 'failed';
					$redirect_to = $this->getRequest()->getAttribute('webroot');

				} elseif ($login_result == 1) {

					//this custom functionn is called on 08-04-2021, to show remaining login attempts
					$remng_attempts_msg = $this->showRemainingLoginAttempts('DmiUserLogs', base64_encode($this->request->getData('email')));//for email encoding
					$message = 'Username or password do not match. <br>' . $remng_attempts_msg;
					$message_theme = 'failed';
					$redirect_to = 'login_user';

				} elseif ($login_result == 2) {

					$remng_attempts_msg = $this->showRemainingLoginAttempts('DmiUserLogs', base64_encode($this->request->getData('email')));//for email encoding
					$message = 'Username or password do not match. <br>' . $remng_attempts_msg;
					$message_theme = 'failed';
					$redirect_to = 'login_user';

				} elseif ($login_result == 3) {

					$captcha_error_msg = 'Sorry... Wrong Code Entered';
					$message_theme = 'failed';
					$this->set('captcha_error_msg', $captcha_error_msg);
					$this->set('already_loggedin_msg',$already_loggedin_msg);
					return null;
					exit;

				} elseif ($login_result == 4) {

					//get applicant email id and apply masking before showing in message by Amol on 25-02-2021
					$email_id = $this->Customfunctions->getMaskedValue($username, 'email');
					$message = 'Your password has been expired, The link to reset password is sent on email id ' . $email_id;
					$redirect_to = 'login_user';

					//created/updated/added on 25-06-2021 for multiple logged in check security updates, by Amol
				} elseif ($login_result == 5) {

					$already_loggedin_msg = 'yes';
				}
			}
		}

		// set variables to show popup messages from view file
		$this->set('already_loggedin_msg',$already_loggedin_msg);
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('redirect_to', $redirect_to);

	}



	// FORGOT PASSWORD
	public function forgotPassword() {

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		//Set the Layout
		$this->viewBuilder()->setLayout('form_layout');

		if ($this->request->is('post')) {

			//captcha check
			if ($this->request->getData('captcha') != "" && $this->request->getSession()->read('code') == $this->request->getData('captcha')) {

				// $table = 'DmiUsers';
                $table = $this->AqcmsWrapper->loadModel('DmiUsers'); // added by shankhpal on 26-03-2024
				$emailforrecovery = htmlentities($this->request->getData('email'), ENT_QUOTES);
				$mobileno = htmlentities($this->request->getData('mobileno'), ENT_QUOTES);

				//check if Customer ID & Email Match in record. //for email encoding
				$check_valid_record = $table->find('all', array('conditions' => array('email' => base64_encode($emailforrecovery), 'phone' => base64_encode($mobileno))))->first();

				if (empty($check_valid_record)) {

					$message = 'Invalid mobile number or email id';
					$message_theme = 'failed';
					$redirect_to = 'forgot_password';

				} else {

					// calling forgot password library function
					$forgot_password_result = $this->Authentication->forgotPasswordLib($table, $emailforrecovery);//for email encoding

					if ($forgot_password_result == 1) {

						$message = 'Sorry... This email is not authorized';
						$message_theme = 'failed';
						$redirect_to = 'forgot_password';

					} elseif ($forgot_password_result == 2) {

						$message = 'Changed Password link Sent on ' . $this->Customfunctions->getMaskedValue($emailforrecovery, 'email');
						$message_theme = 'success';
						$redirect_to = 'forgot_password';
					}
				}

			} else {

				$message = 'Sorry...Wrong Captcha Code Entered';
				$message_theme = 'failed';
				$redirect_to = 'forgot_password';
			}
		}

		// set variables to show popup messages from view file
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('redirect_to', $redirect_to);

	}


	// RESET PASSWORD
	public function resetPassword() {

		//Set the Layout
		$this->viewBuilder()->setLayout('form_layout');
		//Load Models
		$this->AqcmsWrapper->loadModel('DmiUsers');
		$this->AqcmsWrapper->loadModel('DmiUsersResetpassKeys');

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		if (empty($_GET['$key']) || empty($_GET['$id'])) {

			$this->customAlertPage("Sorry You are not authorized to view this page..");
			exit;

		} else {

			$key_id = $_GET['$key'];
			$user_id = $this->Authentication->decrypt($_GET['$id']);
			$this->set('user_id', base64_decode($user_id));//for email encoding

			//call function to check valid key
			$valid_key_result = $this->DmiUsersResetpassKeys->checkValidKey($user_id, $key_id);

			if ($valid_key_result == 1) {

				$user_data = $this->DmiUsers->find('all', array('conditions' => array('email IS' => $user_id)))->first();
				$record_id = $user_data['id'];

				if (!empty($user_data)) {

					if ($this->request->is('post')) {

						$randsalt = $this->request->getSession()->read('randSalt');

						$captchacode1 = $this->request->getSession()->read('code');

						$postData = $this->request->getData();

						$table = 'DmiUsers';
						$username = $user_id;
						$newpassdata = $this->request->getData('new_password');
						$confpassdata = $this->request->getData('confirm_password');

						$reset_pass_result = $this->Authentication->resetPasswordLib($table, $username, $newpassdata, $randsalt,$postData); // calling reset password library function

						if ($reset_pass_result == 1) {

							$this->Customfunctions->saveActionPoint('Reset Password (Email Not Matched)','Failed',$user_id); #Action
							$email_id_not_matched_msg = 'Sorry...Email id not matched by id to save new password';
							$this->set('email_id_not_matched_msg', $email_id_not_matched_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 2) {

							//Added this call to save the user action log on 09-09-2022
							$this->Customfunctions->saveActionPoint('Reset Password (Incorrect Captcha)','Failed',$user_id); #Action
							$incorrect_captcha_msg = 'Incorrect Captcha code entered.';
							$this->set('incorrect_captcha_msg', $incorrect_captcha_msg);
							return null;
							exit;
						} elseif ($reset_pass_result == 3) {

							//Added this call to save the user action log on 09-09-2022
							$this->Customfunctions->saveActionPoint('Reset Password (Password Not Macthed)','Failed',$user_id); #Action
							$comfirm_pass_msg = 'Confirm password not matched';
							$this->set('comfirm_pass_msg', $comfirm_pass_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 4) {

							//Added this call to save the user action log on 09-09-2022
							$this->Customfunctions->saveActionPoint('Reset Password (Password is Same as Last)','Failed',$user_id); #Action
							// SHOW ERROR MESSAGE IF NEW PASSWORD FOUND UNDER LAST THREE PASSWORDS OF USER By Aniket Ganvir dated 16th NOV 2020
							$comfirm_pass_msg = 'This password matched with your last three passwords, Please enter different password';
							$this->set('comfirm_pass_msg', $comfirm_pass_msg);
							return null;
							exit;

						} else {

							//Added this call to save the user action log on 09-09-2022
							$this->Customfunctions->saveActionPoint('Reset Password','Success',$user_id); #Action
							//update link key table status to 1 for successfully
							$this->DmiUsersResetpassKeys->updateKeySuccess($user_id, $key_id);
							$message = 'Password Changed Successfully';
							$message_theme = 'success';
							$redirect_to = '../../users/login_user';
						}
					}
				}

			} elseif ($valid_key_result == 2) {

				$message = 'Sorry.. This link to Reset Password was Expired. Please proceed through "Forgot Password" again.';
				$message_theme = 'info';
				$redirect_to = '../forgot_password';
			}
		}

		// set variables to show popup messages from view file
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('redirect_to', $redirect_to);


	}


	// COMMON USER REDIRECT LOGIN
	// @AUTHOR - AMOL CHOUDHARI
	// @CONTRIBUTER - AKASH THAKRE
	public function commonUserRedirectLogin($user_id) {


		//Set the varibles blank for displaying the messages
		$message = '';
		$message_theme = '';
		$redirect_to = '';
		//Set the Layout
		$this->viewBuilder()->setLayout('form_layout');
		//Load Models
		$this->AqcmsWrapper->loadModel('DmiUsers');
		$this->AqcmsWrapper->loadModel('DmiUserLogs');

		//commented this condition on 23-07-2018 by Amol, conflicting session between DMI/LIMS redirection

		if (!empty($user_id)) {

			$get_user_email_id = $this->DmiUsers->find('all', array('conditions' => array('id IS' => $user_id)))->first();
			$user_email_id = $get_user_email_id['email'];

			if ($this->request->is('post')) {

				$randsalt = $this->request->getSession()->read('randSalt');
				$logindata = $this->request->getData();

				$table = 'DmiUsers';
				$username = $user_email_id;
				$password = $this->request->getData('password');
				$PassFromdb = $this->$table->find('all', array('fields' => 'password', 'conditions' => array('email IS' => $username)))->first();

				if ($PassFromdb != null && $PassFromdb != '') {

					$passarray = $PassFromdb['password'];
					$PassFromdbsalted = $randsalt . $passarray; //adding random salt value to password
					$Dbpasssaltedsha512 = hash('sha512', $PassFromdbsalted);// Encripting salted password to sha512

					// check password to db password
					if ($password == $Dbpasssaltedsha512) {
						// destroy old session data
						$this->Session->destroy();
						session_start();

						// Update status of browser login history, Done By Pravin Bhakare 12-11-2020
						//$this->Authentication->browserLoginStatus($username,'yes');
						$current_ip = $this->getRequest()->clientIp();
						if ($current_ip == '::1') {

							$current_ip = '127.0.0.1';
						}

						$DmiUserLogsEntity = $this->DmiUserLogs->newEntity(array(

							'email_id' => $username,
							'ip_address' => $current_ip,
							'date' => date('Y-m-d'),
							'time_in' => date('H:i:s'),
							'remark' => 'Success'
						));

						$this->DmiUserLogs->save($DmiUserLogsEntity);

						$user_data_query = $this->$table->find('all', array('conditions' => array('email IS' => $username)))->first();
						$f_name = $user_data_query['f_name'];
						$l_name = $user_data_query['l_name'];
						$once_card_no = $user_data_query['once_card_no'];
						$division = $user_data_query['division'];

						// taking user data in session variables
						$this->Session->write('username', $username);
						$this->Session->write('once_card_no', $once_card_no);
						$this->Session->write('division', $division);
						$this->Session->write('f_name', $f_name);
						$this->Session->write('l_name', $l_name);
						$this->Session->write('ip_address', $this->getRequest()->clientIp());
						$this->Session->write('profile_pic', $user_data_query['profile_pic']);
						$this->Session->write('userloggedin','yes');
						$this->redirect('/dashboard/home');

					} else {

						$current_ip = $this->getRequest()->clientIp();
						if ($current_ip == '::1') {

							$current_ip = '127.0.0.1';
						}

						$DmiUserLogsEntity = $this->DmiUserLogs->newEntity(array(

							'email_id' => $username,
							'ip_address' => $current_ip,
							'date' => date('Y-m-d'),
							'time_in' => date('H:i:s'),
							'remark' => 'Failed'
						));

						$this->DmiUserLogs->save($DmiUserLogsEntity);

						$this->Customfunctions->saveActionPoint('Redirection (Password Not Matched)','Failed'); #Action: Password Not Matched
						$this->set('return_error_msg','Sorry.. Password does not matched');
						return null;
					}

				} else {

 					$this->Customfunctions->saveActionPoint('Redirection','Failed'); #Action: Password Not Matched
					$this->set('return_error_msg','Sorry.. This username does not exist');
					return null;
				}
			}

		} else {
			echo "Sorry.. No direct access";
			exit;
		}

		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('return_error_msg',null);
		$this->set('redirect_to', $redirect_to);

	}


	// USER DIVISION TYPES
	// @ AUTHOR - PRAVIN BHAKARE
	public function userDivisionTypes() {

		$this->autoRender = false;

		$user_id = $_POST['user_id'];
		$user_division_value = $this->DmiUsers->find('all', array('fields' => 'division', 'conditions' => array('id IS' => $user_id)))->first();

		?><label style="float:right; font-weight:bold;margin-top: 8px;margin-right: 50px;" id="user_type_text">User Type
		:
		<?php if ($user_division_value['division'] == 'LMIS') {
				echo 'LIMS';
			} else {
			echo $user_division_value['division'];
		} ?>
		</label>
		<?php //above if condition is added on 02-08-2018 to show "LIMS" instead of "LMIS"

	}


	///====================================================| USER MANAGEMENT METHODS |========================================================///

	// AUTHENTICATE USER
	public function authenicateUser() {

		//check user role for access

		$user_access = $this->DmiUserRoles->find('all', array('conditions' => array('add_user' => 'yes', 'user_email_id IS' => $this->request->getSession()->read('username'))))->first();
		if (!empty($user_access)) {
			//proceed
		} else {
			$this->customAlertPage("Sorry You are not authorized to view this page..Sorry.. You don't have permission to view this page");
			exit;
		}

	}


	// USER PROFILE
	public function userProfile() {

		$this->viewBuilder()->setLayout('admin_dashboard');





		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		// Show the assigned users list (Done by Pravin 08-03-2018)
		$assigned_old_roles = $this->DmiUserRoles->find('all', array('conditions' => array('user_email_id IS' => $this->request->getSession()->read('username'))))->toArray();
		$this->set('assigned_old_roles', $assigned_old_roles);


		$user_data = $this->DmiUsers->find('all', array('conditions' => array('email IS' => $this->request->getSession()->read('username'))))->toArray();

		if (!empty($user_data)) {

			//get personal details masked by custom function to show in secure mode applied on 12-10-2017 by Amol
			$user_data[0]['phone'] = $this->Customfunctions->getMaskedValue(base64_decode($user_data[0]['phone']), 'mobile');
			$user_data[0]['email'] = $this->Customfunctions->getMaskedValue(base64_decode($user_data[0]['email']), 'email');
			$this->set('user_data', $user_data);

			if (null !== ($this->request->getData('ok'))) {
				$this->redirect('/dashboard/home');
			} elseif (null !== ($this->request->getData('update'))) {

				//applied condition to check all post data for !empty validation on server side on 21/10/2017 by Amol
				if (!empty($this->request->getData('f_name')) && !empty($this->request->getData('l_name')) && !empty($this->request->getData('email')) &&
					//commented on 15-06-2018 by Amol, no provision to store aadhar
					!empty($this->request->getData('phone')) /*&& !empty($this->request->getData('once_card_no'))*/)
				{

					if (!$this->Customfunctions->validateUniquePostData($this->request->getData('phone'), 'mobile') == 1) {
						$this->set('return_error_msg','Please enter proper Mobile no.');
						return null;

					}

					if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'), 'email') == 1) {
							$this->set('return_error_msg','Please enter proper Email id');
							return null;

					}

					//Html Encoding data before saving
					$htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
					$htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
					$htmlencodedphone = htmlentities($this->request->getData('phone'), ENT_QUOTES);  // as per change request added by shankhpal shende on 11/01/2023
					$htmlencodedlandline = htmlentities($this->request->getData('landline'), ENT_QUOTES);
					//commented on 15-06-2018 by Amol, no provision to store aadhar
					//$htmlencodedaadhar = htmlentities($this->request->getData('once_card_no'), ENT_QUOTES);

					$fetch_user_id = $this->DmiUsers->find('all', array('fields' => 'id', 'conditions' => array('email IS' => $this->request->getSession()->read('username'))))->first();

					$user_id = $fetch_user_id['id'];

					//below query & conditions added on 12-10-2017 by Amol To check if mobile,aadhar post in proper format, if not then save old value itself from DB
					$user_data = $this->DmiUsers->find('all', array('conditions' => array('email IS' => $this->request->getSession()->read('username'))))->first();

					if (preg_match("/^[X-X]{6}[0-9]{4}$/i", $this->request->getData('phone'), $matches) == 1) {

						$htmlencodedphone = $user_data['phone'];
					}else{
						$htmlencodedphone = base64_encode($htmlencodedphone);
					}

					//added on 06-05-2021 for profile pic
					if ($this->request->getData('profile_pic')->getClientFilename() != null) {

						$attachment = $this->request->getData('profile_pic');
						$file_name = $attachment->getClientFilename();
						$file_size = $attachment->getSize();
						$file_type = $attachment->getClientMediaType();
						$file_local_path = $attachment->getStream()->getMetadata('uri');

						$profile_pic = $this->Customfunctions->fileUploadLib($file_name, $file_size, $file_type, $file_local_path); // calling file uploading function

					} else {
						$profile_pic = $user_data['profile_pic'];
					}

					$DmiusersEntity = $this->DmiUsers->newEntity(array(

						'id' => $user_id,
						'f_name' => $htmlencodedfname,
						'l_name' => $htmlencodedlname,
						'phone'=>$htmlencodedphone,  // added by shankhpal shende on 11/01/2023
						'landline' => base64_encode($htmlencodedlandline),
						//'once_card_no'=>$encrypted_aadhar, //commented on 15-06-2018 by Amol, no provision to store aadhar
						'profile_pic' => $profile_pic //added on 06-05-2021 for profile pic
					));

					if ($this->DmiUsers->save($DmiusersEntity)) {

						//Save the user profile update logs history (Done by Pravin 13/02/2018)
						$DmiUserHistoryLogsEntity = $this->DmiUserHistoryLogs->newEntity(array(

							'f_name' => $htmlencodedfname,
							'l_name' => $htmlencodedlname,
							'email' => $user_data['email'],
							'phone' => $htmlencodedphone,
							'landline' => base64_encode($htmlencodedlandline),
							'division' => $user_data['division'],
							'role' => $user_data['role'],
							'password' => $user_data['password'],
							'created_by_user' => $user_data['created_by_user'],
							'posted_ro_office' => $user_data['posted_ro_office'],
							'profile_pic' => $profile_pic,
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s'),
							'done_by'=>$this->request->getSession()->read('username')
						));

						$this->DmiUserHistoryLogs->save($DmiUserHistoryLogsEntity);

						$this->Session->write('f_name', $htmlencodedfname);
						$this->Session->write('l_name', $htmlencodedlname);

						//Added this call to save the user action log on 09-09-2022
						$this->Customfunctions->saveActionPoint('Profile Updated','Success');

						$message = 'Profile data updated successfully';
						$message_theme = 'success';
						$redirect_to = 'user_profile';

					} else {

						//Added this call to save the user action log on 09-09-2022
						$this->Customfunctions->saveActionPoint('Profile Updated','Failed');

						$message = 'Sorry...Please check your fields again';
						$message_theme = 'failed';
						$redirect_to = 'user_profile';
					}

				} else {

					$this->set('return_error_msg','Please check some fields are not entered');
					return null;

				}
			}

		} else {
			echo "Sorry.. You don't have permission to view this page";
			exit();
		}

		// set variables to show popup messages from view file
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('return_error_msg',null);
		$this->set('redirect_to', $redirect_to);

	}


	// ALL USERS
	public function allUsers() {

		//authenticate user
		$this->authenicateUser();
		$this->viewBuilder()->setLayout('admin_dashboard');

		//load Model
		$this->AqcmsWrapper->loadModel('DmiUsers');
		$this->AqcmsWrapper->loadModel('DmiRoOffices');

		$posted_ro_office = array();
		$all_users = array();

		$all_users = $this->DmiUsers->find('all', array('order' => array('f_name' => 'asc'), 'conditions' => array('created_by_user IS' => $this->request->getSession()->read('username'))))->toArray();

		if (!empty($all_users)) {
			$i = 0;
			foreach ($all_users as $each_user) {
				$get_ro_office_id = $each_user['posted_ro_office'];

				if (!empty($get_ro_office_id)) {
					$find_ro_office = $this->DmiRoOffices->find('all', array('conditions' => array('id IS' => $get_ro_office_id)))->first();
					$posted_ro_office[$i] = $find_ro_office['ro_office'];
				}
				$i = $i + 1;
			}
		}

		$this->set('all_users', $all_users);
		$this->set('posted_ro_office', $posted_ro_office);

	}



	// ADD USER
	public function addUser() {

		//authenticate user
		$this->authenicateUser();
		$this->viewBuilder()->setLayout('admin_dashboard');



		//for list of RO offices
		$office_posted = $this->DmiRoOffices->find('list', array('keyField' => 'id', 'valueField' => 'ro_office', 'conditions' => array('office_type IN' => array('RO', 'SO'), 'OR' => array('delete_status IS NULL', 'delete_status' => 'no')), 'order' => 'ro_office'))->toArray();
		$this->set('office_posted', $office_posted);

		//added on 27-07-2018 by Amol to get list of RAL offices
		$ral_office_posted = $this->DmiRoOffices->find('list', array('keyField' => 'id', 'valueField' => 'ro_office', 'conditions' => array('office_type' => 'RAL', 'OR' => array('delete_status IS NULL', 'delete_status' => 'no')), 'order' => 'ro_office'))->toArray();
		$this->set('ral_office_posted', $ral_office_posted);

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		//added on 15-07-2017 by Amol to get & added LMIS user roles
		$get_lmis_roles = $this->UserRole->find('list', array('keyField' => 'role_name', 'valueField' => 'role_name'))->toArray();
		$this->set('get_lmis_roles', $get_lmis_roles);

		if ($this->request->is('post')) {

			//applied condition to check all post data for !empty validation on server side on 21/10/2017 by Amol
			if (!empty($this->request->getData('f_name')) && !empty($this->request->getData('l_name')) && !empty($this->request->getData('email')) &&
				!empty($this->request->getData('phone')) && !empty($this->request->getData('profile_pic')) /* && !empty($this->request->getData('office_posted')) */
				//commented on 15-06-2018 by Amol, no provision to store aadhar
				/* && !empty($this->request->getData('once_card_no')) && $this->request->getData('aadhar_auth_check')==1*/)
			{

				if (!$this->Customfunctions->validateUniquePostData($this->request->getData('phone'), 'mobile') == 1) {
					$this->set('return_error_msg','Please enter proper mobile no.');
					return null;

				}

				if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'), 'email') == 1) {
					$this->set('return_error_msg','Please enter proper Email id');
					return null;

				}

				$usersData = $this->request->getData();

				//changed and added on the 21-02-2022
				$Checkemailexist = $this->DmiUsers->find('all', array('fields' => 'email', 'conditions' => array('email IS' => base64_encode($usersData['email']))))->first(); //for email encoding

				$checkMobileExist = $this->DmiUsers->find('all', array('fields' => 'phone', 'conditions' => array('phone IS' => base64_encode($usersData['phone']))))->first();

				if ($Checkemailexist == null) {
					//check if mobile number is already or not
					if ($checkMobileExist == null) {

						//Html Encoding data before saving
						$htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
						$htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
						$htmlencodedemail = htmlentities($this->request->getData('email'), ENT_QUOTES);
						$htmlencodedphone = htmlentities($this->request->getData('phone'), ENT_QUOTES);
						$htmlencodedlandline = htmlentities($this->request->getData('landline'), ENT_QUOTES);
						//	$htmlencodedaadhar = htmlentities($this->request->getData('once_card_no'), ENT_QUOTES); //commented on 15-06-2018 by Amol, no provision to store aadhar

						//check drop down
						//for RO Office
						$table = 'DmiRoOffices';
						$post_input_request = $this->request->getData('office_posted');
						$posted_ro_office = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);//calling library function

						//added on 12-05-2017 by Amol(for DMI,LMIS & BOTH)
						//checking radio buttons input
						$post_input_request = $this->request->getData('division');
						$division = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
						if ($division == null) {
							return null;
							exit;
						}

						//added on 15-07-2017 by Amol
						if ($division != 'DMI') {
							$lmis_role = $this->request->getData('role');
						} else {
							$lmis_role = null;
						}

						//check LIMS role to avoid double Incharge in same office
						//this check added on 03-12-2019 by Amol
						if ($lmis_role == 'RO/SO OIC' || $lmis_role == 'Inward Officer' || $lmis_role == 'Lab Incharge' || $lmis_role == 'RAL/CAL OIC') {

							//find similar user with same role_name
							$find_user = $this->DmiUsers->find('all', array('conditions' => array('posted_ro_office IS' => $posted_ro_office, 'role IS' => $lmis_role)))->first();
							if (!empty($find_user)) {

								if ($lmis_role == 'RO/SO OIC') {
									$this->set('return_error_msg','Sorry, this office already have Incharge. There should be only one Incharge for one office.');

								} elseif ($lmis_role == 'Inward Officer') {
									$this->set('return_error_msg','Sorry, this Lab already have Inward Officer. There should be only one Inward Officer for one lab.');

								} elseif ($lmis_role == 'Lab Incharge') {
									$this->set('return_error_msg','Sorry, this Lab already have Lab Incharge. There should be only one Lab Incharge for one lab.');

								} elseif ($lmis_role == 'RAL/CAL OIC') {
									$this->set('return_error_msg','Sorry, this Lab already have Incharge. There should be only one Incharge for one lab.');
								}

								return null;

							}
						}

						//added on 06-05-2021 for profile pic
						if ($this->request->getData('profile_pic')->getClientFilename() != null) {

							$attachment = $this->request->getData('profile_pic');
							$file_name = $attachment->getClientFilename();
							$file_size = $attachment->getSize();
							$file_type = $attachment->getClientMediaType();
							$file_local_path = $attachment->getStream()->getMetadata('uri');

							$profile_pic = $this->Customfunctions->fileUploadLib($file_name, $file_size, $file_type, $file_local_path); // calling file uploading function

						} else {
							$profile_pic = '';
						}

						$htmlencodedemail = base64_encode($htmlencodedemail);//for email encoding

						$dmiUsersEntity = $this->DmiUsers->newEntity(array(

							'f_name' => $htmlencodedfname,
							'l_name' => $htmlencodedlname,
							'email' => $htmlencodedemail,
							'phone' => base64_encode($htmlencodedphone),
							'landline' => base64_encode($htmlencodedlandline),
							'division' => $this->request->getData('division'),//for DMI,LMIS & BOTH)
							'role' => $lmis_role,//to save lmis role
							'once_card_no' => '000000000000',//no provision to store aadhar
							'password' => '91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
						//	'password' => '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', //123
							'created_by_user' => $this->request->getSession()->read('username'),
							'posted_ro_office' => $posted_ro_office,
							'status' => 'active',
							'created' => date('Y-m-d H:i:s'),
							'modified' => date('Y-m-d H:i:s'),
							'profile_pic' => $profile_pic //added on 06-05-2021 for profile pic

						));

						if ($this->DmiUsers->save($dmiUsersEntity)) {

							//Save the user profile update logs history
							$DmiUserHistoryLogsEntity = $this->DmiUserHistoryLogs->newEntity(array(

								'f_name' => $htmlencodedfname,
								'l_name' => $htmlencodedlname,
								'email' => $htmlencodedemail,
								'phone' => base64_encode($htmlencodedphone),
								'landline' => base64_encode($htmlencodedlandline),
								'division' => $this->request->getData('division'),
								'role' => $lmis_role,
								'once_card_no' => '000000000000',//no provision to store aadhar
								'password' => '91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
							//	'password' => '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', //123
								'created_by_user' => $this->request->getSession()->read('username'),
								'posted_ro_office' => $posted_ro_office,
								'status' => 'active',
								'created' => date('Y-m-d H:i:s'),
								'modified' => date('Y-m-d H:i:s'),
								'profile_pic' => $profile_pic //added on 06-05-2021 for profile pic

							));

							$this->DmiUserHistoryLogs->save($DmiUserHistoryLogsEntity);

							//called function to send link for reset password on registered email
							//on 13-02-2018 by Amol
							$this->Authentication->forgotPasswordLib('DmiUsers', $htmlencodedemail);
							$user_registered = 'done';
							$this->set('user_registered', $user_registered);
							$this->set('htmlencodedemail', $htmlencodedemail);

							//This Added to show the details of registered users - Akash[27-12-2022]
							$userDetails = $this->DmiUsers->getDetailsByEmail($htmlencodedemail);
							$officeDetails = $this->DmiRoOffices->getOfficeDetailsById($userDetails['posted_ro_office']);
							$this->set(compact('userDetails','officeDetails'));

							//Added this call to save the user action log on 09-09-2022
							$this->Customfunctions->saveActionPoint('New User Added','Success');

						} else {

							$message = 'Sorry...Please check your fields again';
							$message_theme = 'failed';
							$redirect_to = 'add_user';
						}

					} else {

						//Added this call to save the user action log on 09-09-2022
						$this->Customfunctions->saveActionPoint('New User Add','Failed');
						$message = 'Sorry...This Mobile Number is already exists!! Please Try Again. ';
						$message_theme = 'failed';
						$redirect_to = 'add_user';
					}

				} else {

					//Added this call to save the user action log on 09-09-2022
					$this->Customfunctions->saveActionPoint('New User Add','Failed');
					$message = 'Sorry...This Email ID is already exists!! Please Try Again.';
					$message_theme = 'failed';
					$redirect_to = 'add_user';
				}

			} else {

				//Added this call to save the user action log on 09-09-2022
				$this->Customfunctions->saveActionPoint('New User Add','Failed');
				$this->set('return_error_msg','Please check some fields are not entered');
				return null;

			}
		}

		// set variables to show popup messages from view file
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('return_error_msg',null);
		$this->set('redirect_to', $redirect_to);

	}



	// FETCH USER ID
	public function fetchUserId($id) {

		$this->Session->write('user_table_id', $id);
		$this->redirect(array('controller' => 'users', 'action' => 'edit_user'));
	}


	// CHANGE STATUS USER ID
	public function changeStatusUserId($id) {

		$this->Session->write('user_table_id', $id);
		$this->redirect(array('controller' => 'users', 'action' => 'change_status_user'));
	}


	// EDIT USER
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (Migration & Updates)
	// DATE : 12-10-2017 (C), 21-02-2022 (U)

	public function editUser() {

		//authenticate user
		$this->authenicateUser();
		$this->viewBuilder()->setLayout('admin_dashboard');

		//load Models
		$this->AqcmsWrapper->loadModel('DmiRoOffices');
		$this->AqcmsWrapper->loadModel('UserRole');
		$this->AqcmsWrapper->loadModel('DmiUsers');
		$this->AqcmsWrapper->loadModel('DmiUserHistoryLogs');

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$username = $this->Session->read('username'); // added by shankhpal shende

		$user_table_id = $this->request->getSession()->read('user_table_id');

		$user_details = $this->DmiUsers->find('all', array('conditions' => array('id IS' => $user_table_id)))->first();

		//get personal details masked by custom function to show in secure mode
		//applied on 12-10-2017 by Amol

		$user_details['phone'] = $this->Customfunctions->getMaskedValue(base64_decode($user_details['phone']), 'mobile');
		$user_details['email'] = $this->Customfunctions->getMaskedValue(base64_decode($user_details['email']), 'email');
		$this->set('user_details', $user_details);

		//for list of RO offices //added 'office_type'=>'RO' condition on 27-07-2018
		$office_posted = $this->DmiRoOffices->find('list', array('keyField' => 'id', 'valueField' => 'ro_office', 'conditions' => array('office_type IN' => array('RO', 'SO'), 'OR' => array('delete_status IS NULL', 'delete_status' => 'no')), 'order' => 'ro_office'))->toArray();
		$this->set('office_posted', $office_posted);

		//added on 27-07-2018 by Amol to get list of RAL offices
		$ral_office_posted = $this->DmiRoOffices->find('list', array('keyField' => 'id', 'valueField' => 'ro_office', 'conditions' => array('office_type' => 'RAL', 'OR' => array('delete_status IS NULL', 'delete_status' => 'no')), 'order' => 'ro_office'))->toArray();
		$this->set('ral_office_posted', $ral_office_posted);



		//added on 15-07-2017 by Amol to get & added LMIS user roles
		$get_lmis_roles = $this->UserRole->find('list', array('keyField' => 'role_name', 'valueField' => 'role_name'))->toArray();
		$this->set('get_lmis_roles', $get_lmis_roles);

		//added on 15-07-2017 by Amol to get last selected role_name
		$get_selected_lmis_role = $this->UserRole->find('all', array('conditions' => array('role_name IS' => $user_details['role'])))->first();
		if (!empty($get_selected_lmis_role)) {
			$this->set('selected_lmis_role', $get_selected_lmis_role['role_name']);
		} else {
			$this->set('selected_lmis_role', null);
		}

		//get user office type from office table to decide user belongs to radio buttons
		//added on 29-04-2019 by Amol
		$get_user_office = $this->DmiRoOffices->find('all', array('conditions' => array('id IS' => $user_details['posted_ro_office'])))->first();
		$user_office_type = $get_user_office['office_type'];
		$this->set('user_office_type', $user_office_type);

		if ($this->request->is('post')) {

			//applied condition to check all post data for !empty validation on server side
			//on 21/10/2017 by Amol
			if (!empty($this->request->getData('f_name')) && !empty($this->request->getData('l_name')) && !empty($this->request->getData('email')) &&
				!empty($this->request->getData('phone')) /* && !empty($this->request->getData('landline')) && !empty($this->request->getData('office_posted')) */
				//commented on 15-06-2018 by Amol, no provision to store aadhar
				/*&& !empty($this->request->getData('once_card_no')) && $this->request->getData('aadhar_auth_check')==1*/)
			{

				if (!$this->Customfunctions->validateUniquePostData($this->request->getData('phone'), 'mobile') == 1) {
					$this->set('return_error_msg','Please enter proper Mobile no.');
					return null;
				}

				if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'), 'email') == 1) {
					$this->set('return_error_msg','Please enter proper Email id');
					return null;
				}


				$usersData = $this->request->getData();
				$Checkemailexist = $this->DmiUsers->find('all', array('fields' => 'email', 'conditions' => array('id !=' => $user_table_id, 'email' => $usersData['email'])))->first();

				if ($Checkemailexist == null) {

					//Html Encoding data before saving
					$htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
					$htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
					$htmlencodedemail = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));//for email encoding
					$htmlencodedphone = base64_encode(htmlentities($this->request->getData('phone'), ENT_QUOTES));
					$htmlencodedlandline = htmlentities($this->request->getData('landline'), ENT_QUOTES);

					//check drop down
					//for RO Office
					$table = 'DmiRoOffices';
					$post_input_request = $this->request->getData('office_posted');
					$posted_ro_office = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);//calling library function

					//(for DMI,LMIS & BOTH)
					//checking radio buttons input
					$post_input_request = $this->request->getData('division');
					$division = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if ($division == null) {
						return null;
					}

					if ($division != 'DMI') {
						$lmis_role = $this->request->getData('role');
					} else {
						$lmis_role = null;
					}

					//check LIMS role to avoid double Incharge in same office
					if ($lmis_role == 'RO/SO OIC' || $lmis_role == 'Inward Officer' || $lmis_role == 'Lab Incharge' || $lmis_role == 'RAL/CAL OIC') {

						//find similar user with same role_name #Modification -> Added the ID condition to check the same user is updating the profile - Akash[13-01-2023]
						$find_user = $this->DmiUsers->find('all', array('conditions' => array('posted_ro_office IS' => $posted_ro_office, 'role IS' => $lmis_role, 'status' => 'active','id !=' =>$user_table_id )))->first();

						if (!empty($find_user)) {

							if ($lmis_role == 'RO/SO OIC') {

								$this->set('return_error_msg','Sorry, this office already have Incharge. There should be only one Incharge for one office.');

							} elseif ($lmis_role == 'Inward Officer') {

								$this->set('return_error_msg','Sorry, this Lab already have Inward Officer. There should be only one Inward Officer for one lab.');

							} elseif ($lmis_role == 'Lab Incharge') {

								$this->set('return_error_msg','Sorry, this Lab already have Lab Incharge. There should be only one Lab Incharge for one lab.');

							} elseif ($lmis_role == 'RAL/CAL OIC') {

								$this->set('return_error_msg','Sorry, this Lab already have Incharge. There should be only one Incharge for one lab.');
							}

							return null;

						}
					}

					//below query & conditions added on 12-10-2017 by Amol
					//To check if mobile,aadhar & email post in proper format, if not then save old value itself from DB
					$user_data = $this->DmiUsers->find('all', array('conditions' => array('id IS' => $user_table_id)))->first();

					if (preg_match("/^[X-X]{6}[0-9]{4}$/i", $this->request->getData('phone'), $matches) == 1) {
						$htmlencodedphone = $user_data['phone'];
					}

					//added on 06-05-2021 for profile pic
					if ($this->request->getData('profile_pic')->getClientFilename() != null) {

						$attachment = $this->request->getData('profile_pic');
						$file_name = $attachment->getClientFilename();
						$file_size = $attachment->getSize();
						$file_type = $attachment->getClientMediaType();
						$file_local_path = $attachment->getStream()->getMetadata('uri');

						$profile_pic = $this->Customfunctions->fileUploadLib($file_name, $file_size, $file_type, $file_local_path); // calling file uploading function

					} else {
						$profile_pic = $user_data['profile_pic'];
					}

					//for email encoding
					$email_masked_value = $this->Customfunctions->getEmailMasked(base64_decode($user_data['email']));//called custom function
					if ($email_masked_value == $this->request->getData('email')) {
						$htmlencodedemail = $user_data['email'];
					}

					$DmiUsersEntity = $this->DmiUsers->newEntity(array(

						'id' => $user_table_id,
						'f_name' => $htmlencodedfname,
						'l_name' => $htmlencodedlname,
						//'email' => $htmlencodedemail,
						'phone' => $htmlencodedphone,
						'landline' => base64_encode($htmlencodedlandline),
						'division' => $this->request->getData('division'),//(for DMI,LMIS & BOTH)
						'role' => $lmis_role,
						//	'once_card_no'=>$encrypted_aadhar, //no provision to store aadhar
						//	'password'=>'ad8a5ee8aed3dc05353f0d0ab7ea6083',
						'created_by_user' => $this->request->getSession()->read('username'),
						'posted_ro_office' => $posted_ro_office,
						'profile_pic' => $profile_pic,
						'modified'=>date('Y-m-d H:i:s')
					));

					if ($this->DmiUsers->save($DmiUsersEntity)) {

						//Save the user profile update logs history
						$DmiUserHistoryLogsEntity = $this->DmiUserHistoryLogs->newEntity(array(

							'f_name' => $htmlencodedfname,
							'l_name' => $htmlencodedlname,
							'email' => $htmlencodedemail,
							'phone' => $htmlencodedphone,
							'landline' => base64_encode($htmlencodedlandline),
							'division' => $this->request->getData('division'),
							'role' => $lmis_role,
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s'),
							'created_by_user' => $this->request->getSession()->read('username'),
							'posted_ro_office' => $posted_ro_office,
							'profile_pic' => $profile_pic,
							'done_by'=>$username  // as per change request add new field by shankhpal shende on 12/01/2023
						));

						$this->DmiUserHistoryLogs->save($DmiUserHistoryLogsEntity);

						$this->set('htmlencodedemail', $htmlencodedemail);

						$this->Customfunctions->saveActionPoint('Edit User','Success'); #Action : Used Edited
						$message = 'You have successfully updated the user details.';
						$message_theme = 'success';
						$redirect_to = 'edit_user';

					} else {

						$this->Customfunctions->saveActionPoint('Edit User','Failed'); #Action : Used Edited
						$message = 'Sorry...Please check your fields again';
						$message_theme = 'failed';
						$redirect_to = 'edit_user';
					}

				} else {

					$this->Customfunctions->saveActionPoint('Edit User','Failed'); #Action : Used Edited
					$message = 'Sorry...This email_id already exist';
					$message_theme = 'failed';
					$redirect_to = 'edit_user';
				}

			} else {

				$this->Customfunctions->saveActionPoint('Edit User','Failed'); #Action : Used Edited
				$this->set('return_error_msg','Please check some fields are not entered');
				return null;
			}
		}

		// set variables to show popup messages from view file
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('return_error_msg',null);
		$this->set('redirect_to', $redirect_to);

	}



	// CHANGE STATUS USER
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (Migration & Updates)
	// DATE : 21-02-2022 (Updated)

	public function changeStatusUser() {

		//authenticate user
		$this->authenicateUser();
		$this->viewBuilder()->setLayout('admin_dashboard');



		$DmiUserHistoryLogs = $this->AqcmsWrapper->loadModel('DmiUserHistoryLogs');

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$user_table_id = $this->request->getSession()->read('user_table_id');

		$user_detail_values = $this->DmiUsers->find('all', array('conditions' => array('id IS' => $user_table_id)))->first();


		$user_email_id = $user_detail_values['email'];

		if ($user_detail_values['status'] == 'active') {


			//added new function call & condition on 06-08-2019 by Amol for LIMS user
			//check if the user belongs LIMS, then get inprogress sample status
			$samples_inprogress = $this->getLimsUserWiseSamplesInprogress($user_table_id);

			$lims_user_can_deactivate = 'no';

			if ($samples_inprogress == false) {

				$lims_user_can_deactivate = 'yes';
			}


			//call function to check inprocess appl for user
			$user_can_deactivated = $this->checkUserInprocessAppl($user_email_id);

			$ro_office_details = $this->DmiRoOffices->find('all', array('conditions' => array('ro_email_id IS' => $user_email_id)))->toArray();
			$ro_offices = null;
			$new_message = '';

			if (!empty($ro_office_details)) {

				$i = 0;

				foreach ($ro_office_details as $ro_office) {

					$ro_office_name[$i] = $ro_office['ro_office'];
					$i = $i + 1;
				}

				$ro_offices = implode(', ', $ro_office_name);

				if ($user_detail_values['division'] == 'DMI') {
					$user_can_deactivated = 'no';
					$lims_user_can_deactivate = 'yes';
				} elseif ($user_detail_values['division'] == 'LMIS') {
					$lims_user_can_deactivate = 'no';
					$user_can_deactivated = 'yes';
				} elseif ($user_detail_values['division'] == 'BOTH') {
					$user_can_deactivated = 'no';
					$lims_user_can_deactivate = 'no';
				}

				$new_message = 'Currently this user is Incharge of ' . $ro_offices . ' offices. Please Reallocate Incharge for these offices.';
			}

			//added new condition on 06-08-2019 by Amol for LIMS
			if ($user_can_deactivated == 'yes' && $lims_user_can_deactivate == 'yes') {

				$find_user_role_table_id = $this->DmiUserRoles->find('all', array('conditions' => array('user_email_id IS' => $user_email_id)))->first();

				if (!empty($find_user_role_table_id)) {


					//this below line is commented because the Table id was not working for deleting the data.
					//so the entire entity is given to delete the data.
					//on 21-02-2022 bny Akash
					//$user_role_table_id = $find_user_role_table_id['id'];

					$delete_roles_status = $this->DmiUserRoles->delete($find_user_role_table_id);

					if ($delete_roles_status == 1) {
						$DmiUsersEntity = $this->DmiUsers->newEntity(array(
							'id' => $user_table_id,
							'status' => 'disactive',
							'modified' => date('Y-m-d H:i:s')
						));

						if ($this->DmiUsers->save($DmiUsersEntity)) {

							//Added this call to save the user action log on 09-09-2022
							$this->Customfunctions->saveActionPoint('User De-activation','Success');
							$message = 'You have Deactivated this user successfully. All roles given are removed for this user now';
							$message_theme = 'success';
							$redirect_to = 'all_users';
						}
					}

				} else {

					$DmiUsersEntity = $this->DmiUsers->newEntity(array(
						'id' => $user_table_id,
						'status' => 'disactive',
						'modified' => date('Y-m-d H:i:s')
					));

					if ($this->DmiUsers->save($DmiUsersEntity)) {

						//Added this call to save the user action log on 09-09-2022
						$this->Customfunctions->saveActionPoint('User De-activation','Success');
						$message = 'You have Deactivated this user successfully.';
						$message_theme = 'success';
						$redirect_to = 'all_users';
					}
				}

			} else {

				//added new conditions for user specific mesaage for LIMS, DMI, BOTH user on 06-08-2019 by Amol
				if ($user_can_deactivated == 'no' && $lims_user_can_deactivate == 'no')//for BOTH user
				{
					$updated_message = '1) Sorry.. You can not deactivate this user, As belongs to DMI & LIMS both. ' . $new_message . '<br><br>2) Please check and reallocate if any Scrutiny or Site-inspections assigned to this user.<br><br>
					3) Some LIMS samples may also be in progress with this user, before deactivating these works also needs to be transfer to another user, from "User Work Transfer" module on LIMS dashboard.';

				} elseif ($user_can_deactivated == 'no' && $lims_user_can_deactivate == 'yes') {//for DMI user

					$updated_message = '1) Sorry.. You can not deactivate this user. ' . $new_message . '<br><br>2) Please check and reallocate if any Scrutiny or Site-inspections assigned to this user.';

				} elseif ($user_can_deactivated == 'yes' && $lims_user_can_deactivate == 'no') {//for LIMS user

					$updated_message = '1) Sorry.. You can not deactivate this user, ' . $new_message . '<br><br>2) Some LIMS samples may be in progress with this user, before deactivating these works needs to be transfer to another user, from "User Work Transfer" module on LIMS dashboard.';
				}

				//Added this call to save the user action log on 09-09-2022
				$this->Customfunctions->saveActionPoint('User De-activation','Failed');
				//message updated on 17-06-2019 by Amol
				$message = $updated_message;
				$redirect_to = 'all_users';

			}

		} else {

			$DmiUsersEntity = $this->DmiUsers->newEntity(array(

				'id' => $user_table_id,
				'status' => 'active',
				'modified' => date('Y-m-d H:i:s')
			));

			if ($this->DmiUsers->save($DmiUsersEntity)) {

				//Added this call to save the user action log on 09-09-2022
				$this->Customfunctions->saveActionPoint('User Activation','Success');
				$message = 'You have Activated this user successfully. Please set roles for this user now';
				$message_theme = 'success';
				$redirect_to = 'all_users';
			}
		}

		// set variables to show popup messages from view file
		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('redirect_to', $redirect_to);

	}



	// CHECK USER INPROCESS APPL
	// DESCRIPTION : for the users in process applications pending.
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (Migration)
	// DATE : 06-12-2020

	public function checkUserInprocessAppl($user_email_id) {

		//get flow wise tables to check user allocated appln.
		$applTypeArray = $this->Session->read('applTypeArray');
		//Index 1, Now Renewal application will not list except DDO dashboard, any where in list. on 20-10-2022
		unset($applTypeArray['1']);

		$this->AqcmsWrapper->loadModel('DmiFlowWiseTablesLists');
		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all', array('conditions' => array('application_type IN' => $applTypeArray), 'order' => 'id ASC'))->toArray();

		foreach ($flow_wise_tables as $each_flow) {

			$allocation = $each_flow['allocation'];
			$ho_allocation = $each_flow['ho_level_allocation'];
			$final_submit = $each_flow['application_form'];
			$ro_so_comments = $each_flow['ro_so_comments'];
			$this->AqcmsWrapper->loadModel($allocation);
			$this->AqcmsWrapper->loadModel($ho_allocation);
			$this->AqcmsWrapper->loadModel($final_submit);
			$this->AqcmsWrapper->loadModel('DmiRoOffices');
			$this->AqcmsWrapper->loadModel($ro_so_comments);

			$find_first_allocation = $this->$allocation->find('all', array('conditions' => array('OR' => array('level_1 IS' => $user_email_id, 'level_2 IS' => $user_email_id, 'level_3 IS' => $user_email_id, 'level_4_ro IS' => $user_email_id, 'level_4_mo IS' => $user_email_id))))->toArray();

			$find_ho_allocation = $this->$ho_allocation->find('all', array('conditions' => array('OR' => array('dy_ama IS' => $user_email_id, 'ho_mo_smo IS' => $user_email_id, 'jt_ama IS' => $user_email_id, 'ama IS' => $user_email_id))))->toArray();

			//added new query to check RO office table, if incharge of any office
			$ro_office_details = $this->DmiRoOffices->find('all', array('conditions' => array('ro_email_id IS' => $user_email_id)))->toArray();

			$user_can_deactivated = null;

			if (empty($find_first_allocation) && empty($find_ho_allocation) && empty($ro_office_details))//added new condition for RO offices
			{
				$user_can_deactivated = 'yes';
			} else {

				if (!empty($find_first_allocation)) {
					foreach ($find_first_allocation as $each_allocation) {
						$customer_id = $each_allocation['customer_id'];

						//added this check on 23-06-2021, to release application if scrutiny or inspection is overload
						//for scrutiny
						if ($each_allocation['level_1'] == $user_email_id) {
							//check scrutiny done or not
							$check_status = $this->$final_submit->find('all', array('conditions' => array('customer_id IS' => $customer_id, 'status' => 'approved', 'current_level' => 'level_1'), 'order' => 'id desc'))->first();
							if (!empty($check_status)) {

								$user_can_deactivated = 'yes';
							} else {
								$user_can_deactivated = 'no';
								return $user_can_deactivated;//if found any one application, can not be deactivated
							}
						}
						//for inspection or RO/SO grant
						if ($each_allocation['level_2'] == $user_email_id || $each_allocation['level_3'] == $user_email_id || $each_allocation['level_4_ro'] == $user_email_id) {

							//check granted or not
							$check_status = $this->$final_submit->find('all', array('conditions' => array('customer_id IS' => $customer_id, 'status' => 'approved', 'current_level' => 'level_3'), 'order' => 'id desc'))->first();
							if (!empty($check_status)) {
								$user_can_deactivated = 'yes';
							} else {
								$user_can_deactivated = 'no';
								return $user_can_deactivated;//if found any one application, can not be deactivated
							}
						}

						//for level 4 scrutiny in SO jurisdiction
						if ($each_allocation['level_4_mo'] == $user_email_id) {

							//get comments details from ro so comments table
							$check_status = $this->$ro_so_comments->find('all', array('conditions' => array('customer_id IS' => $customer_id, 'from_user' => 'mo'), 'order' => 'id DESC'))->first();
							if (!empty($check_status)) {

								$user_can_deactivated = 'yes';
							} else {
								$user_can_deactivated = 'no';
								return $user_can_deactivated;//if found any one application, can not be deactivated
							}
						}


						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						//    /*	$check_application_status = $this->$final_submit->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'approved','current_level'=>'level_3'),'order'=>'id desc'))->first(); //
						//                                                                                                                                                                                                                 //
						//  if(empty($check_application_status))                                                                                                                                                                           //
						//  {                                                                                                                                                                                                              //
						//	$user_can_deactivated = 'no';                                                                                                                                                                                  //
						//	return $user_can_deactivated;//return if get 'no' either in furst attempts                                                                                                                                     //
						//  }*    /                                                                                                                                                                                                        //
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}
				}

				if (!empty($find_ho_allocation)) {
					foreach ($find_ho_allocation as $each_allocation) {

						$customer_id = $each_allocation['customer_id'];

						$check_application_status = $this->$final_submit->find('all', array('conditions' => array('customer_id IS' => $customer_id, 'status' => 'approved', 'current_level' => 'level_3'), 'order' => 'id desc'))->first();

						if (empty($check_application_status)) {
							$user_can_deactivated = 'no';
							return $user_can_deactivated;//return if get 'no' either in furst attempt
						}
					}
				}

				//this check added on 06-12-2019, to set variable value to 'yes' if there is no "no" from above
				if ($user_can_deactivated != 'no') {
					$user_can_deactivated = 'yes';
				}
			}
		}

		return $user_can_deactivated;


	}



	// GET LIMS USER WISE SAMPLES INPROGRESS
	// DESCRIPTION : For LIMS user deactivation check this function is used to check the LIMS sample in prgress with user, which is selected to deactivate
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (Migration)
	// DATE : 21-02-2022

	public function getLimsUserWiseSamplesInprogress($user_table_id) {

		$from_user = $user_table_id;
		//check status in worflow table regarding this user id
		$this->AqcmsWrapper->loadModel('Workflow');

		//Check from user id in distination user code colume in workflow table and get list of original sample code.
		$asDestinationUserSample = array_unique($this->Workflow->find('list', array('keyField' => 'id', 'valueField' => 'org_sample_code', 'conditions' => array('dst_usr_cd IS' => $from_user), 'order' => 'id desc'))->toList());

		//Check all get original sample code is final graded or not and get final graded sample list.
		$finalGradingCompletedSample = array();
		if (!empty($asDestinationUserSample)) {
			$finalGradingCompletedSample = array_unique($this->Workflow->find('list', array('keyField' => 'id', 'valueField' => 'org_sample_code', 'conditions' => array('org_sample_code IN' => $asDestinationUserSample, 'stage_smpl_flag' => 'FG')))->toList());
		}

		//Getting the sample list that have not final graded yet.
		$PendingFinalGradingSample = array_diff($asDestinationUserSample, $finalGradingCompletedSample);


		//new conditions added on 23-06-2021 by Amol
		//if sample forwarded by the any DMI officer and final grading not done yet,
		//then do not release the officer from LIMS pending work
		$get_user_lims_role = $this->DmiUsers->find('all', array('fields' => 'role', 'conditions' => array('id IS' => $user_table_id)))->first();
		$lims_role = $get_user_lims_role['role'];

		if ($lims_role == 'RO/SO OIC' || $lims_role == 'RO Officer' || $lims_role == 'SO Officer' || $lims_role == 'Ro_assistant') {

			foreach ($PendingFinalGradingSample as $eachkey => $eachValue) {

				//check if the sample is forwarded by the user
				$forwarded_by_user = 'no';
				$forwardStatus = $this->Workflow->find('all', array('fields' => 'org_sample_code', 'conditions' => array('src_usr_cd IS' => $from_user, 'org_sample_code IS' => $eachValue, 'stage_smpl_flag' => 'OF')))->first();
				if (!empty($forwardStatus)) {

					$forwarded_by_user = 'yes';
					break;//if found a single asample forwarded by the user and not final graded
				}
			}

		} else {
			$forwarded_by_user = 'yes'; //to proceed for further logic for LIMS users, default set to 'yes'
		}

		//added this condition on 23-06-2021 by Amol
		if ($forwarded_by_user == 'yes') {

			$teststatus = array();
			$maxresultID = array();
			$pendingtest = array();
			$tabc = array();
			$chemistcode = array();
			$finalresult = array();
			$in_src_usr_cd_pr = array();

			foreach ($PendingFinalGradingSample as $eachkey => $eachValue) {

				//Getting list of stage sample status flag for particular original sample code for from user id
				$result = $this->Workflow->find('list', array('keyField' => 'id', 'valueField' => 'stage_smpl_flag', 'conditions' => array('org_sample_code IS' => $eachValue, 'dst_usr_cd IS' => $from_user), 'order' => 'id'))->toList();

				//Getting current stage sample status flag for particular original sample code for from user id
				$current_sample_status = $this->Workflow->find('all', array('conditions' => array('org_sample_code IS' => $eachValue), 'order' => 'id desc'))->first();

				//Checked and make list of TA and TABC stage sample status flag
				foreach ($result as $eachkey1 => $eachValue1) {

					if (trim($eachValue1) == 'TA') {

						$teststatus[] = $eachkey1;
					}

					if (trim($eachValue1) == 'TABC') {

						$tabc[] = $eachkey1;
					}
				}

				if (in_array(trim($current_sample_status['stage_smpl_flag']), array('SD', 'TA', 'TABC'))) {

					$currentsamplestatus[] = array($current_sample_status['id'], $eachkey1);

				} else {

					$currentsamplestatus[] = array($current_sample_status['id']);
				}


				//store max id of particular original sample code
				$maxresultID[] = $eachkey1;
				$in_src_usr_cd_pr_list = $this->Workflow->find('list', array('keyField' => 'id', 'valueField' => 'stage_smpl_cd', 'conditions' => array('org_sample_code IS' => $eachValue, 'src_usr_cd IS' => $from_user, 'stage_smpl_flag' => 'TA'), 'order' => 'id'))->toList();
				if (!empty($in_src_usr_cd_pr_list)) {
					$in_src_usr_cd_pr[] = $in_src_usr_cd_pr_list;
				}
			}


			foreach ($in_src_usr_cd_pr as $eachloop) {

				foreach ($eachloop as $eachloopkey => $eachloopvalue) {

					$loopvaluewithFT = $this->Workflow->find('all', array('fields' => 'id', 'conditions' => array('stage_smpl_cd IS' => $eachloopvalue, 'stage_smpl_flag' => 'FT')))->first();

					if (empty($loopvaluewithFT)) {

						$update_src_code_id[] = trim($eachloopkey);
					}
				}
			}

			//Getting list of actual pending samples on from user side.
			foreach ($maxresultID as $resultKey => $resultValue) {

				if (in_array($resultValue, $currentsamplestatus[$resultKey])) {

					$finalresult[] = $resultValue;
				}
			}

			//Getting list of actual pending allocated test sample code on from user side.
			if (!empty($teststatus)) {

				foreach ($teststatus as $eachtest) {

					$teststagecd = $this->Workflow->find('all', array('fields' => array('id', 'stage_smpl_cd'), 'conditions' => array('id IS' => $eachtest)))->first();
					$testsamplestage = array();

					if (!empty($teststagecd)) {

						$testsamplestage = $this->Workflow->find('all', array('fields' => array('id', 'stage_smpl_cd'), 'conditions' => array('stage_smpl_cd IS' => $teststagecd['stage_smpl_cd'], 'stage_smpl_flag' => 'FT')))->first();

						if (empty($testsamplestage)) {

							$pendingtest[] = $eachtest;
							$chemistcode[] = trim($teststagecd['stage_smpl_cd']);
						}
					}
				}
			}

			//$this->AqcmsWrapper->loadModel('SamAllocate');

			//$chemist_allocated = $this->SamAllocate->find('list',array('keyField'=>'chemist_code','valueField'=>'sr_no','conditions'=>array('chemist_code'=>$chemistcode)))->toList();

			$finalPendingList = array_unique(array_merge(array_diff($finalresult, $tabc), $pendingtest));

			if (!empty($finalPendingList) || !empty($chemistcode) || !empty($update_src_code_id)) {
				$inprogress_status = 'yes';
			} else {
				$inprogress_status = null;
			}

		} else {//added on 23-06-2021 by Amol
			$inprogress_status = null;
		}

		if ($inprogress_status == 'yes') {
			return true;
		} else {
			return false;
		}


	}



	// LOCK USERS
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 21-02-2022
	/* This function is used for to unlock the locked user by three consecutive unsuccessful login
	/* Why Created : Provided the unlocked user functionality to Admin user */

	public function lockUserRedirect($lockListFor) {
		$this->Session->write('lockListFor', $lockListFor);
		$this->Redirect('/users/lock-users');
	}



	// LOCK USERS
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (Migration)
	// DATE : 21-02-2022 (Updated)

	public function lockUsers() {

		$message = '';
		$message_theme = '';
		$redirect_to = '';
		$this->viewBuilder()->setLayout('admin_dashboard');
		//get value from session
		$lockListFor = $this->request->getSession()->read('lockListFor');

		if ($lockListFor == 'primary') {
			$tableName = 'DmiCustomers';
			$logsTablename = 'DmiCustomerLogs';
		} elseif ($lockListFor == 'secondary') {
			$tableName = 'DmiFirms';
			$logsTablename = 'DmiCustomerLogs';
		} elseif ($lockListFor == 'dmiUsers') {
			$tableName = 'DmiUsers';
			$logsTablename = 'DmiUserLogs';
		}

		//loadModel
		$this->AqcmsWrapper->loadModel($tableName);
		$this->AqcmsWrapper->loadModel($logsTablename);

		$all_users = $this->getLockedUsersList($lockListFor);

		if (!empty($all_users)) {
			$i = 1;
			foreach ($all_users as $each_user) {

				$unlockAllUser[$i] = $each_user['id'];
				$i = $i + 1;
			}
		}

		if (!empty($_POST)) {

			$postvalue = array_keys($this->request->getData());

			if ($postvalue[1] == 'unlockbtn') {
				$postArray = $unlockAllUser;
			} else {
				$postArray = $postvalue;
			}

			foreach ($postArray as $index => $each) {

				if ($index != '0') {//condition applied intensionaly for some reason

					$firms_details = $this->$tableName->find('all', array('conditions' => array('id IS' => $each)))->first();

					if ($lockListFor == 'dmiUsers') {
						$user_id = $firms_details['email'];
						$fieldName = 'email_id';
					} else {
						$user_id = $firms_details['customer_id'];
						$fieldName = 'customer_id';
					}

					$log_ids = $this->$logsTablename->find('all', array('fields' => 'id', 'conditions' => array($fieldName => $user_id), 'order' => 'id DESC'))->first();
					$log_id = $log_ids['id'];

					$current_ip = $this->getRequest()->clientIp();
					if ($current_ip == '::1') {
						$current_ip = '127.0.0.1';
					}

					$logsTablenameEntity = $this->$logsTablename->newEntity(array(
						'id' => $log_id,
						'ip_address' => $current_ip,
						'date' => date('Y-m-d'),
						'time_in' => date('H:i:s'),
						'remark' => 'Success',
						'unlock' => 'by_DMI_user'
					));

					if ($this->$logsTablename->save($logsTablenameEntity)) {
						$unlock = 'successfully';
					}
				}
			}

			if (!empty($unlock)) {
				//Added this call to save the user action log on 09-09-2022
				$this->Customfunctions->saveActionPoint('Unlock User','Success');
				$message = 'Unlock Successfully';
				$message_theme = 'success';
				$redirect_to = 'lock-users';
			}
		}

		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('redirect_to', $redirect_to);
		$this->set('all_users', $all_users);

	}



	// GET LOCKED USERS LIST
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (migration)
	// DATE : 11-05-2021

	public function getLockedUsersList($listFor) {

		if ($listFor == 'primary') {
			$tableName = 'DmiCustomers';
			$logsTablename = 'DmiCustomerLogs';
			$fieldName = 'customer_id';

		} elseif ($listFor == 'secondary') {
			$tableName = 'DmiFirms';
			$logsTablename = 'DmiCustomerLogs';
			$fieldName = 'customer_id';

		} elseif ($listFor == 'dmiUsers') {
			$tableName = 'DmiUsers';
			$logsTablename = 'DmiUserLogs';
			$fieldName = 'email';
		}

		$this->AqcmsWrapper->loadModel($tableName);
		$this->AqcmsWrapper->loadModel($logsTablename);

		$lock_user_list = array();
		$current_date = date('d-m-Y');

		//added condition on 11-05-2021 by Amol for field name email_id
		//check todays users with failed attempts
		if ($logsTablename == 'DmiUserLogs') {

			$all_user_list = $this->$logsTablename->find('all', array('fields' => array('email_id' => 'Distinct(email_id)'), 'conditions' => array('date IS' => $current_date), 'remark' => 'Failed'))->toArray();
		} else {
			$all_user_list = $this->$logsTablename->find('all', array('fields' => array($fieldName => 'Distinct(' . $fieldName . ')'), 'conditions' => array('date IS' => $current_date), 'remark' => 'Failed'))->toArray();
		}


		$j = 0;

		foreach ($all_user_list as $each_user_id) {

			//added condition on 11-05-2021 by Amol for field name email_id
			if ($logsTablename == 'DmiUserLogs') {

				$user_id = $each_user_id['email_id'];
			} else {
				$user_id = $each_user_id[$fieldName];
			}

			$lockout_status = $this->Customfunctions->checkLoginLockout($logsTablename, $user_id);

			if ($lockout_status == 'yes') {
				$user_detail = $this->$tableName->find('all', array('conditions' => array($fieldName => $user_id)))->first();
				if (!empty($user_detail)) {
					$lock_user_list[$j] = $user_detail;
					$j = $j + 1;
				}
			}
		}

		return $lock_user_list;


	}



	// ADMIN LOGS
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (migration)
	// DATE : 29-04-2021

	public function adminLogs() {

		if ($this->Session->read('username') == null) {
			$this->customAlertPage("Sorry You are not authorized to view this page..");
			exit;
		}

		$this->viewBuilder()->setLayout('admin_dashboard');


		$get_admins = $this->DmiUserRoles->find('list', array('keyField' => 'id', 'valueField' => 'user_email_id', 'conditions' => array('super_admin' => 'yes')))->toList();
		$user_logs = $this->DmiUserLogs->find('all', array('conditions' => array('email_id IN' => $get_admins), 'order' => 'id DESC'))->toArray();
		//to hide current session logout time.
		$user_logs[0]['time_out'] = null;
		$this->set('user_logs', $user_logs);

	}



	// ALL USER LOGS
	// @AUTHOR : AMOL CHOUDHARI
	// @CONTRIBUTER : AKASH THAKRE (migration)
	// DATE : 29-04-2021

	public function allUsersLogs() {


		$this->viewBuilder()->setLayout('admin_dashboard');





		$username = $this->Session->read('username');

		if ($username == null) {
			$this->customAlertPage("Sorry You are not authorized to view this page..");
			exit;
		}
		//by default
		$to_dt = date('Y-m-d');
		$from_dt = date('Y-m-d', strtotime('-1 month'));

		if ($this->request->is('post')) {

			//on search
			$to_dt = $this->request->getData('to_dt');
			$from_dt = $this->request->getData('from_dt');


			if (empty($from_dt) || empty($to_dt)) {

				$this->set('return_error_msg','Please  Proper Dates.');
				return null;
			}

			$this->set(compact('to_dt', 'from_dt'));
		}


		if (!empty($from_dt) || !empty($to_dt)) {

			//check current user,if RO/SO In-charge then show logs of users under his/her office only
			$check_incharge = $this->DmiRoOffices->find('list', array('fields' => 'id', 'conditions' => array('ro_email_id IS' => $username, 'office_type IN' => array('RO', 'SO'), 'delete_status IS' => null)))->all()->toList();

			//get users for RO/SO incharge
			if (!empty($check_incharge)) {

				$get_users = $this->DmiUsers->find('list', array('keyField' => 'id', 'valueField' => 'email', 'conditions' => array('division IN' => array('DMI', 'BOTH'), 'posted_ro_office IN' => $check_incharge)))->toArray();
				//get all users for Admin
			} else {

				$get_users = $this->DmiUsers->find('list', array('keyField' => 'id', 'valueField' => 'email', 'conditions' => array('division IN' => array('DMI', 'BOTH'))))->toArray();
			}

			//get logs
			$user_logs = $this->DmiUserLogs->find('all', array('conditions' => array('email_id IN' => $get_users, 'date(date) >=' => $from_dt, 'date(date) <=' => $to_dt), 'order' => 'id DESC'))->toArray();

			//to hide current session logout time.
			$user_logs[0]['time_out'] = null;

			$this->set('user_logs', $user_logs);
		}

		$this->set('return_error_msg',null);

	}



	// showApplStatusPopup
	// DESCRIPTION : to show application basic details to RO/SO user in popup
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 23-06-2021

	public function showApplStatusPopup(){

		$this->autoRender = false;
		//get ajax post data
		$appl_id = $_POST['appl_id'];
		$appl_type = $_POST['appl_type'];

		//get firm details
		$firm_details = $this->Dmi_firm->find('all',array('fields'=>array('firm_name','created'),'conditions'=>array('customer_id IS'=>$appl_id)))->first();
		$firm_name = $firm_details['firm_name'];


		//$last_status = $this->get_application_current_status($appl_id);
		if ($appl_type == 'New') {
			$current_position_table = 'DmiAllApplicationsCurrentPositions';
			$final_submit_table = 'DmiFinalSubmits';
		} elseif ($appl_type == 'Renewal') {
			$current_position_table = 'DmiRenewalAllCurrentPositions';
			$final_submit_table = 'DmiRenewalFinalSubmits';
		}

		//get application applied on
		$applied_on_details = $this->$final_submit_table->find('all',array('fields'=>'created','conditions'=>array('customer_id IS'=>$appl_id,'status'=>'pending'),'order'=>'id desc'))->first();
		$applied_on = $applied_on_details['created'];

		//get application last status
		$applied_on_details = $this->$final_submit_table->find('all',array('fields'=>array('status','created','current_level'),'conditions'=>array('customer_id IS'=>$appl_id),'order'=>'id desc'))->first();
		$last_status = $applied_on_details['status'];
		$last_status_date = $applied_on_details['created'];

		if ($last_status=='approved' && $applied_on_details['current_level']=='level_1') {
			$last_status = 'Scrutinized';
		} elseif ($last_status=='approved' && $applied_on_details['current_level']=='level_2') {
			$last_status = 'Report Filed';
		}

		//get current position details
		$get_pos_details = $this->$current_position_table->find('all',array('fields'=>array('current_level'),'conditions'=>array('customer_id IS'=>$appl_id),'order'=>'id desc'))->first();
		$current_level = $get_pos_details['current_level'];

		if ($current_level == 'applicant') {
			$currently_with = 'Applicant';
		} elseif ($current_level == 'level_1') {
			$currently_with = 'Scrutiny Officer';
		} elseif ($current_level == 'level_2') {
			$currently_with = 'Inspection Officer';
		} elseif ($current_level == 'level_3') {
			$currently_with = 'RO/SO In-charge';
		} elseif ($current_level == 'level_4') {
			$currently_with = 'HO(QC)';
		}

		//create a array to return result
		$result = array(
			'appl_id'=>$appl_id,
			'firm_name'=>$firm_name,
			'applied_on'=>$applied_on,
			'last_status'=>$last_status,
			'currently_with'=>$currently_with,
			'last_status_date'=>$last_status_date
		);

		return '~'.json_encode($result).'~';

	}



	///====================================================| REPLICA * METHODS |========================================================///
	// REPLICA ALLOTED LIST
	// @AUTHOR : AKASH THAKRE
	// DATE : 10-08-2021

	public function replicaAllotedList() {

		$this->viewBuilder()->setLayout('admin_dashboard');
		$this->Customfunctions->replicaAllotedListCall('replica');
	}


	// ALLOTED 15-DIGIT LIST
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 26-11-2021

	public function alloted15DigitList() {

		$this->viewBuilder()->setLayout('admin_dashboard');
		$this->Customfunctions->replicaAllotedListCall('15Digit');
	}


	// ALLOTED E-CODE LIST
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 26-11-2021

	public function allotedECodeList() {

		$this->viewBuilder()->setLayout('admin_dashboard');
		$this->Customfunctions->replicaAllotedListCall('ECode');
	}


	// REPLICA TRANSACTION
	// DESCRIPTION : TO SHOW THE PACKERS TRANSACTION TO THE DDO/PAO AND RO/SO
	// @AUTHOR : AKASH THAKRE
	// DATE : 10-08-2021

	public function replicaTransaction() {

		//Set the Layout
		$this->viewBuilder()->setLayout('admin_dashboard');
		$username = $this->Session->read('username');





		//check role if RO/SO or PAO/DDO
		$current_user_role = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();
		$packer_list = array();
		$packer_name = null;

		//If DDO/PAO
		if ($current_user_role['pao'] == 'yes' && $current_user_role['ro_inspection'] ) {

			//get pao get_details
			$get_pao_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$username,'status'=>'active')))->first();

			$pao_id = $get_pao_details['id'];

			$get_pao_user_id = $this->DmiPaoDetails->find('all',array('fields'=>'id','conditions'=>array('pao_user_id IS'=>$pao_id)))->first();

			$get_packer_list = $this->DmiAdvPaymentDetails->find('all',array('fields'=>'customer_id','conditions'=>array('pao_id IS'=>$get_pao_user_id['id'],'payment_confirmation'=>'confirmed'),'group'=>'customer_id'))->toArray();

			$i=0;
			if (!empty($get_packer_list)) {

				foreach($get_packer_list as $each) {

					$get_packer_id = $each['customer_id'];
					$get_list = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('customer_id IS'=>$get_packer_id,'delete_status IS NULL')))->first();
					$packer_list[$get_packer_id] = $get_list['firm_name'];
					$i = $i+1;
				}
			}

			$this->set('packer_list',$packer_list);

		} else {

			//get RO/SO Incharge details
			$get_ro_details = $this->DmiRoOffices->find('all',array('fields'=>'short_code','conditions'=>array('ro_email_id IS'=>$username)))->toArray();
			//get RO/SO office wise list
			$i=0;

			foreach ($get_ro_details as $eachCode) {

				$short_code = $eachCode['short_code'];

				//get packer list
				$get_packer_list = $this->DmiAdvPaymentDetails->find('all',array('fields'=>'customer_id','conditions'=>array('customer_id like'=>'%/'.$short_code.'/%','payment_confirmation'=>'confirmed'),'group'=>'customer_id'))->toArray();

				if (!empty($get_packer_list)) {

					foreach ($get_packer_list as $each) {

						$get_packer_id = $each['customer_id'];
						$get_list = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('customer_id IS'=>$get_packer_id,'delete_status IS NULL')))->first();

						$packer_list[$get_packer_id] = $get_list['firm_name'];

						$i = $i+1;
					}
				}
			}

			$this->set('packer_list',$packer_list);
		}


		if (null!== ($this->request->getData('packer_id'))) {

			$packer_id = $this->request->getData('packer_list');

			$transactionsHistory = $this->DmiAdvPaymentTransactions->find('all',array('conditions'=>array('customer_id IS'=>$packer_id),'order'=>array('id desc')))->toArray();
			if (!empty($transactionsHistory)) {

				$getPackerName = $this->DmiFirms->find('all')->Select(['firm_name'])->where(['customer_id IS' => $transactionsHistory[0]['customer_id'], 'certification_type' => '1','delete_status IS NULL'])->first();
				$packer_name = $getPackerName['firm_name'];
				$this->set('packer_name',$packer_name);
				$this->set('transactionhistory',$transactionsHistory);
			}
		}

	}


	// Test mail delivery - Aniket G [16-01-2023]
	public function testMailDelivery($email) {

		$this->autoRender = false;

		$to = $email;
		$subject = "Testing mail delivery for ibmreturns.gov.in -- through 10.153.72.52";
		$txt = "Hello there, this is just a test mail for testing ibmreturns.gov.in portal. Kindly ignore this mail as this was intentionally sent for testing purpose only.";
		$headers = "From: no-reply@ibm.gov.in";
		$resp = mail($to,$subject,$txt,$headers);
		print_r($resp); exit;

	}



}

?>
