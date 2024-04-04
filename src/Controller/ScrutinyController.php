<?php
namespace App\Controller;
use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Core\Exception\Exception;
class ScrutinyController extends AppController{


    private $DmiChangeFieldLists;
    private $DmiChemistComments;
    private $DmiChangeApplDetails;
    private $DmiFlowWiseTablesLists;
    private $DmiApplicationCharges;
    private $MCommodity;
    private $MCommodityCategory;
    private $DmiPackingTypes;
    private $DmiChangeFirms;
    private $DmiHoCommentReplyDetails;
    private $DmiChemistTrainingAtRo;
    private $DmiChemistRoToRalLogs;
    private $DmiUserRoles;
    private $DmiSmsEmailTemplates;
    private $DmiCommonScrutinyFlowDetails;
    private $DmiAllDirectorsDetails;
    private $DmiChangeSelectedFields;
    private $DmiAllocations;
    private $DmiChemistRegistrations;
    private $DmiFirms;


    public function initialize(): void {

		parent::initialize();

        // added by shankhpal on 27-03-2024
        $components = [
            'Romoioapplicantcommunicationactions',
            'Communication',
            'Customfunctions',
            'Mastertablecontent',
            'Progressbar',
            'Paymentdetails',
            'Flowbuttons',
            'Randomfunctions',
            'AqcmsWrapper'
        ];

        foreach ($components as $component) {
            $this->loadComponent($component);
        }



		//Set Helpers
		$this->viewBuilder()->setHelpers(['Form', 'Html', 'Time']);
		//Set Sessions
		$this->Session = $this->getRequest()->getSession();

        // Call the loadAllModels method to load necessary models.
        $this->loadAllModels();

	}

