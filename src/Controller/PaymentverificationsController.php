<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;

class PaymentverificationsController extends AppController{

	private $DmiSmsEmailTemplates;
    private $DmiGrantCertificatesPdfs;
    private $DmiAdvPaymentTransactions;
    private $DmiAdvPaymentDetails;
    private $DmiPaoDetails;
    private $DmiUsers;
    private $DmiApplWithRoMappings;
    private $DmiFirms;
    private $DmiFlowWiseTablesLists;
    private $DmiUserRoles;
    private $DmiChemistRegistrations;

	public function initialize(): void {

		parent::initialize();

		$this->loadComponent('Customfunctions');

		$this->viewBuilder()->setHelpers(['Form','Html','Time']);

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
        $this->DmiSmsEmailTemplates = $this->AqcmsWrapper->customeLoadModel('DmiSmsEmailTemplates');
		$this->DmiGrantCertificatesPdfs = $this->AqcmsWrapper->customeLoadModel('DmiGrantCertificatesPdfs');
        $this->DmiAdvPaymentTransactions = $this->AqcmsWrapper->customeLoadModel('DmiAdvPaymentTransactions');
		$this->DmiAdvPaymentDetails = $this->AqcmsWrapper->customeLoadModel('DmiAdvPaymentDetails');
        $this->DmiPaoDetails = $this->AqcmsWrapper->customeLoadModel('DmiPaoDetails');
        $this->DmiUsers = $this->AqcmsWrapper->customeLoadModel('DmiUsers');
        $this->DmiApplWithRoMappings = $this->AqcmsWrapper->customeLoadModel('DmiApplWithRoMappings');
        $this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
        $this->DmiFlowWiseTablesLists = $this->AqcmsWrapper->customeLoadModel('DmiFlowWiseTablesLists');
        $this->DmiUserRoles = $this->AqcmsWrapper->customeLoadModel('DmiUserRoles');
        $this->DmiChemistRegistrations = $this->AqcmsWrapper->customeLoadModel('DmiChemistRegistrations');
    }

