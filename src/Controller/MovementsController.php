<?php
//added new file by Laxmi Bhadade for movement of application on 20-07-2023
namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;
use phpDocumentor\Reflection\Types\This;
use Cake\Http\Response\withHeader;

class MovementsController extends AppController {


   private $DmiApplWithRoMappings;
   private $DmiUserRoles;
   private $DmiChemistTrainingAtRo;
   private $DmiIoAllocationLogs;
   private $DmiChemistEducationDetails;
   private $DmiChemistExperienceDetails;
   private $DmiChemistProfileDetails;
   private $DmiChemistOtherDetails;
   private $DmiChemistRoToRalLogs;
   private $DmiMoAllocationLogs;
   private $DmiFirms;
   private $DmiRoOffices;
   private $DmiPaoDetails;
   private $DmiApplicationTypes;
   private $DmiFlowWiseTablesLists;
   private $DmiChemistRegistrations;
   private $DmiUsers;
   private $DmiChemistTrainingDetails;
   private $DmiChemistRalToRoLogs;

    public function initialize(): void {
			parent::initialize();

            //Load Components
            // added by shankhpal on 27-03-2024
            $components = [
                'AqcmsWrapper'
            ];

            foreach($components as $component){
                $this->loadComponent($component);
            }


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


        $this->DmiApplWithRoMappings = $this->AqcmsWrapper->customeLoadModel('DmiApplWithRoMappings');
        $this->DmiUserRoles = $this->AqcmsWrapper->customeLoadModel('DmiUserRoles');
        $this->DmiChemistTrainingAtRo = $this->AqcmsWrapper->customeLoadModel('DmiChemistTrainingAtRo');
        $this->DmiIoAllocationLogs = $this->AqcmsWrapper->customeLoadModel('DmiIoAllocationLogs');
        $this->DmiChemistEducationDetails = $this->AqcmsWrapper->customeLoadModel('DmiChemistEducationDetails');
        $this->DmiChemistExperienceDetails = $this->AqcmsWrapper->customeLoadModel('DmiChemistExperienceDetails');
        $this->DmiChemistProfileDetails = $this->AqcmsWrapper->customeLoadModel('DmiChemistProfileDetails');
        $this->DmiChemistOtherDetails = $this->AqcmsWrapper->customeLoadModel('DmiChemistOtherDetails');
        $this->DmiChemistRoToRalLogs = $this->AqcmsWrapper->customeLoadModel('DmiChemistRoToRalLogs');
        $this->DmiMoAllocationLogs = $this->AqcmsWrapper->customeLoadModel('DmiMoAllocationLogs');
        $this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
        $this->DmiRoOffices = $this->AqcmsWrapper->customeLoadModel('DmiRoOffices');
        $this->DmiPaoDetails = $this->AqcmsWrapper->customeLoadModel('DmiPaoDetails');
        $this->DmiApplicationTypes = $this->AqcmsWrapper->customeLoadModel('DmiApplicationTypes');
        $this->DmiFlowWiseTablesLists = $this->AqcmsWrapper->customeLoadModel('DmiFlowWiseTablesLists');
        $this->DmiChemistRegistrations = $this->AqcmsWrapper->customeLoadModel('DmiChemistRegistrations');
        $this->DmiUsers = $this->AqcmsWrapper->customeLoadModel('DmiUsers');
        $this->DmiChemistTrainingDetails = $this->AqcmsWrapper->customeLoadModel('DmiChemistTrainingDetails');
        $this->DmiChemistRalToRoLogs = $this->AqcmsWrapper->customeLoadModel('DmiChemistRalToRoLogs');

    }


    public function beforeFilter($event) {
        parent::beforeFilter($event);
        $username = $this->getRequest()->getSession()->read('username');

        if($username == null){
            $this->customAlertPage("Sorry You are not authorized to view this page..");
            exit();
        }else{
            //check if user entry in Dmi_users table for valid user
            $check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();

            if(empty($check_user)){
                $this->customAlertPage("Sorry You are not authorized to view this page..");
                exit();
            }
        }

    }


        public function getApplType(){
            $this->autoRender = false;

            if(NULL != $this->request->getData()){
                $appli_id = $this->request->getData('appl_id');
                 // to check which application type of particular id
                $all_finalSubmit = $this->DmiFlowWiseTablesLists->find('all',['fields'=>['application_form','application_type']])->toArray();

                $applicationIdArray = array();
                if(!empty($all_finalSubmit)){
                    foreach ($all_finalSubmit as $key => $final_submit) {
                        if(!empty($final_submit['application_form'])){
                            $finalSubmitModelArray =  $this->AqcmsWrapper->customeLoadModel($final_submit['application_form']);

                            $finalcheck = $finalSubmitModelArray->find('all',['fields'=>['customer_id'],'conditions'=>['customer_id'=>$appli_id]])->first();

                            if(!empty($finalcheck)){
                            $applicationIdArray[$final_submit['application_type']] = $final_submit['application_form'];
                            }

                        }
                    }
                }


                if(!empty($applicationIdArray)){
                    //check chemist registered with selected id
                    $chemist_details = $this->DmiChemistRegistrations->find('all')->where(array('created_by'=>$appli_id))->first();

                    $keys1 = array();

                    if(!empty($applicationIdArray)){
                      foreach ($applicationIdArray as $key => $value) {

                                $appl[] = $this->DmiApplicationTypes->find('all',['fields'=>['id', 'application_type']])->where(array('delete_status IS'=>NULL, 'id IS'=>$key))->order(['id'=>'ASC'])->first();

                        }
                    }
                      if(!empty($chemist_details)){
                        $chemist_flow = $this->DmiApplicationTypes->find('all',['fields'=>['id', 'application_type']])->where(array('delete_status IS'=>NULL, 'id IS'=>4))->order(['id'=>'ASC'])->toArray();
                        $appl = array_merge($appl, $chemist_flow);
                    }

                     echo json_encode($appl);
                     exit;
                }
                //If No application submitted for this application Id added by Vikas Ravidas on [26-09-2023]
                else{
                    $data = array(
                        'message' => 'No application submitted for this Application ID'
                    );
                    echo json_encode($data);
                    exit;
                }

            }
        }