    public function beforeFilter($event) {

        parent::beforeFilter($event);




        $this->viewBuilder()->setHelpers(['Form','Html']);

        if($this->Session->read('username') == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
        }else{

            //check if user have inspection/scrutiny role
            $user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$this->Session->read('username'))))->first();
            if(
                $user_access['super_admin'] == 'yes' ||
                $user_access['dy_ama'] == 'yes' ||
                $user_access['jt_ama'] == 'yes' ||
                $user_access['ama'] == 'yes' ||
                $user_access['form_verification_home'] == 'yes'
            ){
            //proceed
            }else{
                $this->customAlertPage("Sorry You are not authorized to view this page..");
            }

            $this->set('user_access',$user_access);
        }



    }
    //end of beforeFilter function



    /**
     * Loads all necessary models for the current controller.
     * This method is responsible for loading models required for the controller's functionality.
     * Author: Shankhpal Shende
     * Date: 28-03-2024
     */
    private function loadAllModels(): void {

        $this->DmiChangeFieldLists = $this->AqcmsWrapper->customeLoadModel('DmiChangeFieldLists');
        $this->DmiChemistComments = $this->AqcmsWrapper->customeLoadModel('DmiChemistComments');
        $this->DmiChangeApplDetails = $this->AqcmsWrapper->customeLoadModel('DmiChangeApplDetails');
        $this->DmiFlowWiseTablesLists = $this->AqcmsWrapper->customeLoadModel('DmiFlowWiseTablesLists');
        $this->DmiApplicationCharges = $this->AqcmsWrapper->customeLoadModel('DmiApplicationCharges');
        $this->MCommodity = $this->AqcmsWrapper->customeLoadModel('MCommodity');
        $this->MCommodityCategory = $this->AqcmsWrapper->customeLoadModel('MCommodityCategory');
        $this->DmiPackingTypes = $this->AqcmsWrapper->customeLoadModel('DmiPackingTypes');
        $this->DmiChangeFirms = $this->AqcmsWrapper->customeLoadModel('DmiChangeFirms');
        $this->DmiHoCommentReplyDetails = $this->AqcmsWrapper->customeLoadModel('DmiHoCommentReplyDetails');
        $this->DmiChemistTrainingAtRo = $this->AqcmsWrapper->customeLoadModel('DmiChemistTrainingAtRo');
        $this->DmiChemistRoToRalLogs = $this->AqcmsWrapper->customeLoadModel('DmiChemistRoToRalLogs');
        $this->DmiUserRoles = $this->AqcmsWrapper->customeLoadModel('DmiUserRoles');
        $this->DmiSmsEmailTemplates = $this->AqcmsWrapper->customeLoadModel('DmiSmsEmailTemplates');
        $this->DmiCommonScrutinyFlowDetails = $this->AqcmsWrapper->customeLoadModel('DmiCommonScrutinyFlowDetails');
        $this->DmiAllDirectorsDetails = $this->AqcmsWrapper->customeLoadModel('DmiAllDirectorsDetails');
        $this->DmiChangeSelectedFields = $this->AqcmsWrapper->customeLoadModel('DmiChangeSelectedFields');
        $this->DmiAllocations = $this->AqcmsWrapper->customeLoadModel('DmiAllocations');
        $this->DmiChemistRegistrations = $this->AqcmsWrapper->customeLoadModel('DmiChemistRegistrations');
        $this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
    }

    public function home(){

        $this->Session->delete('form_section_id');
        $this->Session->delete('report_application_subtype');
        $this->Session->delete('application_view_mode');
        $this->Session->delete('current_sequence');
        $this->Session->delete('form_type');
        //$this->Session->write('application_type','new');//for new applications home
    }

    /* Start common functionality for form scrutiny */

    public function formScrutinyFetchId($id,$mode,$application_type,$fromHoLevel=null){



        //added conditions for chemist flow, get details from chemist registration table
        //on 30-09-2021 by Amol
        if($application_type==4){

            $customer_id_result = $this->DmiChemistRegistrations->find('all',array('fields'=>'chemist_id', 'conditions'=>array('id IS'=>$id)))->first();
            $customer_id = $customer_id_result['chemist_id'];
        }else{
            $customer_id_result = $this->DmiFirms->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$id)))->first();
            $customer_id = $customer_id_result['customer_id'];
        }

        $this->Session->write('customer_id',$customer_id);
        $this->Session->write('application_mode',$mode);
        $this->Session->write('fromHoLevel',$fromHoLevel);
        $this->Session->write('application_type',$application_type);
        $this->Session->delete('section_id');

        $this->redirect('/scrutiny/form-scrutiny');
    }

    public function section($id){

        $this->Session->write('section_id',$id);
        $this->redirect('/scrutiny/form-scrutiny');
    }

    public function formScrutiny(){

        $message = "";
        $message_theme = "";
        $redirect_to = "";

        $this->set('cname','scrutiny');
        $this->set('fromHoLevel',$this->Session->read('fromHoLevel'));


        $customer_id = $this->Customfunctions->checkCustomerIdAvailable($this->Session->read('customer_id'));
        $this->set('customer_id',$customer_id);

        $oldapplication = $this->Customfunctions->isOldApplication($customer_id);
        $this->set('oldapplication',$oldapplication);

        // Find out the officer present in SO office, Done by Pravin Bhakare 12-10-2021
        $officerPresentInOff = $this->Customfunctions->findOfficerCountInoffice($this->Session->read('username'));
        $this->set('officer_present_in_off',$officerPresentInOff);

        $authRegFirm = $this->Mastertablecontent->authFirmRegistration($customer_id);
        $this->set('authRegFirm',$authRegFirm);

        $application_type = $this->Session->read('application_type');
        $this->set('application_type',$application_type);

        $document_lists = $this->Mastertablecontent->allDocumentsList();
        $this->set('document_lists',$document_lists);

        //added conditions for chemist flow,
        //on 30-09-2021 by Amol
        if($application_type != 4){
            $this->Customfunctions->showOldCertDetailsPopup($customer_id);
        }

        //commented on 13-04-2023 as per change updates
        /*$changefields = array();
        if($application_type == 3){

            $this->changeApplication();
            $changefields = $this->Session->read('changefield');
        }
        $this->set('changefields',json_encode($changefields));*/

        /*$selectedSections = array();
        if($application_type == 3){
            $this->loadModel('DmiChangeSelectedFields');
            $selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
            $selectedSections = $selectedfields[2];
        }
        $this->set('selectedSections',$selectedSections);*/
        //commented above code and added below one for change module
        //on 13-04-2023 by Amol
        if($application_type == 3){

            $this->changeApplication();

            $selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
            $selectedValues = $selectedfields[0];
            $this->set('selectedValues',$selectedValues);
        }

        if($oldapplication == 'yes' && $application_type == 1){

            $this->viewBuilder()->setLayout('old_app_scrutiny_layout');
        }else{

            $this->viewBuilder()->setLayout('form_scrutiny_layout');
        }



        $user_email_id = $this->Session->read('username');
        $user_as = $this->Session->read('user_as');
        //$this->Session->write('current_level','level_3');
        $current_level = $this->Session->read('current_level');
        $this->set('current_level',$current_level);

        $application_mode = $this->Session->read('application_mode');
        $this->set('application_mode',$application_mode);


        $this->Session->write('application_type',$application_type);
        //added appl type 4  for chemist training by laxmi B. on 22-12-2022
        if ($application_type== 4) {

            //added application_dashboard in session [laxmi - 31/05/23]
              $this->Session->write('application_dashboard','ro');


            $chemistdetails = $this->DmiChemistRegistrations->find('all', array('conditions'=>array('chemist_id IS'=>$customer_id)))->first();

            //set in session variable for chemist training completed or not
            if(!empty($chemistdetails['is_training_completed'])){
                $this->Session->write('is_training_completed', $chemistdetails['is_training_completed']);
                $this->set('is_training_completed', $chemistdetails['is_training_completed']);
                // set packer id in session
                $this->Session->write('packer_id', $chemistdetails['created_by']);
            }


            $isforwardedtoral = $this->DmiChemistRoToRalLogs->find('all', array('fields'=>'is_forwordedtoral', 'conditions'=>array('chemist_id IS'=>$customer_id , 'is_forwordedtoral IS NOT'=>null)))->first();
            if(!empty($isforwardedtoral['is_forwordedtoral'])){
                $this->set('is_forwordedtoral', $isforwardedtoral['is_forwordedtoral']);
                $this->Session->write('is_forwordedtoral',$isforwardedtoral['is_forwordedtoral']);
            }else{
                $isforwardedtoral = 'no';
                $this->set('is_forwordedtoral', $isforwardedtoral);
                $this->Session->write('is_forwordedtoral',$isforwardedtoral);
            }



            $trainingCompleteAtRo = $this->DmiChemistTrainingAtRo->find('all', array('fields'=>'training_completed', 'conditions'=>array('chemist_id IS'=>$customer_id , 'training_completed IS'=>1)))->first();

            if(!empty($trainingCompleteAtRo)){
                $this->Session->write('trainingCompleteAtRo',$trainingCompleteAtRo['training_completed']);
                $this->set('trainingCompleteAtRo', $trainingCompleteAtRo['training_completed']);
            }
        }


        //-----------------------------------------------------------------------------------------------------
        // This section is added for the BGR module when the application type is set to 11 during a session. At that time, if $_SESSION['packer_id'] is set, then $customer_id will be assigned the value of $_SESSION['packer_id']. Otherwise, $customer_id will be assigned the value of $_SESSION['customer_id'].
        // written by :- shankhpal shende on 24/08/2023

        if($application_type == 11){
            if(isset($_SESSION['packer_id'])){
            $customer_id = $_SESSION['packer_id'];
            }elseif(isset($_SESSION['customer_id'])){
                $customer_id = $_SESSION['customer_id'];
            }else{
                $customer_id = null;
            }

        }
        //----------------------------------------------------------------------------------------------------------

        if($current_level){
            $back_to_inspection_level = $current_level;
        }else{
            $back_to_inspection_level = 'level_3';
        }
        $this->set('back_to_inspection_level',$back_to_inspection_level);

        $grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

        //Added on 01-09-2017 check lab export unit
        $export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
        $this->set('export_unit_status',$export_unit_status);

       //export_unit field set in session by laxmi on 09-01-2023
        $this->Session->write('export_unit', $export_unit_status);

        $added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
        $this->set('added_directors_details',$added_directors_details);

        // This function find out the lastest version of Application pdf (By pravin 08-08-2017)
        $download_application_pdf = $this->Customfunctions->findLatestApplicationPdf($customer_id);
        $this->set('download_application_pdf',$download_application_pdf);

        $office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
        $this->set('office_type',$office_type);

        $firm_type = $this->Customfunctions->firmType($customer_id);
        $this->set('firm_type',$firm_type);

        $firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
        $form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

        $final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
        $this->set('final_submit_details',$final_submit_details);

        $ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
        $this->set('ca_bevo_applicant',$ca_bevo_applicant);

        $applicant_type = $this->Customfunctions->checkFatSpreadOrBevo($customer_id);//call fucntion to check bevo or fat spread
        $this->set('applicant_type',$applicant_type);

        //added call to function to check MSEO or BEVO, on 12-09-2023 by Amol
        $MseoOrBevo = $this->Customfunctions->checkMseoOrBevo($customer_id);
        $this->set('MseoOrBevo',$MseoOrBevo);

        if($form_type=='F' && $ca_bevo_applicant=='yes'){
            $form_type='E';
        }

        $this->set('form_type',$form_type);

        // check current form section value
        if(isset($_SESSION['section_id'])){
            $section_id = $this->Session->read('section_id');
        }else{
            $section_id = 1;
            $this->Session->write('section_id',$section_id);
        }

       //added middle name type in array and set for view side like S/o, W/o, D/o by laxmi B on 11-07-2023
        if ($_SESSION['application_type'] == 4) {
            $middle_type = array('S/o'=>'S/o', 'D/o'=>'D/o', 'W/o'=>'W/o');
            $this->set('middle_type', $middle_type);
        }

        // Predefine value
        $business_type = $this->Mastertablecontent->allBusinessType();
        $this->set('business_type',$business_type);
        $state_list = $this->Mastertablecontent->allStateValue();
        asort($state_list);
        $this->set('state_list',$state_list);
        $distict_list = $this->Mastertablecontent->allDistrictValue();
        $this->set('distict_list',$distict_list);
        $all_ca_business_year = $this->Mastertablecontent->allCaBusinessYear();
        $this->set('all_ca_business_year',$all_ca_business_year);
        $all_printing_business_year = $this->Mastertablecontent->allPrintingBusinessYear();
        $this->set('all_printing_business_year',$all_printing_business_year);
        $rushing_refining_period = $this->Mastertablecontent->allCrushingRefiningValue();
        $this->set('rushing_refining_period',$rushing_refining_period);
        $firm_details = $this->DmiFirms->firmDetails($customer_id);
        $this->set('firm_details',$firm_details);

        //added this method call on 13-04-2023 to commoity and packing types details
        $this->Randomfunctions->getCommodityDetails($firm_details,$firm_type);

        $Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

        $Dmi_allocation_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'allocation');
        $Dmi_allocation_table = TableRegistry::getTableLocator()->get($Dmi_allocation_table_name);

        $appl_current_pos_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'appl_current_pos');
        $Dmi_appl_current_pos_table = TableRegistry::getTableLocator()->get($appl_current_pos_table_name);

        $final_submit_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'application_form');
        $Dmi_final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table_name);

        // get current section all details
        $section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,$section_id);

        // get all section all details
        $allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

        $section_model = $section_details['section_model'];
        $section = $section_details['section_name'];
        $this->set('section',$section);

        // get section details
        // updated by shankhpal on 27-03-2024
        $sectionModelTable = $this->AqcmsWrapper->loadModel($section_model);
        $section_form_details = $sectionModelTable->sectionFormDetails($customer_id);

        // if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
        $all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

        // get previous and next button id
        $nextPreviousBtn =    $this->Customfunctions->getNextPreSec($allSectionDetails);
        $this->set('previousbtnid',$nextPreviousBtn[0]);
        $this->set('nextbtnid',$nextPreviousBtn[1]);



        //added below query on 27-07-2023 by Akash
        //to handle cases like when appl. refereed back from HO, RO comments to appicant and applicant replied.
        //now when RO again scrutinized the applicant replies, then no need to again enter records in HO allocation table as pending.
        //RO will comment back from HO inspection comments window.
        $Dmi_ho_allocation = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['ho_level_allocation']);
        $checkHoAllocationEntry = $Dmi_ho_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition),'order'=>'id DESC'))->first();
        $this->set('checkHoAllocationEntry',$checkHoAllocationEntry);

        //This method and variable is to check if the inspection is required or not for perticular application
        //It will used to check the condition at element button "Forward To" from ro_mo_applicant_communication .
        //This added to resolve the button appearing when the form scrutiny is opened from ho communication
        // Akash Thakre [28-07-2023]
        $isSiteInspectionRequired = $this->Customfunctions->isSiteInspectionRequired($customer_id,$application_type);
        $this->set('isSiteInspectionRequired',$isSiteInspectionRequired);

        if(!empty($final_submit_details)){
            $final_submit_status = $final_submit_details['status'];
        }else{
            $final_submit_status = 'no_final_submit';
        }
        $this->set('final_submit_status',$final_submit_status);

        $progress_bar_status = $this->Progressbar->formsProgressBarStatus($allSectionDetails,$customer_id);
        $this->set('progress_bar_status',$progress_bar_status);

        $formScrutinyStatus = $Dmi_final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'approved','current_level'=>'level_1', $grantDateCondition)))->first();

        // fetch all allocation details
        $allocation_deatils = $Dmi_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
        $this->set('allocation_deatils',$allocation_deatils);

        // get current position, added on 17-11-2023 by Amol
        $getCurrentPos = $Dmi_appl_current_pos_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
        $this->set('getCurrentPos',$getCurrentPos);

        //to check if report filed, then hide comm. with MO box, and show button for back to report
        //allow RO to referred back to applicant from report inspection window.
        $check_if_report_filed_id_object = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['inspection_report']);
        $check_if_report_filed_id = $check_if_report_filed_id_object->siteinspectionFinalReportStatus($customer_id);
        if(!empty($check_if_report_filed_id)){
            $check_if_report_filed= $check_if_report_filed_id;
        }else{
            $check_if_report_filed = null;
        }

        // This condition work for optional siteinspection for renewal flow.
        if(!empty($formScrutinyStatus) && empty($allocation_deatils['level_2'])){
            $check_if_report_filed = 'yes';
        }

        $this->set('check_if_report_filed',$check_if_report_filed);

        // Get chemist comment history details. Done by Aakash Thakare 30-09-2021
        $this->Session->write('application_dashboard','ro');
        $this->Communication->singleWindowCommentHistory($customer_id);


        //to check ro current comment to // added on 26-05-2017 by amol
        $level3_current_comment_to = $this->Customfunctions->checkLevel3CurrentCommentTo($allSectionDetails,$customer_id);
        $this->set('level3_current_comment_to',$level3_current_comment_to);

        $fetch_comment_reply = $this->Customfunctions->getCommentReply($section_model,$customer_id);
        $this->set('fetch_comment_reply',$fetch_comment_reply);
        $fetch_applicant_communication = $this->Customfunctions->getApplicantCommentReply($section_model,$customer_id);
        $this->set('fetch_applicant_communication',$fetch_applicant_communication);

        //get forward btn and grant btn display status value
        $forward_to_btn = $this->Flowbuttons->ShowNodalLevelForwardBtnAfterScru($customer_id,$application_type,$section_details,$allSectionDetails);
        $this->set('forward_to_btn',$forward_to_btn);
        $final_granted_btn = $this->Flowbuttons->ShowNodalLevelGrantBtnAfterScru($customer_id,$application_type,$section_details,$allSectionDetails);
        $this->set('final_granted_btn',$final_granted_btn);

        $this->set('tablename',$section_model);
        $this->set('current_form_data',$section_form_details[0]);
        $last_record_with_delete_null = $this->Communication->lastRecordWithDeleteNull($section_model,$customer_id);

        $this->Communication->editDeleteOptionForMoRoCommunication($section_model,$customer_id,$current_level,$last_record_with_delete_null);

        //If the application is Referred back from the Head Office then the Applicant Side Communiaction should be availble - Akash[15-05-2023]
        //The non-empty condition is added to the below code to ensure that the non-empty values - Akash [17-07-2023]


        $check_ho_comm = $this->DmiHoCommentReplyDetails->find()->where(['customer_id IS' => $customer_id])->order('id DESC')->first();
        if (!empty($check_ho_comm)) {
            $check_ho_comm = $check_ho_comm['to_user'];
        } else {
            $check_ho_comm = null;
        }

        $this->set('check_ho_comm',$check_ho_comm);

        //For MO comment
        if (null!==($this->request->getData('save_edited_mo_comment'))){

            $htmlencoded_comment_by_mo = $this->request->getData('edited_mo_comment');

            if(!empty($this->request->getData('mo_comment_ul')->getClientFilename())){

                $file_name = $this->request->getData('mo_comment_ul')->getClientFilename();
                $file_size = $this->request->getData('mo_comment_ul')->getSize();
                $file_type = $this->request->getData('mo_comment_ul')->getClientMediaType();
                $file_local_path = $this->request->getData('mo_comment_ul')->getStream()->getMetadata('uri');

                $mo_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

            }else{ $mo_comment_ul = $section_form_details[0]['mo_comment_ul']; }

            $redirect_location = '/scrutiny/form-scrutiny';
            $this->Communication->saveEditedMoComment($section_model,$htmlencoded_comment_by_mo,$mo_comment_ul,$redirect_location);
        }

        //For RO reply
        if (null!==($this->request->getData('save_edited_ro_reply'))){

            $htmlencoded_reply_by_ro = $this->request->getData('edited_ro_reply');

            if(!empty($this->request->getData('rr_comment_ul')->getClientFilename())){

                $file_name = $this->request->getData('rr_comment_ul')->getClientFilename();
                $file_size = $this->request->getData('rr_comment_ul')->getSize();
                $file_type = $this->request->getData('rr_comment_ul')->getClientMediaType();
                $file_local_path = $this->request->getData('rr_comment_ul')->getStream()->getMetadata('uri');

                $rr_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

            }else{ $rr_comment_ul = $section_form_details[0]['rr_comment_ul']; }

            $redirect_location = '/scrutiny/form-scrutiny';
            $this->Communication->saveEditedRoReply($section_model,$htmlencoded_reply_by_ro,$rr_comment_ul,$redirect_location);
        }


        //Start code for Edit/Delete options For RO on Communication with Applicant.
        if($current_level == 'level_3'){

            $reffered_back_date = $section_form_details[0]['reffered_back_date'];
            $this->Communication->editDeleteOptionForRoApplicantCommunication($customer_id,$reffered_back_date);

            if (null!==($this->request->getData('save_edited_referred_back'))){

                $htmlencoded_edited_referred_back = $this->request->getData('edited_referred_back');

                if(!empty($this->request->getData('rb_comment_ul')->getClientFilename())){

                    $file_name = $this->request->getData('rb_comment_ul')->getClientFilename();
                    $file_size = $this->request->getData('rb_comment_ul')->getSize();
                    $file_type = $this->request->getData('rb_comment_ul')->getClientMediaType();
                    $file_local_path = $this->request->getData('rb_comment_ul')->getStream()->getMetadata('uri');

                    $rb_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

                }else{ $rb_comment_ul = $section_form_details[0]['rb_comment_ul']; }

                $redirect_location = '/scrutiny/form-scrutiny';
                $this->Communication->saveEditedReferredBack($section_model,$htmlencoded_edited_referred_back,$rb_comment_ul,$redirect_location);
            }
        }
        //End code for Edit/Delete options For RO on Communication with Applicant.

        // Saved referredback comments in chemist flow, Done Akash [30-09-2021]
        if(null!==($this->request->getData('che_ro_referred_back'))){

            $result = $this->Communication->singleWindowReferredback($this->request->getData(),$allSectionDetails);
            if($result == 1)
            {
                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
                $message =$firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Reffered back comments to applicant saved successfully";
                $message_theme = "success";
                $redirect_to = '../scrutiny/form-scrutiny';

            }elseif($result == 2){
                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
                $message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reffered back";
                $message_theme = "failed";
                $redirect_to = '../scrutiny/form-scrutiny';
            };

        }elseif (null!==($this->request->getData('accepted'))){

            //commented code removed on 14-04-2017


        //referred back by RO to Applicant
        }elseif(null!==($this->request->getData('ro_referred_back'))) {


            //called this component to optimized code on 02-04-2018
            $result = $this->Romoioapplicantcommunicationactions->referredBackTo($customer_id,$section_model,$section_form_details[0],$this->request->getData('reffered_back_comment'),$this->request->getData('rb_comment_ul'),'Level3ToApplicant');

            if($result == 1)
            {
                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
                $message =$firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Reffered back comments to applicant saved successfully";
                $message_theme = "success";
                $redirect_to = '../scrutiny/form-scrutiny';

            }elseif($result == 2){

                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
                $message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reffered back";
                $message_theme = "warning";
                $redirect_to = '../scrutiny/form-scrutiny';
            };


        // Referred Back by MO to RO
        }elseif (null!==($this->request->getData('mo_referred_back'))) {

            //called this component to optimized code on 02-04-2018
            $result = $this->Romoioapplicantcommunicationactions->referredBackTo($customer_id,$section_model,$section_form_details[0],$this->request->getData('comment_by_mo'),$this->request->getData('mo_comment_ul'),'Level1ToLevel3');

            if($result == 1){

                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
                $message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Reffered back comments to $office_type saved successfully ";
                $message_theme = "success";
                $redirect_to = "../scrutiny/form-scrutiny";

            }elseif($result == 2){

                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
                $message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reffered back";
                $message_theme = "warning";
                $redirect_to = "../scrutiny/form-scrutiny";
            }

        //Ro Reply to MO
        }elseif (null!==($this->request->getData('ro_reply'))) {

            //called this component to optimized code on 02-04-2018
            $result = $this->Romoioapplicantcommunicationactions->RO2MOcommunication($customer_id,$section_model,$section_form_details[0],$this->request->getData('ro_reply_comment'),$this->request->getData('rr_comment_ul'),'Level3ToLevel1');

            if($result == 1 || $result == 3){

                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
                $message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, reply to Scrutinizer saved successfully";
                $message_theme = "success";
                $redirect_to = "../scrutiny/form-scrutiny";

            }elseif($result == 2){

                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
                $message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reply";
                $message_theme = "warning";
                $redirect_to = "../scrutiny/form-scrutiny";

            }elseif($result == 4){

                $this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
                $message = "Scrutinizer is not allocated for this Application yet";
                $message_theme = "failed";
                $redirect_to = "../scrutiny/form-scrutiny";
            }
            //For Form Scrutiny by RO only, not by MO now

        }elseif (null!==($this->request->getData('sent_to_applicant'))){

            //called this component to optimized code on 02-04-2018
            $result = $this->Romoioapplicantcommunicationactions->RO2ApplicantCommentFinalSubmit($customer_id,$section_model,$level3_current_comment_to,$current_level,$allSectionDetails);
            if($result == 1){

                //This below action call is added t save the action log for the user by AKASH on 18-08-2022
                $this->Customfunctions->saveActionPoint('Application Reffered Back to Applicant', 'Success');
                $message = 'All reffered back comments to applicant sent successfully';
                $message_theme = "success";
                $redirect_to = "../dashboard/home";

            }elseif($result == 2){

                //This below action call is added t save the action log for the user by AKASH on 18-08-2022
                $this->Customfunctions->saveActionPoint('Application Reffered Back to Applicant', 'Failed');
                $message = $firm_type_text." - Reffered back are not saved, Please save Referred back on Atleast one Section and then Final Submit";
                $message_theme = "warning";
                $redirect_to = "../scrutiny/form-scrutiny";
            }

        }elseif (null!== ($this->request->getData('sent_to'))){

            //called this component to optimized code on 02-04-2018
            $result = $this->Romoioapplicantcommunicationactions->RO2MOandMO2ROCommentFinalSubmit($customer_id,$level3_current_comment_to,$office_type,$allSectionDetails);
            if($result[0] == 1){

                //This below action call is added t save the action log for the user by AKASH on 19-08-2022
                $this->Customfunctions->saveActionPoint('Application Sent to Scrutinizer', 'Success');
                $message = $firm_type_text." application sent to $result[1] successfully";
                $message_theme = "success";
                $redirect_to ="../dashboard/home";

            }elseif($result[0] == 2){

                //This below action call is added t save the action log for the user by AKASH on 19-08-2022
                $this->Customfunctions->saveActionPoint('Application Sent to Scrutinizer', 'Failed');
                $message = $firm_type_text." - Replies/Comments are not saved, Please save Comments on Atleast one Section and then Final Submit";
                $message_theme = "failed";
                $redirect_to = "../scrutiny/form-scrutiny";

            }elseif($result[0] == 3){

                //This below action call is added t save the action log for the user by AKASH on 19-08-2022
                $this->Customfunctions->saveActionPoint('Application Sent to Scrutinizer', 'Failed');
                $message = "Scrutinizer is not allocated for this application yet";
                $message_theme = "failed";
                $redirect_to ="../scrutiny/form-scrutiny";
            }

        }elseif (null!== ($this->request->getData('mo_verified'))) { //only done by RO but recorded as level 1 entry

            //called this component to optimized code on 02-04-2018

            $result = $this->Romoioapplicantcommunicationactions->ROScrutinizedATMOLevel($customer_id,$section_model,$section_form_details[0],$allSectionDetails);

            if($result == 1 && $oldapplication=='yes' && ($application_type==1 || $application_type==6)){//added appl type 6 cond. on 23-11-2021

                $this->Romoioapplicantcommunicationactions->ROScrutinizedOldApplication($customer_id);

                //This below action call is added t save the action log for the user by AKASH on 19-08-2022
                $this->Customfunctions->saveActionPoint('All Section Scrutinized', 'Success');
                $message = $firm_type_text." - All sections scrutinized and backlog data verified successfully";
                $message_theme = "success";
                $redirect_to =  '../dashboard/home';

            }elseif($result == 1){

                //as per new order by 01-04-2021 from DMI
                //if lab is NABL accreditated then no site inspection will be done, forwarded to HO
                //applied on 29-09-2021 by Amol
                $NablDate = $this->Randomfunctions->checkIfLabNablAccreditated($customer_id);

                //added on 13-04-2023 by Amol, to check if appl is for change and having Inspection or not
                $changeInspection = $this->Customfunctions->inspRequiredForChangeApp($customer_id,$application_type);

                //applied this condition for lab export, on 01-09-2017 by Amol
                //if all Sections scrutinized then stay on same page and show forward to HO btn directly
                // # If Block Statement Updated for the Application Type 8 (ADP Flow)- Shankhpal [17/11/2022]

                if(
                    (($export_unit_status == 'yes' || $NablDate != null) && $firm_type == 3 ) ||  # for the lab export
                    ($firm_type == 3 && $export_unit_status == 'yes' && $application_type == 8) || # for the routine inspection
                    ($application_type == 9 && $firm_type == 3) || # for the surrender application if firm is lab - Akash [17-08-2023]
                    ($application_type==12 && $office_type == 'RO') #for the appeal application : Akash Joshi [21-09-2023]
                ){

                    //to send the application to Head Office after scrutiny
                    $result = $this->Romoioapplicantcommunicationactions->sendToHeadOffice($customer_id,$application_type,$checkHoAllocationEntry);
                    if ($result == 1) {
                        $message = $firm_type_text." - All sections scrutinized and forwarded to HO for Approval";//changed message on 15-11-2023 as UAT suggestion by Amol
                    } elseif ($result == 2) {
                        $message = $firm_type_text." - All sections scrutinized, Please comment back to HO";
                    }
                    $this->Customfunctions->saveActionPoint('All Section Scrutinized', 'Success'); #Action
                    $message_theme = "success";
                    $redirect_to = "../dashboard/home";

                //For Surrender application = 9 with no Site Inspection forward to RO.
                }elseif($application_type==9 || $application_type==12 || ($application_type==3 && $changeInspection=='no')){//updated condition 21-12-2022 for change flow

                    //For Surrender Application
                    //For Appeal - Joshi, Akash
                    $this->Romoioapplicantcommunicationactions->afterScrutinyForwardToRo($customer_id,$application_type,$grantDateCondition,$Dmi_allocation_table,$Dmi_appl_current_pos_table);
                    $this->Customfunctions->saveActionPoint('All Section Scrutinized and Sent to the RO', 'Success'); #Action
                    $message = $firm_type_text." - All sections scrutinized and forwarded to RO successfully";
                    $message_theme = "success";
                    $redirect_to = "../dashboard/home";

                }else{

                    $this->Customfunctions->saveActionPoint('All Section Scrutinized', 'Success'); #Action
                    $message = $firm_type_text." - All sections scrutinized successfully";
                    $message_theme = "success";
                    $redirect_to =  '../dashboard/home';
                }

            }elseif($result == 2){

                $this->Customfunctions->saveActionPoint('Section Scrutinized', 'Success'); #Action
                $message = $firm_type_text." - ".ucwords(str_replace('_',' ',$section_details['section_name']))." Section Scrutinized successfully";
                $message_theme = "success";
                $redirect_to = "../scrutiny/form-scrutiny";

            }elseif($result == 3){

                $this->Customfunctions->saveActionPoint('Section Scrutinized', 'Failed'); #Action
                $message = $firm_type_text." - ".ucwords(str_replace('_',' ',$section_details['section_name']))." Section already Scrutinized";
                $redirect_to = "../scrutiny/form-scrutiny";

            }elseif($result == 4){

                $this->Customfunctions->saveActionPoint('Section Scrutinized', 'Failed'); #Action
                $message = "Please verify old dates entered before scrutinizing the section, The button is given above to edit/view the old dates.'";
                $message_theme = "failed";
                $redirect_to =  '../scrutiny/form-scrutiny';
            }
            //This button is used to Send from Mo to Ro & Ro to MO both

        }elseif(null!== ($this->request->getData('accepted_forward'))){

            if($forward_to_btn == 'RO'){

                $ro_email_id = $this->Customfunctions->getApplRegOfficeId($customer_id,$application_type);
                $allocation_id = $Dmi_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();

                $Dmi_allocation_Entity = $Dmi_allocation_table->newEntity(array(
                    'id'=>$allocation_id['id'],
                    'customer_id'=>$customer_id,
                    'level_4_ro'=>$ro_email_id,
                    'current_level'=>$ro_email_id,
                    'created'=>date('Y-m-d H:i:s'),
                    'modified'=>date('Y-m-d H:i:s')
                ));

                if($Dmi_allocation_table->save($Dmi_allocation_Entity));

                $final_report_status = 'level_4_ro';
                $user_email_id = $ro_email_id;
                $current_level = 'level_4_ro';
                $Dmi_appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level);

                #SMS: RO forwarded to HO
                $this->DmiSmsEmailTemplates->sendMessage(20,$customer_id);

                $this->Customfunctions->saveActionPoint('Application Forwarded', 'Success'); #Action
                $message = $firm_type_text.' Forwarded to '.$forward_to_btn.' successfully';
                $message_theme = "success";
                $redirect_to =  '../dashboard/home';
            }

        }

        $this->set('all_section_status',$all_section_status);
        $this->set('section_details',$section_details);
        $this->set('allSectionDetails',$allSectionDetails);
        $this->set('section_form_details',$section_form_details);

        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);
    }




    public function payment(){

        $customer_id = $this->Session->read('customer_id');
        if(!empty($customer_id)){

            // set variables to show popup messages from view file
            $message = '';
            $redirect_to = '';

            $this->viewBuilder()->setLayout('form_scrutiny_layout');



            if($this->Session->read('current_level')){

                $back_to_inspection_level = $this->Session->read('current_level');
            }else{
                $back_to_inspection_level = 'level_3';
            }
            $this->set('back_to_inspection_level',$back_to_inspection_level);

            $this->set('current_level',$this->Session->read('current_level'));


            // This function find out the lastest version of Application pdf (By pravin 08-08-2017)
            $download_application_pdf = $this->Customfunctions->findLatestApplicationPdf($customer_id);
            $this->set('download_application_pdf',$download_application_pdf);

            //check CA BEVO Applicant
            $ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
            $this->set('ca_bevo_applicant',$ca_bevo_applicant);

            $application_type = $this->Session->read('application_type');
            $office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);

            $firm_type = $this->Customfunctions->firmType($customer_id);
            $this->set('firm_type',$firm_type);

            // Find out the officer present in SO office, Done by Pravin Bhakare 12-10-2021
            $officerPresentInOff = $this->Customfunctions->findOfficerCountInoffice($this->Session->read('username'));
            $this->set('officer_present_in_off',$officerPresentInOff);

            $firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
            $form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
            $this->set('form_type',$form_type);
            $section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,1);

            $allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

            // get previous and next button id
            $previousBtn =    $this->Customfunctions->getNextPreSec($allSectionDetails);
            $previous_button_url = 'scrutiny/section/'.$previousBtn[2];

            // For change flow
            $selectedSections = array();
            if($application_type == 3){



                $selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
                $selectedSections = $selectedfields[2];
            }
            $this->set('selectedSections',$selectedSections);


            $level3_current_comment_to = $this->Customfunctions->checkLevel3CurrentCommentTo($allSectionDetails,$customer_id);
            $this->set('level3_current_comment_to',$level3_current_comment_to);

            $progress_bar_status = $this->Progressbar->formsProgressBarStatus($allSectionDetails,$customer_id);
            $this->set('progress_bar_status',$progress_bar_status);

            $oldapplication = $this->Customfunctions->isOldApplication($customer_id);

            $this->set('oldapplication',$oldapplication);

            // if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
            $all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

            $payment_table = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($application_type,'payment');

            $final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
            $this->set('final_submit_details',$final_submit_details);

            // customer id is changed to packer id to whoes create the chemist added by laxmi B. on 21-12-22
             if($application_type == 4){
                    $DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
                    $chemist_created_by = $DmiChemistRegistrations->find('list', array('valueField'=>'created_by', 'conditions'=>array('chemist_id IS'=>$customer_id)))->first();
                    if(!empty($chemist_created_by)){
                            $customer_id = $chemist_created_by;
                    }

                 }
            $firm_detail = $this->DmiChangeFirms->sectionFormDetails($customer_id);
            $firm_details = $firm_detail[0];
            $this->set('firm_details',$firm_details);
            // revert back customer id to chemist id added by laxmi B. on 21-12-22
            if($application_type == 4){
                 $customer_id = $_SESSION['customer_id'];
            }

            // Fetch submitted Payment Details and show // Done By pravin 13/10/2017
            $this->Paymentdetails->applicantPaymentDetails($customer_id,$firm_details['district'],$payment_table);

            $application_charge = $this->Customfunctions->applicationCharges($application_type,$firm_type);
            $this->set('application_charge',$application_charge);


             // updated by shankhpal on 27-03-2024
            $payment_table = $this->AqcmsWrapper->loadModel($payment_table);
            $list_applicant_payment_id = $payment_table->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
            if(!empty($list_applicant_payment_id)){ $process_query = 'Updated'; }else{ $process_query = 'Saved'; }

            //condition added for change module
            //to get changed commodities or packing types if applied in change
            //on 13-04-2023 by Amol
            if ($application_type == 3) {


                $getChangeDetails = $this->DmiChangeApplDetails->find('all',array('fields'=>array('commodity','packing_types'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
                if (!empty($getChangeDetails)) {
                    if (!empty($getChangeDetails['commodity'])){
                        $firm_details['sub_commodity'] = $getChangeDetails['commodity'];
                    }
                    elseif (!empty($getChangeDetails['packing_types'])) {
                        $firm_details['packaging_materials'] = $getChangeDetails['packing_types'];
                    }
                }

            }

            $sub_commodity_array = explode(',',$firm_details['sub_commodity']);
           //to hide commodities from application type 4 in checmist flow apply condition on or section by laxmi Bhadade on date 21-12-22
            if(!empty($firm_details['sub_commodity']) && $application_type != 4){
                $i=0;
                foreach($sub_commodity_array as $sub_commodity_id)
                {
                    $fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IN'=>$sub_commodity_id)))->first();
                    $commodity_id[$i] = $fetch_commodity_id['category_code'];

                    $sub_commodity_data[$i] =  $fetch_commodity_id;

                    $i=$i+1;
                }

                $unique_commodity_id = array_unique($commodity_id);

                $commodity_name_list = $this->MCommodityCategory->find('all',array('conditions'=>array('category_code IN'=>$unique_commodity_id)))->toArray();

                $this->set('commodity_name_list',$commodity_name_list);

                $this->set('sub_commodity_data',$sub_commodity_data);
            }


            if($application_type == 4){
                //for auto filled payment  fetch payment from table by laxmi on 13-07-2023 ,
                $payment_amt = $this->DmiChemistRegistrations->find('all', array('fields'=>['payment'], 'conditions'=>['chemist_id IS'=>$customer_id]))->first();
               $this->set('payment_amt',$payment_amt['payment']);

               $this->set('application_charge',$payment_amt['payment']);
            }

            //to hide firm details from application type 4 in checmist flow apply condition on or section by laxmi Bhadade on date 21-12-22
            if(!empty($firm_details['packaging_materials']) && $application_type != 4){

                $packaging_materials = explode(',',$firm_details['packaging_materials']);
                $packaging_type = $this->DmiPackingTypes->find('list', array('keyField'=>'id','valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_materials)))->toArray();
                $this->set('packaging_type',$packaging_type);
            }

            if(!empty($final_submit_details)){
                $final_submit_status = $final_submit_details['status'];
            }else{
                $final_submit_status = 'no_final_submit';
            }
            $this->set('final_submit_status',$final_submit_status);
            if($application_type == 4){
                    //for auto filled payment  fetch payment from table by laxmi on 13-07-2023 ,

                    $payment_amt = $this->DmiChemistRegistrations->find('all', array('fields'=>['payment'], 'conditions'=>['chemist_id IS'=>$customer_id]))->first();
                    $this->set('payment_amt',$payment_amt['payment']);

                    $this->set('application_charge',$payment_amt['payment']);
                }
            // set variables to show popup messages from view file
            $this->set('previous_button_url',$previous_button_url);
            $this->set('allSectionDetails',$allSectionDetails);
            $this->set('all_section_status',$all_section_status);
            $this->set('section_details',$section_details);
        }

    }

    //form RO to Applicant referred back
    public function editReferredBack(){
        $this->autoRender = false;
        $id = $_POST['referred_back_max_id'];
        $this->Session->write('edit_referred_back_id',$id);
    }

    public function deleteReferredBack(){
        $this->autoRender = false;
        $id = $_POST['referred_back_max_id'];
        $model_name = $_POST['model_name'];
        // updated by shankhpal on 27-03-2024
        $model_name = $this->AqcmsWrapper->loadModel($model_name);
        $model_name->delete(array('id'=>$id));
    }

    //MO and Level3 user comments edit and delete methods
    public function editMoComment(){
        $this->autoRender = false;
        $id = $_POST['mo_comment_max_id'];
        $this->Session->write('edit_mo_comment_id',$id);
    }

    public function deleteMoComment(){
        $this->autoRender = false;
        $id = $_POST['mo_comment_max_id'];
        $model_name = $_POST['model_name'];
        $model_name = $this->AqcmsWrapper->loadModel($model_name); // updated by shankhpal on 27-03-2024
        $model_name->delete(array('id'=>$id));
    }

    public function editRoReply(){
        $this->autoRender = false;
        $id = $_POST['ro_reply_max_id'];
        $this->Session->write('edit_ro_reply_id',$id);
    }

    public function deleteRoReply(){

        $this->autoRender = false;
        $id = $_POST['ro_reply_max_id'];
        $model_name = $_POST['model_name'];

        $model_name = $this->AqcmsWrapper->loadModel($model_name); // updated by shankhpal on 27-03-2024

        //to check if ro commented first & created new row then delete entire row
        $check_row_comment_by = $this->$model_name->find('all',array('conditions'=>array('id IS'=>$id)))->first();
        if($check_row_comment_by['mo_comment_date'] == null)
        {
            $this->$model_name->delete(array('id'=>$id));
        }
        else{
            $model_name_entity = $model_name->newEntity(array(
                'id'=>$id,
                'ro_reply_comment'=>null,
                'rr_comment_ul'=>null,
                'ro_current_comment_to'=>'both'
            ));
            //only update the row with ro_reply null
            $model_name->save($model_name_entity);
        }
    }

    public function deleteComment($id,$section_id)
    {
        $this->autoRender = false;
        $customer_id = $this->Customfunctions->sessionCustomerID();
        $application_type = $this->Session->read('application_type');

        $office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
        $firm_type = $this->Customfunctions->firmType($customer_id);
        $form_type = $this->Customfunctions->checkApplicantFormType($customer_id);



        $allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

        $section_id = $section_id - 1;
        $section_model = $allSectionDetails[$section_id]['section_model'];

        $section_model = $this->AqcmsWrapper->loadModel($section_model); // updated by shankhpal on 27-03-2024

        $this->DmiChemistComments->id = $id;
        $entity = $this->DmiChemistComments->get($id);
        $this->DmiChemistComments->delete($entity);

        $section_model->updateAll(
            array('form_status' => "saved",'ro_current_comment_to'=>'both'),
            array('customer_id'=>$customer_id,'is_latest'=>'1')
        );

        $this->redirect('/scrutiny/form-scrutiny');
    }


    public function changeApplication(){



        $selectedValues = $this->DmiChangeSelectedFields->selectedChangeFields();

        $fieldResult = $this->DmiChangeFieldLists->changeFieldList($selectedValues[0]);

        $this->Session->write('changefield',$fieldResult[0]);
        $this->Session->write('paymentforchange',$fieldResult[1]);

    }

}
?>
