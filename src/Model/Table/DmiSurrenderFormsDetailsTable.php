<?php 
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
	
class DmiSurrenderFormsDetailsTable extends Table{

	var $name = "DmiSurrenderFormsDetails";

	// Fetch form section all details
	public function sectionFormDetails($customer_id){
		
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		if($latest_id != null){
			$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			
			$form_fields_details = $form_fields;
			
		}else{
			
			$form_fields_details = Array ( 'id'=>"",'created' => "", 'modified' =>"", 'customer_id' => "", 'reffered_back_comment' => "",
											'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 
											'approved_date' => "",'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "",
											'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
											'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",
											'reason' =>"", 
											'required_document' => "", 
											'is_surrender_published'=>"",
											'is_surrender_published_docs'=>"",
											//'is_cabook_submitted'=>"", -> This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
											//'is_cabook_submitted_docs'=>"" ,-> This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
											'is_ca_have_replica'=>"",
											'is_replica_submitted'=>"",
											'is_replica_submitted_docs'=>"",
											'is_balance_printing_submitted'=>"",
											'is_balance_printing_submitted_docs'=>"",
											'printing_declaration'=>"",
											'printing_declaration_docs'=>"",
											'is_packers_conveyed'=>"",
											'is_packers_conveyed_docs'=>"",
											'noc_for_lab'=>"",
											'noc_for_lab_docs'=>"",
											'is_lab_packers_conveyed'=>"",
											'is_lab_packers_conveyed_docs'=>""

										); 
			
		}
		
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
	
		$firmDetails = $DmiFirms->firmDetails($customer_id);	
		$sub_comm_id = explode(',',(string) $firmDetails['sub_commodity']); #For Deprecations
		$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();

		return array($form_fields_details,$sub_commodity_value);
			
	}		
	
	
	// save or update form data and comment reply by applicant
	public function saveFormDetails($customer_id,$forms_data){

		
		$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
		
		if ($dataValidatation == 1 ) {
			
			$CustomersController = new CustomersController;
			$firmType = $CustomersController->Customfunctions->firmType($customer_id);
			$section_form_details = $this->sectionFormDetails($customer_id);


			//Fields details to save
			$reason = htmlentities($forms_data['reason'], ENT_QUOTES);

			if ($firmType == 1) {

				$is_surrender_published = htmlentities($forms_data['is_surrender_published'], ENT_QUOTES);
				#This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				//$is_cabook_submitted = htmlentities($forms_data['is_cabook_submitted'], ENT_QUOTES);
				
				$is_ca_have_replica = htmlentities($forms_data['is_ca_have_replica'], ENT_QUOTES);

			}elseif ($firmType == 2) {

				$is_balance_printing_submitted = htmlentities($forms_data['is_balance_printing_submitted'], ENT_QUOTES);
				$printing_declaration = htmlentities($forms_data['printing_declaration'], ENT_QUOTES);
				$is_packers_conveyed = htmlentities($forms_data['is_packers_conveyed'], ENT_QUOTES);

			}elseif ($firmType == 3) {
				
				$noc_for_lab = htmlentities($forms_data['noc_for_lab'], ENT_QUOTES);
				$is_lab_packers_conveyed = htmlentities($forms_data['is_lab_packers_conveyed'], ENT_QUOTES);
			}
		

			//- FILE UPLOADING CODE -//
			#For Reason Document
			if(!empty($forms_data['required_document']->getClientFilename())){
					
				$file_name = $forms_data['required_document']->getClientFilename();
				$file_size = $forms_data['required_document']->getSize();
				$file_type = $forms_data['required_document']->getClientMediaType();
				$file_local_path = $forms_data['required_document']->getStream()->getMetadata('uri');
				$required_document = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
							
			} else { $required_document = $section_form_details[0]['required_document'];}


			//CA Form
			if ($firmType == 1) {
				#For Publishing Document
				if ($is_surrender_published=='yes') {

					//file uploading					
					if(!empty($forms_data['is_surrender_published_docs']->getClientFilename())){
							
						$file_name = $forms_data['is_surrender_published_docs']->getClientFilename();
						$file_size = $forms_data['is_surrender_published_docs']->getSize();
						$file_type = $forms_data['is_surrender_published_docs']->getClientMediaType();
						$file_local_path = $forms_data['is_surrender_published_docs']->getStream()->getMetadata('uri');
						$is_surrender_published_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
									
					} else { $is_surrender_published_docs = $section_form_details[0]['is_surrender_published_docs'];}

				} else {
					$is_surrender_published_docs=null;
				}
			
				#For CA Book Documents -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				/*
					if ($is_cabook_submitted=='yes') {
						//file uploading					
						if(!empty($forms_data['is_cabook_submitted_docs']->getClientFilename())){
							
							$file_name = $forms_data['is_cabook_submitted_docs']->getClientFilename();
							$file_size = $forms_data['is_cabook_submitted_docs']->getSize();
							$file_type = $forms_data['is_cabook_submitted_docs']->getClientMediaType();
							$file_local_path = $forms_data['is_cabook_submitted_docs']->getStream()->getMetadata('uri');
							$is_cabook_submitted_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
										
						} else { $is_cabook_submitted_docs = $section_form_details[0]['is_cabook_submitted_docs'];}
					} else {
						$is_cabook_submitted_docs = null;
					}
				*/


				#For Replica Documents
				if ($is_ca_have_replica =='yes') {

					$is_replica_submitted = htmlentities($forms_data['is_replica_submitted'], ENT_QUOTES);

					if ($is_replica_submitted == 'yes') {
						//file uploading					
						if(!empty($forms_data['is_replica_submitted_docs']->getClientFilename())){
								
							$file_name = $forms_data['is_replica_submitted_docs']->getClientFilename();
							$file_size = $forms_data['is_replica_submitted_docs']->getSize();
							$file_type = $forms_data['is_replica_submitted_docs']->getClientMediaType();
							$file_local_path = $forms_data['is_replica_submitted_docs']->getStream()->getMetadata('uri');
							$is_replica_submitted_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
										
						} else { $is_replica_submitted_docs = $section_form_details[0]['is_replica_submitted_docs'];}
					}else{
						$is_replica_submitted_docs = null;
					}
					
				}else{ 
					$is_replica_submitted = null;
					$is_replica_submitted_docs = null;
				}
			}else{ 
				$is_surrender_published = null;
				$is_surrender_published_docs = null;
				//$is_cabook_submitted = null; -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				//$is_cabook_submitted_docs = null; -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				$is_ca_have_replica	 = null;
				$is_replica_submitted = null;
				$is_replica_submitted_docs = null;
			}


			//PP Form
			if ($firmType == 2) {
				#For Balance Printing Submitted
				if ($is_balance_printing_submitted=='yes') {

					//file uploading					
					if(!empty($forms_data['is_balance_printing_submitted_docs']->getClientFilename())){
							
						$file_name = $forms_data['is_balance_printing_submitted_docs']->getClientFilename();
						$file_size = $forms_data['is_balance_printing_submitted_docs']->getSize();
						$file_type = $forms_data['is_balance_printing_submitted_docs']->getClientMediaType();
						$file_local_path = $forms_data['is_balance_printing_submitted_docs']->getStream()->getMetadata('uri');
						$is_balance_printing_submitted_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
									
					} else { $is_balance_printing_submitted_docs = $section_form_details[0]['is_balance_printing_submitted_docs'];}

				} else {
					$is_balance_printing_submitted_docs=null;
				}
			
				#For Printing Declaration
				if ($printing_declaration=='yes') {
					//file uploading					
					if(!empty($forms_data['printing_declaration_docs']->getClientFilename())){
						
						$file_name = $forms_data['printing_declaration_docs']->getClientFilename();
						$file_size = $forms_data['printing_declaration_docs']->getSize();
						$file_type = $forms_data['printing_declaration_docs']->getClientMediaType();
						$file_local_path = $forms_data['printing_declaration_docs']->getStream()->getMetadata('uri');
						$printing_declaration_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
									
					} else { $printing_declaration_docs = $section_form_details[0]['printing_declaration_docs'];}
				} else {
					$printing_declaration_docs = null;
				}
				
				#For Packer Conveyed
				if ($is_packers_conveyed == 'yes') {
					//file uploading					
					if(!empty($forms_data['is_packers_conveyed_docs']->getClientFilename())){
							
						$file_name = $forms_data['is_packers_conveyed_docs']->getClientFilename();
						$file_size = $forms_data['is_packers_conveyed_docs']->getSize();
						$file_type = $forms_data['is_packers_conveyed_docs']->getClientMediaType();
						$file_local_path = $forms_data['is_packers_conveyed_docs']->getStream()->getMetadata('uri');
						$is_packers_conveyed_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
									
					} else { $is_packers_conveyed_docs = $section_form_details[0]['is_packers_conveyed_docs'];}
				}else{
					$is_packers_conveyed_docs = null;
				}
			}else{ 
				$is_balance_printing_submitted = null;
				$is_balance_printing_submitted_docs = null;
				$printing_declaration = null;
				$printing_declaration_docs = null;
				$is_packers_conveyed = null;
				$is_packers_conveyed_docs = null;
			}

			
			//LAB Form
			if ($firmType == 3) {
				
				#For Publishing Document
				if ($noc_for_lab=='yes') {

					//file uploading					
					if(!empty($forms_data['noc_for_lab_docs']->getClientFilename())){
							
						$file_name = $forms_data['noc_for_lab_docs']->getClientFilename();
						$file_size = $forms_data['noc_for_lab_docs']->getSize();
						$file_type = $forms_data['noc_for_lab_docs']->getClientMediaType();
						$file_local_path = $forms_data['noc_for_lab_docs']->getStream()->getMetadata('uri');
						$noc_for_lab_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
									
					} else { $noc_for_lab_docs = $section_form_details[0]['noc_for_lab_docs'];}

				} else {
					$noc_for_lab_docs=null;
				}
			
				#For CA Book Documents
				if ($is_lab_packers_conveyed=='yes') {
					//file uploading					
					if(!empty($forms_data['is_lab_packers_conveyed_docs']->getClientFilename())){
						
						$file_name = $forms_data['is_lab_packers_conveyed_docs']->getClientFilename();
						$file_size = $forms_data['is_lab_packers_conveyed_docs']->getSize();
						$file_type = $forms_data['is_lab_packers_conveyed_docs']->getClientMediaType();
						$file_local_path = $forms_data['is_lab_packers_conveyed_docs']->getStream()->getMetadata('uri');
						$is_lab_packers_conveyed_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
									
					} else { $is_lab_packers_conveyed_docs = $section_form_details[0]['is_lab_packers_conveyed_docs'];}
				} else {
					$is_lab_packers_conveyed_docs = null;
				}

			}else{ 
				$noc_for_lab = null;
				$noc_for_lab_docs = null;
				$is_lab_packers_conveyed = null;
				$is_lab_packers_conveyed_docs = null;
			}
		
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
				'reason'=>$reason,
				'required_document'=>$required_document,
				'form_status'=>'saved',
				'customer_reply'=>$htmlencoded_reply,
				'customer_reply_date'=>$customer_reply_date,
				'cr_comment_ul'=>$cr_comment_ul,
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s'),
				'is_surrender_published'=>$is_surrender_published,
				'is_surrender_published_docs'=>$is_surrender_published_docs,
				//'is_cabook_submitted'=>$is_cabook_submitted, -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				//'is_cabook_submitted_docs'=>$is_cabook_submitted_docs, -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				'is_ca_have_replica'=>$is_ca_have_replica,
				'is_replica_submitted'=>$is_replica_submitted,
				'is_replica_submitted_docs'=>$is_replica_submitted_docs,
				'is_balance_printing_submitted'=>$is_balance_printing_submitted,
				'is_balance_printing_submitted_docs'=>$is_balance_printing_submitted_docs,
				'printing_declaration'=>$printing_declaration,
				'printing_declaration_docs'=>$printing_declaration_docs,
				'is_packers_conveyed'=>$is_packers_conveyed,
				'is_packers_conveyed_docs'=>$is_packers_conveyed_docs,
				'noc_for_lab'=>$noc_for_lab,
				'noc_for_lab_docs'=>$noc_for_lab_docs,
				'is_lab_packers_conveyed'=>$is_lab_packers_conveyed,
				'is_lab_packers_conveyed_docs'=>$is_lab_packers_conveyed_docs
			));
			