        public function getChemistApplId(){
            $this->autoRender = false;
            if(NULL != $this->request->getData()){
                $appli_id = $this->request->getData('appl_id');

                $chemist_appl_details =  $this->DmiChemistRegistrations->find('all',['fields'=>['chemist_id','chemist_fname','chemist_lname','created_by']])->where(array('created_by IS'=>$appli_id))->where(['delete_status IS'=>null])->order(['chemist_fname'=>'ASC'])->toArray();
              if(!empty($chemist_appl_details)){
                  echo json_encode($chemist_appl_details);
                  exit;
              }
            }
        }

        public function movementHistory(){
            $this->viewBuilder()->setLayout('admin_dashboard');

            //taking application type id's from session
            $appl_type_array = $this->Session->read('applTypeArray');
            if(!empty($appl_type_array)){
                $all_appl_type = $this->DmiApplicationTypes->find('all')->select(['id', 'application_type'])->where(['id IN'=>$appl_type_array,'delete_status IS'=>NULL])->order(['id'=>'ASC'])->all()->combine('id','application_type')->toArray();
                $this->set('applTypesList', $all_appl_type);
            }


            if(NULL != $this->request->getData()){
                $reqdata = $this->request->getData();
                $appli_type = $reqdata['appl_type'];
                if($appli_type == 4){
                    $appli_id = $reqdata['chemist_id'];
                }else{
                    $appli_id = $reqdata['appl_id'];
                }




                $flowwiseTable = $this->DmiFlowWiseTablesLists->find('all',['fields'=>['application_form','inspection_report','ho_level_allocation','ama_approved_application','ho_comment_reply','allocation', 'commenting_with_mo','esign_status','payment','appl_current_pos','ro_so_comments','grant_pdf','level_4_ro_approved']])
                ->where(['application_type IS'=>$appli_type])->first();
                if(!empty($flowwiseTable)){
                   if(!empty($flowwiseTable['application_form']) ){
                   $applicant =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['application_form']);

                   }
                   if(!empty($flowwiseTable['allocation']) ){
                   $allocation =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['allocation']);
                   }
                   if(!empty($flowwiseTable['inspection_report']) ){
                   $inspection =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['inspection_report']);
                   }
                   if(!empty($flowwiseTable['ho_level_allocation']) ){
                   $ho_lev =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['ho_level_allocation']);
                   }
                   if(!empty($flowwiseTable['ama_approved_application']) ){
                   $ama =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['ama_approved_application']);
                   }
                   if(!empty($flowwiseTable['ho_comment_reply']) ){
                   $ho_comment =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['ho_comment_reply']);
                   }
                   if(!empty($flowwiseTable['commenting_with_mo']) ){
                   $mo_comment =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['commenting_with_mo']);
                   }
                   if(!empty($flowwiseTable['esign_status']) ){
                   $esign =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['esign_status']);
                   }
                   if(!empty($flowwiseTable['ro_so_comments']) ){
                   $ro_So =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['ro_so_comments']);
                   }
                   if(!empty($flowwiseTable['grant_pdf']) ){
                   $grant =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['grant_pdf']);
                   }
                   if(!empty($flowwiseTable['level_4_ro_approved']) ){
                   $leve4App=  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['level_4_ro_approved']);
                   }
                   if(!empty($flowwiseTable['payment']) ){
                   $payment =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['payment']);
                   }
                   if(!empty($flowwiseTable['appl_current_pos']) ){
                   $current =  $this->AqcmsWrapper->customeLoadModel($flowwiseTable['appl_current_pos']);
                   }
                }

                   $to = array();
                   $from =array();
                   $sentdate = array();
                   $action = array();
                   $i=0;
                   //fetch using core join
                   $conn = ConnectionManager::get('default');

                   $isPaymentDone = $payment->find('all')->where(['customer_id IS'=>$appli_id])->order('modified DESC')->toArray();

                   if(!empty($applicant)){
                   $applicant_final= $applicant->find('all')->where(['customer_id IS'=>$appli_id])->first();
                   }
                   $current_pos = $current->find('all', ['conditions'=>['customer_id IS'=>$appli_id]] )->first();

                   //to set application type and firm name whish is selected
                   $appliType = $this->DmiApplicationTypes->find('all', ['fields'=>['application_type'], 'conditions'=>['id IS'=>$appli_type]])->first();

                   $this->set('application_type', $appliType['application_type']);
                   $this->set('application_id', $appli_id);

                   if($appli_type != 4){
                         $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                         $this->set('firm_name',$firm_details['firm_name'] );
                   }else{
                        $chemist_details = $this->DmiChemistRegistrations->find('all', ['fields'=>['chemist_fname','chemist_lname','email','created_by','created'], 'conditions'=>['chemist_id IS'=>$appli_id]])->first();
                        $firmDetails = $this->DmiFirms->find('all', ['fields'=>['firm_name','email'], 'conditions'=>['customer_id IS'=>$chemist_details['created_by']]])->first();
                        $this->set('firm_name',$chemist_details['chemist_fname']." ".$chemist_details['chemist_lname']. "(". $firmDetails['firm_name'].")" );
                    }
                    if(!empty($allocation)){
                    $allcation_table = $allocation->find('all', ['conditions'=>['customer_id IS'=>$appli_id]])->first();
                    }
                    if(!empty($mo_comment)){
                    $comment_by_mo   = $mo_comment->find('all', ['conditions'=>['customer_id IS'=>$appli_id]])->order(['modified'=>'desc'])->toArray();
                    }
                    if(!empty($applicant)){
                      $applicant_level3 = $applicant->find('all', ['conditions'=>['customer_id IS'=>$appli_id]])->order(['modified'=>'desc'])->toArray();
                    }
                    if(!empty($isPaymentDone) && !empty($applicant_final['status']) && !empty($current_pos)){
                     //DDO

                     foreach($isPaymentDone as $isPayment){
                        if($isPayment['payment_confirmation'] == 'pending'){
                           //applicant to ddo
                            if(!empty($applicant_final)){
                                $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                if(!empty($pao_details)){
                                $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                }
                                $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];

                                if($appli_type == 4){
                                 $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                }else{
                                 $from[] = $firm_details['firm_name'];
                                }
                                $sentdate[] = $isPayment['modified'];
                                $action[] = 'payment is ' . $isPayment['payment_confirmation'];
                            }


                        } elseif($isPayment['payment_confirmation'] == 'not_confirmed'){
                            //ddo reffered back
                            if(!empty($applicant_final)){
                                $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                if(!empty($pao_details)){
                                $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                }
                                $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                $from []= $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                $sentdate[] = $isPayment['modified'];
                                if($appli_type == 4){
                                    $to[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                }else{
                                    $to[] = $firm_details['firm_name'];
                                }

                                   $action[] = 'payment is ' . $isPayment['payment_confirmation'];

                            }

                        }elseif( $isPayment['payment_confirmation'] == 'replied' ){
                            if(!empty($applicant_final)){
                                $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                if(!empty($pao_details)){
                                $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                }
                                $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                $to []= $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                $sentdate[] = $isPayment['modified'];
                                if($appli_type == 4){
                                    $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                }else{
                                    $from[] = $firm_details['firm_name'];
                                }

                                $action[] = 'payment is ' . $isPayment['payment_confirmation'];

                            }
                        }else{
                           //ddo approved application is in RO/SO side
                          if($isPayment['payment_confirmation'] == 'confirmed'){
                                    if($current_pos['current_level'] == 'level_3' && $appli_type !=4){
                                        $officer_details =  $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$current_pos['current_user_email_id']]])->first();
                                    }elseif($current_pos['current_level'] == 'level_1' || $current_pos['current_level'] == 'applicant' || $current_pos['current_level'] == 'level_2' || $appli_type == 4){
                                        // allocation table
                                         $roofficer_details = $allocation->find('all', ['fields'=>['level_3'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();

                                         if(!empty($roofficer_details)){
                                          $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roofficer_details['level_3']]])->first();

                                        }
                                    }else{
                                         $roofficer_details = $allocation->find('all', ['fields'=>['level_3'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                            if(!empty($roofficer_details)){
                                                $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roofficer_details['level_3']]])->first();

                                                }
                                        }

                                        $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                        if(!empty($pao_details)){
                                        $ddo = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                        }

                                        $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                        $from[] = $ddo['f_name'].' '.$ddo['l_name'].' '.$ddo['role'];
                                        $sentdate[] = $isPayment['modified'];
                                        $action[] = 'payment is ' . $isPayment['payment_confirmation'];

                           }


                        }
                     }//foreach close



                    }elseif(!empty($current_pos) && !empty($isPaymentDone)){
                        foreach($isPaymentDone as $isPayment){
                            if($isPayment['payment_confirmation'] == 'pending'){
                               //applicant to ddo
                                if(!empty($isPayment)){
                                    $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                    if(!empty($pao_details)){
                                    $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                    }

                                    $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];

                                    if($appli_type == 4){
                                     $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                    }else{
                                     $from[] = $firm_details['firm_name'];
                                    }
                                    $sentdate[] = $isPayment['modified'];
                                    $action[] = 'payment is ' . $isPayment['payment_confirmation'];
                                }


                            } elseif($isPayment['payment_confirmation'] == 'not_confirmed'){
                                //ddo reffered back
                                if(!empty($isPayment)){
                                    $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                    if(!empty($pao_details)){
                                    $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                    }
                                    $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                    $from []= $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                    $sentdate[] = $isPayment['modified'];
                                    if($appli_type == 4){
                                        $to[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                    }else{
                                        $to[] = $firm_details['firm_name'];
                                    }

                                       $action[] = 'payment is ' . $isPayment['payment_confirmation'];

                                }

                            }elseif( $isPayment['payment_confirmation'] == 'replied' ){
                                if(!empty($isPayment)){
                                    $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                    if(!empty($pao_details)){
                                    $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                    }
                                    $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                    $to []= $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                    $sentdate[] = $isPayment['modified'];
                                    if($appli_type == 4){
                                        $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                    }else{
                                        $from[] = $firm_details['firm_name'];
                                    }

                                    $action[] = 'payment is ' . $isPayment['payment_confirmation'];

                                }
                            }else{
                               //ddo approved application is in RO/SO side
                              if($isPayment['payment_confirmation'] == 'confirmed'){
                                        if($current_pos['current_level'] == 'level_3' && $appli_type !=4){
                                            $officer_details =  $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$current_pos['current_user_email_id']]])->first();
                                        }elseif($current_pos['current_level'] == 'level_1' || $current_pos['current_level'] == 'applicant' || $current_pos['current_level'] == 'level_2' || $appli_type == 4){
                                            // allocation table
                                             $roofficer_details = $allocation->find('all', ['fields'=>['level_3'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();

                                             if(!empty($roofficer_details)){
                                              $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roofficer_details['level_3']]])->first();

                                            }
                                        }else{
                                             $roofficer_details = $allocation->find('all', ['fields'=>['level_3'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                                if(!empty($roofficer_details)){
                                                    $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roofficer_details['level_3']]])->first();

                                                    }
                                            }

                                            $pao_details = $this->DmiPaoDetails->find('all',['fields'=>['pao_user_id'], 'conditions'=>['id IS'=>$isPayment['pao_id']]])->first();
                                            if(!empty($pao_details)){
                                            $ddo = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['id IS'=>$pao_details['pao_user_id']]])->first();
                                            }

                                            $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            $from[] = $ddo['f_name'].' '.$ddo['l_name'].' '.$ddo['role'];
                                            $sentdate[] = $isPayment['modified'];
                                            $action[] = 'payment is ' . $isPayment['payment_confirmation'];

                               }


                            }
                         }//foreach close

                    }else{
                        //applicant side
                        if(empty($applicant_final) && empty($current_pos) && empty($isPaymentDone) ){
                            $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();

                            if($appli_type == 4){
                                $to[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                $sentdate[] =  $chemist_details['created'];
                            }else{
                                $to[] = $firm_details['firm_name'];
                                $from[] = $firm_details['firm_name'];
                                $sentdate[] =  $firm_details['created'];
                            }


                            $action[] = 'Not forwarded yet';
                         }
                    }




                          if(!empty($allcation_table)){
                                if(!empty($allcation_table['level_3']) && !empty($allcation_table['level_1'])) {
                                 $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_3']] ])->first();


                                 if(!empty($officer_details)){
                                    $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                  }


                                  $ioAlloc = $this->DmiMoAllocationLogs->find('all', ['conditions'=>['customer_id IS'=>$appli_id, 'application_type' =>$appli_type ]])->first();

                                if(empty($ioAlloc)){
                                   if(!empty($appliType['application_type'])){
                                        $appl_type = $appliType['application_type'];
                                        if(!empty($appl_type)){

                                            $appl_type = strtolower($appliType['application_type']);
                                            $ioAlloc = $this->DmiMoAllocationLogs->find('all', ['conditions'=>['customer_id IS'=>$appli_id,'application_type' =>$appl_type ]])->first();

                                        }

                                    }
                                }
                                  if(!empty($ioAlloc)){
                                     $officer_detailsCurrent   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$ioAlloc['mo_email_id']]])->first();
                                   }
                                   if(!empty($officer_detailsCurrent)){

                                        $to[] = $officer_detailsCurrent['f_name'].' '.$officer_detailsCurrent['l_name'].' '.$officer_detailsCurrent['role'];
                                       if(!empty($applicant)){
                                          $find_level1 = $applicant->find('all', ['conditions'=>['customer_id IS'=>$appli_id, 'status'=>'pending', 'current_level'=>'level_1']])->first();
                                       }
                                        $sentdate[] = $ioAlloc['created'];
                                        $action[] = 'Allocated to Scrutinized';
                                    }
                                }


                                if($appli_type == 4){
                                    $ro_ral_log = $this->DmiChemistRoToRalLogs->find('all')->where(['chemist_id IS'=>$appli_id])->first();
                                 if(!empty($ro_ral_log)){
                                     $forwardOfficer = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['posted_ro_office IS'=>$ro_ral_log['ro_office_id'] , 'role'=>'RO/SO OIC']])->first();
                                    if(!empty($forwardOfficer)){
                                        $from[] = $forwardOfficer['f_name'].' '.$forwardOfficer['l_name'].' '.$forwardOfficer['role'];

                                    }
                                    if(!empty($allcation_table['current_level']) && !empty($allcation_table['level_3'])){

                                     if($allcation_table['current_level'] == $allcation_table['level_3']){

                                        // to check all section approved or not


                                       $form_status_p = $this->DmiChemistProfileDetails->find('all',['conditions'=>['customer_id IS'=>$appli_id, 'form_status'=>'approved']])->last();
                                       $form_status_e = $this->DmiChemistEducationDetails->find('all',['conditions'=>['customer_id IS'=>$appli_id, 'form_status'=>'approved']])->last();
                                       $form_status_ex = $this->DmiChemistExperienceDetails->find('all',['conditions'=>['customer_id IS'=>$appli_id, 'form_status'=>'approved']])->last();
                                       $form_status_t = $this->DmiChemistTrainingDetails->find('all',['conditions'=>['customer_id IS'=>$appli_id, 'form_status'=>'approved']])->last();
                                       $form_status_o = $this->DmiChemistOtherDetails->find('all',['conditions'=>['customer_id IS'=>$appli_id, 'form_status'=>'approved']])->last();

                                      if(!empty($form_status_p) && !empty($form_status_e) && !empty($form_status_ex) && !empty($form_status_t) && !empty($form_status_o)){
                                            $officer_detailsCurrent   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['current_level']]])->first();
                                            if(!empty($officer_detailsCurrent)){
                                                $to[] = $officer_detailsCurrent['f_name'].' '.$officer_detailsCurrent['l_name'].' '.$officer_detailsCurrent['role'];

                                            }
                                            $dateArray = array($form_status_p['approved_date'], $form_status_e['approved_date'], $form_status_ex['approved_date'], $form_status_t['approved_date'], $form_status_o['approved_date']);
                                            usort($dateArray, function ($a, $b) {
                                                return strtotime($a) - strtotime($b);
                                            });

                                            $sentdate[] = $dateArray[4];

                                            $action[] = 'Application Section Approved';
                                       }
                                    }
                                    }

                                 }
                                }




                            }

                            // for chemist_ro_ral
                            if($appli_type == 4){


                                $ro_side_application = $this->DmiChemistRalToRoLogs->find('all')->where(array('chemist_id'=>$appli_id , 'training_completed'=>1))->first();
                                $ral_side = $this->DmiChemistRoToRalLogs->find('all')->where(array('chemist_id'=>$appli_id , 'is_forwordedtoral'=>'yes'))->first();

                              if(!empty($ral_side)){
                                    $ro_id = $ral_side['ro_office_id'];
                                    $ral_id = $ral_side['ral_office_id'];
                                    $ral_Detail  = $this->DmiUsers->find('all',['fields'=>['f_name','l_name','role']])->where(['posted_ro_office IS'=>$ral_id, 'role'=>'RAL/CAL OIC'])->first();
                                    $ro_Detail  = $this->DmiUsers->find('all',['fields'=>['f_name','l_name','role']])->where(['posted_ro_office IS'=>$ro_id, 'role'=>'RO/SO OIC'])->first();
                                   if(!empty($ro_Detail)){
                                    $from[] = $ro_Detail['f_name']. " " .$ro_Detail['l_name']." ".$ro_Detail['role'];;
                                   }
                                   if(!empty($ral_Detail)){
                                    $to[] = $ral_Detail['f_name']. " " .$ral_Detail['l_name']." ".$ral_Detail['role'];
                                   }
                                    $sentdate[] = $ral_side['created'] ;
                                    $action[] = "Forwarded at RAL";
                                }
                                if(!empty($ro_side_application)){
                                    $ro = $ro_side_application['ro_office_id'];
                                    $ral = $ro_side_application['ral_office_id'];
                                    $officer  = $this->DmiUsers->find('all',['fields'=>['f_name','l_name','role']])->where(['posted_ro_office IS'=>$ral, 'role'=>'RAL/CAL OIC'])->first();
                                   $ro_Det  = $this->DmiUsers->find('all',['fields'=>['f_name','l_name','role']])->where(['posted_ro_office IS'=>$ro, 'role'=>'RO/SO OIC'])->first();

                                   if(!empty($officer)){
                                    $from[] = $officer['f_name']. " " .$officer['l_name']." ".$officer['role'];

                                   }
                                   if(!empty($ro_Det)){
                                    $to[] = $ro_Det['f_name']. " " .$ro_Det['l_name']." ".$ro_Det['role'];
                                    }
                                    $sentdate[] = $ro_side_application['created'] ;
                                    $action[] = "Forwarded at RO";
                               }



                            }

                            if(!empty($comment_by_mo)){

                              foreach ($comment_by_mo as $key => $crm) {

                                    if($crm['available_to'] == 'ro'){
                                        $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$crm['comment_by']]])->first();
                                        if(!empty($officer_details)){
                                            $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                        }
                                        $officer_detailsCurrent   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$crm['comment_to']]])->first();
                                        if(!empty($officer_detailsCurrent)){
                                            $to[] = $officer_detailsCurrent['f_name'].' '.$officer_detailsCurrent['l_name'].' '.$officer_detailsCurrent['role'];
                                        }
                                        $sentdate[] = $crm['comment_date'];
                                        $action[] = 'Forwarded Back to RO/SO ';
                                    }elseif($crm['available_to'] == 'mo'){
                                        $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$crm['comment_by']]])->first();
                                        if(!empty($officer_details)){
                                            $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                        }
                                        $officer_detailsCurrent   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$crm['comment_to']]])->first();
                                        if(!empty($officer_detailsCurrent)){
                                            $to[] = $officer_detailsCurrent['f_name'].' '.$officer_detailsCurrent['l_name'].' '.$officer_detailsCurrent['role'];
                                        }
                                        $sentdate[] = $crm['comment_date'];
                                        $action[] = 'Forwarded Back to MO/SMO';
                                    }
                                }
                            }

                            if(!empty($applicant_level3)){
                                if(!empty($current_pos) && $current_pos['current_level'] == 'level_3'){
                                    $officer_details =  $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$current_pos['current_user_email_id']]])->first();
                                }elseif(!empty($current_pos) && ($current_pos['current_level'] == 'level_1' || $current_pos['current_level'] == 'applicant' || $current_pos['current_level'] == 'level_2' || $current_pos['current_level'] == 'level_4')){
                                    // allocation table
                                     $roofficer_details = $allocation->find('all', ['fields'=>['level_3'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                   if(!empty($roofficer_details)){
                                      $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roofficer_details['level_3']]])->first();

                                    }
                                }


                                foreach ($applicant_level3 as $key => $l3) {
                                    if($l3['status'] == 'referred_back'){
                                        if(!empty($officer_details)){
                                            $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                        }
                                        if($appli_type == 4){
                                            $to[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                        }else{
                                            $to[] = $firm_details['firm_name'];
                                        }
                                        $sentdate[] = $l3['modified'];
                                        $action[] = 'Forwarded Back to Applicant ';
                                   }elseif($l3['status'] == 'replied'){
                                        if(!empty($officer_details)){
                                            $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                        }
                                        if($appli_type == 4){
                                            $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                        }else{
                                            $from[] = $firm_details['firm_name'];
                                        }

                                            $sentdate[] = $l3['modified'];
                                            $action[] = 'Forwarded to RO/SO ';
                                    }elseif($l3['status'] == 'approved' && $l3['current_level'] == 'level_1' && $appli_type != 4){
                                        $r_details = $allocation->find('all', [ 'conditions'=>['customer_id IS'=>$appli_id]])->first();

                                        if(!empty($r_details['level_3']) && empty($r_details['level_1'])){
                                            $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$r_details['level_3']]])->first();

                                            if(!empty($officer_details)){
                                                $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                                $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            }
                                            $sentdate[] = $l3['modified'];
                                            $action[] = 'Application Scrutinized';
                                        }

                                    }elseif($l3['status'] == 'pending' && empty($isPaymentDone) && empty ($current_pos)){

                                        if(!empty($allcation_table)){


                                            $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                        }
                                        if($appli_type == 4){
                                            $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                        }else{
                                            $from[] = $firm_details['firm_name'];
                                        }

                                            $sentdate[] = $l3['modified'];
                                            $action[] = 'Forwarded to RO/SO ';
                                    } elseif($l3['status'] == 'pending' && empty($isPaymentDone) && !empty ($current_pos)){
                                        $firm_details = $this->DmiFirms->find('all', ['fields'=>['firm_name','email','created'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();

                                        if(!empty($allocation)){
                                            $roofficer_details = $allocation->find('all', ['fields'=>['level_3'], 'conditions'=>['customer_id IS'=>$appli_id]])->first();
                                            if(!empty($roofficer_details)){
                                                $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roofficer_details['level_3']]])->first();
                                            }
                                        }
                                        if($appli_type == 4){
                                            $to[] =$officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            $from[] = $chemist_details['chemist_fname']." " .$chemist_details['chemist_lname']. "(" .$firmDetails['firm_name'].")";
                                            $sentdate[] =  $l3['created'];
                                        }else{
                                            $to[] =$officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            $from[] = $firm_details['firm_name'];
                                            $sentdate[] =  $l3['created'];
                                        }


                                        $action[] = 'Applicant to RO/SO';
                                    }


                                }

                            }


                            if((!empty($allcation_table['level_2']) && !empty($allcation_table['level_3'])) ){

                                $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_3']]])->first();
                                if(!empty($officer_details)){
                                    $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                }


                                if(!empty($appliType['application_type'])){
                                    $appl_type = $appliType['application_type'];
                                    if(!empty($appl_type)){

                                        $appl_type = strtolower($appliType['application_type']);
                                        $ioAllo = $this->DmiIoAllocationLogs->find('all', ['conditions'=>['customer_id IS'=>$appli_id,'application_type' =>$appl_type ]])->last();
                                    }

                                }
                                if(empty($ioAllo)){
                                    $ioAllo = $this->DmiIoAllocationLogs->find('all', ['conditions'=>['customer_id IS'=>$appli_id,'application_type' =>$appli_type ]])->last();
                                }

                                if(!empty($ioAllo)){
                                   $officer_detailsCurrent   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$ioAllo['io_email_id']]])->first();

                                   if(!empty($officer_detailsCurrent)){
                                    $to[] = $officer_detailsCurrent['f_name'].' '.$officer_detailsCurrent['l_name'].' '.$officer_detailsCurrent['role'];

                                   }
                                    $sentdate[] = $ioAllo['created'];
                                    $action[] = 'Allocated to IO';
                                }


                            }

                            if(!empty($inspection)){
                            $ispectionReportData = $inspection->find('all', ['conditions'=>['customer_id IS'=>$appli_id]])->order('created','ASC')->toArray();
                            }
                            $status= array();
                            $i=0;
                            if(!empty($applicant)){
                                $level_2approved = $applicant->find('all', ['conditions'=>['customer_id IS'=>$appli_id, 'status'=>'approved', 'current_level'=>'level_2']])->first();

                            }
                            if(!empty($ispectionReportData)){
                                    foreach ($ispectionReportData as $key => $inspect) {
                                        if(($inspect['current_level'] == 'level_2'  && $inspect['status'] == 'pending') && !empty($level_2approved)){
                                            if(!empty($officer_details)){

                                                $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            }
                                            if(!empty($allcation_table['level_2'])){
                                                $iOofficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_2']]])->first();
                                                if(!empty($iOofficer_details)){
                                                    $from[] = $iOofficer_details['f_name'].' '.$iOofficer_details['l_name'].' '.$iOofficer_details['role'];
                                                }
                                                $sentdate[] = $level_2approved['modified'];
                                                $action[] = 'IO to RO/SO';
                                            }


                                        }elseif($inspect['current_level'] == 'level_3'  && $inspect['status'] == 'referred_back'){

                                             if(!empty($officer_details)){

                                                $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            }
                                            if(!empty($allcation_table['level_2'])){
                                                $iOofficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_2']]])->first();
                                                if(!empty($iOofficer_details)){
                                                    $to[] = $iOofficer_details['f_name'].' '.$iOofficer_details['l_name'].' '.$iOofficer_details['role'];
                                                    $sentdate[] = $inspect['modified'];
                                                    $action[] = 'Application referred back RO/SO to IO';
                                                }
                                            }
                                        }elseif($inspect['current_level'] == 'level_3'  && $inspect['status'] == 'replied'){
                                            if(!empty($officer_details)){

                                                $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            }
                                            if(!empty($allcation_table['level_2'])){
                                                $iOofficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_2']]])->first();
                                                if(!empty($iOofficer_details)){
                                                    $from[] = $iOofficer_details['f_name'].' '.$iOofficer_details['l_name'].' '.$iOofficer_details['role'];
                                                    $sentdate[] = $inspect['modified'];
                                                    $action[] = 'Application again forwarded IO to RO/SO';
                                                }
                                            }
                                        }elseif($inspect['current_level'] == 'level_3'  && $inspect['status'] == 'ho_allocated'){
                                          //ro to ho
                                          if(!empty($ho_lev)){
                                            $ho__level = $ho_lev->find('all')->where(['customer_id IS'=>$appli_id])->first();
                                             if(!empty($ho__level)){
                                                 $iOofficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$ho__level['dy_ama']]])->first();

                                                 $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];;
                                                 $to[] = $iOofficer_details['f_name'].' '.$iOofficer_details['l_name'].' '.$iOofficer_details['role'];
                                                 $sentdate[] = $ho__level['created'];
                                                 $action[] = 'Application RO/SO to DyAMA';
                                             }

                                         }
                                        } elseif(($inspect['current_level'] == 'level_3' && $inspect['status'] == 'level_4_ro') && (!empty($allcation_table['level_3']) && !empty($allcation_table['level_4_ro']))){
                                           // SO to RO
                                           $officer_details = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_4_ro']]])->first();
                                            if(!empty($officer_details)){

                                             $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                            }
                                                $officer = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_3']]])->first();
                                                $from[] = $officer['f_name'].' '.$officer['l_name'].' '.$officer['role'];



                                                $sentdate[] = $inspect['modified'];
                                                $action[] = 'Forwarded From SO to RO ';

                                        }else{
                                             //ro to ho
                                          if(!empty($ho_lev)){
                                            $ho__level = $ho_lev->find('all')->where(['customer_id IS'=>$appli_id])->first();
                                             if(!empty($ho__level) &&  ($ho__level['current_level'] == $ho__level['dy_ama'])){
                                                 $iOofficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$ho__level['current_level']]])->first();

                                                 $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                                 $to[] = $iOofficer_details['f_name'].' '.$iOofficer_details['l_name'].' '.$iOofficer_details['role'];
                                                 $sentdate[] = $ho__level['created'];
                                                 $action[] = 'Application RO/SO to DyAMA';
                                                }
                                            }
                                        }

                                        $i++;
                                    }

                                }
                                 if(!empty($applicant)){
                                    $level_2approved = $applicant->find('all', ['conditions'=>['customer_id IS'=>$appli_id, 'current_level'=>'level_2']])->first();
                                }


                                //ro/so comment
                                if(!empty($ro_So)){
                                  $roSo_comment = $ro_So->find('all', ['conditions'=>['customer_id IS'=> $appli_id]])->toArray();
                                  if(!empty($roSo_comment)){
                                    foreach($roSo_comment as $roSo){
                                      $officer_data = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roSo['comment_by']]])->first();
                                      if(!empty($officer_data)){
                                         $from[] = $officer_data['f_name'].' '.$officer_data['l_name'].' '.$officer_data['role'];
                                        }
                                        $officer_To_data = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$roSo['comment_to']]])->first();
                                      if(!empty($officer_To_data)){
                                         $to[] = $officer_To_data['f_name'].' '.$officer_To_data['l_name'].' '.$officer_To_data['role'];
                                        }
                                        $sentdate[] = $roSo['created'];
                                        if($roSo['from_user'] == 'ro' && $roSo['to_user'] == 'so' ){
                                            $action[] = 'Forwarded Back from RO to SO';
                                        }elseif($roSo['from_user'] == 'so' && $roSo['to_user'] == 'ro' ){
                                            $action[] = 'Forwarded Back from SO to RO';
                                        }elseif($roSo['from_user'] == 'mo' && $roSo['to_user'] == 'ro' ){
                                            $action[] = 'Forwarded Back from MO to RO';
                                        }elseif($roSo['from_user'] == 'ro' && $roSo['to_user'] == 'mo' ){
                                            $action[] = 'Forwarded Back from RO to MO';
                                        }
                                    }
                                  }

                                }


                                //dyama to jtama communication
                                if(!empty($ho_comment)){
                                    $hoComment_detail = $ho_comment->find('all')->where(['customer_id IS'=>$appli_id])->toArray();

                                      if(!empty($hoComment_detail)){
                                            foreach ($hoComment_detail as $key => $hoComment_details) {


                                                if(!empty($hoComment_details['from_user'])){
                                                    $from_oficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$hoComment_details['comment_by']]])->first();
                                                    $from[] = $from_oficer_details['f_name'].' '.$from_oficer_details['l_name'].' '.$from_oficer_details['role'];
                                                }
                                                if(!empty($hoComment_details['to_user'])){
                                                    $to_oficer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$hoComment_details['comment_to']]])->first();
                                                    $to[] = $to_oficer_details['f_name'].' '.$to_oficer_details['l_name'].' '.$to_oficer_details['role'];
                                                }
                                                $sentdate[] = $hoComment_details['created'];
                                                if($hoComment_details['from_user'] == "dy_ama" && $hoComment_details['to_user'] == "jt_ama"){
                                                    $action[]  = "DyAMA to JtAMA";
                                                }elseif($hoComment_details['from_user'] == "jt_ama" && $hoComment_details['to_user'] == "ama"){
                                                    $action[]  = "JtAMA to AMA";
                                                }elseif($hoComment_details['from_user'] == "ama" && $hoComment_details['to_user'] == "jt_ama"){
                                                    $action[]  = "AMA to JtAMA";
                                                }elseif($hoComment_details['from_user'] == "jt_ama" && $hoComment_details['to_user'] == "dy_ama"){
                                                    $action[]  = " JtAMA to DyAMA";
                                                }elseif($hoComment_details['from_user'] == "dy_ama" && $hoComment_details['to_user'] == "ro"){
                                                    $action[]  = " DyAMA to RO";
                                                }elseif($hoComment_details['from_user'] == "ho_mo_smo" && $hoComment_details['to_user'] == "dy_ama"){
                                                    $action[]  = " MO/SMO to DyAMA";
                                                }elseif($hoComment_details['from_user'] == "dy_ama" && $hoComment_details['to_user'] == "ho_mo_smo"){
                                                    $action[]  = "DyAMA to MO/SMO";
                                                }elseif($hoComment_details['from_user'] == "ro" && $hoComment_details['to_user'] == "dy_ama"){
                                                    $action[]  = "RO to DyAMA ";
                                                }elseif($hoComment_details['from_user'] == "dy_ama" && $hoComment_details['to_user'] == "ro"){
                                                    $action[]  = "DyAMA to RO";
                                                }
                                            }
                                       }

                                }





                                if(!empty($esign)){
                                  $esignedRecord = $esign->find('all', ['conditions'=>['customer_id IS'=>$appli_id]])->first();
                                }
                                if(!empty($grant)){
                                 $grantedRecord = $grant->find('all', ['conditions'=>['customer_id IS'=>$appli_id]])->last();
                                }
                                if(!empty($applicant)){
                                    $level_3_approved = $applicant->find('all', ['conditions'=>['customer_id IS'=>$appli_id, 'status'=>'approved', 'current_level'=>'level_3' ]])->last();
                                   }
                                if((!empty($esignedRecord) && $esignedRecord['certificate_esigned'] == 'yes') && !empty($level_3_approved)){

                                    if(!empty($current_pos['current_user_email_id']) && $current_pos['current_level'] == 'level_3'){
                                        $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$current_pos['current_user_email_id']]])->first();
                                    }

                                    $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                    $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                    $sentdate[]= $esignedRecord['modified'];
                                    $action[]= "Esigned by RO/SO";
                                }
                                if(!empty($grantedRecord)){
                                    // This if statement is updated by Vikas ravidas to check the old application statatus applicant id on 27-02-2024.
                                    if(trim($grantedRecord['user_email_id'])=='old_application' && ($appli_type=='1'  || $appli_type=='2' || $appli_type=='13'))
                                    {
                                        $allcation_table = $allocation->find('all')->select(['id', 'level_3'])->where(['customer_id IS'=>$appli_id])->order(['id'=>'DESC'])->first();
                                        $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$allcation_table['level_3']]])->first();
                                    }else{
                                        $officer_details   = $this->DmiUsers->find('all', ['fields'=>['f_name','l_name','role'], 'conditions'=>['email IS'=>$grantedRecord['user_email_id']]])->first();
                                    }


                                    // $to[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                    $from[] = $officer_details['f_name'].' '.$officer_details['l_name'].' '.$officer_details['role'];
                                    $to[] = $firm_details['firm_name'];
                                    $sentdate[]= $grantedRecord['modified'];
                                    $action[]= " Granted";
                                }

                                $output = array(); $i = 0;
                                foreach ($to as $key => $value) {
                                   $datetime[$i] = str_replace('/', '-', $sentdate[$i]);
                                   $output[$i]['sentdate'] = date('Y-m-d H:i:s ', strtotime($datetime[$i]));
                                   $output[$i]['from'] = $from[$i];
                                   $output[$i]['to'] = $value;
                                   $output[$i]['action'] = $action[$i];
                                   $i++;

                                }

                                foreach ($output as $key => $part) {
                                    $sort[$key] = strtotime($part['sentdate']);
                               }

                               array_multisort(array_map('strtotime',array_column($output,'sentdate')),
                               SORT_DESC,
                               $output);


                            $this->set('output',$output);

                }
            }

        public function ralSideChemistDetails($cust_id){



                $chemist_d = $this->DmiChemistRoToRalLogs->find('all')->where(['chemist_id IS'=>$cust_id, 'is_forwordedtoral IS'=>'yes', 'reshedule_status IS'=>NULL])->first();

                return $chemist_d;

        }


        public function roSideChemistDetails($cust_id){


            $ro_chemist_d = $this->DmiChemistRalToRoLogs->find('all')->where(['chemist_id IS'=>$cust_id, 'training_completed IS'=>1, 'reshedule_status IS'=>'confirm'])->first();

            return $ro_chemist_d;
        }

        //get application id and name of array in ajax success and add in dropdown by laxmi on 20-7-23
        public function getApplId(){
            $this->autoRender = false;


            $appl_type = $this->request->getData('appl_type');
            $username  = $this->Session->read('username');
            $findRole  = $this->DmiUserRoles->find('all')->where(['user_email_id IS'=> $username])->first();
            if($findRole['dy_ama'] == 'yes' || $findRole['jt_ama'] == 'yes' || $findRole['ama'] == 'yes' || $findRole['super_admin'] == 'yes'){
                $findShortCode = $this->DmiRoOffices->find('all', array('fields'=>['short_code'], 'conditions'=>['delete_status IS'=>NULL]))->toArray();
                $condition = '';
                $chemistcondition = '';
                $n = 1;
                foreach($findShortCode as $key => $value){


                        $seprator = ($n!=1)?' OR ':'';
                        $shrtcode = $value['short_code'];
                        $condition .= $seprator."customer_id like '%/$shrtcode/%'";  // dynamic condition to get short code of login users
                        if($appl_type == 4){
                            $chemistcondition .= $seprator."created_by like '%/$shrtcode/%'";
                        }
                        $n++;


                }

                $firmsDetails =  $this->DmiFirms->find('all',['fields'=>['customer_id','firm_name']])->where(array($condition))->where(['delete_status IS'=>null])->order(['firm_name'=>'ASC'])->toArray();

                if($appl_type == 4){

                    $firmsDetails =  $this->DmiChemistRegistrations->find('all',['fields'=>['chemist_id','chemist_fname','chemist_lname','created_by']])->where(array($chemistcondition))->where(['delete_status IS'=>null])->order(['chemist_fname'=>'ASC'])->toArray();
                }

                echo json_encode($firmsDetails);
                exit;



            }elseif($findRole['ro_inspection'] == 'yes'){
                $findId = $this->DmiRoOffices->find('all', array('fields'=>['id','short_code'], 'conditions'=>['ro_email_id IS'=>$username, 'office_type'=>'RO'], 'delete_status IS'=>NULL))->first();

                $findShortCode = $this->DmiRoOffices->find('all', array('fields'=>['short_code'], 'conditions'=>['ro_id_for_so IS'=>$findId['id']] , 'delete_status IS'=>NULL))->toArray();
                $findShortCode_ro = $this->DmiRoOffices->find('all', array('fields'=>['short_code'], 'conditions'=>['short_code IS'=>$findId['short_code']] , 'delete_status IS'=>NULL))->toArray();
                $findShortCode = array_merge($findShortCode_ro, $findShortCode);
                $condition = '';
                $chemistcondition = '';
                $n = 1;
                foreach($findShortCode as $key => $value){


                        $seprator = ($n!=1)?' OR ':'';
                        $shrtcode = $value['short_code'];
                        $condition .= $seprator."customer_id like '%/$shrtcode/%'";  // dynamic condition to get short code of login users
                        if($appl_type == 4){
                            $chemistcondition .= $seprator."created_by like '%/$shrtcode/%'";
                        }
                        $n++;


                }


                $firmsDetails =  $this->DmiFirms->find('all',['fields'=>['customer_id','firm_name']])->where(array($condition))->where(['delete_status IS'=>null])->order(['firm_name'=>'ASC'])->toArray();
                $customer_id = array();
                if($appl_type == 4){
                    $firmsDetails =  $this->DmiChemistRegistrations->find('all',['fields'=>['chemist_id','chemist_fname','chemist_lname','created_by']])->where(array($chemistcondition))->where(['delete_status IS'=>null])->order(['chemist_fname'=>'ASC'])->toArray();
                }

                echo json_encode($firmsDetails);
                exit;

            }elseif($findRole['so_inspection'] == 'yes'){

                $findShortCode = $this->DmiRoOffices->find('all', array('fields'=>['short_code'], 'conditions'=>['ro_email_id IS'=>$username], 'delete_status IS'=>NULL))->toArray();

                $condition = '';
                $chemistcondition = '';
                $n = 1;
                foreach($findShortCode as $key => $value){


                        $seprator = ($n!=1)?' OR ':'';
                        $shrtcode = $value['short_code'];
                        $condition .= $seprator."customer_id like '%/$shrtcode/%'";  // dynamic condition to get short code of login users
                        if($appl_type == 4){
                            $chemistcondition .= $seprator."created_by like '%/$shrtcode/%'";
                        }
                        $n++;


                }

                $firmsDetails =  $this->DmiFirms->find('all',['fields'=>['customer_id','firm_name']])->where(array($condition))->where(['delete_status IS'=>null])->order(['firm_name'=>'ASC'])->toArray();

                if($appl_type == 4){

                     $firmsDetails =  $this->DmiChemistRegistrations->find('all',['fields'=>['chemist_id','chemist_fname','chemist_lname','created_by']])->where(array($chemistcondition))->where(['delete_status IS'=>null])->order(['chemist_fname'=>'ASC'])->toArray();
                }
                echo json_encode($firmsDetails);
                exit;
            }

        }
}
?>