	public function beforeFilter($event) {

		parent::beforeFilter($event);

		$this->viewBuilder()->setLayout('admin_dashboard');
		$this->viewBuilder()->setHelpers(['Form','Html']);

		if ($this->Session->read('username') == null) {

			$this->customAlertPage("Sorry You are not authorized to view this page..");
			exit();

		} else {
			//checkif user have HO level roles

			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('pao'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();

			if(empty($user_access)){

				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit;

			}
		}
	}


	public function home(){

		$this->viewBuilder()->setLayout('admin_dashboard');
	}


	public function renewal_home(){

		$this->viewBuilder()->setLayout('admin_dashboard');
	}


	public function inspectPaymentFetchId($id,$appl_type){

		$this->Session->write('application_type',$appl_type);
		$this->Session->write('payment_table_id',$id);
		$this->redirect('/paymentverifications/inspect-payment-modes');

	}



	public function inspectPaymentModes(){

		$customer_id = $this->Session->read('customer_id');
		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		//get table name from flow wise table
		$appl_type = $this->Session->read('application_type');
		$id = $this->Session->read('payment_table_id');
		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type)))->first();

		$table = $flow_wise_tables['payment'];
        $table = $this->AqcmsWrapper->customeLoadModel($table);
		$customer_id_result = $this->$table->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$id)))->first();

		if (!empty($customer_id_result)) {

			$customer_id = $customer_id_result['customer_id'];
			$this->Session->write('customer_id',$customer_id);
		}


		if (!empty($customer_id)) {

			$allocation_table = $flow_wise_tables['allocation'];
			$office_table = 'DmiRoOffices';
			$field_name = 'ro_email_id';
			$all_applications_current_position = $flow_wise_tables['appl_current_pos'];;
			$redirect_url = '../dashboard/home';

			$this->set('table',$table);

            $office_table = $this->AqcmsWrapper->customeLoadModel($office_table);
            $allocation_table = $this->AqcmsWrapper->customeLoadModel($allocation_table);
            $all_applications_current_position = $this->AqcmsWrapper->customeLoadModel($all_applications_current_position);

			$firm_name = $this->DmiFirms->find('all', array('fields'=>'firm_name', 'conditions' => array('customer_id IS'=>$customer_id)))->first();
			$this->set('firm_name',$firm_name);
			$this->set('customer_id',$customer_id);
			$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
			$district_code = $split_customer_id[2];	//updated on 20-08-2018 by amol


                //for export_unit yes then only application applying to RO And RAL mumbai added by laxmi B. ON 09-01-2023
             if(!empty($appl_type) && $appl_type == 4){

               $packer_data_id = $this->DmiChemistRegistrations->find('all',array('fields'=>'created_by', 'conditions'=>array('chemist_id IS'=>$customer_id)))->first();
                  //to set packer Id
               $this->Session->write('packer_id', $packer_data_id['created_by']);

                  // to set export unit
               $export_unit = $this->Customfunctions->checkApplicantExportUnit($packer_data_id['created_by']);
               $this->Session->write('export_unit',$export_unit);
               }//End by laxmi.

			//updated and added code to get Office table details from appl mapping Model
			$find_office_email_id = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
			$office_incharge_id = $find_office_email_id[$field_name];

			//if appl is for lab (domestic/export), No 'SO' involded as per new scenario
			//to check appl type and get RO in-charge id to allocate
			//applied on 21-09-2021 by Amol
			$firm_type = $this->Customfunctions->firmType($customer_id);

			if ($firm_type==3) {
				//get RO incharge id as per appln
				$office_incharge_id = $this->Customfunctions->getApplRegOfficeId($customer_id,$appl_type);
			}


			$payment_confirmation_query = $this->$table->find('all', array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();

			$verification_action_value = $payment_confirmation_query['payment_confirmation'];

			//added new code to check unique transaction id, if already used.
			//on 15-10-2019 by Amol, called function custom function.
			if ($verification_action_value != 'confirmed') {

				//get existed application details if transaction id already used.
				$trans_id = $payment_confirmation_query['transaction_id'];
				$existed_appl_details = $this->checkUniqueTransIdForPao($trans_id);
				$this->set('existed_appl_details',$existed_appl_details);
			}

			$payment_trasaction_date = explode(' ',(string) $payment_confirmation_query['transaction_date']); #For Deprecations

			$action_value =null;

			if($verification_action_value == 'replied' || $verification_action_value == 'not_confirmed') {

				$action_value = 1;

			} elseif ($verification_action_value == 'confirmed') {

				$action_value = 0;
			}

			$selected_pao_alias_name = $this->DmiPaoDetails->find('all',array('fields'=>'pao_alias_name','conditions'=>array('id IS'=>$payment_confirmation_query['pao_id'])))->first();
			$this->set('selected_pao_alias_name',$selected_pao_alias_name);
			$this->set('payment_confirmation_query',$payment_confirmation_query);
			$this->set('action_value',$action_value);
			$this->set('payment_trasaction_date',$payment_trasaction_date);

			// Fetch all referred back commment data
			$fetch_pao_referred_back = array();
			$fetch_pao_referred_back = $this->$table->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'payment_confirmation'=>'not_confirmed')))->toArray();

			$this->set('fetch_pao_referred_back',$fetch_pao_referred_back);

			//find PAO email id
			$pao_id = $this->$table->find('all', array('fields'=>'pao_id', 'conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$pao_user_id = $this->DmiPaoDetails->find('all',array('fields'=>'pao_user_id', 'conditions'=>array('id IS'=>$pao_id['pao_id'])))->first();
			$pao_user_email_id = $this->DmiUsers->find('all',array('fields'=>'email', 'conditions'=>array('id IS'=>$pao_user_id['pao_user_id'])))->first();

            //find  RO office by ro_email_id for pop-up msg by laxmi B [31-05-2023]
			  $ro_office = $this->$office_table->find('all', ['fields'=>array('ro_office'), 'conditions'=>array($field_name => $office_incharge_id)])->first();

			  // for export application and application type 4 only Mumbai Ro office include [laxmi 1-06-23]

             if(!empty($export_unit) && $export_unit == 'yes' && $appl_type == 4){
				$ro_office = $this->$office_table->find('all', ['fields'=>array('ro_office'), 'conditions'=>array($field_name => $office_incharge_id, 'ro_office'=>'Mumbai')])->first();
			 }


			// Save payment details by applicant
			if (null!==($this->request->getData('payment_verificatin_action'))) {

				$payment_verification_action = $this->request->getData('action');
				$reason_option_comment = $this->request->getData('reasone_list_comment');
				$reasone_comment = htmlentities($this->request->getData('reasone_comment'), ENT_QUOTES);
				$transaction_date = $this->Customfunctions->dateFormatCheck($payment_confirmation_query['transaction_date']);
				$created = $this->Customfunctions->dateFormatCheck($payment_confirmation_query['created']);

				if ($payment_verification_action == 1) {

					$paymentEntity = $this->$table->newEntity(array(
						'customer_id'=>$payment_confirmation_query['customer_id'],
						'once_no'=>$payment_confirmation_query['once_no'],
						'certificate_type'=>$payment_confirmation_query['certificate_type'],
						'amount_paid'=>$payment_confirmation_query['amount_paid'],
						'transaction_id'=>$payment_confirmation_query['transaction_id'],
						'transaction_date'=>$transaction_date,
						'payment_receipt_docs'=>$payment_confirmation_query['payment_receipt_docs'],
						'payment_confirmation'=>'not_confirmed',
						'pao_id'=>$payment_confirmation_query['pao_id'],
						'district_id'=>$payment_confirmation_query['district_id'],
						'bharatkosh_payment_done'=>$payment_confirmation_query['bharatkosh_payment_done'],
						'reason_option_comment'=>$reason_option_comment,
						'reason_comment'=>$reasone_comment,
						'created'=>$created,
						'modified'=>date('Y-m-d H:i:s')
					));

					if ($this->$table->save($paymentEntity)) {

						//Entry in all applications current position table
						$user_email_id = $pao_user_email_id['email'];
						$current_level = 'applicant';

						//below condition is added for advance payment in order to avoid errors
						if ($appl_type != 7) {
							$this->$all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model
						}

						#Action
						$this->Customfunctions->saveActionPoint('Payment Not Confirmed','Success');

						//below condition is added for advance payment for sms
						if ($appl_type == 7) {
							#SMS : Payment Not Confirmed
							$this->DmiSmsEmailTemplates->sendMessage(63,$customer_id); #Applicant
						}else{
							#SMS : Payment Not Confirmed
							$this->DmiSmsEmailTemplates->sendMessage(49,$customer_id); #Applicant
						}


						$message = 'Payment not confirmed and Referred Back to Applicant';
						$message_theme = 'success';
						$redirect_to = $redirect_url;
					}

				} elseif ($payment_verification_action == 0) {

					$paymentEntity = $this->$table->newEntity(array(

						'customer_id'=>$payment_confirmation_query['customer_id'],
						'once_no'=>$payment_confirmation_query['once_no'],
						'certificate_type'=>$payment_confirmation_query['certificate_type'],
						'amount_paid'=>$payment_confirmation_query['amount_paid'],
						'transaction_id'=>$payment_confirmation_query['transaction_id'],
						'transaction_date'=>$transaction_date,
						'payment_receipt_docs'=>$payment_confirmation_query['payment_receipt_docs'],
						'payment_confirmation'=>'confirmed',
						'pao_id'=>$payment_confirmation_query['pao_id'],
						'district_id'=>$payment_confirmation_query['district_id'],  // Save District id to find list District wise
						'bharatkosh_payment_done'=>$payment_confirmation_query['bharatkosh_payment_done'],
						'reason_option_comment'=>$reason_option_comment,
						'reason_comment'=>$reasone_comment,
						'created'=>$created,
						'modified'=>date('Y-m-d H:i:s')
					));


					if ($this->$table->save($paymentEntity)) {

						//below condition is added for advance paynet to redirect ans skip the allocation method on 30-09-2021
						if ($appl_type == 7) {

							$this->advancePaymentConfirm();

						} else {

							//Entry in allocation table for level_3 Ro
							$allocationEntity = $this->$allocation_table->newEntity(array(
								'customer_id'=>$customer_id,
								'level_3'=>$office_incharge_id,
								'current_level'=>$office_incharge_id,
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s')
							));

							if($this->$allocation_table->save($allocationEntity)){

								//Entry in all applications current position table
								$user_email_id = $office_incharge_id;
								$current_level = 'level_3';
								$this->$all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model
							}

						}



						//applied condition on 16-09-2021 by Amol, as per new order for renewal
						//for renewal this will call all grant process if payment confirmed by DDO
						if ($appl_type==2) {

							#SMS : Renewal Payement Confirmed
							//commented on 06-04-2023, and applied at last while redirected to grant certificates list
							//$this->DmiSmsEmailTemplates->sendMessage(51,$customer_id); #Applicant
							//$this->DmiSmsEmailTemplates->sendMessage(52,$customer_id); #RO

							//for temp period of time update the "confirmed" status to "pending" status again, till the grant table entry was not done successfully.
							//this record will be again updated to "confirmed" after entry in the grant table is successful.
							//the another updated query is added in "ApplicationFormpdfController" function "generateGrantCerticatePdf()" under pao & appl type 2 condition.
							//applied on 05-04-2023 by Amol, to avoid payment approval without grant table entry issue.
							$getLastIdRecord = $this->$table->find('all',array('fields'=>array('id','payment_confirmation'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
							if($getLastIdRecord['payment_confirmation']=='confirmed'){

								$this->$table->updateAll(array('payment_confirmation'=>"pending"),array('customer_id IS'=>$customer_id,'id IS'=>$getLastIdRecord['id']));

							}

							//commented on 06-04-2023, and applied at last while redirected to grant certificates list
							/*$this->Customfunctions->saveActionPoint('Renewal Payment Confirmed','Success'); #Action
							$message = 'As the payment confirmed, the application for renewal is granted and available RO/SO In-charge to digitally sign the certificate.';
							$message_theme = 'info';
							$redirect_to = '../inspections/finalGrantCall';*/
							$this->Redirect(array('controller'=>'inspections','action'=>'finalGrantCall'));

						} elseif ($appl_type==7) {

							#SMS : Advance Payement Confirmed
							$this->DmiSmsEmailTemplates->sendMessage(65,$customer_id); #Applicant,DDO,RO
							$this->Customfunctions->saveActionPoint('Payment Confirmed','Success'); #Action
							$message = 'Payment Confirmed Successfully';
							$message_theme = 'success';
							$redirect_to = $redirect_url;


						} else {

							#SMS : Payement Confirmed
							$this->DmiSmsEmailTemplates->sendMessage(51,$customer_id); #Applicant
							$this->DmiSmsEmailTemplates->sendMessage(52,$customer_id); #RO

							$this->Customfunctions->saveActionPoint('Payment Confirmed','Success'); #Action
							//added ro office and id whose forwarded application by laxmi
							$message = 'Payment Confirmed Successfully. And Now application in '.$ro_office['ro_office'].' Office with email id '.base64_decode($office_incharge_id).' ';
							$message_theme = 'success';
							$redirect_to = $redirect_url;
						}
					}
				}
			}

		} else {

			$message = '';
			$redirect_to = $redirect_url;
		}
		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);

	}



	//advancec payment confirm method
	public function advancePaymentConfirm(){

		$customer_id = $this->Session->read('customer_id');

		$currBalance = $this->DmiAdvPaymentTransactions->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		$lastTransId = $this->DmiAdvPaymentTransactions->find('all',array('order'=>array('id desc')))->first();

		$currBalanceAmt = 0;
		$transcationid = 'ADP/'.date('m').'/1000';

		if(!empty($currBalance)){

			$currBalanceAmt = $currBalance['balance_amount'];

			$explodeVal = explode('/',(string) $lastTransId['trans_id']); #For Deprecations
			$trans_id = $explodeVal[2]+1;
			$transcationid = 'ADP/'.date('m').'/'.$trans_id;
		}


		$addBalance = $this->DmiAdvPaymentDetails->find('all', array('fields'=>array('amount_paid'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

		$addBalanceAmt = $addBalance['amount_paid'];

		$finalBalAmt = $addBalanceAmt + $currBalanceAmt;

		$DmiAdvPaymentTransactionsEntity = $this->DmiAdvPaymentTransactions->newEntity(array(

			'customer_id'=>$customer_id,
			'payment_for'=>1,
			'trans_type'=>'credited',
			'trans_amount'=>$addBalanceAmt,
			'balance_amount'=>$finalBalAmt,
			'trans_id'=>$transcationid,
			'created'=>date('Y-m-d H:i:s')
		));

		if($this->DmiAdvPaymentTransactions->save($DmiAdvPaymentTransactionsEntity));

	}




	public function checkUniqueTransIdForPao($trans_id){

		$new_customer_id = $this->Session->read('customer_id');//currently applying applicant
		$allow_id = 'yes';

		//temp static array, will be replaced by query result in phase 2
		//$payment_tables_array = array('Dmi_applicant_payment_detail','Dmi_renewal_applicant_payment_detail');

		$existed_appl_details = null;
		$payment_tables_array = $this->DmiFlowWiseTablesLists->find('all',array('fields'=>'payment','conditions'=>array('application_type IN'=>array('1','2','3'),'payment IS NOT'=>null)))->toArray();

		foreach($payment_tables_array as $each_table){

			$each_table = $each_table['payment'];
            $each_table = $this->AqcmsWrapper->customeLoadModel($each_table);
			//check new app if trans id already exist
			$check_trans_id = $this->$each_table->find('all',array('conditions'=>array('transaction_id IS'=>$trans_id,'customer_id !='=>$new_customer_id),'order'=>'id desc'))->first();


			//for new
			if(!empty($check_trans_id)){

				$existed_customer_id = $check_trans_id['customer_id'];//applicant which already used this trans id.

				//old existed application details
				$existed_appl_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$existed_customer_id)))->first();
				break;
			}

		}


		return $existed_appl_details;
	}




}

?>