			if ($this->save($newEntity)) { return 1; };	
			
		} else { return false; }	
		
	}
	
	
	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to) {
		// Import another model in this model	
		
		$logged_in_user = $_SESSION['username'];
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
			
		} elseif ($reffered_back_to == 'Level3ToLevel1') { // this '1' is added to 'level' as it was not there for RO - MO communication on AKASH [19-08-2022]
			
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
			'reason'=>$forms_data['reason'],
			'required_document'=>$forms_data['required_document'],
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
			'is_surrender_published'=>$forms_data['is_surrender_published'],
			'is_surrender_published_docs'=>$forms_data['is_surrender_published_docs'],
			//'is_cabook_submitted'=>$forms_data['is_cabook_submitted'], -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
			//'is_cabook_submitted_docs'=>$forms_data['is_cabook_submitted_docs'], -> #This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
			'is_ca_have_replica'=>$forms_data['is_ca_have_replica'],
			'is_replica_submitted'=>$forms_data['is_replica_submitted'],
			'is_replica_submitted_docs'=>$forms_data['is_replica_submitted_docs'],
			'is_balance_printing_submitted'=>$forms_data['is_balance_printing_submitted'],
			'is_balance_printing_submitted_docs'=>$forms_data['is_balance_printing_submitted_docs'],
			'printing_declaration'=>$forms_data['printing_declaration'],
			'printing_declaration_docs'=>$forms_data['printing_declaration_docs'],
			'is_packers_conveyed'=>$forms_data['is_packers_conveyed'],
			'is_packers_conveyed_docs'=>$forms_data['is_packers_conveyed_docs'],
			'noc_for_lab'=>$forms_data['noc_for_lab'],
			'noc_for_lab_docs'=>$forms_data['noc_for_lab_docs'],
			'is_lab_packers_conveyed'=>$forms_data['is_lab_packers_conveyed'],
			'is_lab_packers_conveyed_docs'=>$forms_data['is_lab_packers_conveyed_docs']
			
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
		$firmType = $CustomersController->Customfunctions->firmType($customer_id);

		if(empty($section_form_details[0]['id'])){

			if(empty($forms_data['required_document']->getClientFilename())){ $returnValue = null ; }
			
			if ($firmType == 1) {

				if ($forms_data['is_surrender_published'] == 'yes') {
					if(empty($forms_data['is_surrender_published_docs']->getClientFilename())){ $returnValue = null ; }
				}

				#This field is not required as UAT Suggestion by DMI - Akash [12-05-2023]
				//if(empty($forms_data['is_cabook_submitted_docs']->getClientFilename())){ $returnValue = null ; }


				if ($forms_data['is_ca_have_replica'] == 'yes' && $forms_data['is_replica_submitted'] == 'yes') {
					if(empty($forms_data['is_replica_submitted_docs']->getClientFilename())){ $returnValue = null ; }
				}

			}elseif($firmType == 2){

				if ($forms_data['is_balance_printing_submitted'] == 'yes') {
					if(empty($forms_data['is_balance_printing_submitted_docs']->getClientFilename())){ $returnValue = null ; }
				}
				if ($forms_data['printing_declaration'] == 'yes') {
					if(empty($forms_data['printing_declaration_docs']->getClientFilename())){ $returnValue = null ; }
				}
				if ($forms_data['is_packers_conveyed'] == 'yes') {
					if(empty($forms_data['is_packers_conveyed_docs']->getClientFilename())){ $returnValue = null ; }
				}
				

			}elseif($firmType == 3){

				if ($forms_data['noc_for_lab'] == 'yes') {
					if(empty($forms_data['noc_for_lab_docs']->getClientFilename())){ $returnValue = null ; }
				}
				if ($forms_data['is_lab_packers_conveyed'] == 'yes') {
					if(empty($forms_data['is_lab_packers_conveyed_docs']->getClientFilename())){ $returnValue = null ; }
				}

			}
		}

		return $returnValue;
		
	}

} ?>