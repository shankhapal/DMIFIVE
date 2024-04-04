<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;

class OthermodulesController extends AppController{

	private $Workflow;
    private $DmiAllApplicationsCurrentPositions;
    private $DmiFinalSubmits;
    private $DmiRenewalFinalSubmits;
    private $DmiWorkTransferLogs;
    private $DmiWorkTransferHoPermissions;
    private $DmiApplAddedForReEsigns;
    private $DmiReEsignGrantLogs;
    private $DmiRenewalAllCurrentPositions;
    private $DmiRenewalApplicantPaymentDetails;
    private $DmiRenewalApplicationsResetLogs;
    private $DmiRtiReportPdfRecords;
    private $DmiRtiAllocations;
    private $DmiRtiFinalReports;
    private $DmiRoutineInspectionPeriod;
    private $DmiFlowWiseTablesLists;
    private $DmiLaboratoryOtherDetails;
    private $DmiUserRoles;
    private $DmiApplWithRoMappings;
    private $DmiUpdateFirmDetails;
    private $DmiCustomers;
    private $MSampleAllocate;
    private $DmiBgrGrantCertificatePdfs;
    private $DmiRoOffices;
    private $DmiApplicationTypes;
    private $DmiStates;
    private $DmiDistricts;
    private $DmiFirms;
    private $DmiGrantCertificatesPdfs;
    private $DmiPaoDetails;
    private $DmiUsers;
    private $DmiRejectedApplLogs;

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



		$this->viewBuilder()->setHelpers(['Form','Html','Time']);
		$this->viewBuilder()->setLayout('admin_dashboard');
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

        $this->Workflow = $this->AqcmsWrapper->customeLoadModel('Workflow');
		$this->DmiAllApplicationsCurrentPositions = $this->AqcmsWrapper->customeLoadModel('DmiAllApplicationsCurrentPositions');
		$this->DmiFinalSubmits = $this->AqcmsWrapper->customeLoadModel('DmiFinalSubmits');
		$this->DmiRenewalFinalSubmits = $this->AqcmsWrapper->customeLoadModel('DmiRenewalFinalSubmits');
        $this->DmiWorkTransferLogs = $this->AqcmsWrapper->customeLoadModel('DmiWorkTransferLogs');
        $this->DmiWorkTransferHoPermissions = $this->AqcmsWrapper->customeLoadModel('DmiWorkTransferHoPermissions');
        $this->DmiApplAddedForReEsigns = $this->AqcmsWrapper->customeLoadModel('DmiApplAddedForReEsigns');
        $this->DmiReEsignGrantLogs = $this->AqcmsWrapper->customeLoadModel('DmiReEsignGrantLogs');
		$this->DmiRenewalAllCurrentPositions = $this->AqcmsWrapper->customeLoadModel('DmiRenewalAllCurrentPositions');
		$this->DmiRenewalApplicantPaymentDetails = $this->AqcmsWrapper->customeLoadModel('DmiRenewalApplicantPaymentDetails');
		$this->DmiRenewalApplicationsResetLogs = $this->AqcmsWrapper->customeLoadModel('DmiRenewalApplicationsResetLogs');
        $this->DmiRtiReportPdfRecords = $this->AqcmsWrapper->customeLoadModel('DmiRtiReportPdfRecords');
        $this->DmiRtiAllocations = $this->AqcmsWrapper->customeLoadModel('DmiRtiAllocations');
        $this->DmiRtiFinalReports = $this->AqcmsWrapper->customeLoadModel('DmiRtiFinalReports');
        $this->DmiRoutineInspectionPeriod = $this->AqcmsWrapper->customeLoadModel('DmiRoutineInspectionPeriod');
        $this->DmiFlowWiseTablesLists = $this->AqcmsWrapper->customeLoadModel('DmiFlowWiseTablesLists');
        $this->DmiLaboratoryOtherDetails = $this->AqcmsWrapper->customeLoadModel("DmiLaboratoryOtherDetails");
        $this->DmiUserRoles = $this->AqcmsWrapper->customeLoadModel("DmiUserRoles");
        $this->DmiApplWithRoMappings = $this->AqcmsWrapper->customeLoadModel("DmiApplWithRoMappings");
        $this->DmiUpdateFirmDetails = $this->AqcmsWrapper->customeLoadModel('DmiUpdateFirmDetails');
        $this->DmiCustomers = $this->AqcmsWrapper->customeLoadModel('DmiCustomers');
        $this->MSampleAllocate = $this->AqcmsWrapper->customeLoadModel('MSampleAllocate');
        $this->DmiBgrGrantCertificatePdfs = $this->AqcmsWrapper->customeLoadModel('DmiBgrGrantCertificatePdfs');
        $this->DmiRoOffices = $this->AqcmsWrapper->customeLoadModel('DmiRoOffices');
		$this->DmiApplicationTypes = $this->AqcmsWrapper->customeLoadModel('DmiApplicationTypes');
        $this->DmiStates = $this->AqcmsWrapper->customeLoadModel('DmiStates');
		$this->DmiDistricts = $this->AqcmsWrapper->customeLoadModel('DmiDistricts');
		$this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
		$this->DmiGrantCertificatesPdfs = $this->AqcmsWrapper->customeLoadModel('DmiGrantCertificatesPdfs');
		$this->DmiPaoDetails = $this->AqcmsWrapper->customeLoadModel('DmiPaoDetails');
		$this->DmiUsers = $this->AqcmsWrapper->customeLoadModel('DmiUsers');
        $this->DmiRejectedApplLogs = $this->AqcmsWrapper->customeLoadModel('DmiRejectedApplLogs');

    }

	//Before Filter
	public function beforeFilter($event) {

		parent::beforeFilter($event);


		$username = $this->getRequest()->getSession()->read('username');

		if ($username == null){
			$this->customAlertPage("Sorry You are not authorized to view this page..");
		} else {


			//Check User
			$check_user = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();

			if (empty($check_user)) {
				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit();
			}
		}
	}

/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

																/***###| RE-ESIGN MODULE|###***/

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ RE-ESIGN ]|
	//To RE-ESIGN the Renewal Granted PDFs
	public function reEsignModule(){

		//default dummy id in session to prevent customer id session warings.
		if ($this->Session->read('customer_id')==null) {

			$this->Session->write('customer_id','000/0/000/000');
		}

		$this->Session->write('current_level','level_3');
		//$this->Session->delete('re_esigning');
		//$this->Session->delete('reason_to_re_esign');

		//Check Application Already Re-Esigned
		$appl_re_esigned = $this->DmiReEsignGrantLogs->find('list',array('valueField'=>'customer_id','conditions'=>array('re_esigned_by IS'=>$this->Session->read('username'))))->toArray();

		//commented logic to view application only to whome, who granted last
		//on 07-04-2021 by Amol, suggested by Tarun Sir
		/*
		//Get Application Added by Admin From Master
		$this->LoadModel('DmiApplAddedForReEsigns');
		$added_appl = $this->DmiApplAddedForReEsigns->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array('action_status'=>'active')))->toArray();

		//Get Renewal Granted Applcation Conditionlly
		$this->loadModel('DmiGrantCertificatesPdfs');
		$appl_list = $this->DmiGrantCertificatesPdfs->find('list',array('keyField'=>'customer_id','valueField'=>'customer_id','conditions'=>array('user_email_id IS'=>$this->Session->read('username'),'customer_id IN'=>$added_appl)))->toArray();
		*/

		//As per new logic now appl will available to current RO/SO In-charge of that office
		//on 07-04-2021 by Amol, suggested by Tarun Sir
		//get office Short code for login user as Incharge
		$getOffCode = $this->DmiRoOffices->find('list',array('valueField'=>'short_code','conditions'=>array('ro_email_id IS'=>$this->Session->read('username'))))->toList();

		//get short code of the sub offices if current user is RO incharge
		$getRoOfficeAsIncharge = $this->DmiRoOffices->find('list',array('valueField'=>'id','conditions'=>array('ro_email_id IS'=>$this->Session->read('username'),'office_type IS'=>'RO')))->toArray();
		if(!empty($getRoOfficeAsIncharge)){
			//get all sub office where RO id for SO id is present
			$subOfficeShortCodes = $this->DmiRoOffices->find('list',array('valueField'=>'short_code','conditions'=>array('ro_id_for_so IN'=>$getRoOfficeAsIncharge,'office_type IS'=>'SO')))->toList();
			$getOffCode = array_merge($getOffCode,$subOfficeShortCodes);
		}
		//get application added by Admin from master
		$appl_list = array();
		foreach($getOffCode as $scode){
			$get_appl = $this->DmiApplAddedForReEsigns->find('list',array('keyField'=>'customer_id','valueField'=>'customer_id','conditions'=>array('customer_id LIKE'=>'%'.$scode.'%','action_status'=>'active','re_esign_status'=>'Pending')))->toArray();
			$appl_list = array_merge($appl_list,$get_appl);
		}

		//the below code and conditions updated on 04-07-2023 by Amol
		//If application is from SO jurisdiction check SO grant power else list appl in RO dashboard
		//else RO incharge will re-esign
		$tempAssignArr = $appl_list;
		$appl_list = array();
		foreach($tempAssignArr as $each){


			$customer_id = $each;
			$splitId = explode('/',(string) $customer_id);
			$curIncharge = $this->DmiRoOffices->find('all',array('fields'=>array('ro_email_id','office_type','ro_id_for_so'),'conditions'=>array('short_code IS'=>$splitId[2])))->first();

			//if application comes under SO jurisdiction, check SO grant power
			if($curIncharge['office_type']=='SO'){
				if($splitId[1] == 1){
					//if appl is CA BEVO
					$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
					if($form_type=='E'){
						//get RO incharge id
						$curIncharge = $this->DmiRoOffices->find('all',array('fields'=>array('ro_email_id'),'conditions'=>array('id IS'=>$curIncharge['ro_id_for_so'])))->first();
						if($curIncharge['ro_email_id']==$this->Session->read('username')){
							$appl_list[$customer_id]=$customer_id;
						}
					}else{
						//show to sub office
						$appl_list[$customer_id]=$customer_id;
					}

				}elseif($splitId[1] == 2){
					//check user SO incharge role
					$getRoles = $this->DmiUserRoles->find('all',array('fields'=>'so_grant_pp','conditions'=>array('user_email_id IS'=>$curIncharge['ro_email_id'])))->first();
					if($getRoles['so_grant_pp'] != 'yes'){
						//get RO incharge id
						$curIncharge = $this->DmiRoOffices->find('all',array('fields'=>array('ro_email_id'),'conditions'=>array('id IS'=>$curIncharge['ro_id_for_so'])))->first();
						if($curIncharge['ro_email_id']==$this->Session->read('username')){
							$appl_list[$customer_id]=$customer_id;
						}
					//else show appl to sub office
					}elseif($curIncharge['office_type']=='SO' && $curIncharge['ro_email_id']==$this->Session->read('username')){
						$appl_list[$customer_id]=$customer_id;
					}

				}elseif($splitId[1] == 3){
					//get RO incharge id
					$curIncharge = $this->DmiRoOffices->find('all',array('fields'=>array('ro_email_id'),'conditions'=>array('id IS'=>$curIncharge['ro_id_for_so'])))->first();
					if($curIncharge['ro_email_id']==$this->Session->read('username')){
						$appl_list[$customer_id]=$customer_id;
					}
				}
			//else if application comes under RO jurisdiction, no change
			}else{
				$appl_list[$customer_id]=$customer_id;
			}
		}

		$this->set('appl_list',$appl_list);
		$this->set('appl_re_esigned',$appl_re_esigned);


		if(null !== ($this->request->getData('cancel'))) {

			//deleteing all created sessions
			$this->Session->delete('customer_id');
			$this->Session->delete('current_level');
			$this->Session->delete('re_esigning');
			$this->Session->delete('reason_to_re_esign');
			$this->Session->delete('re_esign_grant_date');
			$this->Session->delete('pdf_file_name');

			$this->redirect(array('controller'=>'dashboard','action'=>'home'));
		}

	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ RE-ESIGN ]|
	//AJAX Function For Selecting Session IDs//
	Public function onSelectSetCustomerIdSession(){

		$customer_id = $_POST['appl_id'];
		$this->Session->write('customer_id',$customer_id);

		//to get file path
		//updated logic as per new order on 01-04-2021, 5 years validity for PP and Laboratory
		//as the module is to reesign renewal certificate only, So now need to re-esign the first grant also, if granted with 2 years of validity
		//but not the old first grant record
		//on 15-09-2021 by Amol

		//$get_file_path = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>array('pdf_file','date'),'conditions'=>array('pdf_version'=>'2','customer_id'=>$customer_id)))->first();

	$get_file_path = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,/*'user_email_id IS NOT'=>'old_application'*/),'order'=>'id desc'))->first();

		//updated above query, commented old appl. condition on 03-11-2023 by Amol
		//applied below new cond. on 03-11-2023 by Amol, to resign new certificate also
		if (!empty($get_file_path) && $get_file_path['user_email_id'] != 'old_application') {

			$file_path = $get_file_path['pdf_file'];

			$grant_date = chop($get_file_path['date'],'00:00:00');
			$valid_upto = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$grant_date);

			//returning file path and validity date both, to be used in view file
			echo $file_path.'@'.$valid_upto;
		}else{
			echo '';
		}

		exit;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ RE-ESIGN ]|
	//To Create re-esign Session when concent is checked
	Public function createReEsignSession(){

		$this->Session->write('re_esigning','yes');
		$this->Session->write('reason_to_re_esign',$_POST['reason_to_re_esign']);


		//get renewal grant date from DB to maintain on new certificate pdf
		//store in session and use while creating certificate pdf
		//updated logic as per new order on 01-04-2021, 5 years validity for PP and Laboratory
		//as the module is to reesign renewal certificate only, So now need to re-esign the first grant also, if granted with 2 years of validity
		//but not the old first grant record
		//on 15-09-2021 by Amol

		//$grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id'=>$this->Session->read('customer_id'),'pdf_version'=>'2')))->first();

	$grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$this->Session->read('customer_id'),/*'user_email_id IS NOT'=>'old_application'*/),'order'=>'id desc'))->first();
		//$grant_date = chop($grant_details['date'],'00:00:00');

		//updated above query, commented old appl. condition on 03-11-2023 by Amol
		//applied below new cond. on 03-11-2023 by Amol, to resign new certificate also
		if (!empty($grant_details) && $grant_details['user_email_id'] != 'old_application') {

			$grant_date = explode(' ',$grant_details['date']);
			$grant_date = $grant_date[0];
			$this->Session->write('re_esign_grant_date',$grant_date);

			//creating application type session
			$applicationType = 1;
			if ($grant_details['pdf_version'] > 1) {

				$applicationType = 2;
			}

			$this->Session->write('application_type',$applicationType);
		}

		exit;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ RE-ESIGN ]|
	//To Create Grant Certificate PDFs to ReESign
	/*	public function createGrantCertificatePdfToReEsign() {

		$customer_id = $this->Session->read('customer_id');
		$split_customer_id = explode('/',$customer_id);

		//$view = new View($this, false);
		//$view->layout = null;
		$all_data_pdf = $this->render($pdf_view_path);

		if ($split_customer_id[1] == 1) {

			$all_data_pdf = $view->render('/Applicationformspdfs/grant_ca_certificate_pdf');

		} elseif ($split_customer_id[1] == 2) {

			$all_data_pdf = $view->render('/Applicationformspdfs/grant_printing_certificate_pdf');

		} elseif ($split_customer_id[1] == 3) {

			$all_data_pdf = $view->render('/Applicationformspdfs/grant_laboratory_certificate_pdf');
		}

		//commented all mpdf code and used tcpdf functionality on 27-01-2020
		/*	$this->Mpdf->init();
			$stylesheet = file_get_contents('css/forms-style.css');
			//$this->Mpdf->WriteHTML($stylesheet,1);

			$this->Mpdf->ob_clean();
			$this->Mpdf->SetDisplayMode('fullpage');
			$this->Mpdf->WriteHTML($all_data_pdf);
		*/

		/*		$rearranged_id = 'G-'.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];

		//Check Applicant Last Record Version to Increment
		$this->loadModel('DmiGrantCertificatesPdfs');

		//updated logic as per new order on 01-04-2021, 5 years validity for PP and Laboratory
		//as the module is to reesign renewal certificate only, So now need to re-esign the first grant also, if granted with 2 years of validity
		//but not the old first grant record
		//on 15-09-2021 by Amol

		//$list_id = $this->DmiGrantCertificatesPdfs->find('list', array('fields'=>'id', 'conditions'=>array('customer_id'=>$customer_id)))->toArray();
		//For Version 2 Only
		//$current_pdf_version = 2;

		$grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'user_email_id !='=>'old_application'),'order'=>'id desc'))->first();
		$current_pdf_version = $grant_details['pdf_version'];

		//taking complete file name in session, which will be use in esign controller to esign the file.
		$this->Session->write('pdf_file_name',$rearranged_id.'('.$current_pdf_version.')'.'.pdf');

		$applicationPdf = new ApplicationformspdfsController();
		$applicationPdf->callTcpdf($all_data_pdf,'I',$customer_id,'re_esign');
		$applicationPdf->callTcpdf($all_data_pdf,'F',$customer_id,'re_esign');//on 27-01-2020 with save mode


	}*/

