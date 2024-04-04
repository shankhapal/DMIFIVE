<?php
namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;
use App\Controller\ChemistController;
use Cake\Log\Log;

class RomoioapplicantcommunicationactionsComponent extends Component {

    public $components= array('Session','Customfunctions');
    public $controller = null;
    public $session = null;

    public function initialize(array $config): void{
        parent::initialize($config);
        $this->Controller = $this->_registry->getController();
        $this->Session = $this->getController()->getRequest()->getSession();
    }


    public function RO2MOcommunication($customer_id,$tablename,$forms_data,$ro_reply_comment,$reply_upload,$reply_to) {

        $grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

        //Change on 01-03-2019
        $application_type = $this->Session->read('application_type');
        $Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
        $Dmi_allocation = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['allocation']);
        $allocationTable = $Dmi_final_submit_tb['allocation'];
        $firm_detail_table_name = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

        if (!empty($reply_upload->getClientFilename())) {

            $file_name = $reply_upload->getClientFilename();
            $file_size = $reply_upload->getSize();
            $file_type = $reply_upload->getClientMediaType();
            $file_local_path = $reply_upload->getStream()->getMetadata('uri');

            $rr_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

        } else {
            $rr_comment_ul = null;
        }

        $check_mo_allocation = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition, 'level_1 IS NOT NULL')))->first();

        if (!empty($check_mo_allocation)) {

            $check_comment_with_reply = $firm_detail_table_name->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id, 'mo_comment IS NOT NULL', 'ro_reply_comment IS NOT NULL', 'delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL')))->toArray();

            if (!empty($check_comment_with_reply)) {

                $check_max_id = max($check_comment_with_reply);
                $find_last_comment_id = $firm_detail_table_name->find('list',array('valueField'=>'id','conditions'=>array('id >'=>$check_max_id, 'customer_id IS'=>$customer_id, 'mo_comment IS NOT NULL', 'ro_reply_comment IS NULL')))->toArray();
            } else {
                $find_last_comment_id = $firm_detail_table_name->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id, 'mo_comment IS NOT NULL', 'ro_reply_comment IS NULL','delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL')))->toArray();
            }

            if (!empty($find_last_comment_id)) {

                $latest_id = max($find_last_comment_id);
                $find_last_record_data = $firm_detail_table_name->find('all',array('conditions'=>array('id IS'=>$latest_id)))->first();
                $reply_to_user = $find_last_record_data['user_email_id'];

                //html encoding post data before saving
                $htmlencoded_ro_reply = htmlentities($ro_reply_comment, ENT_QUOTES);
                $ro_reply = $htmlencoded_ro_reply;

                // calling custome function from model
                $ro_reply_status = $this->Customfunctions->updateRoReply($latest_id,$ro_reply,$rr_comment_ul,$customer_id,$tablename);

                if ($ro_reply_status == 1) {
                    return 1;
                } else {
                    return 2;
                };

            } else {

                //html encoding post data before saving
                $htmlencoded_ro_reply = htmlentities($ro_reply_comment, ENT_QUOTES);
                $comment = $htmlencoded_ro_reply;

                // calling custome functio from model
                $ro_reply_status = $firm_detail_table_name->saveReferredBackComment($customer_id,$forms_data,$comment,$rr_comment_ul,$reply_to);

                if ($ro_reply_status == 1) {
                    return 3;

                }
            }

        } else {
            return 4;
        }

    }


    public function referredBackTo($customer_id,$tablename,$forms_data,$reffered_back_comment,$referred_back_upload,$reffered_back_to) {

        $firm_detail_table_name = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

        //html encoding post data before saving
        $htmlencoded_reffered_back_comment = htmlentities($reffered_back_comment, ENT_QUOTES);
        $comment = $htmlencoded_reffered_back_comment;

        if (!empty($referred_back_upload->getClientFilename())) {

            $file_name = $referred_back_upload->getClientFilename();
            $file_size = $referred_back_upload->getSize();
            $file_type = $referred_back_upload->getClientMediaType();
            $file_local_path = $referred_back_upload->getStream()->getMetadata('uri');

            $rb_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

        } else { $rb_comment_ul = null; }

        //Inserting referred back comment to form
        // calling custome functio from model
        $referred_back_status = $firm_detail_table_name->saveReferredBackComment($customer_id,$forms_data,$comment,$rb_comment_ul,$reffered_back_to);

        if ($referred_back_status == 1) {

            return 1;

        } else {

            return 2;
        };

    }


    public function ROScrutinizedATMOLevel($customer_id,$tablename,$forms_data,$allSectionDetails){

        $application_type = $this->Session->read('application_type');
        $Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
        $Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['application_form']);
        $DmiOldCertDateUpdateLogs = TableRegistry::getTableLocator()->get('DmiOldCertDateUpdateLogs');
        $firm_detail_table_name = TableRegistry::getTableLocator()->get($tablename);//initialize model in component
        $DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');//initialize model in component
        $DmiBgrSmsEmailTemplate = TableRegistry::getTableLocator()->get('DmiBgrSmsEmailTemplate'); // added by shankhpal for BGR Module on 18-01-2024
        $firm_type = $this->Customfunctions->firmType($customer_id);

        $current_level = 'level_1'; //forced to save the entry as from level_1, but done by RO

        $oldapplication = $this->Customfunctions->isOldApplication($customer_id);
        $old_dates_verified = $DmiOldCertDateUpdateLogs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

        if (!($forms_data['form_status'] == 'approved' && $forms_data['current_level'] == $current_level)) {

            $list_id = $firm_detail_table_name->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
            $max_id = $firm_detail_table_name->find('all',array('fields'=>array('id','form_status'), 'conditions'=>array('id' => MAX($list_id))))->first();

            $fetch_id = $max_id['id'];

            $last_user_email_id = $forms_data['user_email_id'];
            $last_user_aadhar_no = $forms_data['user_once_no'];
            $last_record_id = $fetch_id;

            // calling custome method from model for accepted
            $form_accepted_result = $this->Customfunctions->formScrutinized($customer_id, $current_level, $last_user_email_id, $last_user_aadhar_no, $last_record_id, $tablename);

            if($form_accepted_result == 1){

                //call funcion to check other forms status.
                $allFormsApproved = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

                if($allFormsApproved == 2 ){

                    //for specific flows where office type is RO and Stay on the window after Scrtiny to Grant
                    //condition added on 13-04-2023 by Amol, to avoid redirect after all section scrutinized
                    //FLOWS : Chemist , Surrender, Change
                    $office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
                    if($application_type==3 || $application_type==4 || $application_type==9 || $application_type==12){

                        //added officetype =='SO' on 24-05-2023 by Amol, to stay on same page after scrutiny
                        if (($office_type=='RO' || $office_type=='SO') && $application_type==3) {
                            $changeInspection = $this->Customfunctions->inspRequiredForChangeApp($customer_id,$application_type);
                            if ($changeInspection=='no') {
                                return 2;
                            }

                        } elseif ($application_type==4) {

                            //comment below line and fetch details from chemist registration details for chemist to find chemist is old or new added by laxmi on 02-05-2023
                            //$NewChemist = 'yes';
                            //if ($NewChemist=='yes') {return 2;}

                            // to fetch chemist is alredy registerd or new chemist added by laxmi on 21-12-22
                            $DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
                            $chemistdetails = $DmiChemistRegistrations->find('list', array('valueField'=>'is_training_completed', 'conditions'=>array('chemist_id IS'=>$customer_id)))->first();

                            // set in session variable for chemist training completed or not
                            if(!empty($chemistdetails)){
                                $this->Session->write('is_training_completed', $chemistdetails);
                            }

                            if ((!empty($chemistdetails) && $chemistdetails=='no') && ($office_type == 'RO' || $office_type == 'SO')) {
                                return 2;
                            }


                        } elseif ($office_type=='RO' && $application_type==9) {
                            #the firm type is added to forward the lab appliction to the HO - Akash [17-08-2023]
                            if ($firm_type == 3) {

                                $Dmi_final_submit_entity = $Dmi_final_submit->newEntity(array(
                                    'customer_id'=>$customer_id,
                                    'status'=>'approved',
                                    'current_level'=>$current_level,
                                    'created'=>date('Y-m-d H:i:s'),
                                    'modified'=>date('Y-m-d H:i:s')
                                ));

                                $Dmi_final_submit->save($Dmi_final_submit_entity);


                                    #SMS: RO all Sections Scrutinized
                                    $DmiSmsEmailTemplates->sendMessage(13,$customer_id);

                                return 1;
                            } else {
                                return 2;
                            }
                        }

                    }

                    if($oldapplication == 'yes' && empty($old_dates_verified) && $application_type==1){

                        $firm_detail_table_name_Entity = $firm_detail_table_name->newEntity(array(
                            'id'=>$last_record_id,
                            'form_status'=>$max_id['form_status'],
                        ));

                        if($firm_detail_table_name->save($firm_detail_table_name_Entity));

                        return 4;

                    }else{

                        if($application_type==11){ // condition added by shankhpal for grant table entry on 06/09/2023
                            $ChemistController = new ChemistController;
                            $grant_table_entry = $ChemistController->BiannualGrading->bgrGrantTableEntry($customer_id);

                        }

                        $Dmi_final_submit_entity = $Dmi_final_submit->newEntity(array(
                            'customer_id'=>$customer_id,
                            'status'=>'approved',
                            'current_level'=>$current_level,
                            'created'=>date('Y-m-d H:i:s'),
                            'modified'=>date('Y-m-d H:i:s')
                        ));

                        $Dmi_final_submit->save($Dmi_final_submit_entity);

                        //The Chemist SMS after approval is added this condition - Akash[11-10-2022]
                        if ($_SESSION['application_type'] == 4) {
                            #SMS: Chemist Approved
                            $DmiSmsEmailTemplates->sendMessage(72,$customer_id); #Chemist
                            $DmiSmsEmailTemplates->sendMessage(73,$customer_id); #Packer
                        }elseif($application_type == 11){ //The Chemist/Applicant SMS after approval is added this condition - Shankhpal [18-01-2024]

                            $DmiBgrSmsEmailTemplate->sendMessage(1,$customer_id); #Packer

                            $DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
                            $chemist_incharge = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'incharge'=>'yes')))->first();

                            if (!empty($chemist_incharge)) {
                                $chemist_id = $chemist_incharge['chemist_id'];
                                $DmiBgrSmsEmailTemplate->sendMessage(2,$chemist_id); #Chemist
                            }

                        }else{
                            #SMS: RO all Sections Scrutinized
                            $DmiSmsEmailTemplates->sendMessage(13,$customer_id);
                        }

                        return 1;
                    }

                }else{
                    return 2;
                }
            }

        }else{
            return 3;
        }

    }


    public function RO2MOandMO2ROCommentFinalSubmit($customer_id,$level3_current_comment_to,$office_type,$sections){

        $grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

        //Change on 01-03-2019
        $application_type = $this->Session->read('application_type');
        $Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
        $Dmi_allocation = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['allocation']);
        $Dmi_mo_level3_comments_detail = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['commenting_with_mo']);
        $Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['appl_current_pos']);

        $allocationTable = $Dmi_final_submit_tb['allocation'];

        $DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');

        $split_customer_id = explode('/', (string) $customer_id); // added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-03-2024]
        $current_level = $this->Session->read('current_level');
        $find_user_id = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();

        $comment_by = $this->Session->read('username');

        //this condition is added on 05-07-2017 by Amol to checl level 1 allocation
        if(!empty($find_user_id['level_1'])){

            if($current_level == 'level_1'){

                $comment_to = $find_user_id['level_3'];

            }elseif($current_level == 'level_3'){

                $comment_to = $find_user_id['level_1'];
            }

            //this condition is added on 05-07-2017 by Amol to check comment saved for MO before submit by RO
            if(($current_level == 'level_3' && $level3_current_comment_to == 'mo') || $current_level == 'level_1'){

                if($Dmi_mo_level3_comments_detail->saveCommentsDetails($customer_id,$comment_by,$comment_to) == 1){

                    //Update record in all applications current position table
                    //created and applied on 03-04-2017 by amol

                    $user_email_id = $comment_to;
                    if($current_level == 'level_1'){

                        $level = 'level_3';
                        $sent_to = $office_type;
                        $sms_id = 11;
                    }elseif($current_level == 'level_3'){

                        $level = 'level_1';
                        $sent_to = 'MO';
                        $sms_id = 12;
                    }

                    $this->Customfunctions->updateLevel3CurrentCommentTo($sections,$customer_id);

                    $Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$level);//call to custom function from model

                    #SMS: MO commented to RO
                    $DmiSmsEmailTemplates->sendMessage($sms_id,$customer_id);

                    return array(1,$sent_to);
                }

            }else{
                return array(2);
            }

        }else{
            return array(3);
        }

    }


    public function RO2ApplicantCommentFinalSubmit($customer_id,$tablename,$ro_current_comment_to,$current_level,$sections){

        $application_type = $this->Session->read('application_type');
        $Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
        $Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['application_form']);
        $Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['appl_current_pos']);
        $Dmi_ro_office = TableRegistry::getTableLocator()->get('DmiRoOffices');//initialize model in component
        $DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');//initialize model in component
        $DmiBgrSmsEmailTemplate = TableRegistry::getTableLocator()->get('DmiBgrSmsEmailTemplate'); // load model for BGR by shankhpal on 18/01/2024
        $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');

        if($ro_current_comment_to == 'applicant')
        {
            if($this->Customfunctions->sentToApplicant($customer_id,$current_level,$Dmi_final_submit_tb['application_form']) == 1)
            {
                // Send to current application postion entry to all_applications_current_position Table (By Pravin 19/05/2017)
                $split_customer_id = explode('/',(string) $customer_id); //this is explode function is udpated for the Decraption by PHP 8.1 - Akash [07-10-2022]
                $district_ro_code = $split_customer_id[2];

                $firm_details = $DmiFirms->firmDetails($customer_id);
                $user_email_id = $firm_details['email'];

                $current_level = 'applicant';

                $this->Customfunctions->updateLevel3CurrentCommentTo($sections,$customer_id);

                $Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level); //call to custom function from model

                //to send to the chemist for referred back
                if ($_SESSION['application_type'] == 4) {
                    #SMS: RO referred back to chemist
                    $DmiSmsEmailTemplates->sendMessage(70,$customer_id); #Chemist
                }
                elseif($application_type == '11') {#SMS: RO referred back to chemist and Applicant added by shankhpal on 18/01/2024


                    $DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');

                    $chemist_incharge = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'incharge'=>'yes')))->first();

                    if (!empty($chemist_incharge)) {

                        $chemist_id = $chemist_incharge['chemist_id'];
                        $DmiBgrSmsEmailTemplate->sendMessage(3,$chemist_id); #SMS Chemist
                    }

                }
                else {
                    #SMS: RO referred back to applicant
                    $DmiSmsEmailTemplates->sendMessage(7,$customer_id); #Applicant
                }


                return 1;
            }

        }else{
            return 2;
        }

    }




    public function ROScrutinizedOldApplication($customer_id){

        //flow wise table query added on 24-11-2021 by Amol
        $application_type = $this->Session->read('application_type');
        $Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $Dmi_flow_wise_tbl = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

        $DmiFinalSubmits = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tbl['application_form']);
        $DmiOldApplicationCertificateDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
        $DmiOldApplicationRenewalDates = TableRegistry::getTableLocator()->get('DmiOldApplicationRenewalDates');
        $DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tbl['grant_pdf']);

        $DmiFinalSubmitsEntity1 = $DmiFinalSubmits->newEntity(array(
            'customer_id'=>$customer_id,
            'status'=>'approved',
            'current_level'=>'level_2',
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s')
        ));

        $DmiFinalSubmitsEntity2 = $DmiFinalSubmits->newEntity(array(
            'customer_id'=>$customer_id,
            'status'=>'approved',
            'current_level'=>'level_3',
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s')
        ));

        $DmiFinalSubmits->save($DmiFinalSubmitsEntity1);
        $DmiFinalSubmits->save($DmiFinalSubmitsEntity2);

        //get old application certificate details
        $get_old_certificate_details = $DmiOldApplicationCertificateDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
        if(!empty($get_old_certificate_details)){
            $old_certificate_docs = $get_old_certificate_details['old_certificate_pdf'];
        }

        //get old application renewal details
        $get_old_renewal_details = $DmiOldApplicationRenewalDates->find('list',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

        //check enrty in old renewal details table, if empty then take grant date from old applications details table
        $old_grant_date = null;
        if(!empty($get_old_renewal_details)){
            //get last renewal details
            $get_last_renewal_details = $DmiOldApplicationRenewalDates->find('all',array('conditions'=>array('id'=>max($get_old_renewal_details))))->first();
            $old_grant_date = $get_last_renewal_details['renewal_date'];
        }else{
            if(!empty($get_old_certificate_details)){
                $old_grant_date = $get_old_certificate_details['date_of_grant'];
            }
        }

        //for get old details for E-code appl
        //added on 23-11-2021 by Amol
        $appl_type = $this->Session->read('application_type');

        if($appl_type==6){

            $DmiECodeApplDetails = TableRegistry::getTableLocator()->get('DmiECodeApplDetails');
            $check_appl = $DmiECodeApplDetails->find('all',array('fields'=>array('granted_on','old_cert_doc','granted_e_code'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
            $old_grant_date = $check_appl['granted_on'];
            $old_certificate_docs = $check_appl['old_cert_doc'];

            //insert record in table to map each E-code with each applicant.
            $DmiECodeForApplicants = TableRegistry::getTableLocator()->get('DmiECodeForApplicants');
            $DmiECodeForApplicantsEntity = $DmiECodeForApplicants->newEntity(array(

                'customer_id'=>$customer_id,
                'e_code'=>$check_appl['granted_e_code'],
                'created'=>date('Y-m-d H:i:s'),
                'modified'=>date('Y-m-d H:i:s')
            ));

            $DmiECodeForApplicants->save($DmiECodeForApplicantsEntity);
        }

        //added on 01-10-201 by Amol to formate date before save
        $old_grant_date = $this->Customfunctions->changeDateFormat($old_grant_date);

        $DmiGrantCertificatesPdfsEntity = $DmiGrantCertificatesPdfs->newEntity(array(
            'customer_id'=>$customer_id,
            'user_email_id'=>'old_application',
            'pdf_file'=>$old_certificate_docs,
            'date'=>$old_grant_date,
            'pdf_version'=>1,
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s')
        ));
        //entry in Grant certificate table.
        $DmiGrantCertificatesPdfs->save($DmiGrantCertificatesPdfsEntity);

        return true;
    }



    // After Scrutiny Forward To Ro
    // Description: This method is used to save the entries to the allocation tables for Level 4 Ro entries
    // By: Akash
    // Date: 02-12-2022

    public function afterScrutinyForwardToRo($customer_id,$application_type,$grantDateCondition,$Dmi_allocation_table,$Dmi_appl_current_pos_table){

        $Dmi_allocation_table = TableRegistry::getTableLocator()->get($Dmi_allocation_table);
        $Dmi_appl_current_pos_table = TableRegistry::getTableLocator()->get($Dmi_appl_current_pos_table);


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

        $user_email_id = $ro_email_id;
        $current_level = 'level_4_ro';
        $Dmi_appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level);
        return true;
    }


    // Description: This method is used to save the entries to the allocation tables for HO entries which is used for forward
    // By: Akash
    // Date: 02-12-2022

    public function sendToHeadOffice($customer_id,$application_type,$checkHoAllocationEntry){

        //Load Model
        log::info("RomoioapplicantcommunicationactionsComponent: sendToHeadOffice, CustomerID: $customer_id, Application Type: $application_type, checkHoAllocationEntry: $checkHoAllocationEntry, Date: " . date("Y-m-d H:i:s"));
        
        $DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');
        $DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
        $DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');
        $Dmi_final_submit_tb = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();


        //this HO level allocation applied for lab export application
        //on 02-09-2017 by Amol
        $find_dy_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes','OR'=>array('super_admin IS NULL','super_admin'=>'no'))))->first();
        $dy_ama_email_id = $find_dy_ama_user['user_email_id'];

        $find_jt_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes','OR'=>array('super_admin IS NULL','super_admin'=>'no'))))->first();;
        $jt_ama_email_id = $find_jt_ama_user['user_email_id'];

        $find_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes','OR'=>array('super_admin IS NULL','super_admin'=>'no'))))->first();;
        $ama_email_id = $find_ama_user['user_email_id'];
        
        $Dmi_ho_allocation_table_name=$Dmi_final_submit_tb['ho_level_allocation'];
        $Dmi_ho_allocation = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['ho_level_allocation']);
        $Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['appl_current_pos']);
        log::info("RomoioapplicantcommunicationactionsComponent: sendToHeadOffice, HOAllocationTable: $Dmi_ho_allocation_table_name, Date: " . date("Y-m-d H:i:s"));
    
        //This below empty condition is checking is the varible is $checkHoAllocationEntry then and then only save the entry. - Akash [31-07-2023]
        if (empty($checkHoAllocationEntry)) {

            $Dmi_ho_allocation_entity = $Dmi_ho_allocation->newEntity(array(

                'customer_id'=>$customer_id,
                'dy_ama'=>$dy_ama_email_id,
                'jt_ama'=>$jt_ama_email_id,
                'ama'=>$ama_email_id,
                'current_level'=>$dy_ama_email_id,
                'created'=>date('Y-m-d H:i:s'),
                'modified'=>date('Y-m-d H:i:s')
            ));
            
            log::info("RomoioapplicantcommunicationactionsComponent: sendToHeadOffice Before Saving to HO Allocation Entry, Date: " . date("Y-m-d H:i:s"));
            $isEntrySaved=false;

            //Logs and exception handling is applied on 06-03-2024 : Akash Joshi
            try{
                $isEntrySaved=$Dmi_ho_allocation->save($Dmi_ho_allocation_entity);
                if($isEntrySaved){
                    log::info("RomoioapplicantcommunicationactionsComponent: sendToHeadOffice After Saving to HO Allocation Entry, Date: " . date("Y-m-d H:i:s"));
                    //Update record in all applications current position table
                    //created and applied on 02-09-2017 by amol
                    $user_email_id = $dy_ama_email_id;
                    $current_level = 'level_4';
                    $Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model

                    $DmiSmsEmailTemplates->sendMessage(20,$customer_id); #SMS: RO forwarded to HO

                }
            }
            finally {
                log::info("RomoioapplicantcommunicationactionsComponent: sendToHeadOffice ,isentrysaved: $isEntrySaved, Date: " . date("Y-m-d H:i:s"));
            }
            return 1;
        } else {
            return 2;
        }

    }


}

?>
