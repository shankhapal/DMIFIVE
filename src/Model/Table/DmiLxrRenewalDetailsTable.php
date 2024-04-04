<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;

class DmiLxrRenewalDetailsTable extends Table{

    // Fetch form section all details
    public function sectionFormDetails($customer_id){

        $CustomersController = new CustomersController;
        $DmiLaboratoryOtherDetails = TableRegistry::getTableLocator()->get('DmiLaboratoryOtherDetails');
        $DmiNablDates = TableRegistry::getTableLocator()->get('DmiNablDates');

        $grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
    
        $form_fields = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id desc'))->first();
     
        if($form_fields != null){
            $form_fields_details = $form_fields;
        }else{
            $form_fields_details = array ( 'id'=>"",'created' => "", 'modified' =>"", 'customer_id' => "", 'reffered_back_comment' => "",
                                            'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"",
                                            'approved_date' => "",'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "",
                                            'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
                                            'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
                                            'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",
                                            'accreditation_no' =>"",
                                            'accreditation_scope' =>"",
                                            'nabl_accreditated_upto' =>"",
                                            'nabl_cert_docs' =>"",
                                            'apeda_cert_docs' =>"",
                                            'grading_details_docs' =>"",

                                        );
        }
        
        if ($DmiNablDates->getNablDate($customer_id) !== null) {
            $previous_details = $DmiNablDates->getNablDate($customer_id);
            $previous_details = $previous_details['nabl_date'];
        } else {
            $previous_details = $DmiLaboratoryOtherDetails->find()->select(['nabl_accreditated_upto'])->where(['customer_id IS' => $customer_id])->order('id DESC')->first();
            $previous_details = $previous_details['nabl_accreditated_upto'];
        }
        
        return array($form_fields_details,$previous_details);
            
    }