/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/


																/***###| WORK TRANSFER MODULE|###***/

	// created new function to list of applications fro specific user which restrict admin to deactivate the user.
	// This list will be available to RO/SO dashboard with option to get permission from HO to reallocate the work of any user under his office.
	// Created on 23-06-2021 by Amol
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	public function userWorkTransfer(){

		$message='';
		$redirect_to='';
		$workNotPendingMsg = '';

		//get RO/SO office id of current logged in RO/SO
		$ro_email_id = $this->Session->read('username');
		$get_office_id = $this->DmiRoOffices->find('list',array('keyField'=>'id','conditions'=>array('ro_email_id IS'=>$ro_email_id,'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();



		//get all users under the current logged in RO/SO
		$users_list = $this->DmiUsers->find('list',array('keyField'=>'email','valueField'=>'email','conditions'=>array('posted_ro_office IN'=>$get_office_id,'status'=>'active')))->toArray();
		//added below loop //for email encoding
			$newArray = array();
			foreach($users_list as $key => $emailId) {

				$newArray[$key] = base64_decode($emailId);
			}
			$users_list = $newArray;
		//till here
		$this->set('users_list',$users_list);

		//if RO/SO officer handles more than one office
		$req_by_office = implode(',',$get_office_id);

		if (null !== ($this->request->getData('get_details'))) {

			$postData = $this->request->getData();
			$user_email_id = $postData['users_list'];
			$inProgressWork = array();

			//get ho permission status
			$get_ho_perm_status = $this->DmiWorkTransferHoPermissions->find('all',array('fields'=>'status','conditions'=>array('req_by_office IN'=>$req_by_office,'req_by_user IS'=>$ro_email_id,'req_for_user IS'=>$user_email_id),'order'=>'id desc'))->first();

			if (!empty($get_ho_perm_status)) {

				$ho_perm_status = $get_ho_perm_status['status'];

			} else {

				$ho_perm_status = '';
			}


			//get scrutiny officers list
			$scrutiny_officers = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('OR'=>array('mo_smo_inspection'=>'yes','ho_mo_smo'=>'yes'))))->toArray();
			//added below loop //for email encoding
				$newArray = array();
				foreach($scrutiny_officers as $key => $emailId) {
					$newArray[$key] = base64_decode($emailId);
				}
				$scrutiny_officers = $newArray;
			//till here

			//added below loop //for email encoding
				$new_users_list = array();
				foreach($users_list as $key => $emailId) {
					$new_users_list[$key] = base64_encode($emailId);
				}
			//till here

			//get Inspection officers list
			$inspection_officers = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('user_email_id IN'=>$new_users_list,'io_inspection'=>'yes')))->toArray();
			//added below loop //for email encoding
			$newArray = array();
				foreach($inspection_officers as $key => $emailId) {
					$newArray[$key] = base64_decode($emailId);
				}
				$inspection_officers = $newArray;
			//till here

			$applTypeArray = $this->Session->read('applTypeArray');
			//Index 1, Now Renewal application will not list except DDO dashboard, any where in list. on 20-10-2022
			unset($applTypeArray['1']);

			//get flow wise tables
			$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$applTypeArray),'order'=>'id ASC'))->toArray();

			$i=0;
			foreach($flow_wise_tables as $each_flow){

				$appl_type_id = $each_flow['application_type'];
				$DmiAllocations = $each_flow['allocation'];
				$DmiHoLevelAllocations = $each_flow['ho_level_allocation'];
				$DmiFinalSubmits = $each_flow['application_form'];

                $DmiAllocations = $this->AqcmsWrapper->customeLoadModel($DmiAllocations);
                $DmiHoLevelAllocations = $this->AqcmsWrapper->customeLoadModel($DmiHoLevelAllocations);
				$DmiFinalSubmits = $this->AqcmsWrapper->customeLoadModel($DmiFinalSubmits);

				//get application type name
				$get_appl_type = $this->DmiApplicationTypes->find('all',array('fields'=>'application_type','conditions'=>array('id'=>$appl_type_id)))->first();
				$appl_type = $get_appl_type['application_type'];

				//check new application allocation
				$find_first_allocation = $this->$DmiAllocations->find('all',array('conditions'=>array('OR'=>array('level_1'=>$user_email_id, 'level_2'=>$user_email_id, 'level_3'=>$user_email_id))))->toArray();

				if (!empty($find_first_allocation)) {

					foreach ($find_first_allocation as $each_allocation) {

						$customer_id = $each_allocation['customer_id'];

						//updated on 23-06-2021, check for scrutiny with approved level 1
						$check_scrutiny_status = $this->$DmiFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'approved','current_level'=>'level_1'),'order'=>'id desc'))->first();

						if (empty($check_scrutiny_status)) {
							//check from which the appl to be released
							//for scrutiny
							if($each_allocation['level_1'] == $user_email_id){

								//This below condtion is added to check the apploication is rejected if it is rejected then exlucde it from the array - Akash Thakre [06-09-2023]
								$is_application_rejected = $this->DmiRejectedApplLogs->find('all')->select(['customer_id'])->where(['customer_id IS ' => $customer_id,'appl_type' => $appl_type_id])->order('id DESC')->first();
								if (empty($is_application_rejected)) {

									$inProgressWork[$i]['rels_from'] = 'Scrutiny Allocation';
									$inProgressWork[$i]['appl_type'] = $appl_type;
									$inProgressWork[$i]['appl_id'] = $customer_id;

									$i=$i+1;
								}
							}
						}


						//updated on 23-06-2021, check for inspection with approved level 3
						$check_inspection_status = $this->$DmiFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'approved','current_level'=>'level_3'),'order'=>'id desc'))->first();

						if(empty($check_inspection_status)) {
							//for Inspection
							if ($each_allocation['level_2'] == $user_email_id) {

								//This below condtion is added to check the apploication is rejected if it is rejected then exlucde it from the array - Akash Thakre [06-09-2023]
								$is_application_rejected = $this->DmiRejectedApplLogs->find('all')->select(['customer_id'])->where(['customer_id IS ' => $customer_id,'appl_type' => $appl_type_id])->order('id DESC')->first();
								if (empty($is_application_rejected)) {

									$inProgressWork[$i]['rels_from'] = 'Inspection Allocation';
									$inProgressWork[$i]['appl_type'] = $appl_type;
									$inProgressWork[$i]['appl_id'] = $customer_id;

									$i=$i+1;
								}
							}
						}
					}
				}

				//Check HO level application allocation
				$find_ho_allocation = $this->$DmiHoLevelAllocations->find('all',array('conditions'=>array('ho_mo_smo'=>$user_email_id)))->toArray();

				if (!empty($find_ho_allocation)) {

					foreach ($find_ho_allocation as $each_allocation) {

						$customer_id = $each_allocation['customer_id'];

						//check in new flow
						$check_new_status = $this->$DmiFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'approved','current_level'=>'level_3'),'order'=>'id desc'))->first();

						//if pending in any flow
						if (empty($check_new_status)) {

							//This below condtion is added to check the apploication is rejected if it is rejected then exlucde it from the array - Akash Thakre [06-09-2023]
							$is_application_rejected = $this->DmiRejectedApplLogs->find('all')->select(['customer_id'])->where(['customer_id IS ' => $customer_id,'appl_type' => $appl_type_id])->order('id DESC')->first();
							if (empty($is_application_rejected)) {

								$inProgressWork[$i]['rels_from'] = 'Scrutiny Allocation(HO)';
								$inProgressWork[$i]['appl_type'] = $appl_type;
								$inProgressWork[$i]['appl_id'] = $customer_id;

								$i=$i+1;
							}
						}
					}
				}
			}

			if (empty($inProgressWork)) {

				//get user table id
				$get_id = $this->DmiUsers->find('all',array('fields'=>'id','conditions'=>array('email IS'=>$user_email_id)))->first();

				$limsWork = $this->getLimsUserWiseSamplesInProgress($get_id['id']);

				if ($limsWork == false) { //for email encoding
					$workNotPendingMsg = '<p class="alert alert-primary middle">No work is pending with <span class="badge badge-info">'.base64_decode($user_email_id).'</span> in DMI module as <b>Scrutiny/Inspection Officer</b>, You can proceed to deactivate this user.</p>';
				}else{
					$workNotPendingMsg = '<p class="alert alert-primary middle">No work is pending with <span class="badge badge-info">'.base64_decode($user_email_id).'</span> in DMI module as <b>Scrutiny/Inspection Officer</b>,<br>But some work pending in <b>LIMS</b> module. Please contact Admin to Transfer LIMS work from <b>User Work Transfer</b> option in LIMS.</p>';
				}
			}

			$this->set(compact('users_list','inProgressWork','scrutiny_officers','inspection_officers','get_ho_perm_status','ho_perm_status'));
		}

		$this->set('workNotPendingMsg',$workNotPendingMsg);

		if (null !== ($this->request->getData('get_ho_permission'))){

			$postData = $this->request->getData();
			$user_email_id = $postData['users_list'];


			$DmiWorkTranferEntity = $this->DmiWorkTransferHoPermissions->newEntity(array(

				'req_by_office'=>$req_by_office,
				'req_by_user'=>$ro_email_id,
				'req_for_user'=>$user_email_id,
				'status'=>'Requested',
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s'),
			));

			//save request record in ho permission table
			if ($this->DmiWorkTransferHoPermissions->save($DmiWorkTranferEntity)) { //for email encoding

				$message = 'The request for HO(QC) permisssion to transfer work of user id "'.base64_decode($user_email_id).'" has been made successfully. <br><br> Once HO(QC) permits the request, you will be able to transfer the work to another users. Thank you';
				$redirect_to = 'user_work_transfer';
			}
		}

		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);

	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	//to reallocate /transfer the selected application when action button hits by RO/SO user
	//on 23-06-2021 by Amol
	public function transferWork(){

		$this->autoRender = false;
		//get ajax post data
		$for_user_id = $_POST['for_user_id'];
		$appl_type = $_POST['appl_type'];
		$appl_id = $_POST['appl_id'];
		$rels_from = $_POST['rels_from'];
		$allocate_to = $_POST['allocate_to'];

		$ro_email_id = $this->Session->read('username');
		$get_office_id = $this->DmiRoOffices->find('list',array('keyField'=>'id','conditions'=>array('ro_email_id IS'=>$ro_email_id,'delete_status is NULL')))->toArray();

		//if RO/SO officer handles more than one office
		$by_office = implode(',',$get_office_id);

		//to replace the allocation conditionally

		//get application type from name
		$get_appl_type = $this->DmiApplicationTypes->find('all',array('fields'=>'id','conditions'=>array('application_type IS'=>$appl_type)))->first();
		$appl_type_id = $get_appl_type['id'];

		//get flow wise tables
		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id),'order'=>'id ASC'))->first();

		$DmiAllocations = $flow_wise_tables['allocation'];
		$DmiHoLevelAllocations = $flow_wise_tables['ho_level_allocation'];

        $DmiAllocations = $this->AqcmsWrapper->customeLoadModel($DmiAllocations);
        $DmiHoLevelAllocations = $this->AqcmsWrapper->customeLoadModel($DmiHoLevelAllocations);


		//if HO allocation need to change
		if ($rels_from == 'Scrutiny Allocation(HO)') {

			$allocation_table = $DmiHoLevelAllocations;
			$level_to_update = 'ho_mo_smo';

		} else {// else other allocation tables

			$allocation_table = $DmiAllocations;

			if ($rels_from == 'Scrutiny Allocation') {

				$level_to_update = 'level_1';

			} elseif ($rels_from == 'Inspection Allocation') {

				$level_to_update = 'level_2';
			}
		}

		//get latest record id of allocation to update
        $allocation_table = $this->AqcmsWrapper->customeLoadModel($allocation_table);
		$get_last_id = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$appl_id,$level_to_update=>$for_user_id),'order'=>'id desc'))->first();
		//update the allocation record
		$mod_date = date('Y-m-d H:i:s');

		//if current level also contain same user id
		if($get_last_id['current_level'] == $for_user_id){
			$this->$allocation_table->updateAll(array($level_to_update=>"$allocate_to",'modified'=>"$mod_date",'current_level IS'=>"$allocate_to"),array('id'=>$get_last_id['id']));

		}else{
			$this->$allocation_table->updateAll(array($level_to_update=>"$allocate_to",'modified'=>"$mod_date"),array('id IS'=>$get_last_id['id']));

		}

		//to save application transfer logs
		$DmiWorkTranferLogEntity = $this->DmiWorkTransferLogs->newEntity(array(

			'customer_id'=>$appl_id,
			'by_office'=>$by_office,
			'by_user'=>$ro_email_id,
			'from_stage'=>$rels_from,
			'from_user'=>$for_user_id,
			'to_user'=>$allocate_to,
			'appl_type'=>$appl_type,
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s'),
		));


		if ($this->DmiWorkTransferLogs->save($DmiWorkTranferLogEntity)) {

			echo '~done~';
		}else{
			echo '~error~';
		}
		exit;
	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	//to display work transfer request on HO dashboard in work transfer request window
	//added menu on left side for this, //on 23-06-2021 by Amol
	public function workTransferRequests(){

		$this->viewBuilder()->setLayout('admin_dashboard');
		$allRequests = $this->DmiWorkTransferHoPermissions->find('all',array('order'=>'id desc'))->toArray();

		//get office name from office id
		$i=1;
		$office_name = array();

		foreach ($allRequests as $each) {

			$office_id = explode(',',(string) $each['req_by_office']); #For Deprecations
			$get_office_name = '';

			foreach ($office_id as $each_office) {

				$office_details = $this->DmiRoOffices->find('all',array('fields'=>'ro_office','conditions'=>array('id'=>$each_office)))->first();
				$get_office_name .= $office_details['ro_office'].', ';
			}

			$office_name[$i] = $get_office_name;
			$i=$i+1;
		}

		$this->set(compact('allRequests','office_name'));

	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	//to update the status as Permitted when HO click for permission
	//on 23-06-2021 by Amol
	public function hoPermittedForTransfer(){

		$this->autoRender = false;
		//get ajax post data
		$req_by_user = base64_encode($_POST['req_by_user']); //for email encoding
		$req_for_user = base64_encode($_POST['req_for_user']); //for email encoding
		$mod_date = date('Y-m-d H:i:s');

		$this->DmiWorkTransferHoPermissions->updateAll(array('status'=>'Permitted','modified'=>"$mod_date"),array('req_by_user'=>$req_by_user,'req_for_user'=>$req_for_user));

		echo '~done~';

	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	//to update the status as rejected when HO click for rejection
	//on 23-06-2021 by Amol
	public function hoRejectedForTransfer(){

		$this->autoRender = false;
		//get ajax post data
		$req_by_user = base64_encode($_POST['req_by_user']); //for email encoding
		$req_for_user = base64_encode($_POST['req_for_user']); //for email encoding
		$mod_date = date('Y-m-d H:i:s');

		$this->DmiWorkTransferHoPermissions->updateAll(array('status'=>'Rejected','modified'=>"$mod_date"),array('req_by_user'=>$req_by_user,'req_for_user'=>$req_for_user));

		echo '~done~';

	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	//to show application basic details to RO/SO user in popup
	//on 23-06-2021 by Amol
	public function showApplStatusPopup(){

		$this->autoRender = false;
		//get ajax post data
		$appl_id = $_POST['appl_id'];
		$appl_type = trim($_POST['appl_type']);

		//get firm details
		$firm_details = $this->DmiFirms->find('all',array('fields'=>array('firm_name','created'),'conditions'=>array('customer_id'=>$appl_id)))->first();
		$firm_name = $firm_details['firm_name'];

		//get application type from name
		$get_appl_type = $this->DmiApplicationTypes->find('all',array('fields'=>'id','conditions'=>array('application_type'=>$appl_type)))->first();
		$appl_type_id = $get_appl_type['id'];

		//get flow wise tables
		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type'=>$appl_type_id),'order'=>'id ASC'))->first();
		$current_position_table = $flow_wise_tables['appl_current_pos'];
		$final_submit_table = $flow_wise_tables['application_form'];

		//get application applied on
        $final_submit_table = $this->AqcmsWrapper->customeLoadModel($final_submit_table);
		$applied_on_details = $this->$final_submit_table->find('all',array('fields'=>'created','conditions'=>array('customer_id'=>$appl_id,'status'=>'pending'),'order'=>'id desc'))->first();
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
        $current_position_table = $this->AqcmsWrapper->customeLoadModel($current_position_table);
		$get_pos_details = $this->$current_position_table->find('all',array('fields'=>array('current_level'),'conditions'=>array('customer_id'=>$appl_id),'order'=>'id desc'))->first();
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

		echo '~'.json_encode($result).'~';
		exit;

	}


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ WORK-TRANSFER ]|
	//For LIMS user deactivation check on 13-08-2019
	//this function is used to check the LIMS sample in prgress with user, which is selected to deactivate
	public function getLimsUserWiseSamplesInProgress($user_table_id) {

		$from_user = $user_table_id;


		//check status in worflow table regarding this user id
		//Check from user id in distination user code colume in workflow table and get list of original sample code.
		$checkUserDestinationCode = $this->Workflow->find('list',array('keyField'=>'id','valueField'=>'org_sample_code','conditions'=>array('dst_usr_cd IS'=>$from_user),'order'=>'id desc'))->toArray();

		$asDestinationUserSample = array_unique($checkUserDestinationCode);

		//Check all get original sample code is final graded or not and get final graded sample list.
		$getOriginalSampleCode = $this->Workflow->find('list',array('keyField'=>'id','valueField'=>'org_sample_code','conditions'=>array('org_sample_code IN'=>$asDestinationUserSample,'stage_smpl_flag'=>'FG')))->toArray();
		$finalGradingCompletedSample = array_unique($getOriginalSampleCode);


		//Getting the sample list that have not final graded yet.
		$PendingFinalGradingSample = array_diff($asDestinationUserSample,$finalGradingCompletedSample);

		//new conditions added on 23-06-2021 by Amol
		//if sample forwarded by the any DMI officer and final grading not done yet,
		//then do not release the officer from LIMS pending work

		$get_user_lims_role = $this->DmiUsers->find('all',array('fields'=>'role','conditions'=>array('id'=>$user_table_id)))->first();
		$lims_role = $get_user_lims_role['role'];

		if ($lims_role == 'RO/SO OIC' || $lims_role == 'RO Officer' || $lims_role == 'SO Officer' || $lims_role == 'Ro_assistant') {

			foreach ($PendingFinalGradingSample as $eachkey => $eachValue ) {

				//check if the sample is forwarded by the user
				$forwarded_by_user = 'no';
				$forwardStatus = $this->Workflow->find('all',array('fields'=>array('org_sample_code'),'conditions'=>array('src_usr_cd'=>$from_user,'org_sample_code'=>$eachValue,'stage_smpl_flag'=>'OF')))->first();

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

			$teststatus =array();  $maxresultID = array(); $pendingtest = array();
			$tabc = array(); $chemistcode = array(); $finalresult =array(); $in_src_usr_cd_pr = array();$update_src_code_id=array();

			foreach ($PendingFinalGradingSample as $eachkey => $eachValue ) {

				//Getting list of stage sample status flag for particular original sample code for from user id
				$result = $this->Workflow->find('list',array('keyField'=>'id','valueField'=>'stage_smpl_flag','conditions'=>array('org_sample_code'=>$eachValue,'dst_usr_cd'=>$from_user),'order'=>'id'))->toArray();

				//Getting current stage sample status flag for particular original sample code for from user id
				$current_sample_status = $this->Workflow->find('all',array('fields'=>array('id','stage_smpl_flag'),'conditions'=>array('org_sample_code'=>$eachValue),'order'=>'id desc'))->first();

				//Checked and make list of TA and TABC stage sample status flag
				foreach($result as $eachkey1 => $eachValue1){

					if(trim($eachValue1) == 'TA'){

						$teststatus[] = $eachkey1;
					}
					if(trim($eachValue1) =='TABC'){

						$tabc[] = $eachkey1;
					}
				}

				if (in_array(trim($current_sample_status['stage_smpl_flag']),array('SD','TA','TABC'))) {

					$currentsamplestatus[] = array($current_sample_status['id'],$eachkey1);

				}else{

					$currentsamplestatus[] = array($current_sample_status['id']);
				}


				//store max id of particular original sample code
				$maxresultID[] = $eachkey1;


				$in_src_usr_cd_pr_list = $this->Workflow->find('list',array('keyField'=>'id','valueField'=>'stage_smpl_cd','conditions'=>array('org_sample_code'=>$eachValue,'src_usr_cd'=>$from_user,'stage_smpl_flag'=>'TA'),'order'=>'id'))->toArray();

				if (!empty($in_src_usr_cd_pr_list)) {

					$in_src_usr_cd_pr[] = $in_src_usr_cd_pr_list;
				}
			}



			foreach ($in_src_usr_cd_pr as $eachloop) {

				foreach ($eachloop as $eachloopkey =>$eachloopvalue ) {

					$loopvaluewithFT = $this->Workflow->find('all',array('fields'=>array('id'),'conditions'=>array('stage_smpl_cd'=>$eachloopvalue,'stage_smpl_flag'=>'FT')))->first();

					if (empty($loopvaluewithFT)) {

						$update_src_code_id[] = trim($eachloopkey);
					}
				}
			}

			//Getting list of actual pending samples on from user side.
			foreach ($maxresultID as $resultKey =>$resultValue) {

				if (in_array($resultValue,$currentsamplestatus[$resultKey])) {

					$finalresult[] = $resultValue;
				}
			}

			//Getting list of actual pending allocated test sample code on from user side.
			if(!empty($teststatus)){

				foreach($teststatus as $eachtest){

					$teststagecd = $this->Workflow->find('all',array('fields'=>array('id','stage_smpl_cd'),'conditions'=>array('id'=>$eachtest)))->first();
					$testsamplestage = $this->Workflow->find('all',array('fields'=>array('id','stage_smpl_cd'),'conditions'=>array('stage_smpl_cd'=>$teststagecd['stage_smpl_cd'],'stage_smpl_flag'=>'FT')))->first();

					if (empty($testsamplestage)) {

						$pendingtest[] = $eachtest;
						$chemistcode[] = trim($teststagecd['stage_smpl_cd']);
					}
				}
			}




			//$chemist_allocated = $this->MSampleAllocate->find('list',array('fields'=>array('chemist_code','sr_no'),'conditions'=>array('chemist_code IN'=>$chemistcode)))->toArray();

			$finalPendingList = array_unique(array_merge(array_diff($finalresult,$tabc),$pendingtest));

			if (!empty($finalPendingList) || !empty($chemistcode) || !empty($update_src_code_id)) {

				$inprogress_status = 'yes';

			} else {

				$inprogress_status = null;
			}

		} else {

			//added on 23-06-2021 by Amol
			$inprogress_status = null;
		}

		if ($inprogress_status == 'yes') {

			return true;
		} else {
			return false;
		}

	}


/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

																/***###| UPDATE FIRM DETAILS MODULE|###***/

	//Update Details Function For Update the Firm Details For Primary and Secondary Firm on 29-12-2021 By Akash
	//for request to change email id and mobile no. of firms and primary applicants

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ UPDATE FIRM DETAILS ]|
	public function firmsListToUpdate(){

		$this->viewBuilder()->setLayout('admin_dashboard');

		//deleteing all created sessions
		$this->Session->delete('customer_id');
		$this->Session->delete('current_level');
		$this->Session->delete('re_esigning');
		$this->Session->delete('reason_to_re_esign');
		$this->Session->delete('re_esign_grant_date');
		$this->Session->delete('pdf_file_name');

		$userName = $this->Session->read('username');

		$conn = ConnectionManager::get('default');

		$user_role = $this->DmiUserRoles->find('all',array('fields'=>'super_admin','conditions'=>array('user_email_id IS'=>$this->Session->read('username'))))->first();

		$list = array();

		if ($user_role['super_admin'] == 'yes') {

			$for_firm = 'primary';
			$primary_id = $this->DmiCustomers->find()->select(['id','modified','f_name','l_name','customer_id'])->toArray();
			$this->set('primary_id',$primary_id);

		} else {

			$for_firm = 'secondary';
			$userOffice = $this->DmiUsers->find('all',array('fields'=>array('posted_ro_office'),'conditions'=>array('email IS'=>$userName)))->first();
			$userPostedOffice = $userOffice['posted_ro_office'];

			$office_type = $this->DmiRoOffices->getOfficeDetails($userName);
			$roDistricts = $this->DmiRoOffices->find('list',array('valueField'=>array('id'),'conditions'=>array('ro_email_id IS'=>$userName)))->toArray();

			if ($office_type[1] == 'SO') {
				$conditionA = array('so_id IN'=>$roDistricts);
				$conditionB = array('so_id IN'=>$userPostedOffice);
			}else{
				$conditionA = array('ro_id IN'=>$roDistricts);
				$conditionB = array('ro_id IN'=>$userPostedOffice);
			}

			if (!empty($roDistricts)) {
				$districtlis = $this->DmiDistricts->find('list',array('valueField'=>array('id'),'conditions'=>$conditionA))->toArray();
			} else {
				$districtlis = $this->DmiDistricts->find('list',array('valueField'=>array('id'),'conditions'=>$conditionB))->toArray();
			}


			foreach($districtlis as $each){

				$firmDetails = $conn->execute("SELECT df.id,df.modified,df.customer_id, df.firm_name, df.email, dd.district_name, df.customer_primary_id, dc.email
												FROM dmi_firms AS df
												INNER JOIN dmi_districts AS dd ON dd.id = df.district::integer
												INNER JOIN dmi_customers AS dc ON dc.customer_id = df.customer_primary_id
												WHERE df.district='$each' OR df.delete_status = 'null' OR df.delete_status = 'no'")->fetchAll('assoc');

				if(!empty($firmDetails)){ $list[] = $firmDetails; }
			}

			$this->set('datalist',$list);
		}

		$this->set('for_firm',$for_firm);

	}



	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ UPDATE FIRM DETAILS ]|
	//for fetching the id to edit
	public function fetchFirmId($id) {

		$this->Session->write('firm_id',$id);
		$this->redirect(array('controller'=>'othermodules','action'=>'update_firm_details'));
	}



	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ UPDATE FIRM DETAILS ]|
	//for fetching the id to edit
	public function fetchPrimaryFirmId($id) {

		$this->Session->write('primary_firm_id',$id);
		$this->redirect(array('controller'=>'othermodules','action'=>'update_firm_details'));
	}



	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////|[ UPDATE FIRM DETAILS ]|
	//Main Function to edit the firm details
	public function updateFirmDetails() {

		$this->viewBuilder()->setLayout('admin_dashboard');
		$firm_id = $this->Session->read('firm_id');
		$this->Session->write('current_level','level_3');

		//Set the variables for dislaying messsages
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		//check valid user
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$this->Session->read('username'))))->first();

		if (!empty($user_access)) {

			if ($user_access['super_admin'] == 'yes') {

				$type='primary';
				$model = 'DmiCustomers';
				$log_model = 'DmiCustomersHistoryLogs';

			} elseif ($user_access['ro_inspection'] == 'yes' || $user_access['so_inspection'] == 'yes') {

				$type='firm';
				$model = 'DmiFirms';
				$log_model = 'DmiFirmHistoryLogs';

			}

			$this->set('type',$type);

            $model = $this->AqcmsWrapper->customeLoadModel($model);
			$firm_details = $this->$model->find('all',array('conditions'=>array('id'=>$firm_id)))->first();
			$customer_id = $firm_details['customer_id'];

			$this->Session->write('customer_id',$customer_id);//for further use
			$this->set('firm_details',$firm_details);
			$this->set('model',$model);


			//check if the firm is granted or not
			$is_firm_granted = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$this->set('is_firm_granted',$is_firm_granted);

			$button = 'Udpate';
			if (!empty($is_firm_granted)) {
				$appl_cur_status = 'granted';
			} else {
				$appl_cur_status = 'in_progress';
			}

			//get update log to map the updated field last time, in different color
			$email_updated='';
			$mob_updated='';

			$get_update_log = $this->DmiUpdateFirmDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

			if (!empty($get_update_log)) {

				if ($get_update_log['prev_email']!= $get_update_log['cur_email']) {

					$email_updated='yes';
				}

				if ($get_update_log['prev_mob']!= $get_update_log['cur_mob']) {

					$mob_updated='yes';
				}
			}

			$this->set('email_updated',$email_updated);
			$this->set('mob_updated',$mob_updated);


			//post values
			if ($this->request->is('post')) {

				$postData = $this->request->getData();
				//without esign
				if ($postData['update_details'] == 'Update') {

					$htmlEncodedUpdatedMobile = base64_encode(htmlentities($postData['mobile_no'], ENT_QUOTES));
					$htmlEncodedUpdatedEmail  = base64_encode(htmlentities($postData['email'], ENT_QUOTES));//for email encoding

					if (!empty($firm_id)) {

						//For Primary Firm
						if ($user_access['super_admin'] == 'yes') {

							$previous_email = $firm_details['email'];
							$previous_mobile = $firm_details['mobile'];
							$customer_id = $firm_details['customer_id'];

							//for main table
							$result =array('id'=>$firm_id,
											'mobile'=>$htmlEncodedUpdatedMobile,
											'email'=>$htmlEncodedUpdatedEmail,
											'modified'=>date('Y-m-d H:i:s'));


							//for log table
							$log_result = array(

								'f_name'=>$firm_details['f_name'],
								'm_name'=>$firm_details['m_name'],
								'l_name'=>$firm_details['l_name'],
								'street_address'=>$firm_details['street_address'],
								'district'=>$firm_details['district'],
								'state'=>$firm_details['state'],
								'postal_code'=>$firm_details['postal_code'],
								'mobile'=>$htmlEncodedUpdatedMobile,
								'email'=>$htmlEncodedUpdatedEmail,
								'landline'=>$firm_details['landline'],
								'file'=>$firm_details['file'],
								'modified'=>$firm_details['modified'],
								'document'=>$firm_details['document'],
								'password'=>$firm_details['password'],
								'customer_id'=>$firm_details['customer_id'],
								'photo_id_no'=>$firm_details['photo_id_no']

							);

						} else {

							$previous_email = $firm_details['email'];
							$previous_mobile = $firm_details['mobile_no'];
							$customer_id = $firm_details['customer_id'];

							//for main table
							$result = array('id'=>$firm_id,
											'mobile_no'=>$htmlEncodedUpdatedMobile,
											'email'=>$htmlEncodedUpdatedEmail,
											'modified'=>date('Y-m-d H:i:s'));

							//for log table
							$log_result = array(

								'customer_primary_id'=>$firm_details['customer_primary_id'],
								'firm_name'=>$firm_details['firm_name'],
								'certification_type'=>$firm_details['certification_type'],
								'commodity'=>$firm_details['commodity'],
								'sub_commodity'=>$firm_details['sub_commodity'],
								'street_address'=>$firm_details['street_address'],
								'state'=>$firm_details['state'],
								'mobile_no'=>$htmlEncodedUpdatedMobile,
								'email'=>$htmlEncodedUpdatedEmail,
								'district'=>$firm_details['district'],
								'postal_code'=>$firm_details['postal_code'],
								'modified'=>$firm_details['modified'],
								'customer_id'=>$firm_details['customer_id'],
								'password'=>$firm_details['password'],
								'total_charges'=>$firm_details['total_charges'],
								'export_unit'=>$firm_details['export_unit'],
								'fax_no'=>$firm_details['fax_no'],
								'packaging_materials'=>$firm_details['packaging_materials'],
								'other_packaging_details'=>$firm_details['other_packaging_details'],
								'is_already_granted'=>$firm_details['is_already_granted']

							);

						}

						$ModelEntity = $this->$model->newEntity($result);

						if($this->$model->save($ModelEntity)){

							//this log table is clone of main table
                            $log_model = $this->AqcmsWrapper->customeLoadModel($log_model);

							$LogModelEntity = $this->$log_model->newEntity($log_result);

							$this->$log_model->save($LogModelEntity);

							//another log table by user side to maintain the change
							$DmiUpdateFirmDetailsEntity = $this->DmiUpdateFirmDetails->newEntity(array(

								'customer_id'=>$customer_id,
								'update_by'=>$this->Session->read('username'),
								'prev_email'=>$previous_email,
								'prev_mob'=>$previous_mobile,
								'cur_email'=>$htmlEncodedUpdatedEmail,
								'cur_mob'=>$htmlEncodedUpdatedMobile,
								'appl_cur_status'=>$appl_cur_status,
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'reason'=>htmlentities($postData['reason'], ENT_QUOTES)
							));


							$this->DmiUpdateFirmDetails->save($DmiUpdateFirmDetailsEntity);

							$this->Session->write('reason_to_re_esign',htmlentities($postData['reason'], ENT_QUOTES));//to update the re-esign grant log table

							if(!empty($is_firm_granted) && $is_firm_granted['user_email_id'] != 'old_application'){
								//set button for re-esign
								$this->set('btn_to_re_esign','yes');

							}

							//again to get updated firm details on screen when retrun false, hence set below
							$firm_details = $this->$model->find('all',array('conditions'=>array('id IS'=>$firm_id)))->first();
							$this->set('firm_details',$firm_details);

							if($type=='primary'){
								$this->set('return_message','The details of primary applicant has been updated');
								return null;
								exit;

							}else{

								if(!empty($is_firm_granted) && $is_firm_granted['user_email_id'] != 'old_application'){
									$this->set('return_message','Firm details are updated, kindly proceed to re-esign the certificate');
									return null;
									exit;

								}else{

									$this->set('return_message','Firm details are updated Successfully');
									return null;
									exit;
								}
							}

							$this->set('return_message',null);
							//return null;

						}
					}

				} else {
						//reesign
				}
			}

			$this->set('button',$button);
		}
	}


