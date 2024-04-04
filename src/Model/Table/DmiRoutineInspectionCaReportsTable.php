<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiRoutineInspectionCaReportsTable extends Table{
	
	var $name = "DmiRoutineInspectionCaReports";
	
	public $validate = array(
		
			'referred_back_comment'=>array(
					'rule'=>array('maxLength',200),				
				),											
			'io_reply'=>array(
					'rule'=>array('maxLength',200),
				),
			
				
	);
	
	
	
	public function sectionFormDetails($customer_id)
	{
	
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			

		if($latest_id != null){
			$report_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields_details = $report_fields;
			
		}else{
			$form_fields_details = Array (  'id' =>"", 'customer_id' =>"",'date_last_inspection'=>"",'up_to_date'=>"",'designation_inspecting_officer'=>"",'fssai_approved_docs'=>"",
			'concerned_offices'=>"",'discrepancies_replica_aco'=>"",'fssai_approved_docs'=>"",'fssai_approved'=>"",'up_to_date_docs'=>"",'name_inspecting_officer'=>"",'are_you_upto_date'=>"",
			'premises_adequately'=>"",'lab_properly_equipped'=>"",'date_p_inspection'=>"",'record_of_invice'=>"",'last_lot_no'=>"",'quantity_graded'=>"",'shortcomings_noticed'=>"",'suggestions'=>"",'replica_account_correct'=>"",'signature'=>"",'signature_name'=>"",'enumerate_briefly_suggestions'=>"", 'io_reply_once_no' =>"",  'referred_back_comment' =>"", 'referred_back_date' =>"", 'io_reply' =>"",'name_of_packer'=>"",'suggestions1'=>'','e_briefly_suggestions_radio'=>"", 
			'io_reply_date' =>"", 'form_status' =>"",'referred_back_by_email' =>"", 'referred_back_by_once' =>"", 'current_level' =>"", 'delete_ro_referred_back' =>""); 
			
		}
		
    $Dmi_grant_certificates_pdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		$get_last_grant_date = $Dmi_grant_certificates_pdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		$last_grant_date = $get_last_grant_date['date'];

		$CustomersController = new CustomersController;
		$certificate_valid_upto = $CustomersController->Customfunctions->getCertificateValidUptoDate($customer_id,$last_grant_date);

		
	
		$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
		$attached_lab = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'lab_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();

	  $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
	
		$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
		$added_firm_field = $added_firms[0];			
    
		//taking id of multiple sub commodities	to show names in list	
		$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
		$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();
 
		$Dmi_check_samples = TableRegistry::getTableLocator()->get('DmiCheckSamples');
    $sample_details = $Dmi_check_samples->RoutineInspectionSampleDetails();	
	  $added_sample_details = $sample_details[1];


		$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
	
		// added by Amol on 21-10-2022 as per replica controller

		$attached_lab = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'lab_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();
			//get printing list
		$lab_list = [];	
		if(!empty($attached_lab)){
			$lab_list = $DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS NULL','id IN'=>$attached_lab),'order'=>'firm_name asc'))->toArray();
		}
		
		$attached_pp = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'pp_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();    
    
		$printers_list = [];
		if(!empty($attached_pp)){
		$printers_list = $DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null','id IN'=>$attached_pp),'order'=>'firm_name asc'))->toArray();
		}

		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');

		$self_registered_chemist = $DmiChemistRegistrations->find('all',array('conditions'=>array('created_by IS'=>$customer_id)))->toArray();
   

		//$self_registered_chemist = $DmiChemistRegistrations->find('all',array('conditions'=>array('created_by IS' => $customer_id)))->toArray();

			return array($form_fields_details,$added_sample_details,$certificate_valid_upto,$sub_commodity_value,$lab_list,$printers_list,$self_registered_chemist);			
	}
	
	
	public function saveFormDetails($customer_id,$forms_data){

		$CustomersController = new CustomersController;			
		
		$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
	
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			
		$final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
	
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($final_submit_table);
			
		$report_final_status = $Dmi_siteinspection_final_report->siteinspectionFinalReportStatus($customer_id);
	
		
		$message_id = 1;
		$current_level = $_SESSION['current_level'];						
	
		$section_form_details = $this->sectionFormDetails($customer_id);
	
		if(!empty($section_form_details[0]['id'])){
			
			$message_id = 2;																		
			
			if(isset($forms_data['io_reply'])){$io_reply = $forms_data['io_reply']; }else{ $io_reply = null; }
			if(isset($report_final_status['status'])){$reportFinalStatusValue = $report_final_status['status']; }else{ $reportFinalStatusValue = null; }
			
			if($current_level == 'level_2' && empty($io_reply) && $reportFinalStatusValue == 'referred_back'){
				
				return 4;  //error "comment required"
			}
			
			if($current_level == 'level_2' && !empty($io_reply) && $reportFinalStatusValue == 'referred_back'){
				
				$message_id = 3;							
			}
		
		}

			//html encoding post data before saving
			$htmlencoded_date_last_inspection = htmlentities($forms_data['date_last_inspection'], ENT_QUOTES);
			$htmlencoded_date_p_inspection = htmlentities($forms_data['date_p_inspection'], ENT_QUOTES);	
			$htmlencoded_record_of_invice = htmlentities($forms_data['record_of_invice'], ENT_QUOTES);	
			$premises_adequately = $forms_data['premises_adequately'];
			$lab_properly_equipped = $forms_data['lab_properly_equipped'];	
			$htmlencoded_last_lot_no = htmlentities($forms_data['last_lot_no'], ENT_QUOTES);	
			$quantity_graded = $forms_data['quantity_graded'];	
      $replica_account_correct = $forms_data['replica_account_correct'];
			$htmlencoded_discrepancies_replica_aco = htmlentities($forms_data['discrepancies_replica_aco'], ENT_QUOTES);
			$htmlencoded_shortcomings_noticed = htmlentities($forms_data['shortcomings_noticed'], ENT_QUOTES);
			$concerned_offices = htmlentities($forms_data['concerned_offices'], ENT_QUOTES);
			$htmlencoded_valid_upto = htmlentities($forms_data['valid_upto'], ENT_QUOTES);
			$htmlencoded_name_Inspecting_officer = htmlentities($forms_data['name_inspecting_officer'], ENT_QUOTES);
			$Designation_inspecting_officer =  $forms_data['designation_inspecting_officer'];
			$name_of_packer = $forms_data['name_of_packer'];
			$are_you_upto_date = htmlentities($forms_data['are_you_upto_date'], ENT_QUOTES);
			$enumerate_briefly_suggestions = $forms_data['enumerate_briefly_suggestions'];
			$e_briefly_suggestions_radio = $forms_data['e_briefly_suggestions_radio'];
			$fssai_approved = $forms_data['fssai_approved'];
			
		
			//file uploading
			if(!empty($forms_data['fssai_approved_docs']->getClientFilename())){

				$file_name = $forms_data['fssai_approved_docs']->getClientFilename();
				$file_size = $forms_data['fssai_approved_docs']->getSize();
				$file_type = $forms_data['fssai_approved_docs']->getClientMediaType();
				$file_local_path = $forms_data['fssai_approved_docs']->getStream()->getMetadata('uri');

				$fssai_approved_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{	
				$fssai_approved_docs = '';
			}
			if(!empty($forms_data['up_to_date_docs']->getClientFilename())){

				$file_name = $forms_data['up_to_date_docs']->getClientFilename();
				$file_size = $forms_data['up_to_date_docs']->getSize();
				$file_type = $forms_data['up_to_date_docs']->getClientMediaType();
				$file_local_path = $forms_data['up_to_date_docs']->getStream()->getMetadata('uri');

				$up_to_date_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{	
				$up_to_date_docs = '';
			}
			

			if(!empty($forms_data['signature']->getClientFilename())){

				$file_name = $forms_data['signature']->getClientFilename();
				$file_size = $forms_data['signature']->getSize();
				$file_type = $forms_data['signature']->getClientMediaType();
				$file_local_path = $forms_data['signature']->getStream()->getMetadata('uri');

				$signature = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{	
				$signature = '';
			}


			if(!empty($forms_data['signature_name']->getClientFilename())){

				$file_name = $forms_data['signature_name']->getClientFilename();
				$file_size = $forms_data['signature_name']->getSize();
				$file_type = $forms_data['signature_name']->getClientMediaType();
				$file_local_path = $forms_data['signature_name']->getStream()->getMetadata('uri');

				$signature_name = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{											
				$signature_name = '';
			}
			

		
		if(!empty($report_final_status)){
			if($report_final_status['status'] == 'referred_back' && !empty($forms_data['io_reply'])){
				
				$htmlencoded_io_reply = htmlentities($forms_data['io_reply'], ENT_QUOTES);
				
				if(!empty($forms_data['ir_comment_ul']->getClientFilename())){				
					
					$file_name = $forms_data['ir_comment_ul']->getClientFilename();
					$file_size = $forms_data['ir_comment_ul']->getSize();
					$file_type = $forms_data['ir_comment_ul']->getClientMediaType();
					$file_local_path = $forms_data['ir_comment_ul']->getStream()->getMetadata('uri');
					
					$ir_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
				}else{ $ir_comment_ul = null; }
				
				$ioReplyEntity = $this->newEntity(array(
					'id'=>$section_form_details[0]['id'],
					'io_reply_once_no'=>$_SESSION['once_card_no'],
					'io_reply_date'=>date('Y-m-d H:i:s'),
					'io_reply'=>$htmlencoded_io_reply,
					'ir_comment_ul'=>$ir_comment_ul,
					'current_level'=>'level_3',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')
				)); 
				
				$this->save($ioReplyEntity);
			}
		}						
			
		$formSavedEntity = $this->newEntity(array(	
			'id'=>$section_form_details[0]['id'],
			'customer_id'=>$customer_id,
			'user_email_id'=>$_SESSION['username'],
			'user_once_no'=>$_SESSION['once_card_no'],
			'date_p_inspection'=>$htmlencoded_date_p_inspection,
			'date_last_inspection'=>$htmlencoded_date_last_inspection,
			'e_briefly_suggestions_radio'=>$e_briefly_suggestions_radio,
			'premises_adequately'=>$premises_adequately,
			'lab_properly_equipped'=>$lab_properly_equipped,
			// 'up_to_date'=>$htmlencoded_valid_upto,
			'last_lot_no'=>$htmlencoded_last_lot_no,
			'quantity_graded'=>$quantity_graded,
			'shortcomings_noticed'=>$htmlencoded_shortcomings_noticed,
			'concerned_offices'=>$concerned_offices,
			'replica_account_correct'=>$replica_account_correct,
			'discrepancies_replica_aco'=>$htmlencoded_discrepancies_replica_aco,
			'signature'=>$signature,
			'signature_name'=>$signature_name,
		  'up_to_date_docs'=>$up_to_date_docs,
			'name_inspecting_officer'=>$htmlencoded_name_Inspecting_officer,
			'designation_inspecting_officer'=>$Designation_inspecting_officer,
			'name_of_packer'=>$name_of_packer,
			'record_of_invice'=>$htmlencoded_record_of_invice,
			'enumerate_briefly_suggestions'=>$enumerate_briefly_suggestions,
			'are_you_upto_date'=>$are_you_upto_date,
			'fssai_approved'=>$fssai_approved,
			'form_status'=>'saved',
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s')
		));	
		
		if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }  		
	}


	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
	
		$formSavedEntity = $this->newEntity(array(			
			'customer_id'=>$customer_id,
			'user_email_id'=>$report_details['user_email_id'],
			'user_once_no'=>$report_details['user_once_no'],
			'date_p_inspection'=>$report_details['date_p_inspection'],
			'date_last_inspection'=>$report_details['date_last_inspection'],
			'record_of_invoice_up_to_date'=>$report_details['record_of_invoice_up_to_date'],
			'premises_adequately'=>$report_details['premises_adequately'],
			'lab_properly_equipped'=>$report_details['lab_properly_equipped'],
			'up_to_date'=>$report_details['up_to_date'],
			'last_lot_no'=>$report_details['last_lot_no'],
			'quantity_graded'=>$report_details['quantity_graded'],
			'shortcomings_noticed'=>$report_details['shortcomings_noticed'],
			'concerned_offices'=>$report_details['concerned_offices'],
			'replica_account_correct'=>$report_details['replica_account_correct'],
			'discrepancies_replica_aco'=>$report_details['discrepancies_replica_aco'],
			'signature'=>$report_details['signature'],
			'signature_name'=>$report_details['signature_name'],
			'fssai_approved_docs'=>$report_details['fssai_approved_docs'],
			'up_to_date_docs'=>$report_details['up_to_date_docs'],
			'name_inspecting_officer'=>$report_details['name_inspecting_officer'],
			'designation_inspecting_officer'=>$report_details['designation_inspecting_officer'],
			'name_of_packer'=>$report_details['name_of_packer'],
		  'are_you_upto_date'=>$report_details['are_you_upto_date'],
			'enumerate_briefly_suggestions'=>$report_details['enumerate_briefly_suggestions'],
			'e_briefly_suggestions_radio'=>$report_details['e_briefly_suggestions_radio'],
			'fssai_approved'=>$report_details['fssai_approved'],
			'referred_back_comment'=>$reffered_back_comment,
			'rb_comment_ul'=>$rb_comment_ul,
			'referred_back_date'=>date('Y-m-d H:i:s'),
			'referred_back_by_email'=>$_SESSION['username'],
			'referred_back_by_once'=>$_SESSION['once_card_no'],
			'form_status'=>'referred_back',
			'current_level'=>$_SESSION['current_level'],
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s')				
		));	
		if($this->save($formSavedEntity)){
			
			return 1;
		}else{
			
			return 0;
		}	
		
	}
			
}

?>