    // save or update form data and comment reply by applicant
    public function saveFormDetails($customer_id,$forms_data){

        
        $dataValidatation = $this->postDataValidation($customer_id,$forms_data);
        
        if ($dataValidatation == 1 ) {
            
            $CustomersController = new CustomersController;
            $section_form_details = $this->sectionFormDetails($customer_id);

            
            $accreditation_no = htmlentities($forms_data['accreditation_no'], ENT_QUOTES);
            $accreditation_scope = htmlentities($forms_data['accreditation_scope'], ENT_QUOTES);
            $nabl_accreditated_upto = $CustomersController->Customfunctions->dateFormatCheck(htmlentities($forms_data['nabl_accreditated_upto'], ENT_QUOTES));
            
            // If applicant have referred back on give section
            if ($section_form_details[0]['form_status'] == 'referred_back') {
                
                $max_id = $section_form_details[0]['id'];
                $htmlencoded_reply = htmlentities($forms_data['customer_reply'], ENT_QUOTES);
                $customer_reply_date = date('Y-m-d H:i:s');
                
                if (!empty($forms_data['cr_comment_ul']->getClientFilename())) {
                    
                    $file_name = $forms_data['cr_comment_ul']->getClientFilename();
                    $file_size = $forms_data['cr_comment_ul']->getSize();
                    $file_type = $forms_data['cr_comment_ul']->getClientMediaType();
                    $file_local_path = $forms_data['cr_comment_ul']->getStream()->getMetadata('uri');
                    
                    $cr_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
            
                } else { $cr_comment_ul = null; }
                    
            } else {
            
                $htmlencoded_reply = '';
                $max_id = '';
                $customer_reply_date = '';
                $cr_comment_ul = null;
            }

            if (empty($section_form_details[0]['created'])) {
                $created = date('Y-m-d H:i:s');
            } else {
                //added date function on 31-05-2021 by Amol to convert date format, as saving null
                $created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']);
            }

            //file uploads
            if(!empty($forms_data['nabl_cert_docs']->getClientFilename())){				
                
                $file_name = $forms_data['nabl_cert_docs']->getClientFilename();
                $file_size = $forms_data['nabl_cert_docs']->getSize();
                $file_type = $forms_data['nabl_cert_docs']->getClientMediaType();
                $file_local_path = $forms_data['nabl_cert_docs']->getStream()->getMetadata('uri');			
            
                $nabl_cert_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function				
            
            }else{ $nabl_cert_docs = $section_form_details[0]['nabl_cert_docs']; }

            if(!empty($forms_data['apeda_cert_docs']->getClientFilename())){				
                
                $file_name = $forms_data['apeda_cert_docs']->getClientFilename();
                $file_size = $forms_data['apeda_cert_docs']->getSize();
                $file_type = $forms_data['apeda_cert_docs']->getClientMediaType();
                $file_local_path = $forms_data['apeda_cert_docs']->getStream()->getMetadata('uri');			
            
                $apeda_cert_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function				
            
            }else{ $apeda_cert_docs = $section_form_details[0]['apeda_cert_docs']; }

            if(!empty($forms_data['grading_details_docs']->getClientFilename())){				
                
                $file_name = $forms_data['grading_details_docs']->getClientFilename();
                $file_size = $forms_data['grading_details_docs']->getSize();
                $file_type = $forms_data['grading_details_docs']->getClientMediaType();
                $file_local_path = $forms_data['grading_details_docs']->getStream()->getMetadata('uri');			
            
                $grading_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function				
            
            }else{ $grading_details_docs = $section_form_details[0]['grading_details_docs']; }
            
            $newEntity = $this->newEntity(array(

                'id' => $max_id,
                'customer_id' => $customer_id,
                'accreditation_no' => $accreditation_no,
                'accreditation_scope' => $accreditation_scope,
                'nabl_accreditated_upto' => chop($nabl_accreditated_upto,' 00:00:00'),
                'form_status'=>'saved',
                'customer_reply'=>$htmlencoded_reply,
                'customer_reply_date'=>$customer_reply_date,
                'cr_comment_ul'=>$cr_comment_ul,
                'created'=>$created,
                'modified'=>date('Y-m-d H:i:s'),
                'nabl_cert_docs' =>$nabl_cert_docs,
                'apeda_cert_docs' =>$apeda_cert_docs,
                'grading_details_docs' =>$grading_details_docs
            
            ));
            
            if ($this->save($newEntity)) { return 1; }
            
        } else { return false; }
        
    }


