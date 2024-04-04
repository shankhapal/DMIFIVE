<?php
//To access the properties of main controller used initialize function.
namespace app\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Controller\CustomersController;

class AuthenticationComponent extends Component {


	public $controller = null;
	public $session = null;

	public function initialize(array $config): void {
		parent::initialize($config);
        $this->components = ['Session'];   // for solving error [Type of app\Controller\Component\CreatecaptchaComponent::$components must be array (as in class Cake\Controller\Component)] added by shankhpal on 26-03-2024
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();
	}



	// Encrypt
	// Description : To encrypt given string with unique key value currently using for aadhar no. encryption.
	// @AUTHOR : Amol Chaudhari
	// #CONTRIBUTER : ----
	// DATE : 01-06-2017

	public function encrypt($string) {

		$result = '';
		$key="D@M@I753||=+753agmark(nic)";

		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}

		return base64_encode($result);
	}




	// Decrypt
	// Description : To decrypt given string with unique key value currently using for aadhar no. decryption
	// @AUTHOR : Amol Chaudhari
	// #CONTRIBUTER : ----
	// DATE : 01-06-2017

	public function decrypt($string) {

		$result = ''; $key="D@M@I753||=+753agmark(nic)";
		$string = base64_decode($string);

		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}

		return $result;
	}




	// Customer Login Library
	// Description : This fuction is created for the Login authentication for customers.
	// @AUTHOR : Amol Chaudhari
	// #CONTRIBUTER : ----
	// DATE : ----

	public function customerLoginLib($table_name,$username,$password,$randsalt) {

		$Dmitable = TableRegistry::getTableLocator()->get($table_name);
		$DmiCustomerLogs = TableRegistry::getTableLocator()->get('DmiCustomerLogs');

		//Checking for catpcha
		if ($this->getController()->getRequest()->getData('captcha') !="" && $this->Session->read('code') == $this->getController()->getRequest()->getData('captcha')) {
			//This if condition added on 31-07-2017 by Amol to avoid deleted firm login
			if ($table_name == 'DmiCustomers') {
				//for primary Applicant
				$PassFromdb = $Dmitable->find('all', array('conditions'=> array('customer_id IS' => $username)))->first();
				if (!empty($PassFromdb)) {

					$mobile_no = $PassFromdb['mobile'];
					$email_id = $PassFromdb['email'];
				}

			} elseif ($table_name == 'DmiFirms') {
				//for secondary applicant(firm)
				$PassFromdb = $Dmitable->find('all', array('conditions'=> array('customer_id IS'=>$username,'delete_status IS NULL')))->first();
				if (!empty($PassFromdb)) {

					$mobile_no = $PassFromdb['mobile_no'];
					$email_id = $PassFromdb['email'];
				}
			}

			if ($PassFromdb != null && $PassFromdb != '') {

				$passarray = $PassFromdb['password'];
				$emailforrecovery = $email_id;

				if (strlen($passarray) == 128 ) {

					//Adding random salt value to password
					$PassFromdbsalted = $randsalt . $passarray;

					//Encrypting salted password to sha512
					$Dbpasssaltedsha512 = hash('sha512',$PassFromdbsalted);

					//Check password to db password
					if ($password == $Dbpasssaltedsha512 ) {

						//created/updated/added on 25-06-2021 for multiple logged in check security updates, by Amol
						$checkLog = $this->alreadyLoggedInCheck($username);

						if ($checkLog == 'norecord') {
							//the logic from here is transffered to the function and called here on 25-06-2021 by Amol
							$this->customerProceedLogin($username,$Dmitable);
						} else {
							$_SESSION['username'] = $username;
							$_SESSION['userloggedin'] = 'no';
							return 5;
						}

					} else {

						$DmiCustomerLogs->saveLog($username,'Failed');
						return 1;
					}

				} else {

					//added by AMOL on 16-04-2021 for reset password by fro sha512 hashing
					$this->forgotPasswordLib($table_name,$emailforrecovery);//for email encoding
					$sms_text = 'Your password has been expired, The link to reset password is sent on email id.'.base64_decode($emailforrecovery).', Please contact the concerned office. AGMARK';
					$CustomersController = new CustomersController;
					$CustomersController->SmsEmail->sendSms(144,$mobile_no,$sms_text,1107161673478221041,'DmiGeneralSentSmsLogs'); //For Forgot Password SMS
					$CustomersController->SmsEmail->sendEmail(144,$sms_text,$emailforrecovery,'Forgot Password Link',1107161673478221041,'DmiGeneralSentEmailLogs'); //For Forgot Password EMAIL

					return 4;
				}

			} else {
				// Save log for invalid user, Done By pravin Bhakare, 12-11-2020
				$DmiCustomerLogs->saveLog($username,'Failed');
				return 2;
			}

		} else {

			return 3;
		}


	}





	// Forgot Password Library
	// Description : This fuction is created for the forgot password saving and sending part.
	// @AUTHOR : Amol Chaudhari
	// #CONTRIBUTER : ----
	// DATE : ----

	public function forgotPasswordLib($table,$emailforrecovery,$customer_id=null) {

		$Dmitable = TableRegistry::getTableLocator()->get($table);

		// To check if the mail is encoded or not - Akash [20-10-2022]
		if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $emailforrecovery)) {} else { $emailforrecovery = base64_encode($emailforrecovery); }

		//applied condition on 19-09-2022 by Amol, if chemist applied through forgot password.
		if ($table=='DmiChemistRegistrations') { $customer_id=null; }

		//added this condition on 25-10-2018 by Amol, to apply query with/without customer id condition
		if (!empty($customer_id)) {
			$get_record_details = $Dmitable->find('all', array('conditions'=> array('email IS' => $emailforrecovery, 'customer_id IS'=>$customer_id)))->first();
		} else {
			$get_record_details = $Dmitable->find('all', array('conditions'=> array('email IS' => $emailforrecovery)))->first();
		}

		if ($get_record_details == null) {
			return 1;
		} else {

			if ($table=='DmiCustomers' || $table=='DmiFirms') {

				// Added the urlencode funtion to fix the issue of +,<,# etc issue in gettin through get parameter//
				$key_id = md5($get_record_details['id'].time().rand());
				$encrypted_user_id = urlencode($this->encrypt($get_record_details['customer_id']));
				$controller = 'customers';

			//Added for the chemist forgot password on 09-09-2021 By Akash
			} elseif ($table=='DmiChemistRegistrations') {

				$key_id = md5($get_record_details['id'].time().rand());
				$encrypted_user_id = urlencode($this->encrypt($get_record_details['chemist_id']));
				$controller = 'chemist';

			} elseif ($table=='DmiUsers') {

				// Added the urlencode funtion to fix the issue of +,<,# etc issue in gettin through get parameter//
				$key_id = md5($get_record_details['id'].time().rand());
				$encrypted_user_id = urlencode($this->encrypt($emailforrecovery));
				$controller = 'users';
			}

			$url = 'home.?'.'$key='.$key_id.'&'.'$id='.$encrypted_user_id;
			$host_path = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
			$sendlink = "<html><body><a href='$host_path/DMI/$controller/reset_password/$url'>Please click here to set Password</a></body></html>";

			 //function to check is the string is proper email id
			$subject = 'DMI AGMARK Set Password Link';
			//updated on 18-03-2019, email pattern changed
			$txt = 	'Hello' .
					"<html><body><br></body></html>".'Click the below link OR copy it to browser address bar:' .
					"<html><body><br></body></html>" .$sendlink.
					"<html><body><br></body></html>".'Above link will be active only for 24 hours. If expired, then try to set your password from forgot Password option on DMI portal'.
					"<html><body><br></body></html>".'Thanks & Regards,' .
					"<html><body><br></body></html>" .'Directorate of Marketing & Inspection,' .
					"<html><body><br></body></html>" .'Ministry of Agriculture and Farmers Welfare,' .
					"<html><body><br></body></html>" .'Government of India.';

			$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: dmiqc@nic.in";

			//This Condition is added to check wheather the Email is Encoded form or not - Akash [20-10-2022]
			if (filter_var($emailforrecovery, FILTER_VALIDATE_EMAIL)) {
				$to = $emailforrecovery;
			} else {
				$to = base64_decode($emailforrecovery);
			}

			//mail($to,$subject,$txt,$headers, '-f dmiqc@nic.in'); //added new parameter '-f dmiqc@nic.in' on 08-12-2018 by Amol

			//commented above line and added below code with new email setting on 17-03-2023
			require_once(ROOT . DS .'vendor' . DS . 'phpmailer' . DS . 'mail.php');
			$from = "dmiqc@nic.in";
			send_mail($from, $to, $subject, $txt);

			#Save Log For the Sent Email - Akash[02-02-2023]
			$DmiSentEmailLogs = TableRegistry::getTableLocator()->get('DmiSentEmailLogs');
			$DmiSentEmailLogs->saveLog('Forgot Password',$emailforrecovery,$txt,'Not Available');

			//store reset password link keys in DB

			if ($table=='DmiCustomers' || $table=='DmiFirms') {

				$DmiApplicantsResetpassKeys = TableRegistry::getTableLocator()->get('DmiApplicantsResetpassKeys');
				$DmiApplicantsResetpassKeys->saveKeyDetails($get_record_details['customer_id'],$key_id);

			//Added for the chemist forgot password on 09-09-2021 By Akash
			} elseif ($table =='DmiChemistRegistrations') {

				$DmiApplicantsResetpassKeys = TableRegistry::getTableLocator()->get('DmiChemistsResetpassKeys');
				$DmiApplicantsResetpassKeys->saveKeyDetails($get_record_details['chemist_id'],$key_id);

			} elseif ($table=='DmiUsers') {

				$DmiUsersResetpassKeys = TableRegistry::getTableLocator()->get('DmiUsersResetpassKeys');
				$DmiUsersResetpassKeys->saveKeyDetails($emailforrecovery,$key_id);
			}

			return 2;
		}


	}




	// Change Password Library
	// Description : This fuction is created for the change password checking , validating and saving part.
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : Akash Thakre (u) (m) / Aniket Ganvir (u)
	// DATE : 18-04-2022

	public function changePasswordLib($table,$username,$oldpassdata,$newpassdata,$confpassdata,$randsalt) {

		$Dmitable = TableRegistry::getTableLocator()->get($table);

		// CHECK LAST THREE PASSWORD WITH NEW PASSWORD
		// IF FOUND, THROW ERROR FOR RESTRICT BRUTE FORCE ATTACK UNDER SECURITY AUDIT
		// By Aniket Ganvir dated 16th NOV 2020
		$newpassdataEncoded = htmlentities($newpassdata, ENT_QUOTES);
		$passwordWithoutSalt = substr($newpassdataEncoded,strlen($randsalt));
		$DmiPasswordLogs = TableRegistry::getTableLocator()->get('DmiPasswordLogs');
		$checkPastThreePassword = $DmiPasswordLogs->checkPastThreePassword($username, $table, $passwordWithoutSalt);

		if ($checkPastThreePassword != 'found') {

			if ($newpassdata == $confpassdata) {

				if ($table == 'DmiUsers') {
					//for admin users
					$userDetails = $Dmitable->find('all', array('conditions'=> array('email IS' => $username)))->first();
					$mobile_no = $userDetails['phone'];
					$passUser = $userDetails['f_name']." ".$userDetails['l_name'];

				} elseif ($table == 'DmiCustomers' || $table == 'DmiFirms') {
					//for customers
					$userDetails = $Dmitable->find('all', array('conditions'=> array('customer_id IS' => $username)))->first();
					if($table == 'DmiCustomers'){
						$mobile_no = $userDetails['mobile'];
						$passUser = $userDetails['f_name']." ".$userDetails['l_name'];
					}else{
						$mobile_no = $userDetails['mobile_no'];
						$passUser = $userDetails['firm_name'];
					}

				} elseif ($table == 'DmiChemistRegistrations') {
					//for chemist
					$userDetails = $Dmitable->find('all', array('conditions'=> array('chemist_id IS' => $username)))->first();
					$mobile_no = $userDetails['mobile'];
					$passUser = $userDetails['chemist_fname']." ".$userDetails['chemist_lname'];
				}

				$passarray = $userDetails['password'];
				$PassFromdbsalted = $randsalt . $passarray;
				$Dbpasssaltedsha512 = hash('sha512',$PassFromdbsalted);

				if ($oldpassdata == $Dbpasssaltedsha512) {

					$Removesaltnewpass = substr($newpassdata,strlen($randsalt));

					if ($table == 'DmiUsers') {
						//for admin users
						$Dmitable_id = $Dmitable->find('all',array('fields'=>'id','conditions'=>array('email IS' => $username),'order'=>array('id desc')))->first();
					} elseif ($table == 'DmiCustomers' || $table == 'DmiFirms') {
						//for customers
						$Dmitable_id = $Dmitable->find('all',array('fields'=>'id','conditions'=>array('customer_id IS' => $username),'order'=>array('id desc')))->first();
					} elseif ($table == 'DmiChemistRegistrations') {
						//for chemist
						$Dmitable_id = $Dmitable->find('all', array('fields'=>'id','conditions'=>array('chemist_id IS' => $username),'order'=>array('id desc')))->first();
					}

					if ($Dmitable_id) {

						$DmitableEntity = $Dmitable->newEntity(['id' => $Dmitable_id['id'],'password' => $Removesaltnewpass,'modified'=>date('Y-m-d H:i:s')]);
						$Dmitable->save($DmitableEntity);

						// MAINTAIN PASSWORD LOGS FOR RESTRICT BRUTE FORCE ATTACK By Aniket Ganvir dated 16th NOV 2020
						$DmiPasswordLogs->savePasswordLogs($username, $table, $Removesaltnewpass);

						#SMS: Password Set
						$sms_text = 'Hello '.$passUser.', your password has been set successfully AGMARK.';
						$email_id = $userDetails['email'];

						$CustomersController = new CustomersController;
						$CustomersController->SmsEmail->sendSms('Change Password',$mobile_no,$sms_text,1107160801193314481,'DmiGeneralSentSmsLogs'); //For Change Password SMS
						$CustomersController->SmsEmail->sendEmail('Change Password',$sms_text,$email_id,'Password Changed Successfully',1107161673478221041,'DmiGeneralSentEmailLogs'); //For Forgot Password EMAIL

					} else {
						return 1;
					}

				} else {
					return 2;
				}

			} else {
				return 3;
			}

		} else {
			return 4;
		}

	}




	// Reset Password Library
	// Description : This fuction is created for the reset password checking , validating and saving part.
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : Akash Thakre (u) (m) / Aniket Ganvir (u)
	// DATE : 18-04-2022

	public function resetPasswordLib($table,$username,$newpassdata,$randsalt,$postData) {

		$Dmitable = TableRegistry::getTableLocator()->get($table);

		// CHECK LAST THREE PASSWORD WITH NEW PASSWORD IF FOUND, THROW ERROR FOR RESTRICT BRUTE FORCE ATTACK UNDER SECURITY AUDIT By Aniket Ganvir dated 16th NOV 2020
		$newpassdataEncoded = htmlentities($newpassdata, ENT_QUOTES);
		$passwordWithoutSalt = substr($newpassdataEncoded,strlen($randsalt));
		$DmiPasswordLogs = TableRegistry::getTableLocator()->get('DmiPasswordLogs');
		$checkPastThreePassword = $DmiPasswordLogs->checkPastThreePassword($username, $table, $passwordWithoutSalt);

		if ($checkPastThreePassword == 'found') {
			return 4;
		}

		//this condition added on 14-02-2018 by Amol
		if ($table=='DmiFirms' || $table=='DmiCustomers') {

			$form_name = TableRegistry::getTableLocator()->get('DmiCustomers');
			/* Update the last logs user entery with 'Success' status - Change on 05-12-2018 - By Pravin Bhakare - Suggested by Navin Sir */
			/* Why Change :- If user lock by three consecutive unsuccessful login and if user change the password successfuly then system automatically unlock the user */
			$log_table = TableRegistry::getTableLocator()->get('DmiCustomerLogs');

		} elseif ($table=='DmiUsers') {

			$form_name = TableRegistry::getTableLocator()->get('DmiUsers');
			/* Update the last logs user entery with 'Success' status - Change on 05-12-2018 - By Pravin Bhakare - Suggested by Navin Sir */
			/* Why Change :- If user lock by three consecutive unsuccessful login and if user change the password successfuly then system automatically unlock the user */
			$log_table = TableRegistry::getTableLocator()->get('DmiUserLogs');

		} elseif ($table=='DmiChemistRegistrations') {

			$form_name = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
			$log_table = TableRegistry::getTableLocator()->get('DmiChemistLogs');
		}

		$Dmilogtable = $log_table;

		if ($newpassdata == $postData['confirm_password']) {

			if ($postData['captcha'] !="" && $_SESSION["code"] == $postData['captcha']) {

				$Removesaltnewpass = substr($newpassdata,strlen($randsalt));

				if ($table == 'DmiUsers') {
					//for admin users
					$Dmitable_id = $Dmitable->find('all',array('conditions'=>array('email IS'=>$username),'order'=>array('id desc')))->first();
					/* Update the last logs user entery with 'Success' status - Change on 05-12-2018 - By Pravin Bhakare - Suggested by Navin Sir */
					/* Why Change :- If user lock by three consecutive unsuccessful login and if user change the password successfuly then system automatically unlock the user */
					$log_ids = $Dmilogtable->find('all',array('fields'=>'id','conditions'=>array('email_id IS'=>$username),'order'=>array('id desc')))->first();

					//This Below Block Code is Added to sent the Message Varible after Password Reset - Akash[09-02-2023]
					$mobile_no = $Dmitable_id['phone'];
					$passUser = $Dmitable_id['f_name']." ".$Dmitable_id['l_name'];

				} elseif ($table == 'DmiCustomers' || $table == 'DmiFirms') {
					//for customers
					$Dmitable_id = $Dmitable->find('all',array('conditions'=>array('customer_id IS'=>$username),'order'=>array('id desc')))->first();
					/* Update the last logs user entery with 'Success' status - Change on 05-12-2018 - By Pravin Bhakare - Suggested by Navin Sir */
					/* Why Change :- If user lock by three consecutive unsuccessful login and if user change the password successfuly then system automatically unlock the user */
					$log_ids = $Dmilogtable->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$username),'order'=>array('id desc')))->first();

					//This Below Block Code is Added to sent the Message Varible after Password Reset - Akash[09-02-2023]
					if($table == 'DmiCustomers'){
						$mobile_no = $Dmitable_id['mobile'];
						$passUser = $Dmitable_id['f_name']." ".$Dmitable_id['l_name'];
					}else{
						$mobile_no = $Dmitable_id['mobile_no'];
						$passUser = $Dmitable_id['firm_name'];
					}

				// For chemist registration module, Done by Pravin Bhakare 24-07-2021
				} elseif ($table=='DmiChemistRegistrations') {

					$Dmitable_id = $Dmitable->find('all',array('conditions'=>array('chemist_id IS'=>$username),'order'=>array('id desc')))->first();
					$log_ids = $Dmilogtable->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$username),'order'=>array('id desc')))->first();

					//This Below Block Code is Added to sent the Message Varible after Password Reset - Akash[09-02-2023]
					$mobile_no = $Dmitable_id['mobile'];
					$passUser = $Dmitable_id['chemist_fname']." ".$Dmitable_id['chemist_lname'];
				}

				if ($Dmitable_id) {

					$DmitableEntity = $Dmitable->newEntity(['id'=>$Dmitable_id['id'],'password'=>$Removesaltnewpass,'modified'=>date('Y-m-d H:i:s')]);
					$Dmitable->save($DmitableEntity);

					// MAINTAIN PASSWORD LOGS FOR RESTRICT BRUTE FORCE ATTACK By Aniket Ganvir dated 16th NOV 2020
					$DmiPasswordLogs->savePasswordLogs($username, $table, $Removesaltnewpass);

					/* Update the last logs user entery with 'Success' status - Change on 05-12-2018 - By Pravin Bhakare - Suggested by Navin Sir */
					/* Why Change :- If user lock by three consecutive unsuccessful login and if user change the password successfuly then system automatically unlock the user */
					if (!empty($log_ids['id'])) {

						$log_id = $log_ids['id'];
						$log_tableEntity = $log_table->newEntity([
							'id'=>$log_id,
							'ip_address'=>$this->Controller->getRequest()->clientIp(),
							'date'=>date('Y-m-d'),
							'time_in'=>date('H:i:s'),
							'remark'=>'Success',
							'unlock'=>'by_login_user'
						]);

						$log_table->save($log_tableEntity);
					}

					#SMS: Password Set
					$sms_text = 'Hello '.$passUser.', your password has been set successfully AGMARK.';
					$email_id = $Dmitable_id['email'];

					$CustomersController = new CustomersController;
					$CustomersController->SmsEmail->sendSms('Reset Password',$mobile_no,$sms_text,1107160801193314481,'DmiGeneralSentSmsLogs'); //For Change Password SMS
					$CustomersController->SmsEmail->sendEmail('Reset Password',$sms_text,$email_id,'Password Changed Successfully',1107160801193314481,'DmiGeneralSentEmailLogs'); //For Change Password EMAIL

				} else {
					return 1;
				}

			} else {
				return 2;
			}

		} else {
			return 3;
		}

	}




	// User Login Library
	// Description : This fuction is created for the user Login library, validating and saving part.
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : Akash Thakre (u) (m)
	// DATE : 18-04-2022

	public function userLoginLib($table_name,$username,$password,$randsalt) {

		$table = TableRegistry::getTableLocator()->get($table_name);
		$DmiUserLogs = TableRegistry::getTableLocator()->get('DmiUserLogs');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');
		$username = base64_encode($username);//for email encoding
		//captcha check
		if ($this->getController()->getRequest()->getData('captcha') !="" && $this->Session->read('code') == $this->getController()->getRequest()->getData('captcha')) {

			$PassFromdb = $table->find('all', array('conditions'=> array('email IS' => $username, 'status'=>'active')))->first();

			if ($PassFromdb != null && $PassFromdb != '') {

				$passarray = $PassFromdb['password'];
				$emailforrecovery = $PassFromdb['email'];

				if (strlen($passarray) == 128 ) {

					//adding random salt value to password
					$PassFromdbsalted = $randsalt . $passarray;

					// Encripting salted password to sha512
					$Dbpasssaltedsha512 = hash('sha512',$PassFromdbsalted);

					// check password to db password
					if ($password == $Dbpasssaltedsha512) {

						$userProceedLogin = 'no';
						$user_data_query = $table->find('all', array('conditions'=> array('email IS' => $username)))->first();
						$user_roles = $DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();

						if ($user_data_query['division'] == 'DMI' || $user_data_query['division'] == 'BOTH') {

							$userProceedLogin = 'yes';

						} elseif ($user_data_query['division'] == 'LMIS' && !empty($user_roles)) {

							if ($user_roles['set_roles']=='yes') {
								$userProceedLogin = 'yes';
							}
						}

						if ($userProceedLogin == 'yes') {

							$checkLog = $this->alreadyLoggedInCheck($username);

							if ($checkLog == 'norecord') {

								//the logic from here is transffered to the function and called here on 25-06-2021 by Amol
								$this->userProceedLogin($username,$table);

							}else{

								$_SESSION['username'] = $username;
								$_SESSION['userloggedin'] = 'no';
								return 5;
							}

						}else{

							 return 0;
						}


					} else {

						// Save log for invalid user, Done By pravin Bhakare, 12-11-2020
						$DmiUserLogs->saveLog($username,'Failed');
						return 1;
					}

				} else {

					$this->forgotPasswordLib($table_name,$emailforrecovery);
					$user_data_query = $table->find('all', array('conditions'=> array('email' => $username)))->first();
					$mobile_no = $user_data_query['phone'];
					$sms_text = 'Your password has been expired, the link to reset password is sent on email id '.base64_decode($emailforrecovery).'. AGMARK';

					$CustomersController = new CustomersController;
					$CustomersController->SmsEmail->sendSms('Forgot Password',$mobile_no,$sms_text,1107161673473567580,'DmiGeneralSentSmsLogs'); //For Forgot Password SMS
					$CustomersController->SmsEmail->sendEmail('Forgot Password',$sms_text,$emailforrecovery,'Forgot Password Link',1107161673473567580,'DmiGeneralSentEmailLogs'); //For Forgot Password EMAIL
					return 4;
				}

			} else {

				// Save log for invalid user, Done By pravin Bhakare, 12-11-2020
				$DmiUserLogs->saveLog($username,'Failed');
				return 2;
			}

		} else {
			return 3;
		}

	}


	// Browser Login Status
	// Description : Created new function for valided multiple browser login
	// @AUTHOR : Pravin Bhakare (c)
	// #CONTRIBUTER : Akash Thakre (u) (m)
	// DATE : 12-11-2020

	public function browserLoginStatus($username,$curr_loggedin=null) {

		$DmiLoginStatuses = TableRegistry::getTableLocator()->get('DmiLoginStatuses');

		$countspecialchar = substr_count($username ,"/");

		if ($countspecialchar == 1) { $userType = 'dp'; }
		if ($countspecialchar == 2) { $userType = 'ch'; }
		if ($countspecialchar == 3) { $userType = 'df'; }
		if ($countspecialchar == 0) { $userType = 'du'; }

		$current_ip = $this->getController()->getRequest()->clientIp();
		if ($current_ip == '::1')
		{
			$current_ip = '127.0.0.1';
		}

		$loginStatusCreated = date('Y-m-d H:i:s');
		$currLoggedin = $DmiLoginStatuses->find('all',array('conditions'=>array('user_id IS'=>$username,'user_type IS'=>$userType),'order'=>'id'))->first();

		$loginStatusId = '';

		if (!empty($currLoggedin)) {
			$loginStatusId = $currLoggedin['id'];
			$loginStatusCreated =  $currLoggedin['created'];
		}

		$sessionid = md5(rand());

		$DmiLoginStatusEntity = $DmiLoginStatuses->newEntity([
			'id'=>$loginStatusId,
			'user_id'=>$username,
			'user_type'=>$userType,
			'curr_loggedin'=>$curr_loggedin,
			'ipaddress'=>$current_ip,
			'sessionid'=>$sessionid,
			'created'=>$loginStatusCreated,
			'modified'=>date('Y-m-d H:i:s')
		]);

		$DmiLoginStatuses->save($DmiLoginStatusEntity);

		$_SESSION['browser_session_d'] = $sessionid;

	}




	// User Proceed Login
	// Description : this function contains the login logic for Authorized  user & on for multiple logged in check security updates
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : ----
	// DATE : 25-06-2021

	public function userProceedLogin($username,$table) {

		$DmiUserLogs = TableRegistry::getTableLocator()->get('DmiUserLogs');
        $DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');
        $DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');

	    // destroy old Session data
	    $this->Controller->getRequest()->getSession()->destroy();
	    Session_start();

	    // Update status of browser login history, Done By Pravin Bhakare 12-11-2020
	    $this->browserLoginStatus($username,'yes');

	    $current_ip = $this->Controller->getRequest()->clientIp();
	    if ($current_ip == '::1') {
	        $current_ip = '127.0.0.1';
	    }

	    $DmiUserLog = $DmiUserLogs->newEntity(
	        ['email_id'=>$username,
	        'ip_address'=>$current_ip,
	        'date'=>date('Y-m-d'),
	        'time_in'=>date('H:i:s'),
	        'remark'=>'Success']
	    );

	    $DmiUserLogs->save($DmiUserLog);

	    $user_data_query = $table->find('all', array('conditions'=> array('email IS' => $username)))->first();
	    $f_name = $user_data_query['f_name'];
	    $l_name = $user_data_query['l_name'];

	    //taking aadhar no. as default '000000000000', now no provosion to store aadhar no. updated on 15-06-2018 by Amol
	    $once_card_no = '000000000000';//$user_data_query[$table]['once_card_no'];
	    $division = $user_data_query['division'];
	    $role = $user_data_query['role'];

	    // taking user data in Session variables
	    $this->Session->write('userloggedin','yes');
	    $this->Session->write('username',$username);
	    $this->Session->write('once_card_no',$once_card_no);
	    $this->Session->write('last_login_time_value',time()); // Store the "login time" into Session for checking user activity time (Done by pravin 24/4/2018)
	    $this->Session->write('division',$division);
	    $this->Session->write('f_name',$f_name);
	    $this->Session->write('l_name',$l_name);
	    $this->Session->write('ip_address',$this->Controller->getRequest()->clientIp());
	    $this->Session->write('role',$role);
	    $this->Session->write('profile_pic',$user_data_query['profile_pic']); //added on 06-05-2021 for profile pic


	    // Check assigned roles for logged in user
	    $username = $this->Session->read('username');
	    $user_roles = $DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();

	    $this->Controller->redirect('/dashboard/home');

	}





	// Customer Proceed Login
	// Description : this function contains the login logic for Authorized  user & on for multiple logged in check security updates for customers
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : Akash Thakre (u) (m)
	// DATE : 25-06-2021

	public function customerProceedLogin($username,$table) {

		$DmiCustomerLogs = TableRegistry::getTableLocator()->get('DmiCustomerLogs');

		$this->Controller->getRequest()->getSession()->destroy();
	    Session_start();

		// Update status of browser login history, Done By Pravin Bhakare 12-11-2020 & added on 28-04-2021 by Akash
		$this->browserLoginStatus($username,'yes');

		$current_ip = $this->getController()->getRequest()->clientIp();

		if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

		$DmiCustomerLog = $DmiCustomerLogs->newEntity(['customer_id'=>$username,
		                                                'ip_address'=>$current_ip,
		                                                'date'=>date('Y-m-d'),
		                                                'time_in'=>date('H:i:s'),
		                                                'remark'=>'Success']);
		//updating customer successful logs
		$DmiCustomerLogs->save($DmiCustomerLog);

		$this->Session->write('username',$username);
		$this->Session->write('last_login_time_value',time()); // Store the "login time" into session for checking user activity time (Done by pravin 24/4/2018)
		$this->Session->write('ip_address',$this->getController()->getRequest()->clientIp());

		$customer_data_query = $table->find('all', array('conditions'=> array('customer_id IS' => $username)))->first()->toArray();

		$this->Session->write('profile_pic',$customer_data_query['profile_pic']); //added on 06-05-2021 for profile pic

		//$once_card_no = $customer_data_query[$table]['once_card_no']; //commented on 23-03-2018 to avoid mandatory for aadhar
		$once_card_no = null; //added on 23-03-2018 to avoid mandatory for aadhar
		$this->Session->write('once_card_no',$once_card_no);

		$countspecialchar = substr_count($username ,"/");

		$this->Session->write('userloggedin','yes');

		if ($countspecialchar == 1) {

		    $customer_f_name = $customer_data_query['f_name'];
		    $this->Session->write('f_name',$customer_f_name);

		    $customer_l_name = $customer_data_query['l_name'];
		    $this->Session->write('l_name',$customer_l_name);

		    $this->Controller->redirect(array('controller'=>'customers', 'action'=>'primary_home'));

		} elseif ($countspecialchar == 3) {

		    $firm_name = $customer_data_query['firm_name'];
		    $this->Session->write('firm_name',$firm_name);
		    $this->Controller->redirect(array('controller'=>'customers', 'action'=>'secondary_home'));
		}


	}





	// Customer Proceed Login
	// Description : // this function is created from the function created in customerscontroller "already_logged_in()"
					 // now the call from customercontroller through ajax call is depricated,
					 // as need to check after matching user details so now calling in login library functions after password and user matched.
	// @AUTHOR : Amol Chaudhari (c)
	// #CONTRIBUTER : Akash Thakre (u) (m)
	// DATE : 25-06-2021

	public function alreadyLoggedInCheck($userID) {


		$DmiLoginStatus = TableRegistry::getTableLocator()->get('DmiLoginStatuses');
		$result = null;
		$countspecialchar = substr_count($userID ,"/");

		if ($countspecialchar == 1) { $userType = 'dp'; }
		if ($countspecialchar == 2) { $userType = 'ch'; }
		if ($countspecialchar == 3) { $userType = 'df'; }
		if ($countspecialchar == 0) { $userType = 'du'; }


			$currLoggedin = $DmiLoginStatus->find('all',array('fields'=>array('curr_loggedin'),'conditions'=>array('user_id'=>$userID,'user_type'=>$userType),'order'=>'id'))->first();

			if (!empty($currLoggedin)) {
				$currLoggedinRes = $currLoggedin['curr_loggedin'];

				if ($currLoggedinRes == 'yes') {
					$result = 'yes';
				} else {
					$result = 'norecord';
				}

			} else {
				$result = 'norecord';
			}

		return  $result;

	}


}
?>
