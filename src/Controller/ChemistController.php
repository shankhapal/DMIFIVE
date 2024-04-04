<?php
namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class ChemistController extends AppController {

    public $name = 'Chemist';

    //to initialize our custom requirements
    public function initialize(): void {

        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Createcaptcha');
        $this->loadComponent('Authentication');
        $this->loadComponent('Customfunctions');
        $this->loadComponent('BiannualGrading'); /// for BGR module added by shankhpal on 20-02-2024

        $this->viewBuilder()->setHelpers(['Form','Html','Time']);

        $this->Session = $this->getRequest()->getSession();

        //Load Models
        $this->loadModel('DmiChemistRegistrations');
        $this->loadModel('DmiFirms');
        $this->loadModel('DmiChemistsResetpassKeys');
        $this->loadModel('DmiChemistLogs');
        $this->loadModel('DmiChemistProfileDetails');
        $this->loadModel('DmiChemistExperienceDetails');
        $this->loadModel('DmiChemistEducationDetails');
        $this->loadModel('DmiChemistTrainingDetails');
        $this->loadModel('DmiChemistFinalSubmits');
        $this->loadModel('DmiStates');
        $this->loadModel('DmiDistricts');
        $this->loadModel('DmiEducationTypes');
        $this->loadModel('DmiDivisionGrades');
        $this->loadModel('DmiChemistAllocations');
        $this->loadModel('DmiRoOffices');
        $this->loadModel('DmiChemistComments');
        $this->loadModel('DmiChemistAllotments');
        $this->loadModel('DmiChemistOtherDetails');
        $this->loadModel('DmiSmsEmailTemplates');


    }


    // BEFORE FILTER
    public function beforeFilter($event) {

        parent::beforeFilter($event);
        //To Find Customer Last Login
        $customer_last_login = $this->Customfunctions->customerLastLogin();
        $this->set('customer_last_login', $customer_last_login);
        // Change layout for Ajax requests
        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
        }

        // Checked final submit status, moved here on 10-08-2021 by Amol, for common use
        $chemist_id = $this->Session->read('username');
        $final_submit_record = $this->DmiChemistFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id),'order'=>'id desc'))->first();
        $final_status = '';

        if (!empty($final_submit_record)) {
            $final_status = $final_submit_record['status'];
        }

        $this->set('final_submit_status',$final_status);
    }



    //To create captcha code, called from component on 14-07-2017 by Amol
    public function createCaptcha() {

        $this->autoRender = false;
        $this->Createcaptcha->createCaptcha();
    }


    //To Refresh Captcha Code
    public function refreshCaptchaCode() {

        $this->autoRender = false;
        $this->Createcaptcha->refreshCaptchaCode();
        exit;
    }




    // VALID USER
    // @AUTHOR : PRAVIN BHAKARE
    // #CONTRIBUTER : AKASH THAKRE (Migration)

    public function validUser() {

        $application_dashboard = $this->Session->read('application_dashboard');

        if ($this->Session->read('username') == null) {
            $this->customAlertPage("Sorry You are not authorized to view this page..");
        } else {

            if ($application_dashboard == 'packer') {
                //checking applicant id pattern ex.102/1/PUN/006
                if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'),$matches) !=1) {
                    $this->customAlertPage("Sorry You are not authorized to view this page..");
                }
            }
        }


        if ($application_dashboard == 'packer') {

            $show_renewal_btn = $this->Customfunctions->checkApplicantValidForRenewal($this->Session->read('username'));
            $this->set('show_renewal_btn',$show_renewal_btn);

            //Find the value of "is_already_granted" flag status to redirect the application on appropriate new application or old application controller
            //Done by pravin 27-09-2017
            $is_already_granted = null;
            $get_is_already_granted = $this->DmiFirms->find('all', array('fields'=>'is_already_granted','conditions'=>array('customer_id IS'=>$this->Session->read('username'))))->first();

            if (!empty($get_is_already_granted)) {

                $is_already_granted = $get_is_already_granted['is_already_granted'];
            }

            $this->set('is_already_granted',$is_already_granted);
        }

    }



    // CHEMIST LOGIN
    // @AUTHOR : PRAVIN BHAKARE
    // #CONTRIBUTER : AKASH THAKRE (Migration)
    // DATE : 25-06-2021

    public function chemistLogin() {


        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';
        $login_result = '';
        $already_loggedin_msg = 'no';

        //Set Layout
        $this->viewBuilder()->setLayout('form_layout');

        if ($this->request->is('post')) {

            //check login lockout status, applied on 24-04-2018 by Amol
            $lockout_status = $this->Customfunctions->checkLoginLockout('DmiChemistLogs',$this->request->getData('chemist_id'));

            if ($lockout_status == 'yes') {

                $message = 'Sorry... Your account is disabled for today, on account of 3 login failure.';
                $message_theme = 'failed';
                $redirect_to = $this->getRequest()->getAttribute('webroot');

            } else {


                $countspecialchar = substr_count($this->request->getData('chemist_id') ,"/");

                if ($countspecialchar == 2) {

                    if (substr_count($this->request->getData('chemist_id') ,"/")!=0) {

                        $split_customer_id = explode('/',(string) $this->request->getData('chemist_id')); #For Deprecations

                        $randsalt = $this->Session->read('randSalt');
                        $captchacode1 = $this->Session->read('code');
                        $logindata = $this->request->getData();
                        $username = $this->request->getData('chemist_id');
                        $password = $this->request->getData('password');
                        $captcharequest = $this->request->getData('captcha');
                        $current_ip = $this->getRequest()->clientIp();

                        if ($current_ip == '::1') {

                            $current_ip = '127.0.0.1';
                        }

                        if ($this->request->getData('captcha') !="" && $this->Session->read('code') == $this->request->getData('captcha')) {

                            $PassFromdb = $this->DmiChemistRegistrations->find('all', array('conditions'=> array('chemist_id IS' => $username,'delete_status IS NULL')))->first();

                            $userType = 'chem';

                            if ($PassFromdb != null && $PassFromdb != '') {

                                $passarray1 = $PassFromdb;
                                $passarray2 = $passarray1['password'];
                                $emailforrecovery = $passarray1['email'];
                                $PassFromdbsalted = $randsalt . $passarray2; //adding random salt
                                $DbpasssaltedSHA512 = hash('sha512',$PassFromdbsalted); // Encripting

                                // check password to db password
                                if ($password == $DbpasssaltedSHA512 ) {

                                    $checkLog = $this->Authentication->alreadyLoggedInCheck($username);
                                    if ($checkLog == 'norecord') {

                                        //the logic from here is transffered to the function and called here
                                        //on 25-06-2021 by Amol
                                        $this->chemistLoginProced($username);

                                    } else {

                                        $_SESSION['username'] = $username;
                                        $_SESSION['userloggedin'] = 'no';
                                        $already_loggedin_msg = 'yes';
                                    }

                                } else {

                                    //Save Chemist Logs Failed Status
                                    $DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(
                                        ['customer_id'=>$username,
                                        'ip_address'=>$current_ip,
                                        'date'=>date('Y-m-d'),
                                        'time_in'=>date('H:i:s'),
                                        'remark'=>'Failed']
                                    );

                                    $this->DmiChemistLogs->save($DmiChemistLogsEntity);

                                    $login_result = 1;
                                }

                            } else {

                                $login_result = 2;
                            }

                        } else {

                            $login_result = 3;
                        }

                        // show user login failed messgae (by pravin 27/05/2017)
                        if ($login_result == 1) {

                            //this custom functionn is called on 08-04-2021, to show remaining login attempts
                            $remng_attempts_msg = $this->showRemainingLoginAttempts('DmiChemistLogs',$this->request->getData('chemist_id'));
                            $message = 'Username or password do not match. <br>'.$remng_attempts_msg;
                            $message_theme = 'failed';
                            $redirect_to = 'chemist_login';

                        } elseif ($login_result == 2) {

                            //this custom functionn is called on 08-04-2021, to show remaining login attempts
                            $remng_attempts_msg = $this->showRemainingLoginAttempts('DmiChemistLogs',$this->request->getData('chemist_id'));
                            $message = 'Username or password do not match. <br>'.$remng_attempts_msg;
                            $message_theme = 'info';
                            $redirect_to = 'chemist_login';

                        } elseif ($login_result == 3) {

                            $message = 'Sorry...Wrong Captcha Code Entered';
                            $message_theme = 'failed';
                            $redirect_to = 'chemist_login';
                        }

                    } else {

                        $message = 'Username or password do not match or your account is freezed';
                        $message_theme = 'failed';
                        $redirect_to = 'chemist_login';
                    }

                } else {

                    $message = 'Username or password do not match or your account is freezed';
                    $message_theme = 'failed';
                    $redirect_to = 'chemist_login';
                }
            }
        }

        // set variables to show popup messages from view file
        $this->set('already_loggedin_msg',$already_loggedin_msg);
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);

        if ($message != null) {
            $this->render('/element/message_boxes');
        }

    }



    // CHEMIST REGISTRATION
    // @AUTHOR : PRAVIN BHAKARE
    // #CONTRIBUTER : AKASH THAKRE (Migration)
    // DATE : 25-06-2021

    public function chemistRegistration() {

        $this->loadModel('MCommodityCategory');
        $this->loadModel('MCommodity');

        $this->Session->write('application_dashboard','packer');
        $this->validUser();
        //Set Varibles For Display
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        //Show button on Side menu
        $this->Beforepageload->showButtonOnSecondaryHome();

        //Set the Layout
        $this->viewBuilder()->setLayout('secondary_customer');

        $already_present = 'no';
        $present_email  = '';
        $present_mobile = '';

        // $commodity_categories = $this->MCommodityCategory->find('list',array('valueField'=>'category_name','conditions'=>array('display'=>'Y'),'order'=>array('category_name asc')))->toArray();
        //$this->set('commodity_categories',$commodity_categories);
        $username = $this->Session->read('username');

        $this->loadModel('DmiFirms');
        $fetchedFirmCategory= $this->DmiFirms->find('all' , array('conditions'=>['customer_id IS'=>$username]))->first();

        $sub_commodities = $fetchedFirmCategory['sub_commodity'];
        $sub_commodity_array = explode(',',$sub_commodities);

        $i=0;
        foreach ($sub_commodity_array as $sub_commodity_id)
        {
            $fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IS'=>$sub_commodity_id)))->first();
            $commodity_id[$i] = $fetch_commodity_id['category_code'];
            $sub_commodity_data[$i] =  $fetch_commodity_id;
            $i=$i+1;
        }

        $unique_commodity_id = array_unique($commodity_id);

        //$commodity_name_list = $this->MCommodityCategory->find('all',array('conditions'=>array('category_code IN'=>$unique_commodity_id)))->toArray();
        $commodity_categories = $this->MCommodityCategory->find('all')->select(['category_code', 'category_name'])->where(array('category_code IN'=>$unique_commodity_id))->combine('category_code', 'category_name')->toArray();
        $this->set('commodity_categories',$commodity_categories);
        $this->set('sub_commodity_data',$sub_commodity_data);

        if ($this->request->is('post')) {

            //applied condition to check all post data for !empty validation on server side //on 21/10/2017 by Amol
            if (!empty($this->request->getData('email')) && !empty($this->request->getData('mobile')) && !empty($this->request->getData('dob'))) {

                $usersData = $this->request->getData();

                $checkEmailExist =  $this->DmiChemistRegistrations->find('all', array('fields' => 'email', 'conditions' => array('email IS' => base64_encode($usersData['email']))))->first();
                $checkMobileExist =  $this->DmiChemistRegistrations->find('all', array('fields' => 'mobile', 'conditions' => array('mobile IS' => $usersData['mobile'])))->first();

                //below added for payment by laxmi 10-07-2023
                $post_subcommodities = $this->request->getData('selected_commodity');
                $i=0;
                foreach ($post_subcommodities as $sub_commodity_id)
                {
                    $fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IS'=>$sub_commodity_id)))->first();
                    $commodity_id[$i] = $fetch_commodity_id['category_code'];
                    $sub_commodity_data[$i] =  $fetch_commodity_id;
                    $i=$i+1;
                }

                $unique_commodity_id = array_unique($commodity_id);
                $count_subcommodities = count($unique_commodity_id);

                //added cond. on 20-10-2023 by Laxmi, to save payment only for chemist for training
                if(!empty($count_subcommodities)){
                    $payment_amnt = ($this->request->getData('is_training_completed') == 'no') ? (5000 * $count_subcommodities) : null;
                }

                $sub_commodities = $this->request->getData('selected_commodity');
                $subcsubcommoditities = implode(', ', $sub_commodities);
                // added selected group of commodities by laxmi on 28-07-2023
                $commodity = implode(', ', $unique_commodity_id);

                if (
                    $this->request->getData('chemist_fname') !="" &&
                    $this->request->getData('chemist_lname') !="" &&
                    $this->request->getData('email') !="" &&
                    $this->request->getData('mobile') !="" &&
                    $this->request->getData('dob') !=""
                ) {

                    if ($checkEmailExist == null) {

                        if ($checkMobileExist == null) {

                            $last_registered_record	= $this->DmiChemistRegistrations->find('all', array('fields'=>'chemist_id','order'=>'id desc'))->first();
                            if ($last_registered_record != null) {
                                $last_registered_id = substr($last_registered_record['chemist_id'],-4) + 1;
                                $chemist_id = 'CHM/'.date('y').'/'.$last_registered_id;
                            } else {
                                $chemist_id = 'CHM/'.date('y').'/1001';
                            }

                            //set email to add email id in success msg by laxmi B. on 30-05-23
                            $email = $this->request->getData('email');

                            $htmlEncoded_dob= htmlentities($this->Customfunctions->changeDateFormat($this->request->getData('dob')), ENT_QUOTES);
                            $htmlEncoded_email = htmlentities(base64_encode($this->request->getData('email')), ENT_QUOTES); //for email encoding
                            $htmlEncoded_mobile = htmlentities($this->request->getData('mobile'), ENT_QUOTES);
                            $htmlEncoded_chemistFirstname = htmlentities($this->request->getData('chemist_fname'), ENT_QUOTES);
                            $htmlEncoded_chemistLastname = htmlentities($this->request->getData('chemist_lname'), ENT_QUOTES);

                            $certificationType = explode('/',(string) $username); #For Deprecations
                            $DmiChemistRegistrationsEntity = $this->DmiChemistRegistrations->newEntity(array(
                                'chemist_fname'=>$htmlEncoded_chemistFirstname,
                                'chemist_lname'=>$htmlEncoded_chemistLastname,
                                'chemist_id'=>$chemist_id,
                                'email'=>$htmlEncoded_email,
                                'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
                                //'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', // 123
                                //chemist training alredy completed or not status added in db by laxmi bhadade on 12-12-22
                                'is_training_completed'=>$this->request->getData('is_training_completed'),
                                'mobile'=>base64_encode($htmlEncoded_mobile),
                                'dob'=>$htmlEncoded_dob,
                                'created_by'=>$username,
                                'usertype'=>$certificationType[1],
                                'created'=>date('Y-m-d H:i:s'),
                                'modified'=>date('Y-m-d H:i:s'),
                                'commodity'=>$commodity,
                                'sub_commodities'=>$subcsubcommoditities,
                                'payment'=>$payment_amnt,
                            ));


                            if ($this->DmiChemistRegistrations->save($DmiChemistRegistrationsEntity)) {

                                //Save Chemist Logs
                                $DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(array(

                                    'chemist_fname'=>$htmlEncoded_chemistFirstname,
                                    'chemist_lname'=>$htmlEncoded_chemistLastname,
                                    'chemist_id'=>$chemist_id,
                                    'email'=>$htmlEncoded_email,
                                    'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
                                    //'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', // 123
                                    'mobile'=>base64_encode($htmlEncoded_mobile),
                                    'dob'=>$htmlEncoded_dob,
                                    'created_by'=>$username,
                                    'usertype'=>$certificationType[1],
                                    //chemist training alredy completed or not status added in db by laxmi bhadade on 12-12-22
                                    'is_training_completed'=>$this->request->getData('is_training_completed'),
                                    'created'=>date('Y-m-d H:i:s'),
                                    'modified'=>date('Y-m-d H:i:s')
                                ));

                                $this->DmiChemistLogs->save($DmiChemistLogsEntity);

                                $this->set('new_customer_id',$chemist_id);
                                $this->set('htmlencodedemail',base64_decode($htmlEncoded_email)); //for email encoding
                                $this->set('htmlencodedchemist_fname',$htmlEncoded_chemistFirstname);
                                $this->set('htmlencodedchemist_lname',$htmlEncoded_chemistLastname);

                                //called function to send link for reset password on registered email//on 13-02-2018 by Amol
                                //In below condition the #Customer ID is passed to fetch the newly created Customer ID on Forgot Password - Akash[20-03-2023]
                                $this->Authentication->forgotPasswordLib('DmiChemistRegistrations', $htmlEncoded_email,$chemist_id);

                                //Save Chemist Allotment Entry
                                $DmiChemistAllotmentsEntity = $this->DmiChemistAllotments->newEntity(array(

                                    'chemist_id'=>$chemist_id,
                                    'customer_id'=>$username,
                                    'created_by'=>$username,
                                    'usertype'=>$certificationType[1],
                                    'status'=>1,
                                    'incharge'=>'no',
                                    'created'=> date('Y-m-d H:i:s'),
                                    'modified'=>date('Y-m-d H:i:s')
                                ));

                                $this->DmiChemistAllotments->save($DmiChemistAllotmentsEntity);

                                #SMS: Chemist Registration
                                $this->DmiSmsEmailTemplates->sendMessage(66,$chemist_id); #Packer
                                $this->DmiSmsEmailTemplates->sendMessage(67,$chemist_id); #Chemist

                                // added email id and change success pop-up msg by laxmi B on 30-05-2023
                                $message = 'You have registered Chemist <strong>"'.$htmlEncoded_chemistFirstname.' '.$htmlEncoded_chemistLastname.'"</strong> with chemist ID is <strong>"'.$chemist_id.'"</strong> .<br>An email has been sent to you and your chemist. to set your login password. <br> <strong>Now chemist need to login and complete profile verification.';
                                $message_theme = 'success';
                                $redirect_to = '../customers/secondary_home';

                            } else {
                                $message = 'Your chemist details are not saved please check again';
                                $message_theme = 'warning';
                                $redirect_to = 'chemist_registration';
                            }

                        } else {
                            $present_mobile = 'mobile number';
                            $already_present = 'yes';
                        }

                    } else {
                        $present_email = 'email id';
                        $already_present = 'yes';
                    }

                    if ($already_present == 'yes') {
                        $message = 'This '.$present_email.' '.$present_mobile.' is already registered with us.';
                        $message_theme = 'failed';
                        $redirect_to = '../customers/secondary_home';
                    }

                } else {
                    $message = 'Please enter all details. Do not leave any field empty!!';
                    $message_theme = 'warning';
                    $redirect_to = 'chemist_registration';
                }

            } else {
                $message = 'Please enter all details. Do not leave any field empty!!';
                $message_theme = 'warning';
                $redirect_to = 'chemist_registration';
            }
        }

        // set variables to show popup messages from view file
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);

        if ($message != null) {
            $this->render('/element/message_boxes');
        }
    }



    // RESET PASSWORD
    // @AUTHOR : PRAVIN BHAKARE
    // #CONTRIBUTER : AKASH THAKRE (Migration)
    // DATE : 25-06-2021

    public function resetPassword() {

        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        $this->viewBuilder()->setLayout('form_layout');
        if (empty($_GET['$key']) || empty($_GET['$id'])) {
            $this->customAlertPage("Sorry You are not authorized to view this page..");
        } else {

            $key_id = $_GET['$key'];
            // Added the urldecode funtion to fix the issue of +,<,# etc issue in gettin through get parameter
            // added on 26/11/2018

            $user_id = $this->Authentication->decrypt($_GET['$id']);
            $this->set('user_id',$user_id);

            $countspecialchar = substr_count($user_id ,"/");
            if ($countspecialchar != 2) {
                $this->customAlertPage("Sorry You are not authorized to view this page..");
            }

            //fetch applicant details
            $get_record_details = $this->DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id'=>$user_id)))->first();
            $record_id = $get_record_details['id'];

            //call function to check valid key
            $valid_key_result = $this->DmiChemistsResetpassKeys->checkValidKey($user_id,$key_id);
            if ($valid_key_result == 1) {

                if ($this->request->is('post')) {

                    $randsalt = $this->Session->read('randSalt');
                    $captchacode1 = $this->Session->read('code');
                    $postData = $this->request->getData();
                    $username = $this->request->getData('chemist_id');
                    $countspecialchar = substr_count($username ,"/");

                    if ($countspecialchar != 2) {
                        $user_id_not_valid_msg = 'This User Id is not valid';
                        $this->set('user_id_not_valid_msg',$user_id_not_valid_msg);
                        return null;
                    }

                    $newpassdata = $this->request->getData('new_password');
                    $confpassdata = $this->request->getData('confirm_password');
                    $reset_pass_result = $this->Authentication->resetPasswordLib('DmiChemistRegistrations',$username,$newpassdata,$randsalt,$postData);

                    if ($reset_pass_result == 1) {
                        $this->Customfunctions->saveActionPoint('Reset Password (Email Not Matched)','Failed',$user_id); #Action
                        $email_id_not_matched_msg = 'Email id & User Id not Matched.';
                        $this->set('email_id_not_matched_msg',$email_id_not_matched_msg);
                        return null;

                    } elseif ($reset_pass_result == 2) {

                        $this->Customfunctions->saveActionPoint('Reset Password (Incorrect Captcha)','Failed',$user_id); #Action
                        $incorrect_captcha_msg = 'Incorrect Captcha code entered.';
                        $this->set('incorrect_captcha_msg',$incorrect_captcha_msg);
                        return null;

                    } elseif ($reset_pass_result == 3) {

                        $this->Customfunctions->saveActionPoint('Reset Password (Password Not Macthed)','Failed',$user_id); #Action
                        $comfirm_pass_msg = 'Confirm password not matched';
                        $this->set('comfirm_pass_msg',$comfirm_pass_msg);
                        return null;

                    } elseif ($reset_pass_result == 4) {

                        $this->Customfunctions->saveActionPoint('Reset Password (Password is Same as Last)','Failed',$user_id); #Action
                        $comfirm_pass_msg = 'This password matched with your last three passwords, Please enter different password';
                        $this->set('comfirm_pass_msg',$comfirm_pass_msg);
                        return null;

                    } else {

                        $this->Customfunctions->saveActionPoint('Reset Password','Success',$user_id); #Action
                        //update link key table status to 1 for successfully
                        $this->DmiChemistsResetpassKeys->updateKeySuccess($user_id,$key_id);
                        $message = 'Password Changed Successfully';
                        $message_theme = 'success';
                        $redirect_to = '../../chemist/chemist_login';
                    }
                }

            } elseif ($valid_key_result == 2) {

                $message = 'Sorry.. This link to reset password is already used or expired. Please proceed through "Forgot Password" again.';
                $message_theme = 'failed';
                $redirect_to = '../../customers/forgot_password';
            }
        }

        // set variables to show popup messages from view file
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);
    }



    // HOME
    // @AUTHOR : PRAVIN BHAKARE
    // DATE : 25-06-2021

    public function home() {

        $this->viewBuilder()->setLayout('chemist_home_layout');
        $this->Session->write('application_dashboard','chemist');
        //chemist application pdf added on chemist dashboard by laxmi on 31-08-2023
        $this->loadModel('DmiChemistPdfRecords');
        $chemist_appl_pdf = $this->DmiChemistPdfRecords->find('list',array('valueField'=>'pdf_file', 'conditions'=>array('customer_id'=>$_SESSION['username'])))->last();
        if(!empty($chemist_appl_pdf)){
            $this->set('chemist_appl_pdf_file', $chemist_appl_pdf);
        }

        //chemist application shedule letter display in dashboard added by laxmi b.on 30-12-22
        $this->loadModel('DmiChemistRalToRoLogs');
        $scheduleLetter = $this->DmiChemistRalToRoLogs->find('list',array('valueField'=>'reshedule_pdf', 'conditions'=>array('chemist_id'=>$_SESSION['username'], 'training_completed IS'=>NULL, 'reshedule_status IS'=>'confirm')))->first();
        if(!empty($scheduleLetter)){
            $this->set('pdf_file', $scheduleLetter);
        }

        //chemist reliving  letter from RO display in dashboard added by laxmi b.on 03-01-2023
        $this->loadModel('DmiChemistTrainingAtRo');
        $relivingLetter = $this->DmiChemistTrainingAtRo->find('list',array('valueField'=>'pdf_file', 'conditions'=>array('chemist_id'=>$_SESSION['username'])))->first();
        if(!empty($relivingLetter)){
            $this->set('relivingLetter', $relivingLetter);
        }

        //grant certificate PDF display in dashboard added by laxmi b.on 05-01-2023
        $this->loadModel('DmiChemistGrantCertificatePdfs');
        $certificates = $this->DmiChemistGrantCertificatePdfs->find('all',array('fields'=>'pdf_file', 'conditions'=>array('customer_id'=>$_SESSION['username'])))->first();
        if(!empty($certificates)){
            $this->set('certificate', $certificates['pdf_file']);
        }

        //Ro side Training schedule letter added by laxmi on 6-01-2023
        $this->loadModel('DmiChemistRoToRalLogs');
        $ro_side_schedule_letter = $this->DmiChemistRoToRalLogs->find('all',array('fields'=>'ro_schedule_letter', 'conditions'=>array('chemist_id'=>$_SESSION['username'])))->first();
        if(!empty($ro_side_schedule_letter)){
            $this->set('ro_side_schedule_letter', $ro_side_schedule_letter['ro_schedule_letter']);
        }
        //Ral side Training letter added by laxmi on 6-01-2023
        $this->loadModel('DmiChemistRalToRoLogs');
        $ral_letter = $this->DmiChemistRalToRoLogs->find('all',array('fields'=>'pdf_file', 'conditions'=>array('chemist_id'=>$_SESSION['username'])))->first();
        if(!empty($ral_letter)){
            $this->set('ral_letter', $ral_letter['pdf_file']);
        }

        //check application yet to start with ddo or RO or not for withdraw application added by laxmi Bhadade

        //for payment confirm or not
        $this->loadModel('DmiChemistPaymentDetails');
        $chemist_id = $this->Session->read('username');
        $is_payment_confirm = $this->DmiChemistPaymentDetails->find('all', ['conditions'=>['customer_id IS'=>$chemist_id]])->last();

        if(!empty($is_payment_confirm)){
            $this->set('is_payment_confirm',$is_payment_confirm['payment_confirmation']);
        }


        // set application type in 4 for chemist
        if($this->Session->read('application_dashboard') =='chemist'){
            $application_type = 4;
        }

        //get packer id in session
        $this->loadModel('DmiChemistRegistrations');
        $chemist_reg = $this->DmiChemistRegistrations->find('all')->where(array('chemist_id IS' => $chemist_id ))->first();

        $this->Session->write('packer_id', $chemist_reg['created_by']);
        $packer_id =  $chemist_reg['created_by'];
        $this->Session->write('is_training_completed',$chemist_reg['is_training_completed']);
        $this->Session->write('user_email',$chemist_reg['email']);


        $office_type = 'RO';
        $form_type = 'CHM';
        $form_type = $this->Customfunctions->checkApplicantFormType($chemist_id, $application_type);
        $firm_type  =  $this->Customfunctions->firmType($chemist_id);


        // get all section all details
        $this->loadModel('DmiCommonScrutinyFlowDetails');
        $allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);


        // if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
        $all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$chemist_id);
        if(!empty($all_section_status)){
            $this->set('all_section_status',$all_section_status);
        }

        // check application is rejected
        $this->loadModel('DmiRejectedApplLogs');
        $rejectEntry = $this->DmiRejectedApplLogs->find('all')->where(['customer_id IS'=>$chemist_id])->first();

        $this->set('rejectEntry', $rejectEntry);

        // Query added by Shankhpal Shende to check whether the logged-in chemist is an in-charge.
        // If the chemist is an in-charge, the Bianually Grading Report menu will be displayed;
        // otherwise, it won't be displayed.
        // This comment is written on August 22, 2023.
        $chemist_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('chemist_id IS'=>$_SESSION['username'],'status'=>1,'incharge'=>'yes')))->first();

        $this->set('rejectEntry', $rejectEntry);
        $this->set('chemist_incharge', $chemist_incharge);
    }





    // REPLICA ALLOTED LIST
    // @AUTHOR : AKASH THAKRE
    // DATE : 25-06-2021

    public function replicaAllotedList() {

        $this->viewBuilder()->setLayout('chemist_home_layout');
        $this->Customfunctions->replicaAllotedListCall('replica');
    }


    // ALLOTED 15 DIGIT LIST
    // @AUTHOR : AKASH THAKRE
    // DATE : 25-06-2021

    public function alloted15DigitList() {

        $this->viewBuilder()->setLayout('chemist_home_layout');
        $this->Customfunctions->replicaAllotedListCall('15Digit');

    }


    // ALLOTED E CODE LIST
    // @AUTHOR : AKASH THAKRE
    // DATE : 25-06-2021


    public function allotedECodeList() {

        $this->viewBuilder()->setLayout('chemist_home_layout');
        $this->Customfunctions->replicaAllotedListCall('ECode');

    }



    // LOGOUT
    // @AUTHOR : AKASH THAKRE
    // DATE : 28-04-2021

    public function logout() {
        //LOAD MODEL
        $this->loadModel('DmiChemistLogs');
        $list_id = $this->DmiChemistLogs->find('list', array('valueField' => 'id', 'conditions' => array('customer_id IS' => $this->Session->read('username'))))->toArray();

        if (!empty($list_id)) {

            $fetch_last_id_query = $this->DmiChemistLogs->find('all', array('fields' => 'id', 'conditions' => array('id' => max($list_id), 'remark' => 'Success')))->first();
            $fetch_last_id = $fetch_last_id_query['id'];
            $DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(array('id' => $fetch_last_id,'time_out' => date('H:i:s')));
            $this->DmiChemistLogs->save($DmiChemistLogsEntity);

            // Update status of browser login history, Done By Pravin Bhakare 12-11-2020 & Added on 28-04-2021 by Akash
            $this->Authentication->browserLoginStatus($this->Session->read('username'),null);
            $this->Session->destroy();
            $this->redirect('/');

        } else {

            $this->customAlertPage("Sorry You are not authorized to view this page..");

        }

    }




    // CHEMIST LOGIN PROCED
    // @AUTHOR : PRAVIN BHAKARE
    // DATE : 28-04-2021

    public function chemistLoginProced($username) {



        $this->Session->destroy();// destroy old session data
        session_start();

        $current_ip = $this->getRequest()->clientIp();
        if ($current_ip == '::1')
        {
            $current_ip = '127.0.0.1';
        }

        $this->Authentication->browserLoginStatus($username,'yes');

        //updating customer successful logs
        $DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(
            ['customer_id'=>$username,
            'ip_address'=>$current_ip,
            'date'=>date('Y-m-d'),
            'time_in'=>date('H:i:s'),
            'remark'=>'Success']
        );

        $this->DmiChemistLogs->save($DmiChemistLogsEntity);

        $customer_data_query = $this->DmiChemistRegistrations->find('all', array('conditions'=> array('chemist_id IS' => $username)))->first();

        $this->Session->write('username',$username);
        $this->Session->write('last_login_time_value',time()); // Store the "login time" into session for checking user activity time (Done by pravin 24/4/2018)
        $this->Session->write('ip_address',$this->getRequest()->clientIp());


        $customer_f_name = $customer_data_query['chemist_fname'];
        $this->Session->write('f_name',$customer_f_name);

        $customer_l_name = $customer_data_query['chemist_lname'];
        $this->Session->write('l_name',$customer_l_name);

        $this->redirect(array('controller'=>'chemist', 'action'=>'home'));


    }


    // forward application from Ro to RAL added by laxmi B. on 21-12-2022
    public function forwardApplicationtoRal(){

        $message = "";
        $message_theme = "";
        $redirect_to = "";


        $username = $this->getRequest()->getSession()->read('username');
        if($username == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
        }else{
            $this->loadModel('DmiUsers');
            //check if user entry in Dmi_users table for valid user
            $check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();
            if(empty($check_user)){
                $this->customAlertPage("Sorry You are not authorized to view this page..");
            }
        }


        $this->viewBuilder()->setLayout('admin_dashboard');

        $ro_email = $this->Session->read('username');
        $ro_fname = $this->Session->read('f_name');
        $ro_lname = $this->Session->read('l_name');
        $ro_role  = $this->Session->read('role');
        $chemist_id = $this->Session->read('customer_id');

        //for information
        $this->set('ro_fname', $ro_fname);
        $this->set('ro_lname', $ro_lname);
        $this->set('ro_role', $ro_role);

        //for chemist information
        $this->loadModel('DmiChemistRegistrations');
        $this->loadComponent('Customfunctions');

        $chemist_details = $this->DmiChemistRegistrations->find('all')->where(array('chemist_id'=>$chemist_id))->first();
        $this->set('chemist_id', $chemist_id);

        if(!empty($chemist_details['chemist_fname'] && !empty($chemist_details['chemist_lname']))){
            $this->set('chemist_fname', $chemist_details['chemist_fname']);
            $this->set('chemist_lname', $chemist_details['chemist_lname']);
        }

        // for Ral Office and RAL information
        $this->loadModel('DmiRoOffices');
        $ral_details =$this->DmiRoOffices->find('all')->select(['id','ro_office'])->where(['office_type IS'=>'RAL', 'delete_status IS '=>NULL])->toArray();
        if(!empty($ral_details)){
            $this->set('ral_details',$ral_details);
        }

        $ro_office_id= $this->DmiRoOffices->find('all')->where(array('ro_email_id'=>$ro_email, 'or'=>array(['office_type IS'=>'RO'],['office_type IS'=>'SO'])))->first();
        // for export unit  condition added by laxmi on 9-1-23
        if(!empty($_SESSION['export_unit']) && $_SESSION['export_unit'] == 'yes'){
            $ral_details = $this->DmiRoOffices->find('all')->select(['id','ro_office'])->where(array('office_type IS'=>'RAL', 'ro_office IS'=> 'Mumbai', 'delete_status IS'=>NULL ))->toArray();

            if(!empty($ral_details)){
                $this->set('ral_details',$ral_details);
            }

            $ro_office_id= $this->DmiRoOffices->find('all')->where(array('ro_email_id'=>$ro_email, 'or'=>array(['office_type IS'=>'RO'],['office_type IS'=>'SO'])))->first();
            //for export unit is yes for chemist then application with only Mumbai office
            $export_unit = $this->Session->read('export_unit');
            if(!empty($export_unit) && $export_unit == 'yes'){
                $ro_office_id= $this->DmiRoOffices->find('all')->where(array('ro_email_id'=>$ro_email, 'ro_office IS'=>'Mumbai', 'or'=>array(['office_type IS'=>'RO'],['office_type IS'=>'SO'])))->first();
            }
        }



        if($this->request->is('post') != '' ){

            $document= $this->request->getData('document');
            $shedule_from = $this->request->getData('shedule_from');
            $from_date = date('Y-m-d H:i:s', strtotime(str_replace('/','-',$shedule_from)));
            $shedule_to = $this->request->getData('shedule_to');
            $to_date = date('Y-m-d H:i:s', strtotime(str_replace('/','-',$shedule_to)));

            if (!empty($this->request->getData('document')->getClientFilename())) {

                $attchment = $document;
                $file_name = $attchment->getClientFilename();
                $file_size = $attchment->getSize();
                $file_type = $attchment->getClientMediaType();
                $file_local_path = $attchment->getStream()->getMetadata('uri');
                // calling file uploading function

                $document = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path);

            }else{
                $document = "";
            }

            $postdata = $this->request->getData();

            if(!empty($postdata['ro_office']) && !empty($postdata['shedule_from'])  && !empty($postdata['shedule_to'])){


                $this->loadModel('DmiChemistRoToRalLogs');
                $chemistId = $postdata['chemist_id'];

                $data = $this->DmiChemistRoToRalLogs->newEntity(array(
                    'chemist_id' =>$postdata['chemist_id'],
                    'ro_first_name' =>$postdata['ro_first_name'],
                    'ro_last_name' => $postdata['ro_last_name'],
                    'chemist_first_name' => $postdata['chemist_first_name'],
                    'chemist_last_name' => $postdata['chemist_last_name'],
                    'ral_office_id' => $postdata['ro_office'],
                    'remark' => $postdata['remark'],
                    'document' => $document,
                    'is_forwordedtoral' => 'yes',
                    'created' => date('Y-m-d H:i:s'),
                    'appliaction_type'=> 4,
                    'shedule_from' => $from_date,
                    'shedule_to' => $to_date,
                    'ro_office_id' =>$ro_office_id['id'],
                ));

                if($this->DmiChemistRoToRalLogs->save($data)){

                    //to enter RAL Email id in allocation and current position table added by laxmi on 10-01-2023
                    $this->loadModel('DmiRoOffices');
                    $find_office_email_id = $this->DmiRoOffices->find('all',array( 'conditions'=>array('id'=>$data['ral_office_id'])))->first();
                    $office_incharge_id = $find_office_email_id['ro_email_id'];

                    //Entry in allocation table for level_3 Ro
                    $this->loadModel('DmiChemistAllocations');
                    $allocationEntity = $this->DmiChemistAllocations->newEntity(array(
                        'customer_id'=>$chemist_id,
                        'level_3'=>$office_incharge_id,
                        'current_level'=>$office_incharge_id,
                        'created'=>date('Y-m-d H:i:s'),
                        'modified'=>date('Y-m-d H:i:s')
                    ));

                    if($this->DmiChemistAllocations->save($allocationEntity)){

                        $this->loadModel('DmiChemistAllCurrentPositions');
                        //Entry in all applications current position table
                        $customer_id =  $chemist_id;
                        $user_email_id = $office_incharge_id;
                        $current_level = 'level_3';
                        $this->DmiChemistAllCurrentPositions->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model
                    }

                    $message ="Chemist Application Forwarded to RAL "  .$find_office_email_id['ro_office'].". And the email id is ".base64_decode($office_incharge_id)."  ";
                    $message_theme = "success";

                    // for rescheduling chemist training at RAL and generate letter pdf so comment this redirect url and redirect on  chemist module  by laxmi B. on 10-05-2023  for chemist modeule
                    //$redirect_to = '../applicationformspdfs/chemistAppPdfRoToRal/';

                    $redirect_to = '../chemist/listOfChemistApplRoToRal/';

                }else{

                    $message ="Something went wrong, Please Try Again!";
                    $message_theme = "warning";
                    $redirect_to = '../scrutiny/form-scrutiny';
                }

            }else{
                $message ="Please Enter all Field data";
                $message_theme = "warning";
                $redirect_to = '';
            }
        }

        // set variables to show popup messages from view file
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);

    }


    // List of chemist application  forwarded by ro to RAL added by laxmi on 26-12-2022
    public function listOfChemistApplRoToRal(){

        $username = $this->getRequest()->getSession()->read('username');
        if($username == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
        }else{
            $this->loadModel('DmiUsers');
            //check if user entry in Dmi_users table for valid user
            $check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();
            if(empty($check_user)){
                $this->customAlertPage("Sorry You are not authorized to view this page..");
            }
        }

        $this->loadModel('DmiChemistRoToRalLogs');
        $this->loadModel('DmiRoOffices');
        $this->loadModel('DmiChemistRegistrations');
        $this->loadModel('DmiChemistRalToRoLogs');
        $this->viewBuilder()->setLayout('admin_dashboard');

        $ro_email = $_SESSION['username'];
        $ro_office_ids = $this->DmiRoOffices->find('all',array('fields'=>['id', 'office_type'], 'conditions'=>array('ro_email_id IS'=>$ro_email)))->first();
        $ro_office_id = $ro_office_ids['id'];


        $export_unit = $this->Session->read('export_unit');
        if(!empty($export_unit) && $export_unit == 'yes'){
            $ro_office_ids = $this->DmiRoOffices->find('all',array('valueField'=>['id','office_type'], 'conditions'=>array('ro_email_id IS'=>$ro_email, 'ro_office IS'=>'Mumbai')))->first();
            $ro_office_id = $ro_office_ids['id'];
        }

        $this->set('office_type', $ro_office_ids['office_type']);

        $listofApp = $this->DmiChemistRoToRalLogs->find('all')->where(array('is_forwordedtoral IS NOT '=>NULL, 'ro_office_id IS'=>$ro_office_id))->order('created desc')->toArray();

        $i=0;
        $ral_offices= array();
        $chemistId= array();
        $ro_offices = array();
        $ral_schedule_pdf = array();

        if(!empty($listofApp)){

            $this->set('listOfChemistApp',$listofApp);

            foreach($listofApp as $list){
                $ral_result = $this->DmiRoOffices->find('all',array('fields'=>'ro_office', 'conditions'=>array('id IS'=>$list['ral_office_id'])))->first();
                $ral_offices[$i] = $ral_result['ro_office'];
                $chemistId[$i] = $this->DmiChemistRegistrations->find('all',array('fields'=>'id', 'conditions'=>array('chemist_id'=>$list['chemist_id'])))->first();

                $ro_officesId = $this->DmiRoOffices->find('all',array('fields'=>'ro_office', 'conditions'=>array('id IS'=>$list['ro_office_id'])))->first();
                if(!empty($ro_officesId)){
                    $ro_offices[$i]= $ro_officesId['ro_office'];
                }

                $ral_schedule = $this->DmiChemistRalToRoLogs->find('all')->where(array('chemist_id'=>$list['chemist_id'], 'reshedule_pdf IS NOT'=>NULL, 'reshedule_status'=>'confirm'))->last();
                if(!empty($ral_schedule)){$ral_schedule_pdf[$i] = $ral_schedule['reshedule_pdf'];}
                    $i= $i+1;
            }

            $this->set('ro_office', $ro_offices);
            $this->set('ral_offices',$ral_offices);
            $this->set('chemisttblId',$chemistId);
            $this->set('ral_schedule_pdf',$ral_schedule_pdf);
        }

    }



    //list of application forwarded back Ral to RO added by laxmi on 29/12/2022
    public function  listOfChemistApplRalToRo(){

        $username = $this->getRequest()->getSession()->read('username');
        if($username == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
        }
        else{
            $this->loadModel('DmiUsers');
            //check if user entry in Dmi_users table for valid user
            $check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();
            if(empty($check_user)){
                $this->customAlertPage("Sorry You are not authorized to view this page..");
            }
        }

        $this->viewBuilder()->setLayout('admin_dashboard');

        $this->loadModel('DmiChemistRalToRoLogs');
        $this->loadModel('DmiRoOffices');
        $this->loadModel('DmiChemistTrainingAtRo');
        $this->loadModel('DmiChemistRegistrations');
        $this->loadModel('DmiChemistAllCurrentPositions');
        $this->loadModel('DmiChemistRoToRalLogs');
        $this->loadModel('DmiRejectedApplLogs');
        $conn = ConnectionManager::get('default');

        $ro_email = $this->Session->read('username');

        $chemist_allocation  = $this->DmiChemistAllCurrentPositions->find('all',array('fields'=>array('current_level', 'current_user_email_id')))->where(array('current_user_email_id IS'=>$ro_email))->first();
        if(!empty($chemist_allocation)){
            $this->set('current_level', $chemist_allocation['current_level']);
            $this->Session->write('current_level', $chemist_allocation['current_level']);
        }

        $ro_office_data = $this->DmiRoOffices->find('all',array('fields'=>array('id', 'office_type','ro_office'), 'conditions'=>array('ro_email_id IS'=>$ro_email, 'ro_email_id'=>$chemist_allocation['current_user_email_id'])))->first();
        $export_unit = $this->Session->read('export_unit');
        if(!empty($export_unit) && $export_unit == 'yes'){
            $ro_office_data = $this->DmiRoOffices->find('all',array('fields'=>array('id', 'office_type','ro_office'), 'conditions'=>array('ro_email_id IS'=>$ro_email, 'ro_email_id'=>$chemist_allocation['current_user_email_id'],'ro_office'=>'Mumbai')))->first();
        }
        $this->set('level_3_for', $ro_office_data['office_type']);
        $this->Session->write('level_3_for', $ro_office_data['office_type']);

        $query = $conn->execute( "SELECT * FROM dmi_chemist_ral_to_ro_logs  WHERE chemist_id NOT IN (SELECT customer_id FROM dmi_rejected_appl_logs) AND ro_office_id = '".$ro_office_data['id']."' AND training_completed = '1' ORDER BY id DESC" );
        $listofApp = $query->fetchAll('assoc');

                    $i=0;
                    $ral_offices = array();
                    $isTainingCompleted = array();
                    $pdf_file = array();
                    $chemistTblid = array();
                    $is_trainingScheduleRO = array();
                    $ro_schedule_letter = array();
                    $reschedule_status =  array();
                    $appl_type = array();
                    $status  = array();

                if(!empty($listofApp)){
                    foreach($listofApp as $list){
                        $ral_offices[$i] = $this->DmiRoOffices->find('list',array('valueField'=>'ro_office', 'conditions'=>array('id IS'=>$list['ral_office_id'])))->first();

                        //training complete or not
                        $trainingComplete = $this->DmiChemistTrainingAtRo->find('all', array('fields'=>array('training_completed', 'pdf_file')))->where(array('chemist_id IS'=>$list['chemist_id']))->first();

                        if(!empty($trainingComplete)){
                        $isTainingCompleted[$i] = $trainingComplete['training_completed'];
                        $pdf_file[$i]  = $trainingComplete['pdf_file'];

                        }

                                $chemistTableid = $this->DmiChemistRegistrations->find('all',array('fields'=>['id', 'chemist_id'], 'conditions'=>array('chemist_id'=>$list['chemist_id'])))->first();
                            if(!empty($chemistTableid)){
                                $chemistTblid[$i] = $chemistTableid['id'];
                            }

                            //training schedule at ro side training
                            $ro_schedule_training = $this->DmiChemistRoToRalLogs->find('all', array('conditions'=>array('chemist_id'=>$list['chemist_id'])))->last();

                        if(!empty($ro_schedule_training)){
                            $is_trainingScheduleRO[$i] = $ro_schedule_training['is_training_scheduled_ro'];
                            $ro_schedule_letter[$i] = $ro_schedule_training['ro_schedule_letter'];
                            $reschedule_status[$i] = $ro_schedule_training['reshedule_status'];
                        }
                        if(!empty($list['appliaction_type'])){
                        $this->loadModel('DmiApplicationTypes');
                        $application_type = $this->DmiApplicationTypes->find('all',array( 'conditions'=>['id IS'=>$list['appliaction_type']]))->first();
                        $appl_type[$i] = $application_type['application_type'];
                        }

                        $this->loadModel('DmiChemistGrantCertificatePdfs');
                        $g_status = $this->DmiChemistGrantCertificatePdfs->find('all',array('fields'=>array('pdf_file'), 'conditions'=>['customer_id IS'=>$chemistTableid['chemist_id']]))->last();
                        if(!empty($g_status)){
                            $status[$i] =$g_status['pdf_file'];
                        }
                            $i= $i+1;
                    }

                        //check application is final granted


                        $this->set('grant_approval_pdf',$status);

                        $this->set('ro_schedule_letter',$ro_schedule_letter);
                        $this->set('is_trainingScheduleRO',$is_trainingScheduleRO);
                        $this->set('chemistTblid',$chemistTblid);
                        $this->set('listOfChemistApp',$listofApp);
                        $this->set('ral_office', $ral_offices);
                        $this->set('isTrainingComplete',$isTainingCompleted);
                        $this->set('pdf_file',$pdf_file);
                        $this->set('ro_offices',$ro_office_data['ro_office']);
                        $this->set('reschedule_status',$reschedule_status);
                        $this->set('appl_type', $appl_type);

                }

    }



    //List of chemist training application where training completed at ro office added by laxmi on 3/1/2023
    public function chemistTrainingCompleteAtRo ($id){
        //$username = $this->Session->read('username');
        $username = $this->getRequest()->getSession()->read('username');

        if($username == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
            exit();
        }else{
            $this->loadModel('DmiUsers');
            //check if user entry in Dmi_users table for valid user
            $check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();

            if(empty($check_user)){
                $this->customAlertPage("Sorry You are not authorized to view this page..");
                exit();
            }
        }
            $this->viewBuilder()->setLayout('admin_dashboard');
            $message="";
            $message_theme ="";
            $redirect_to   ="";


            $ro_fname = $this->Session->read('f_name');
            $ro_lname = $this->Session->read('l_name');
            $this->set('ro_fname',$ro_fname);
            $this->set('ro_last_name',$ro_lname);


            $this->loadModel('DmiChemistRalToRoLogs');
            $roToRalData = $this->DmiChemistRalToRoLogs->find('all')->where(array('id'=>$id))->first();
            if(!empty($roToRalData)){
                $this->set('chemist_fname',$roToRalData['chemist_first_name']);
                $this->set('chemist_lname',$roToRalData['chemist_last_name']);
                $this->set('chemist_id',$roToRalData['chemist_id']);
                $this->Session->write('customer_id', $roToRalData['chemist_id']);
                $ro_id = $roToRalData['ro_office_id'];
                $customer_id =$roToRalData['chemist_id'];
            }

            if($this->request->is('post') != ''){
                $postdata = $this->request->getData();

                if (!empty($postdata['document']->getClientFilename()) && !empty($postdata['document'])) {

                    $attchment = $postdata['document'];
                    $file_name = $attchment->getClientFilename();
                    $file_size = $attchment->getSize();
                    $file_type = $attchment->getClientMediaType();
                    $file_local_path = $attchment->getStream()->getMetadata('uri');
                    // calling file uploading function

                    $document = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path);
                }else{
                    $document = NULL;
                }


                if(!empty($postdata['training_completed'] && !empty($postdata['chemist_id']))){

                        $this->loadModel('DmiChemistTrainingAtRo');

                        $chemist_id = htmlentities($postdata['chemist_id'], ENT_QUOTES);
                        $chemist_first_name = htmlentities($postdata['chemist_first_name'], ENT_QUOTES);
                        $chemist_last_name = htmlentities($postdata['chemist_last_name'], ENT_QUOTES);
                        $remark = htmlentities($postdata['remark'], ENT_QUOTES);
                        $training_completed = htmlentities($postdata['training_completed'], ENT_QUOTES);
                        $appl_type = 4;

                        $data = $this->DmiChemistTrainingAtRo->newEntity(array(
                        'chemist_id' =>$chemist_id,
                        'chemist_fname' => $chemist_first_name,
                        'chemist_lname' => $chemist_last_name,
                        'remark' => $remark,
                        'document' => $document,
                        'training_completed' =>$training_completed,
                        'ro_office_id' =>$ro_id,
                        'appliaction_type'=> $appl_type,
                        'created' => date('Y-m-d H:i:s'),
                        ));

                        $result = $this->DmiChemistTrainingAtRo->save($data);
                    if($result){
                            $lastInsertedId = $result['id'];
                            $message ="Chemist Application Training done at " .$_SESSION['level_3_for']."";
                            $message_theme = "success";
                            $redirect_to = '../../Applicationformspdfs/chemistTrainingCompPdfRo/'.$lastInsertedId;
                    }else{

                        $message ="Something went wrong, Please Try Again!";
                        $message_theme = "warning";
                        $redirect_to = '../scrutiny/form-scrutiny';
                    }

                }else{
                    $message ="Please Enter all Field data";
                    $message_theme = "warning";
                    $redirect_to = '';
                }
            }
                // set variables to show popup messages from view file
                $this->set('message',$message);
                $this->set('message_theme',$message_theme);
                $this->set('redirect_to',$redirect_to);
    }

    // Training scheduled at Ro added by laxmi on 27-01-2023
    public function trainingScheduleAtRo($id=null){
        //$username = $this->Session->read('username');
        $username = $this->getRequest()->getSession()->read('username');

        if($username == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
            exit();
        }
        else{
            $this->loadModel('DmiUsers');
            //check if user entry in Dmi_users table for valid user
            $check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();

            if(empty($check_user)){
                $this->customAlertPage("Sorry You are not authorized to view this page..");
                exit();
            }
        }
            $message = "";
            $message_theme = "";
            $redirect_to = "";
            $this->viewBuilder()->setLayout('admin_dashboard');
            $ro_email = $this->Session->read('username');
            $ro_fname = $this->Session->read('f_name');
            $ro_lname = $this->Session->read('l_name');
            $ro_role  = $this->Session->read('role');
            //for information
            $this->set('ro_fname', $ro_fname);
            $this->set('ro_lname', $ro_lname);
            $this->set('ro_role', $ro_role);
            //for chemist information
            $this->loadModel('DmiChemistRegistrations');
            $this->loadModel('DmiChemistRalToRoLogs');

            $ralToRoDatas = $this->DmiChemistRalToRoLogs->find('all')->where(['id'=>$id])->last();
            $chemist_id =  $ralToRoDatas['chemist_id'];

            $this->Session->write('customer_id',$ralToRoDatas['chemist_id']);

            $chemist_details = $this->DmiChemistRegistrations->find('all')->where(array('chemist_id'=>$chemist_id))->first();
            $this->set('chemist_id', $chemist_id);

            $this->set('ral_reschedule_status', $ralToRoDatas['reshedule_status']);
            if(!empty($chemist_details['chemist_fname'] && !empty($chemist_details['chemist_lname']))){
                $this->set('chemist_fname', $chemist_details['chemist_fname']);
                $this->set('chemist_lname', $chemist_details['chemist_lname']);

            }

            $this->loadModel('DmiChemistRoToRalLogs');
            $check_reschedule = $this->DmiChemistRoToRalLogs->find('all', ['conditions'=>['chemist_id IS'=> $chemist_id]])->last();


            $this->set('ro_schedule_from',date('d/m/Y', strtotime(str_replace("/","-",$check_reschedule['ro_schedule_from']))));
            $this->set('ro_schedule_to', date('d/m/Y',strtotime(str_replace("/","-",$check_reschedule['ro_schedule_to']))));
            $this->set('reschedule_status', $check_reschedule['ro_reschedule_status']);

            $this->set('is_training_scheduled_ro', $check_reschedule['is_training_scheduled_ro']);

        if($this->request->is('post') != '' ){

                    $shedule_from = $this->request->getData('shedule_from');
                    $from_date = date('Y-m-d H:i:s', strtotime(str_replace('/','-',$shedule_from)));
                    $shedule_to = $this->request->getData('shedule_to');
                    $to_date = date('Y-m-d H:i:s', strtotime(str_replace('/','-',$shedule_to)));

                        $postdata = $this->request->getData();

                        if(!empty($postdata['shedule_from'])  && !empty($postdata['shedule_to'])){


                                $this->loadModel('DmiChemistRoToRalLogs');
                                $chemistId = $postdata['chemist_id'];
                            if(empty($check_reschedule['ro_reschedule_status']) && empty($check_reschedule['is_training_scheduled_ro'])) {

                                $data = array(
                                'ro_schedule_from' => $from_date,
                                'ro_schedule_to'=>$to_date,
                                'is_training_scheduled_ro' => 1,
                                'modified' => date('Y-m-d'),
                                );

                                $result = $this->DmiChemistRoToRalLogs->updateAll($data,array('chemist_id'=>$chemistId));

                                if($result){
                                    $message ="Chemist Training Schedule at "  .$_SESSION['level_3_for']. "";
                                    $message_theme = "success";

                                        //for reschedule dates at ro side comment this pdf genration redirection  and redirect on list by laxmi B. on  10-05-2023 for chemist training module
                                        //$redirect_to = '../../applicationformspdfs/trainingScheduleLetterFromRo/';

                                        $redirect_to = '../../chemist/listOfChemistApplRalToRo/';
                                }else{

                                    $message ="Something went wrong, Please Try Again!";
                                    $message_theme = "warning";
                                    $redirect_to = '../../chemist/listOfChemistApplRalToRo/';
                                }

                            }else{

                                $reqData = $this->request->getData();
                                $ro_office_id = $this->DmiRoOffices->find('all', ['conditions'=>['ro_email_id IS'=> $_SESSION['username'],'OR'=>array(['office_type IS'=>'RO'], ['office_type IS'=>'SO']), 'delete_status IS'=> NULL ]])->first();

                                $from = date('d-m-Y',strtotime(str_replace('/','-',$reqData['shedule_from'])));
                                $to   = date('d-m-Y',strtotime(str_replace('/','-',$reqData['shedule_to'])));


                                $this->loadModel('DmiChemistRoToRalLogs');
                                $rescheduleDateData = $this->DmiChemistRoToRalLogs->newEntity( array('chemist_id' => $reqData['chemist_id'],
                                'chemist_first_name' => $reqData['chemist_first_name'],
                                'chemist_last_name' => $reqData['chemist_last_name'],
                                'ro_first_name' => $reqData['ro_first_name'],
                                'ro_last_name' => $reqData['ro_last_name'],
                                'ro_schedule_from'=> date('Y-m-d H:i:s',strtotime(str_replace('/','-',$reqData['shedule_from']))),
                                'ro_schedule_to'=> date('Y-m-d H:i:s',strtotime(str_replace('/','-',$reqData['shedule_to']))),
                                'is_training_scheduled_ro'=> 1,
                                'created' => date('Y-m-d H:i:s'),
                                'reshedule_remark' =>$reqData['reshedule_remark'],
                                'modified' => date('Y-m-d H:i:s'),
                                'appliaction_type' =>4,
                                'reshedule_status' =>'confirm',
                                'ro_office_id' =>$check_reschedule['ro_office_id'],
                                ));

                                $result = $this->DmiChemistRoToRalLogs->save($rescheduleDateData);
                                if($result){
                                    $message ="Chemist Training Schedule Dates & Confirm at  " .$_SESSION['level_3_for']. "";
                                    $message_theme = "success";
                                    $redirect_to = '../../applicationformspdfs/trainingScheduleLetterFromRo/';
                                }else{

                                    $message ="Something went wrong, Please Try Again!";
                                    $message_theme = "warning";
                                    $redirect_to = '../../chemist/listOfChemistApplRalToRo/';
                                }

                            }

                        }else{
                                $message ="Please Enter all Field data";
                                $message_theme = "warning";
                                $redirect_to = '';
                        }
        }


            // set variables to show popup messages from view file
            $this->set('message',$message);
            $this->set('message_theme',$message_theme);
            $this->set('redirect_to',$redirect_to);

    }

    //For reject chemist application from ro side and save value in rejectedLogs table for chemist training module added by laxmi B. on 18-05-2023
    public function chemistApplicationReject(){

        $this->setLayout= false;
        $this->autoRender = false;


        if($this->request->is('post')){
        $reqData = $this->request->getData();
        $app_type = $reqData['appl_type'];
        $chemistId = $reqData['chemist_id'];
        $reason    = $reqData['remark'];
        $byuser    = $this->Session->read('username');

        $this->loadModel('DmiRejectedApplLogs');
        $this->loadModel('DmiApplicationTypes');

        $appl_type = $this->DmiApplicationTypes->find('all',array('fields'=>['id'], 'conditions'=>['application_type'=>$app_type]))->first();

        if($appl_type['id'] == 4) {

        $form_type='CHM';
        $appll_dashboard = $this->Session->read('application_dashboard');
        if(!empty($appll_dashboard) && $appll_dashboard == 'chemist'){
        $byuser = $this->Session->read('user_email');
        }

        }

        if(!empty($reason)){
        $DmiRejectedApplLogsEntity = $this->DmiRejectedApplLogs->newEntity(
        array(
        'appl_type'   => $appl_type['id'],
        'form_type'   => $form_type,
        'customer_id' => $chemistId,
        'by_user'     => $byuser,
        'remark'      => $reason,
        'created'     => date('Y-m-d H:i:s'),
        ));
        }else{
        $message = "Please enter all fields data.";
        }


        if($this->DmiRejectedApplLogs->save($DmiRejectedApplLogsEntity) ) {
        $message ="Application Rejected successfully.";
        }else{
        $message ="Something went wrong, Please try Again.";
        }

        }
    }


    //to ftech subcommodity after selected commodity category alery selected by CA added by laxmi 14-07-2023
    public function showCommodityDropdown(){
        $this->autoRender = false;
        $this->loadModel('MCommodity');
        $category_id = $_POST['commodity'];
        //fetch subcommodities first from firm table
        $this->loadModel('DmiFirms');
        $sub_commodities = $this->DmiFirms->find('all', array('fields'=>['sub_commodity'], 'conditions'=>['customer_id IS'=>$_SESSION['username']]))->first();
        $sub_commodities_array = explode(',',$sub_commodities['sub_commodity']);

        $commodities = $this->MCommodity->find('all', array('fields'=>array('commodity_code','commodity_name'), 'conditions'=>array('category_code IS'=>$category_id,'display'=>'Y', 'commodity_code IN'=>$sub_commodities_array),'order'=>array('commodity_name asc')))->toArray();
        ?>
                <option value=""><?php echo "Select Commodity";?></option>
        <?php foreach ($commodities as $commodity) { ?>
                <option value="<?php echo $commodity['commodity_code'];?>"><?php echo $commodity['commodity_name'];?></option>
        <?php }
        exit;
    }


    // This function is used to display the mapped CA dropdown.
    // Added by Shankhpal Shende on August 22, 2023.
    public function displayMappedCADropdown(){

        $message = "";
        $message_theme = "";
        $redirect_to = "";

        $customer_id = $_SESSION['packer_id'];
        $referBackApplication = [];
        $this->viewBuilder()->setLayout('chemist_home_layout');
        $this->loadModel('DmiBgrCommodityReports');
        $this->loadModel('DmiFirms'); // added by shankhpal for BGR module on 15/11/2023

        $alloted_chemist = $this->DmiChemistAllotments->find('all',array('keyField'=>'customer_id','valueField'=>'customer_id','conditions'=>array('chemist_id IS'=>$_SESSION['username'],'status'=>1,'incharge'=>'yes')))->toArray();

        $referBackApplications = [];
        $approvedApplications = [];
        if(!empty($alloted_chemist)){

            foreach($alloted_chemist as $eachCustomer){

                $customer_id = $eachCustomer['customer_id'];
                $referBackApplicationsForCustomer  = $this->DmiBgrCommodityReports->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'form_status'=>'referred_back')))->toArray();
                $approvedApplicationsForCustomer  = $this->DmiBgrCommodityReports->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'form_status'=>'approved')))->toArray();
                $approvedApplications = array_merge($approvedApplications, $approvedApplicationsForCustomer);
                $referBackApplications = array_merge($referBackApplications, $referBackApplicationsForCustomer);

            }



        }

        $this->set('approvedApplications', $approvedApplications);
        $this->set('referBackApplications', $referBackApplications);


        $ca_options = [];
        foreach ($alloted_chemist as $eachvalue) {
            $customer_id = $eachvalue['customer_id'];
            $firm_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
            if (!empty($firm_details)) {
                $customer_id = $firm_details[0]['customer_id'];
        $firm_name = $firm_details[0]['firm_name'];
        $ca_options[$customer_id] = $customer_id. " "."(".$firm_name.")";
        }
        }

        $this->set('alloted_chemist', $alloted_chemist);
        $this->set('ca_options', $ca_options);
        $finacialYears = $this->BiannualGrading->computeBiannualPeriod();
        $startDate = $finacialYears['startDate'];
        $endDate = $finacialYears['endDate'];

        $finacialYearsArray[$startDate . ' - ' . $endDate] = $startDate . ' - ' . $endDate;
        $this->set('finacialYearsArray',$finacialYearsArray);


        if (null!== ($this->request->getData('continue-btn'))) {

            $packerid = $_POST['packerid'];
            $this->Session->write('packerid',$packerid);
            $financialYear = $_POST['financialYear'];

            if(!empty($packerid) && !empty($financialYear)){

                $this->request->getSession()->write('packer_id',$packerid);
                $this->request->getSession()->write('financialYear',$financialYear);
                if(isset($_SESSION)){
                    $this->redirect(['controller' => 'application','action' => 'applicationType',11]);
                    $this->redirect(['controller' => 'application','action' => 'applicationForCertificate',11]);
                }

            }else{
                $message = "Please select both a financial year and a packer ID.";
                $message_theme = 'warning';
                $redirect_to = '../chemist/display-mapped-c-a-dropdown';
                $this->render('/element/message_boxes');
            }

        }


        $this->set('message_theme',$message_theme);
        $this->set('message',$message);
        $this->set('redirect_to',$redirect_to);

        if ($message != null) {
            $this->render('/element/message_boxes');
        }

    }


    //created new function to save chemist photo in db as preview  added by laxmi[08-09-2023]
    public function chemistPhotoPreview(){
        $this->autoRender = false;
        if(NULL != $this->request->getData()){
            if ($this->request->getData('file')->getClientFilename() != null) {

                $attachment = $this->request->getData('file');
                $file_name = $attachment->getClientFilename();
                $file_size = $attachment->getSize();
                $file_type = $attachment->getClientMediaType();
                $file_local_path = $attachment->getStream()->getMetadata('uri');

                $file = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
                $this->loadModel('DmiChemistProfilePhoto');
                $chemist_id = htmlentities($this->Session->read('username'), ENT_QUOTES);
                $data =  $this->DmiChemistProfilePhoto->newEntity(array(
                    'chemist_id' => $chemist_id,
                    'profile_photo' => $file,
                    'sign' => NULL,
                    'created' => date('Y-m-d H:i:s'),
                ));

                if(!empty($data)){
                    $result = $this->DmiChemistProfilePhoto->save($data);
                    if($result){

                    if(!empty($chemist_id)){
                        $chemist_temp_photo = $this->DmiChemistProfilePhoto->find('all')->where(array('chemist_id IS'=>$chemist_id))->last();
                        if(!empty($chemist_temp_photo)){
                            echo json_encode($chemist_temp_photo);exit;
                        }
                    }
                    }
                }
            }


        }

    }

    //created new function to save chemist signiture in db as preview  added by laxmi[08-09-2023]
    public function chemistSignPreview(){
        $this->autoRender = false;
        if(NULL != $this->request->getData()){
            if ($this->request->getData('file')->getClientFilename() != null) {

                $attachment = $this->request->getData('file');
                $file_name = $attachment->getClientFilename();
                $file_size = $attachment->getSize();
                $file_type = $attachment->getClientMediaType();
                $file_local_path = $attachment->getStream()->getMetadata('uri');

                $file = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
                $this->loadModel('DmiChemistProfilePhoto');
                $chemist_id = htmlentities($this->Session->read('username'), ENT_QUOTES);
                $data =  $this->DmiChemistProfilePhoto->newEntity(array(
                    'chemist_id' => $chemist_id,
                    'profile_photo' => NULL,
                    'sign' =>$file ,
                    'created' => date('Y-m-d H:i:s'),
                ));

                if(!empty($data)){
                    $result = $this->DmiChemistProfilePhoto->save($data);
                    if($result){

                    if(!empty($chemist_id)){
                        $chemist_temp_photo = $this->DmiChemistProfilePhoto->find('all')->where(array('chemist_id IS'=>$chemist_id))->last();
                        if(!empty($chemist_temp_photo)){
                            echo json_encode($chemist_temp_photo);exit;
                        }
                    }
                    }
                }
            }


        }

    }


    public function manual(){
        $this->viewBuilder()->setLayout('chemist_home_layout');

    }



}

?>