    // To save 	RO/SO referred back  and MO reply comment
    public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to) {
        
        // Import another model in this model
        
        $current_level = $_SESSION['current_level'];
        
        $DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
        
        $CustomersController = new CustomersController;
        $oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);
        
        //added date function on 31-05-2021 by Amol to convert date format, as saving null
        $created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);
        
        if ($reffered_back_to == 'Level3ToApplicant') {
            
            $form_status = 'referred_back';
            $reffered_back_comment = $comment;
            $reffered_back_date = date('Y-m-d H:i:s');
            $rb_comment_ul = $comment_upload;
            $ro_current_comment_to = 'applicant';
            $mo_comment = null;
            $mo_comment_date = null;
            $mo_comment_ul = null;
            $ro_reply_comment = null;
            $ro_reply_comment_date = null;
            $rr_comment_ul = null;
            
        } elseif ($reffered_back_to == 'Level1ToLevel3') {
            
            $form_status = $forms_data['form_status'];
            $reffered_back_comment = null;
            $reffered_back_date = null;
            $rb_comment_ul = null;
            $ro_current_comment_to = null;
            $mo_comment = $comment;
            $mo_comment_date = date('Y-m-d H:i:s');
            $mo_comment_ul = $comment_upload;
            $ro_reply_comment = null;
            $ro_reply_comment_date = null;
            $rr_comment_ul = null;
            
        } elseif ($reffered_back_to == 'Level3ToLevel1') { // this '1' is added to 'level' as it was not there for RO - MO communication on Akash Thakre [19-08-2022]
            
            $form_status = $forms_data['form_status'];
            $reffered_back_comment = $forms_data['reffered_back_comment'];
            $reffered_back_date = $forms_data['reffered_back_date'];
            $rb_comment_ul = $forms_data['rb_comment_ul'];
            $ro_current_comment_to = 'mo';
            $mo_comment = null;
            $mo_comment_date = null;
            $mo_comment_ul = null;
            $ro_reply_comment = $comment;
            $ro_reply_comment_date = date('Y-m-d H:i:s');
            $rr_comment_ul = $comment_upload;
        }
        
        $nabl_accreditated_upto = htmlentities($forms_data['nabl_accreditated_upto'], ENT_QUOTES);
        $nabl_accreditated_upto = $CustomersController->Customfunctions->dateFormatCheck($nabl_accreditated_upto);
        
        $newEntity = $this->newEntity(array(
        
            'customer_id'=>$customer_id,
            'accreditation_no' => $forms_data['accreditation_no'],
            'accreditation_scope' => $forms_data['accreditation_scope'],
            'nabl_accreditated_upto'=>chop($nabl_accreditated_upto,' 00:00:00'),
            'reffered_back_comment'=>$reffered_back_comment,
            'reffered_back_date'=>$reffered_back_date,
            'form_status'=>$form_status,
            'rb_comment_ul'=>$rb_comment_ul,
            'user_email_id'=>$_SESSION['username'],
            'user_once_no'=>$_SESSION['once_card_no'],
            'current_level'=>$current_level,
            'ro_current_comment_to'=>$ro_current_comment_to,
            'mo_comment'=>$mo_comment,
            'mo_comment_date'=>$mo_comment_date,
            'mo_comment_ul'=>$mo_comment_ul,
            'ro_reply_comment'=>$ro_reply_comment,
            'ro_reply_comment_date'=>$ro_reply_comment_date,
            'rr_comment_ul'=>$rr_comment_ul,
            'created'=>$created_date,
            'modified'=>date('Y-m-d H:i:s'),
            'nabl_cert_docs' => $forms_data['nabl_cert_docs'],
            'apeda_cert_docs' => $forms_data['apeda_cert_docs'],
            'grading_details_docs' => $forms_data['grading_details_docs'],
            
            
        ));
        
        if($this->save($newEntity)){
        
            if($oldapplication == 'yes'){
                                
                $old_certificate_details = $DmiOldApplicationDetails->oldApplicationCertificationDetails($customer_id);

                $DmiOldApplicationDetailsEntity = $DmiOldApplicationDetails->newEntity(array(
                                        'id'=>$old_certificate_details['id'],
                                        'old_certificate_pdf'=>$old_certificate_details['old_certificate_pdf'],
                                        'old_application_docs'=>$old_certificate_details['old_application_docs'],
                ));
                    
                if($DmiOldApplicationDetails->save($DmiOldApplicationDetailsEntity)){ return true;  }
                
            }else{ return true; }
        }

    }


    public function postDataValidation($customer_id,$forms_data){

        $returnValue = true;
        if (empty($forms_data['accreditation_no'])) { $returnValue = null ; }
        if (empty($forms_data['accreditation_scope'])) { $returnValue = null ; }
        if (empty($forms_data['nabl_accreditated_upto'])) { $returnValue = null ; }

        $section_form_details = $this->sectionFormDetails($customer_id);
        if(empty($section_form_details[0]['id'])){
            if (empty($forms_data['nabl_cert_docs']->getClientFilename())) { $returnValue = null ; }
            if (empty($forms_data['apeda_cert_docs']->getClientFilename())) { $returnValue = null ; }
            if (empty($forms_data['grading_details_docs']->getClientFilename())) { $returnValue = null ; }
        }
        return $returnValue;

    }

} ?>
