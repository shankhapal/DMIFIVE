<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiBgrStatementOfReplicaTable extends Table{

		var $name = "DmiBgrStatementOfReplica";
		
		// Fetch form section all details
		public function sectionFormDetails($customer_id)
		{
		
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
			if($latest_id != null){
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				
				$form_fields_details = $form_fields;
				
			}else{
				
				$form_fields_details = Array ( 'id'=>"", 'customer_id' => "",
				'reffered_back_comment' => "",
				'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'approved_date' => "",
				'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "", 'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
				'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
				
			}


		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
	  $DmiBgrAnalysisAddMoreDetails = TableRegistry::getTableLocator()->get('DmiBgrAnalysisAddMoreDetails');
		$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
		$added_firm_field = $added_firms[0];	
		
		//taking id of multiple sub commodities	to show names in list	
		$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
		$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();

	  $DmiBgrStatementAddMoreDetails = TableRegistry::getTableLocator()->get('DmiBgrStatementAddMoreDetails');
    $statement_details = $DmiBgrStatementAddMoreDetails->statementDetails();	
		
	  $added_statement_details = $statement_details[1];

		return array($form_fields_details,$added_statement_details,$sub_commodity_value);
				
		}		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
		//pr($forms_data);die;
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if ($dataValidatation == 1 ) {
				
				$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
				$CustomersController = new CustomersController;
				$section_form_details = $this->sectionFormDetails($customer_id);
			
				$firm_details = $Dmi_firm->firmDetails($customer_id);
			
			
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
				
				$newEntity = $this->newEntity(array(			
					'id'=>$max_id,
					'customer_id'=>$customer_id,
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'),
					
				));
				
				if ($this->save($newEntity)) { return 1; };	
				
			} else { return false; }	
			
					
		}
				
		
		
		// To save 	RO/SO referred back  and MO reply comment
		public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{			
			// Import another model in this model	
			
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];
			
			$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
			
			$CustomersController = new CustomersController;
			$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);
			
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
			$created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);
			
			if($reffered_back_to == 'Level3ToApplicant'){
				
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
				
			}elseif($reffered_back_to == 'Level1ToLevel3'){
				
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
				
			}elseif($reffered_back_to == 'Level3ToLevel1'){ // this '1' is added to 'level' as it was not there for RO - MO communication on AKASH [19-08-2022]
				
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
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'any_other_info' => $forms_data['any_other_info'],
				'any_other_upload' => $forms_data['any_other_upload'],
				'form_status'=>$form_status,
				'reffered_back_comment'=>$reffered_back_comment,
				'reffered_back_date'=>$reffered_back_date,
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
			$section_form_details = $this->sectionFormDetails($customer_id);
			
			$CustomersController = new CustomersController;
			$form_type = $CustomersController->Customfunctions->checkApplicantFormType($customer_id);
			
			
			
			return $returnValue;
			
		}

} ?>