/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

																/***###| APPLICANT DETAILS MODULE|###***/

	// APPLICANT DETAILS
	// DESCRIPTION : Show applicant email details for new audit changes
	// @AUTHOR : PRAVIN BHAKARE
	// @CONTRIBUTER : AKASH THAKRE (migration)
	// DATE : 25-02-2021

	public function applicantDetails() {

		$this->viewBuilder()->setLayout('admin_dashboard');
		$userName = $this->Session->read('username');

		$conn = ConnectionManager::get('default');
		$user_role = $this->DmiUserRoles->find('all', array('fields' => 'super_admin', 'conditions' => array('user_email_id IS' => $this->Session->read('username'))))->first();

		if ($user_role['super_admin'] == 'yes') {

			$districtlist = $this->DmiDistricts->find('list', array('fields' => array('id'), 'order' => array('district_name')))->toArray();

		} else {

			$userOffice = $this->DmiUsers->find('all', array('fields' => array('posted_ro_office'), 'conditions' => array('email IS' => $userName)))->first();
			$userPostedOffice = $userOffice['posted_ro_office'];

			$roDistricts = $this->DmiRoOffices->find('list', array('fields' => array('id'), 'conditions' => array('ro_email_id' => $userName)))->toArray();

			if (!empty($roDistricts)) {
				$districtlist = $this->DmiDistricts->find('list', array('fields' => array('id'), 'conditions' => array('ro_id IN' => $roDistricts)))->toArray();
			} else {
				$districtlist = $this->DmiDistricts->find('list', array('fields' => array('id'), 'conditions' => array('ro_id IS' => $userPostedOffice)))->toArray();
			}

		}

		$list = array();
		foreach ($districtlist as $each) {

			$firmDetails = $conn->execute("SELECT df.customer_id, df.firm_name, df.email, dd.district_name, df.customer_primary_id, dc.email
											FROM dmi_firms AS df INNER JOIN dmi_districts AS dd ON dd.id = df.district::INTEGER
											INNER JOIN dmi_customers AS dc ON dc.customer_id = df.customer_primary_id WHERE df.district='$each'")->fetchAll('assoc');
			if (!empty($firmDetails)) {
				$list[] = $firmDetails;
			}
		}

		$this->set('datalist', $list);

	}

/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

																/***###| MY TEAM MODULE|###***/

	// myTeam
	// Author : Akash Thakre
	// Description : This function is created to show the list of office users
	// Date : 30-05-2022

	public function myTeam(){

		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$username = $this->Session->read('username');



		//get details
		$officeDetails = $this->DmiRoOffices->getOfficeDetails($username);

		$office_name = $officeDetails[0];
		$office_type = $officeDetails[1];

		//set pao
		$getPao = $this->DmiPaoDetails->getPaoDetails($username,null);

		//Set HO Usersd
		$getHoUsers = $this->DmiUserRoles->getHORoles();

		$dy_ama = $getHoUsers['dy_ama'];
		$jt_ama = $getHoUsers['jt_ama'];
		$ama = $getHoUsers['ama'];

		//Full Name for Head Office Users
		$dy_ama_name = $this->DmiUsers->getFullName($dy_ama);
		$jt_ama_name = $this->DmiUsers->getFullName($jt_ama);
		$ama_name = $this->DmiUsers->getFullName($ama);

		//Get Scrutiny Officers
		$get_scrutinizers_list = $this->DmiRoOffices->getScrutinizerForCurrentOffice();
		$this->set('get_scrutinizers_list',$get_scrutinizers_list);

		// Get Inspection Officers
		$get_io_list = $this->DmiRoOffices->getIoForCurrentOffice();
		$this->set('get_io_list',$get_io_list);

		//Set HO MO SMO
		$ho_scrutinizers_list = $this->DmiUserRoles->getHoScrutinizerForCurrentOffice();
		$this->set('ho_scrutinizers_list',$ho_scrutinizers_list);

		if ($officeDetails[1] == 'SO') {

			$soInchargeEmail = $officeDetails[2];
			$soInchargeName = $this->DmiUsers->getFullName($soInchargeEmail);

			$roInchargeEmail = $this->DmiRoOffices->getRoOfficeEmail($officeDetails[3]);
			$roInchargeName = $this->DmiUsers->getFullName($roInchargeEmail);

		} else {

			$soInchargeEmail = '';
			$soInchargeName = '';
			$roInchargeEmail = $officeDetails[2];
			$roInchargeName = $this->DmiUsers->getFullName($roInchargeEmail);

		}


		//post values
		if ($this->request->is('post')) {

			//get the post value
			$postData = $this->request->getData();
			$customer_id = trim($postData['search_applicant_id']);

			$firm_details = $this->DmiFirms->firmDetails($customer_id);

			if (!empty($firm_details)) {


				$officeDetails = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
				$ro_id_from_district = $this->DmiDistricts->getRoIdFromDistrictId($firm_details['district']);
				$ro_id_from_session = $this->DmiUsers->getPostedOffId($_SESSION['username']);

				if (in_array($ro_id_from_session,$ro_id_from_district)) {
					//DDO Details
					$getPao = $this->DmiPaoDetails->getPaoDetails(null,$customer_id);

					//Get Scrutiny Officers
					$get_scrutinizers_list = $this->DmiRoOffices->getScrutinizerForCurrentOffice();
					// Get Inspection Officers
					$get_io_list = $this->DmiRoOffices->getIoForCurrentOffice();


					$office_name = $officeDetails['ro_office'];
					$office_type = $officeDetails['office_type'];

					//check the Office type
					if ($officeDetails['office_type'] == 'SO') {

						$soInchargeEmail = $officeDetails['ro_email_id'];
						$soInchargeName = $this->DmiUsers->getFullName($soInchargeEmail);

						$roInchargeEmail = $this->DmiRoOffices->getRoOfficeEmail($officeDetails['ro_id_for_so']);
						$roInchargeName = $this->DmiUsers->getFullName($roInchargeEmail);

					} elseif ($officeDetails['office_type'] == 'RO') {

						$roInchargeEmail = $officeDetails['ro_email_id'];
						$roInchargeName = $this->DmiUsers->getFullName($roInchargeEmail);
					}

				} else {

					$message = 'Sorry, The entered Applicant Id is not belongs to this office';
					$message_theme = 'failed';
					$redirect_to = '../othermodules/my_team';
				}

			} else {

				$message = 'Sorry, The entered Applicant Id is not Valid';
				$message_theme = 'failed';
				$redirect_to = '../othermodules/my_team';
			}
		}

		//Set DDO
		$this->set('getPao',$getPao);

		//Set Office
		$this->set('office_name',$office_name);
		$this->set('office_type',$office_type);

		//Set RO Information
		$this->set('roInchargeEmail',$roInchargeEmail);
		$this->set('roInchargeName',$roInchargeName);

		//set SO Information
		$this->set('soInchargeEmail',$soInchargeEmail);
		$this->set('soInchargeName',$soInchargeName);

		$this->set(compact('dy_ama','jt_ama','ama'));
		$this->set(compact('dy_ama_name','jt_ama_name','ama_name'));

		$this->set('message', $message);
		$this->set('message_theme', $message_theme);
		$this->set('redirect_to', $redirect_to);

	}



	// getScenarios
	// Author : Shankhpal Shende
	// Description : This function is created to show scenarios view
	// Date : 08-02-2023
	public function getScenarios(){


		$username = $this->getRequest()->getSession()->read('username');
		$this->set('username',$username);
		// Find out the officer present in RO/SO office,
		$officerPresentInOff = $this->Customfunctions->findOfficerCountInoffice($username);
		$this->set('officerPresentInOff',$officerPresentInOff);

		$office = $this->DmiRoOffices->getOfficeDetails($username);
		$office_type = $office[1];

		$this->set('office_type',$office_type);

		// SO  office where SO In-charge has power(role) to grant (more than one officer posted)
		$so_power_to_grant_appl = $this->soAuthorisedToGrantApp($username);

		$this->set('so_power_to_grant_appl',$so_power_to_grant_appl);
	}


	// checked if SO have power to grant the CA Non Bevo or printing application
	// added by shankhpal shende on 02/02/2023
	public function soAuthorisedToGrantApp($username){


		$nodalOfficerId = $username;
		$soPowerToGrantApp = 'no';

		$soGrantPP = $this->DmiUserRoles->find('all',array('conditions'=>array('so_grant_pp'=>'yes','user_email_id IS'=>$nodalOfficerId)))->first();

		if(!empty($soGrantPP))
		{
			$soPowerToGrantApp = 'yes';
		}

		return $soPowerToGrantApp;
	}

	//added method to check if the lab application is NABL accreditated
	//on 03-02-2023 by Shankhpal Shende
	public function checkIfLabNablAccreditated($username){

			//check if the applicant for laboratory selected the NABL accreditation


			$checkNabl = $this->DmiLaboratoryOtherDetails->find('all',['conditions'=>['user_email_id'=>$username],'order'=>'id desc'])->first();

			if(!empty($checkNabl) && $checkNabl['is_accreditated']=='yes'){

				return true;
			}else{
				return null;
			}

	}




	// Author : Shankhpal Shende
	// Description : This function is created for display list of records whose status approved and matched
	// Date : 08-02-2023
	// Note : For Routine Inspection (RTI)

	// Function updated on 12/06/2023 by shankhpal shende
	public function routineInspectionList(){

		$this->viewBuilder()->setLayout('admin_dashboard');

		$conn = ConnectionManager::get('default');

		$this->Session->write('application_type',10);
		$username = $this->Session->read('username');
		$application_type = $this->Session->read('application_type');
		$customer_id = $this->Session->read('customer_id');

		$get_period = $conn->execute("SELECT periods.* FROM dmi_routine_inspection_period AS periods")->fetchAll('assoc');

		$period_ca = null;
		$period_lab = null;
		$period_pp = null;

		if (!empty($get_period[0]['period'])) {
				$period_ca = $get_period[0]['period'];
		}
		if (!empty($get_period[1]['period'])) {
				$period_lab = $get_period[1]['period'];
		}
		if (!empty($get_period[2]['period'])) {
				$period_pp = $get_period[2]['period'];
		}

		$get_short_codes = $this->DmiRoOffices->find('list',array('valueField'=>'short_code','conditions'=>array('ro_email_id IS'=>$username)))->toArray(); //get RO/SO Incharge details

		$conditions = ['ca' => '','pp' => '','lab' => ''];

		$n = 1;
		foreach ($get_short_codes as $key => $value) {
			if ($key != (count($get_short_codes) - 1)) {
					$separator = ($n != 1) ? ' OR ' : '';
					$conditions['ca'] .= $separator . "customer_id like '%/1/$value/%'";
					$conditions['pp'] .= $separator . "customer_id like '%/2/$value/%'";
					$conditions['lab'] .= $separator . "customer_id like '%/3/$value/%'";
					$n++;
			}
		}

		$condition_ca = $conditions['ca'];
		$condition_pp = $conditions['pp'];
		$condition_lab = $conditions['lab'];

		$to_date = date('Y-m-d H:i:s');
		//dates between to fetch records
		$from_date_ca = date("Y-m-d H:i:s",strtotime("-$period_ca month"));
		$from_date_pp = date("Y-m-d H:i:s",strtotime("-$period_pp month"));
		$from_date_lab = date("Y-m-d H:i:s",strtotime("-$period_lab month"));

		$conditions = [
				'OR' => [
						$condition_ca,
						$condition_pp,
						$condition_lab,
				],
				'AND' => [
						[
								'OR' => [
										'AND' => [
												'date(created) >=' => $from_date_ca,
												'date(created) <=' => $to_date,
										],
										'AND' => [
												'date(created) >=' => $from_date_pp,
												'date(created) <=' => $to_date,
										],
										'AND' => [
												'date(created) >=' => $from_date_lab,
												'date(created) <=' => $to_date,
										],
								],
						],
				],
		];

		$results = $this->DmiRtiAllocations->find('list', [
				'keyField' => 'id',
				'valueField' => 'customer_id',
				'conditions' => $conditions,
				'order' => 'id desc',
		])->toArray();

		// Separate the results based on allocation type
		$list_array_ca = [];
		$list_array_pp = [];
		$list_array_lab = [];

		foreach ($results as $key => $value) {
			if (strpos($value, '/1/') !== false) {
					$list_array_ca[$key] = $value;
			} elseif (strpos($value, '/2/') !== false) {
					$list_array_pp[$key] = $value;
			} elseif (strpos($value, '/3/') !== false) {
					$list_array_lab[$key] = $value;
			}
		}

		// to get array list for allocated ca application
		$list_array_ca = $this->DmiRtiAllocations->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array($condition_ca, array('date(created) >=' => $from_date_ca, 'date(created) <=' =>$to_date)),'order'=>'id desc'))->toArray();

		// to get array list for allocated pp application
		$list_array_pp = $this->DmiRtiAllocations->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array($condition_pp,array('date(created) >=' => $from_date_pp, 'date(created) <=' =>$to_date)),'order'=>'id desc'))->toArray();

		// to get array list for allocated lab application
		$list_array_lab = $this->DmiRtiAllocations->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array($condition_lab,array('date(created) >=' => $from_date_lab, 'date(created) <=' =>$to_date)),'order'=>'id desc'))->toArray();



		//added by shankhpal for approved list of ca 16/05/2023
		$get_rti_approved_list_for_ca = [];
		if(!empty($list_array_ca)){
					$get_rti_approved_list_for_ca = $this->DmiRtiFinalReports->find('all',array('conditions'=>array('customer_id IN'=>$list_array_ca,'status IN'=>'approved','current_level'=>'level_3'),'order'=>'id desc'))->toArray();
		}

		$get_rti_approved_list_for_pp = [];

		if(!empty($list_array_pp)){
			$get_rti_approved_list_for_pp = $this->DmiRtiFinalReports->find('all',array('conditions'=>array('customer_id IN'=>$list_array_pp,'status IN'=>'approved','current_level'=>'level_3'),'order'=>'id desc'))->toArray();
		}

		//added by shankhpal for approved list of lab 16/05/2023
		$get_rti_approved_list_for_lab = [];
		if(!empty($list_array_lab)){
				$get_rti_approved_list_for_lab = $this->DmiRtiFinalReports->find('all',array('conditions'=>array('customer_id IN'=>$list_array_lab,'status IN'=>'approved','current_level'=>'level_3'),'order'=>'id desc'))->toArray();
		}

		$flow_wise_table = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

		$report_pdf_table = $flow_wise_table['DmiRtiReportPdfRecords'];


		$appl_array_ca = array();
		$i=0;
		foreach($get_rti_approved_list_for_ca as $each){

			$customer_id = $each['customer_id'];
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

			$report_pdf_field = 'pdf_file';
			$get_report_pdf = $this->DmiRtiReportPdfRecords->find('all',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();


			$report_pdf = '';
			$pdf_version_ca = '';
			if(!empty($get_report_pdf)){
				$report_pdf = $get_report_pdf[$report_pdf_field];
				$pdf_version_ca = $get_report_pdf['pdf_version'];
			}
			//get firm details
			$firm_details = $this->DmiFirms->firmDetails($customer_id);
			$firm_name = $firm_details['firm_name'];
			$firm_table_id = $firm_details['id'];

			$report_link = '../inspections/routine_inspection_report_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';

			$appl_array_ca[$i]['customer_id'] = $customer_id.'-'.$form_type;
			$appl_array_ca[$i]['firm_name'] = $firm_name;
			$appl_array_ca[$i]['on_date'] = $each['created'];
			$appl_array_ca[$i]['report_pdf'] = $report_pdf;
			$appl_array_ca[$i]['report_link'] = $report_link;
			$appl_array_ca[$i]['pdf_version'] = $pdf_version_ca;

			$i=$i+1;
		}

		$appl_array_pp = array();
		$i=0;

		foreach($get_rti_approved_list_for_pp as $each){

			$customer_id = $each['customer_id'];
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			$report_pdf_field = 'pdf_file';
			$get_report_pdf = $this->DmiRtiReportPdfRecords->find('all',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();

			$pdf_version_pp = '';
			$report_pdf = '';
			if(!empty($get_report_pdf)){
				$report_pdf = $get_report_pdf[$report_pdf_field];
				$pdf_version_pp = $get_report_pdf['pdf_version'];

			}
			//get firm details
			$firm_details = $this->DmiFirms->firmDetails($customer_id);
			$firm_name = $firm_details['firm_name'];
			$firm_table_id = $firm_details['id'];

			$report_link = '../inspections/routine_inspection_report_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';

			$appl_array_pp[$i]['customer_id'] = $customer_id.'-'.$form_type;
			$appl_array_pp[$i]['firm_name'] = $firm_name;
			$appl_array_pp[$i]['on_date'] = $each['created'];
			$appl_array_pp[$i]['report_pdf'] = $report_pdf;
			$appl_array_pp[$i]['report_link'] = $report_link;
			$appl_array_pp[$i]['pdf_version'] = $pdf_version_pp;
			$i=$i+1;
		}

		$appl_array_lab = array();
		$i=0;
		foreach($get_rti_approved_list_for_lab as $each){

			$customer_id = $each['customer_id'];
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

			$report_pdf_field = 'pdf_file';
			$get_report_pdf = $this->DmiRtiReportPdfRecords->find('all',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();

			$report_pdf = '';
			$pdf_version_lab = '';
			if(!empty($get_report_pdf)){
				$report_pdf = $get_report_pdf[$report_pdf_field];
				$pdf_version_lab= $get_report_pdf['pdf_version'];
			}

			//get firm details
			$firm_details = $this->DmiFirms->firmDetails($customer_id);
			$firm_name = $firm_details['firm_name'];
			$firm_table_id = $firm_details['id'];

			$report_link = '../inspections/routine_inspection_report_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';


			$appl_array_lab[$i]['customer_id'] = $customer_id.'-'.$form_type;
			$appl_array_lab[$i]['firm_name'] = $firm_name;
			$appl_array_lab[$i]['on_date'] = $each['created'];
			$appl_array_lab[$i]['report_pdf'] = $report_pdf;
			$appl_array_lab[$i]['report_link'] = $report_link;
			$appl_array_lab[$i]['pdf_version'] = $pdf_version_lab;

			$i=$i+1;
		}

		$this->set('appl_array_ca',$appl_array_ca);
		$this->set('appl_array_pp',$appl_array_pp);
		$this->set('appl_array_lab',$appl_array_lab);

	}
	//to show officers wise pending application list on RO/SO Incharge dashboard
	//on 22-06-2023 by Amol
	public function getOfficerWisePendingAppl(){

		$InchargeId = $this->Session->read('username');
		$result = $this->commonMethodForOfficersPendingAppl($InchargeId);
		$this->set('appl_list',$result[0]);
		$this->set('checkCurPosition',$result[1]);
		$this->set('getOfficerUnderIncharge',$result[2]);

	}

	//to show RO's wise pending application list on HO QC dashboard
	//on 22-06-2023 by Amol
	public function getRoWisePendingAppl(){

		//get list of all RO offices

		$roOffices = $this->DmiRoOffices->find('all',array('fields'=>array('ro_office','ro_email_id'),'conditions'=>array('office_type IS'=>'RO','delete_status IS NULL'),'order'=>'id asc'))->toArray();

		$roWisePendingResult = array();
		$roWiseCurPosResult = array();
		$getOfficerUnderIncharge = array();
		$roOffice = array();

		foreach($roOffices as $each){
			$roOffice[] = $each['ro_office'];
			$InchargeId = $each['ro_email_id'];
			$result = $this->commonMethodForOfficersPendingAppl($InchargeId);
			$roWisePendingResult[] = $result[0];
			$roWiseCurPosResult[] = $result[1];
			$getOfficerUnderIncharge[] = $result[2];
		}

		$this->set('roOffice',$roOffice);
		$this->set('roWisePendingResult',$roWisePendingResult);
		$this->set('roWiseCurPosResult',$roWiseCurPosResult);
		$this->set('getOfficerUnderIncharge',$getOfficerUnderIncharge);

	}


	//created common method to get the officer's pending appl office wise under RO
	//27-06-2023 by Amol
	public function commonMethodForOfficersPendingAppl($InchargeId){

		//get list of offices under this incharge

		//get RO Incharge Posted Office
		$getOfficesUnderIncharge = array();
		$getROInchargeOffice = $this->DmiUsers->find('all',array('fields'=>array('posted_ro_office'),'conditions'=>array('email IS'=>$InchargeId,'status IS'=>'active')))->first();
		if(!empty($getROInchargeOffice)){
			$getOfficesUnderIncharge = $this->DmiRoOffices->find('list',array('valueField'=>'id','conditions'=>array('OR'=>array('ro_email_id IS'=>$InchargeId,'ro_id_for_so IS'=>$getROInchargeOffice['posted_ro_office']),'delete_status IS NULL')))->toArray();
		}

		//get all officers under this Incharge
		$getOfficerUnderIncharge = array();
		if(!empty($getOfficesUnderIncharge)){
			$getOfficerUnderIncharge = $this->DmiUsers->find('all',array('fields'=>array('f_name','l_name','email','posted_ro_office'),'conditions'=>array('posted_ro_office IN'=>$getOfficesUnderIncharge,'status IS'=>'active')))->toArray();
		}


		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
		$this->set('flow_wise_tables',$flow_wise_tables);



		$level_arr = array('level_1','level_2','level_3','level_4','level_4_ro','level_4_mo','pao');
		$this->set('level_arr',$level_arr);

		$appl_list = array();
		$checkCurPosition = array();

		$l=0;
		foreach($getOfficerUnderIncharge as $eachofficer){

			//get Office name for each officer
			$getOfficeName = $this->DmiRoOffices->find('all',array('fields'=>'ro_office','conditions'=>array('id IS'=>$eachofficer['posted_ro_office'])))->first();

			//for each flow
			$i=0;
			foreach($flow_wise_tables as $eachflow){

				//get application type
				$getApplType = $this->DmiApplicationTypes->find('all',array('fields'=>'application_type','conditions'=>array('id IS'=>$eachflow['application_type'])))->first();

				//flow wise appl tables
				$applPosTable = $eachflow['appl_current_pos'];
                $applPosTable = $this->AqcmsWrapper->customeLoadModel($applPosTable);
				$finalSubmitTable = $eachflow['application_form'];
                $finalSubmitTable = $this->AqcmsWrapper->customeLoadModel($finalSubmitTable);
				$grantCertTable = $eachflow['grant_pdf'];
                $grantCertTable = $this->AqcmsWrapper->customeLoadModel($grantCertTable);

				//for each level
				$j=0;
				foreach($level_arr as $eachLevel){

					//check appl position with current user and level
					$checkCurPosition[$l][$i][$j] = $this->$applPosTable->find('all',array('conditions'=>array('current_level IS'=>$eachLevel,'current_user_email_id IS'=>$eachofficer['email'])))->toArray();

					$k=0;
					foreach($checkCurPosition[$l][$i][$j] as $eachAppl){

						//check entry in rejected/junked table

						$checkIfRejected = $this->DmiRejectedApplLogs->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$eachAppl['customer_id']/*,'appl_type IS'=>$eachflow['application_type']*/)))->first();

						if(empty($checkIfRejected)){
							if($eachLevel=='level_1' || $eachLevel=='level_2' || $eachLevel=='level_4_ro' || $eachLevel=='level_4_mo' || $eachLevel=='pao'){

								//check if appl submission and granted
								$checkLastStatus = $this->$finalSubmitTable->find('all',array('conditions'=>array('customer_id IS'=>$eachAppl['customer_id']),'order'=>'id desc'))->first();
								if(!empty($checkLastStatus) && (($checkLastStatus['status']=='approved' && ($checkLastStatus['current_level']=='level_3' || $checkLastStatus['current_level']=='level_4')) ||
									($eachflow['application_type'] == 4 && $checkLastStatus['status']=='approved' && ($checkLastStatus['current_level']=='level_3' || $checkLastStatus['current_level']=='level_1')))){
									//nothing
								}else{
									$appl_list[$l][$i][$j][$k]['appl_type'] = $getApplType['application_type'];
									$appl_list[$l][$i][$j][$k]['appl_id'] = $eachAppl['customer_id'];
									$appl_list[$l][$i][$j][$k]['last_trans_date'] = $eachAppl['modified'];
									if(empty($eachAppl['modified'])){
										$appl_list[$l][$i][$j][$k]['last_trans_date'] = $eachAppl['created'];
									}

									$appl_list[$l][$i][$j][$k]['office_name'] = $getOfficeName['ro_office'];

									if($eachLevel=='level_1'){
										$appl_list[$l][$i][$j][$k]['process'] = 'Scrutiny';

									}elseif($eachLevel=='level_2'){
										$appl_list[$l][$i][$j][$k]['process'] = 'Site Inspection';

									}elseif($eachLevel=='level_4_ro'){
										$appl_list[$l][$i][$j][$k]['process'] = 'SO appl. communication';

									}elseif($eachLevel=='level_4_mo'){
										$appl_list[$l][$i][$j][$k]['process'] = 'SO appl. Scrutiny at RO';

									}elseif($eachLevel=='pao'){
										$appl_list[$l][$i][$j][$k]['process'] = 'Payment Verification';
										$appl_list[$l][$i][$j][$k]['last_trans_date'] = $eachAppl['created'];//intensionally taken created date for PAO

									}
									$k=$k+1;
								}

							}elseif($eachLevel=='level_3' || $eachLevel=='level_4'){

								//check if appl submission and granted
								$checkLastStatus = $this->$finalSubmitTable->find('all',array('conditions'=>array('customer_id IS'=>$eachAppl['customer_id']),'order'=>'id desc'))->first();
								if(!empty($checkLastStatus) && (($checkLastStatus['status']=='approved' && ($checkLastStatus['current_level']=='level_3' || $checkLastStatus['current_level']=='level_4')) ||
									($eachflow['application_type'] == 4 && $checkLastStatus['status']=='approved' && ($checkLastStatus['current_level']=='level_3' || $checkLastStatus['current_level']=='level_1')))){
									//nothing
								}else{
									$appl_list[$l][$i][$j][$k]['appl_type'] = $getApplType['application_type'];
									$appl_list[$l][$i][$j][$k]['appl_id'] = $eachAppl['customer_id'];
									$appl_list[$l][$i][$j][$k]['last_trans_date'] = $eachAppl['modified'];
									if(empty($eachAppl['modified'])){
										$appl_list[$l][$i][$j][$k]['last_trans_date'] = $eachAppl['created'];
									}

									$appl_list[$l][$i][$j][$k]['office_name'] = $getOfficeName['ro_office'];

									if($eachLevel=='level_3'){
										$appl_list[$l][$i][$j][$k]['process'] = 'Nodal Officer';

									}elseif($eachLevel=='level_4'){
										$appl_list[$l][$i][$j][$k]['process'] = 'HO QC Officer';

									}
									$k=$k+1;
								}

							}
						}


					}
					$j=$j+1;
				}

				$i=$i+1;
			}
		$l=$l+1;
		}

		return array($appl_list,$checkCurPosition,$getOfficerUnderIncharge);
	}



	//To reset the entries in the database whose renewals are pending and unable to find in the dashboard
	//Author : Akash Thakre
	//Date : 09-08-2023
	//For : Renewal Applications

	function renewalApplicationReset() {

		$message = '';
		$message_theme = '';
		$redirect_to = '';

		$conn = ConnectionManager::get('default');

		$firmDetails = $conn->execute("SELECT dg.customer_id FROM
			(
				SELECT customer_id,created,pdf_version
				FROM dmi_grant_certificates_pdfs
				WHERE id IN (
				SELECT MAX(id) id
				FROM dmi_grant_certificates_pdfs
				GROUP BY customer_id
				ORDER BY id)
			) AS dg

			INNER JOIN (SELECT customer_id,created
			FROM dmi_renewal_applicant_payment_details
			WHERE id IN (
				SELECT MAX(id) id
				FROM dmi_renewal_applicant_payment_details
				WHERE payment_confirmation = 'confirmed'
				GROUP BY customer_id
				ORDER BY id)
			) AS drm ON drm.customer_id = dg.customer_id AND drm.created::TIMESTAMP >  dg.created::TIMESTAMP")->fetchAll('assoc');

		 // Initialize arrays to store rejected and not rejected customer IDs
		 $rejectedArray = [];
		 $notRejectedArray = [];

		 foreach ($firmDetails as $each) {
			// Check if the customer ID exists in the rejected table
			$ifRejected = $this->DmiRejectedApplLogs->find('all')
				->where(['customer_id' => $each['customer_id']])
				->order(['id' => 'DESC'])
				->toArray();

			// Decide whether to add the customer ID to rejected or notRejected array
			if (!empty($ifRejected)) {
				$rejectedArray[] = $each['customer_id'];
			} else {
				$notRejectedArray[] = $each['customer_id'];
			}
		}

		$appl_list = [];
		foreach ($notRejectedArray as $customer) {
			$firm_details = $this->DmiFirms->firmDetails($customer);
			$appl_list[$customer] = $firm_details['customer_id'] . ' - ' . $firm_details['firm_name'];
		}

		$this->set('appl_list',$appl_list);

		//Check if the Reset Logs have data to present
		$all_records = $this->DmiRenewalApplicationsResetLogs->find('all')->toArray();
		if (!empty($all_records)) {
			$this->set('all_records',$all_records);
		}else{
			$this->set('all_records',null);
		}

		if ($this->request->is('post')) {

			$customer_id = trim($this->request->getData('customer_id'));

			// Check if the customer_id is in the $notRejectedArray
			if (in_array($customer_id, $notRejectedArray)) {

				$getApplicantPaymemtDetails = $this->DmiRenewalApplicantPaymentDetails->find('all')->where(['customer_id' => $customer_id,'payment_confirmation' => 'confirmed'])->order('id DESC')->first();

				$getCurrentPositionDetails = $this->DmiRenewalAllCurrentPositions->find('all')->where(['customer_id' => $customer_id])->order('id DESC')->first();

				//get the ddo details from customer id
				$firm_details = $this->DmiFirms->firmDetails($customer_id);
				$pao_id = $this->DmiDistricts->find('all',array('fields'=>'pao_id','conditions'=>array('id IS'=>$firm_details['district'])))->first();
				$paoID = $pao_id['pao_id'];

				$getPaoDetails = $this->DmiPaoDetails->find('all')->select(['pao_user_id'])->where(['id IS' => $paoID])->first();

				$paoNameDetails = $this->DmiUsers->find('all')->where(['id IS' => $getPaoDetails['pao_user_id'],'status'=>'active'])->first();


				if (!empty($paoNameDetails)) {

					//Update the entries in the renewal payment table
					$this->DmiRenewalApplicantPaymentDetails->updateAll(
						['pao_id' => $paoID, 'payment_confirmation' => 'pending','modified'=>date('Y-m-d H:i:s')],
						['id' => $getApplicantPaymemtDetails['id']]
					);


					// Capture the previous values before updating the renewal current position table
					$previousCurrentLevel = $getCurrentPositionDetails['current_level'];
					$previousUserEmailId = $getCurrentPositionDetails['current_user_email_id'];

					// Update the entries in renewal current position table
					$this->DmiRenewalAllCurrentPositions->updateAll(
						['current_level' => 'pao', 'current_user_email_id' => $paoNameDetails['email'],'modified'=>date('Y-m-d H:i:s')],
						['id' => $getCurrentPositionDetails['id']]
					);

					// Save the reset log with previous values
					$savelog = $this->DmiRenewalApplicationsResetLogs->saveResetLog(
						$customer_id,
						$paoID,
						$paoNameDetails['id'],
						$paoNameDetails['email'],
						$previousCurrentLevel, // Log the previous current level
						$previousUserEmailId  // Log the previous user email id
					);

					if ($savelog==true) {

						$message = 'The the application is transfered to user id "'.base64_decode($paoNameDetails['email']).'" successfully. Now PAO/DDO can re-verify the application in order to complete renewal process';
						$message_theme = 'success';
						$redirect_to = 'renewal_application_reset';
					}
				}


			} else {

				$message = 'The application entered is not valid for reseting or maybe it rejected.';
				$message_theme = 'failed';
				$redirect_to = 'renewal_application_reset';
			}
		}


		$this->set(compact('message','message_theme','redirect_to'));

		 // You might have more code here to perform further actions


	}


	//to get the details for the firms to show in the selection of the customer id.
	//Author : Akash Thakre
	//Date : 09-08-2023
	//For : Renewal Applications

	public function getFirmDetailsForRenewalReset(){

		$this->autoRender = false;

		//PostData
		$customer_id = $this->request->getData('customer_id');

		//Get the firm details
		$firm_details = $this->DmiFirms->firmDetails(trim($customer_id));

		$list_id = $this->DmiGrantCertificatesPdfs->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

		if (!empty($list_id)) {

			$fetch_last_grant_data = $this->DmiGrantCertificatesPdfs->find('all', array('conditions'=>array('id'=>max($list_id))))->first();
			$grant_date = $fetch_last_grant_data['date'];

			$certificate_validity_date = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$grant_date);
		}

		//get the ddo details from customer id
		$firm_details = $this->DmiFirms->firmDetails($customer_id);
		$pao_id = $this->DmiDistricts->find('all',array('fields'=>'pao_id','conditions'=>array('id IS'=>$firm_details['district'])))->first();
		$paoID = $pao_id['pao_id'];

		$getPaoDetails = $this->DmiPaoDetails->find('all')->select(['pao_user_id'])->where(['id IS' => $paoID])->first();

		$paoNameDetails = $this->DmiUsers->find('all')->where(['id IS' => $getPaoDetails['pao_user_id'],'status'=>'active'])->first();



		$response = [
			'customer_id' =>$firm_details->customer_id,
			'firm_name' => $firm_details->firm_name,
			'street_address' => $firm_details->street_address,
			'district_name' => $this->DmiDistricts->getDistrictNameById(trim($firm_details['district'])),
			'state_name'=> $this->DmiStates->getStateNameById(trim($firm_details['state'])),
			'postal_code'=> $firm_details->postal_code,
			'export_unit' => $export_unit = $this->Customfunctions->checkApplicantExportUnit($customer_id),
			'form_type' => $this->Customfunctions->checkApplicantFormType($customer_id),
			'forRenewal' => $this->Customfunctions->checkApplicantValidForRenewal($customer_id),
			'lastUpdate' => $this->Customfunctions->getApplicationCurrentStatus($customer_id),
			'certificate_validity_date' => $certificate_validity_date,
			'pao_details' => $paoNameDetails['f_name'] ." ". $paoNameDetails['l_name'] . " ( " .base64_decode($paoNameDetails['email']) ." ) "
		];

		echo '~' . json_encode($response) . '~';
		exit;

	}




	//To add the application in the reject table by the head office.
	//Author : Amol Choudhari
	//Date : 09-08-2023
	//For : Reject Applications

	public function markApplRejected(){



		//get Application types list
		$applTypesList = $this->DmiApplicationTypes->find('list',array('valueField'=>'application_type','order'=>'id ASC'))->toArray();
		$this->set('applTypesList',$applTypesList);

	}

	public function rejectApplication(){

		$this->autoRender= false;

		$customer_id = trim($_POST['customer_id']);
		$appl_type = htmlentities($_POST['appl_type'], ENT_QUOTES);
		$remark = htmlentities($_POST['remark'], ENT_QUOTES);




		//First Check if the applcation is already exist in the reject table

		$is_application_rejected = $this->DmiRejectedApplLogs->find('all')->where(['customer_id IS ' => $customer_id,'appl_type' => $appl_type])->order('id DESC')->first();
		if (empty($is_application_rejected)) {

			//insert record in reject log table
			$rejectlogEntity = $this->DmiRejectedApplLogs->NewEntity(array(
				'appl_type' => $appl_type,
				'form_type' => $this->Customfunctions->checkApplicantFormType($customer_id,$appl_type),
				'customer_id' => $customer_id,
				'by_user' => $this->Session->read('username'),
				'remark' => $remark,
				'created' => date('Y-m-d H:i:s')
			));

			$this->DmiRejectedApplLogs->save($rejectlogEntity);

			$message = '1';

		} else {

			$message = "Sorry this application is already has been rejected on date :"	.$is_application_rejected['created']. " by the user :	"
			.base64_decode($is_application_rejected['by_user'])	. 	"	with reason : " .$is_application_rejected['remark'] ;
		}

		echo '~'.$message.'~';
		exit;
	}



	//get the details to mark reject the application
	//Author : Amol Choudhari
	//Date : 09-08-2023
	//For : Reject Applications

	public function getApplDetailsToMarkReject() {

		$customer_id = $_POST['customer_id'];




		$check_user_role = array();
		$this->loadComponent('Randomfunctions');
		$check_user_role['super_admin']='yes';//set default
		$resultArray = $this->Randomfunctions->dashboardApplicationSearch($customer_id,$check_user_role);

		$rejectedData = $this->DmiRejectedApplLogs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->last();

		//$isApplSurrender = $this->Customfunctions->isApplicationSurrendered($customer_id);
		//$isApplSuspended = $this->Customfunctions->isApplicationSuspended($customer_id);
		//$isApplCancelled = $this->Customfunctions->isApplicationCancelled($customer_id);

		if ($resultArray['no_result']==null) {

			/*if (!empty($isApplCancelled)) {
				echo "<b>This Application is Cancelled on ".$isApplCancelled." and no longer available.</b>";
			} else {

				//Check if application is suspended
				if (!empty($isApplSuspended)) {
					echo "<b>This Application is Suspended Upto ".$isApplSuspended." and no longer available.</b>";
				} else {

					//Check if the Application is Surrendered
					if (!empty($isApplSurrender)) {
						echo "<b>This Application is Surrendered on ".$isApplSurrender." and no longer available.</b>";
					} else {
						*/
						echo "
							<h5>Application Details</h5>
							<table class='table table-sm table-bordered'>
							<thead>
								<tr>
									<th>Appl. Type</th>
									<th>Appl. Id</th>
									<th>Firm Name</th>
									<th>District</th>
									<th>Position</th>
									<!--<th>Available With</th>-->
									<!--<th>Status</th>-->";

							echo "</tr>
							</thead>
							<tbody>
								<tr>
									<td>".$resultArray['process']."</td>
									<td>".$customer_id."</td>
									<td>".$resultArray['firm_data']['firm_name']."</td>
									<td>".$resultArray['firm_data']['district']."</td>
									<td>".$resultArray['current_position']."</td>
									<!--<td>".$resultArray['currentPositionUser']." <br>( ".$resultArray['getEmailCurrent']." )"."</td>-->";
									//added by laxmi on 13-12-23
									/*if(!empty($rejectedData['customer_id']) && $rejectedData['customer_id'] == $customer_id){
										echo "<td>Rejected</td>";
									}else{//added appl_status on 19-07-2023 by Amol
										echo "<td>".$resultArray['appl_status']."</td>";
									}*/

							echo "</tr>
							</tbody>
						</table>";
				//	}
			//	}
		//	}

		}else{
			echo $resultArray['no_result'];
		}

		exit;
	}


	public function appealHistory(){

		$username = $this->Session->read('username');
		$conn = ConnectionManager::get('default');

		//get posted office id
		$postedOffice = $this->DmiUsers->getPostedOffId($username);





		$office_type = $this->DmiRoOffices->find('all',array('fields'=>'office_type','conditions'=>array('ro_email_id' => $username),'order'=>'id desc'))->first();

        if($office_type['office_type']=='RO'){
		$roDistricts = $this->DmiRoOffices->find('list', array('fields' => array('id'), 'conditions' => array('ro_email_id' => $username)))->toArray();
		}
		else{

					$roDistricts = $this->DmiRoOffices
			->find()
			->select(['ro_id_for_so'])
			->where(['ro_email_id' => $username])
				->toArray();

			$roIdForSoArray = [];
			foreach ($roDistricts as $roDistrict) {
				$roIdForSoArray[] = $roDistrict['ro_id_for_so'];
			}
			$roDistricts=$roIdForSoArray;

		}

		if (!empty($roDistricts)) {
			$districtlist = $this->DmiDistricts->find('list', array('fields' => array('id'), 'conditions' => array('ro_id IN' => $roDistricts)))->toArray();
		} else {
			$districtlist = $this->DmiDistricts->find('list', array('fields' => array('id'), 'conditions' => array('ro_id IS' => $postedOffice)))->toArray();
		}

		$appealApprovedList = array();

		foreach ($districtlist as $each) {
			$firmDetails = $conn->execute("SELECT dms.id, dms.customer_id, dms.pdf_file,
			df.firm_name, df.email, df.mobile_no, dms.date
			FROM dmi_apl_grant_certificate_pdfs AS dms
			INNER JOIN dmi_firms AS df ON df.customer_id = dms.customer_id
			INNER JOIN dmi_districts AS dd ON dd.id = df.district::INTEGER
			WHERE df.district = '$each' ")
			->fetchAll('assoc');

			if (!empty($firmDetails)) {
				$appealApprovedList[] = $firmDetails;
			}

		}

		$filteredRecords = [];
		foreach ($appealApprovedList as $subarray) {
			foreach ($subarray as $each) {
				// Add the record to the filtered array
				$filteredRecords[] = $each;
			}
		}
		$this->set('approved_appeal_list', $filteredRecords);
	}




    //This function are written by shankhpal shende on 07/09/2023
	// for biannually grading report
	public function listOfBgrReport(){



		$fetch_all_granted_pdf = $this->DmiBgrGrantCertificatePdfs->find('all',array('fields'=>array('customer_id','id','pdf_file','created','pdf_version','user_email_id'),'order'=>'id DESC'))->toArray();



		if(!empty($fetch_all_granted_pdf)){

			$appl_array_ca = array();
			$i=0;
			foreach ($fetch_all_granted_pdf as $eachrecord) {


				$customer_id = $eachrecord['customer_id'];

				$firm_details = $this->DmiFirms->firmDetails($customer_id);
				$firm_name = $firm_details['firm_name'];
				$firm_table_id = $firm_details['id'];

				$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
				$appl_type_id = 11;
				$report_form = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;

				$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
				if($split_customer_id[1] == 1){
					$cert_type = 'CA';

				}elseif($split_customer_id[1] == 2){
					$cert_type = 'Printing Press';

				}elseif($split_customer_id[1] == 3){
					$cert_type = 'Laboratory';

				}

				$report_pdf_field = 'pdf_file';
				$report_pdf = $eachrecord['pdf_file'];
				$pdf_version = $eachrecord['pdf_version'];

				$firm_details = $this->DmiFirms->firmDetails($customer_id);
				$firm_name = $firm_details['firm_name'];
				$firm_table_id = $firm_details['id'];

				$appl_array[$i]['cert_type'] = $cert_type.' - '.$form_type;
				$appl_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
				$appl_array[$i]['firm_name'] = $firm_name;
				$appl_array[$i]['grant_date'] = $eachrecord['created'];
				$appl_array[$i]['report_form'] = $report_form;
				$appl_array[$i]['report_pdf'] = $report_pdf;
				$appl_array[$i]['pdf_version'] = $pdf_version;

				$i=$i+1;

			}
		}

		$this->set('appl_array',$appl_array);

	}




}

?>
