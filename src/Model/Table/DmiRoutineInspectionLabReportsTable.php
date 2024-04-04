<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\ConnectionManager;  //added by shankhpal shende on 25/05/2023

class DmiRoutineInspectionLabReportsTable extends Table{
	
	var $name = "DmiRoutineInspectionLabReports";
	
	public $validate = array(
		
			'referred_back_comment'=>array(
					'rule'=>array('maxLength',200),				
				),											
			'io_reply'=>array(
					'rule'=>array('maxLength',200),
				),
		
				
	);
	
	/*  Comment
			Reason: as per comments and suggestion for UAT module after test run updated function
			Name of person : shankhpal shende
			Date: 25/05/2023
	*/
	public function sectionFormDetails($customer_id)
	{
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
			if($latest_id != null){
				$report_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $report_fields;
				
			}else{
				$form_fields_details = Array ( 'id' =>"", 'customer_id' =>"", 'io_reply_once_no' =>"", 'user_email_id' =>"", 'user_once_no' =>"", 'referred_back_comment' =>"", 'referred_back_date' =>"", 'io_reply' =>"",'lab_properly_maintain'=>"",'fwd_Concerned_offices'=>"",'last_lot_no'=>"",'dates'=>"",'p_analytical_reg'=>"",'suggestion_during_last'=>'','suggestions'=>"",'signature_name'=>"",'signature'=>"",'short_noticed'=>"",'suggestion_during_last'=>"",'commodity'=>"", 
				'date_last_inspection'=>"",'date_p_inspection'=>"",'approved_chemist'=>"",'properly_equipped'=>"",'is_equipment'=>"",'eq_working_order'=>"",
				'io_reply_date' =>"", 'form_status' =>"", 'premises_inspected' =>"", 'premises_inspected_status' =>"", 'premises_inspected_docs' =>"", 'room_site_plan_no' =>"",'room_details_docs' =>"", 'storage_site_plan_no' =>"", 'storage_details_docs' =>"", 'locking_adequate' =>"no", 'locking_details' =>"", 'locking_details_docs' =>"", 'lighted_ventilated' =>"no",
				'ventilation_details' =>"", 'ventilation_details_docs' =>"", 'conditions_fulfilled' =>"no", 'condition_details' =>"", 'condition_details_docs' =>"", 'approved_date' =>"",'referred_back_by_email' =>"", 'referred_back_by_once' =>"", 'current_level' =>"", 'constituent_oil_mill_docs' =>"", 'separate_pipe_lines' =>"no", 'delete_ro_referred_back' =>""); 
				
			}
		
		
			$user_email_id = $_SESSION['username'];
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
	
			$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();		
			$added_firm_field = $added_firms[0];			
			
			//taking id of multiple sub commodities	to show names in list	
			$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
			$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();
			
			// added for fetching registered chemist incharge
			$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
			
			$chemist_register = $DmiChemistRegistrations->find('all',array('conditions'=>array('created_by IS'=>$customer_id)))->toArray();
		
			$DmiChemistFinalSubmits = TableRegistry::getTableLocator()->get('DmiChemistFinalSubmits');

			# This sql query are use to fetch registered chemist with status is approved
			# addded by shankhpal on 25/05/2023
			$conn = ConnectionManager::get('default');

			$approved_chemist = "SELECT  cr.chemist_fname, cr.chemist_lname, cr.chemist_id,cr. created_by
			FROM dmi_chemist_registrations AS cr
			INNER JOIN dmi_chemist_final_submits AS cfs ON cfs.customer_id = cr.chemist_id
			WHERE cr.created_by = '$customer_id' AND status = 'approved' AND current_level = 'level_3'";

			$q = $conn->execute($approved_chemist);

			$all_approved_chemist = $q->fetchAll('assoc');
			$chemist_full_name = [];
			
			if (!empty($all_approved_chemist)) {
				$chemist_full_name = [];
				foreach ($all_approved_chemist as $each_chemist) {
						$full_name = $each_chemist['chemist_fname'] . ' ' . $each_chemist['chemist_lname'];
						$chemist_full_name[$full_name] = $full_name;
				}
			}else{
					$chemist_full_name = [];
					// Add other manual options if needed
				
			}
		
			# We use mapping table to fetch how much ca packer are attached this laboratory 
			$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');

			# To get table id of lab
			$firm_data = $DmiFirms->find('all')->select(['id'])->where(['customer_id' => $customer_id])->first();
			
			$lab_tabl_id = [];
			if(!empty($firm_data)){
					$lab_tabl_id = $firm_data['id'];
					
					# To get the list of attached lab with ca packer
					$attached_lab_with_ca = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array('lab_id IS'=>$lab_tabl_id),'order'=>'id asc'))->toList();
					# To get name of packers
					$packer_data = $DmiFirms->find('all',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id IN'=>$attached_lab_with_ca),'order'=>'firm_name asc'))->toArray();

					if(!empty($packer_data)){
						$list_of_packer = [];
						foreach ($packer_data as $each_data) {
							$packer_name = $each_data['firm_name'];
							$list_of_packer[$packer_name] = $packer_name;
						}
					}else{
						$list_of_packer = [];
					}
			}
			
			return array($form_fields_details,$sub_commodity_value,$chemist_full_name,$list_of_packer);			
	}
	
	
	public function saveFormDetails($customer_id,$forms_data){
	 
		$CustomersController = new CustomersController;			
		
		// $ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
		
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
		$approved_chemist = $forms_data['approved_chemist'];
		$htmlencoded_properly_equipped = htmlentities($forms_data['properly_equipped'], ENT_QUOTES);
		$htmlencoded_is_equipment = htmlentities($forms_data['is_equipment'], ENT_QUOTES);
		$htmlencoded_p_analytical_reg = htmlentities($forms_data['p_analytical_reg'], ENT_QUOTES);
		$htmlencoded_lab_properly_maintained = htmlentities($forms_data['lab_properly_maintained'], ENT_QUOTES);
		$htmlencoded_concerned_offices = htmlentities($forms_data['concerned_offices'], ENT_QUOTES);
		$htmlencoded_last_lot_no = htmlentities($forms_data['last_lot_no'], ENT_QUOTES);
		$eq_working_order = $forms_data['eq_working_order'];
		$htmlencoded_date = htmlentities($forms_data['date'], ENT_QUOTES);
		$htmlencoded_short_noticed = htmlentities($forms_data['short_noticed'], ENT_QUOTES);
		$htmlencoded_suggestions = htmlentities($forms_data['suggestions'], ENT_QUOTES);
		$htmlencoded_suggestion_during_last = htmlentities($forms_data['suggestion_during_last'], ENT_QUOTES);
		$htmlencoded_commodity = htmlentities($forms_data['commodity'], ENT_QUOTES);
		
		
		

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
			'date_last_inspection'=>$htmlencoded_date_last_inspection,
			'date_p_inspection'=>$htmlencoded_date_p_inspection,
      'approved_chemist'=>$approved_chemist,
			'properly_equipped'=>$htmlencoded_properly_equipped,
			'is_equipment'=>$htmlencoded_is_equipment,
			'lab_properly_maintain'=>$htmlencoded_lab_properly_maintained,
			'last_lot_no'=>$htmlencoded_last_lot_no,
			'p_analytical_reg'=>$htmlencoded_p_analytical_reg,
			'dates'=>$htmlencoded_date,
			'eq_working_order'=>$eq_working_order,
			'fwd_concerned_offices'=>$htmlencoded_concerned_offices,
			'short_noticed'=>$htmlencoded_short_noticed,
		  'suggestions'=>$htmlencoded_suggestions,
      'signature'=>$signature,
			'signature_name'=>$signature_name,
			'suggestion_during_last'=>$htmlencoded_suggestion_during_last,
			'commodity'=>$htmlencoded_commodity,
			'user_email_id'=>$_SESSION['username'],
			'user_once_no'=>$_SESSION['once_card_no'],
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
			'date_last_inspection'=>$report_details['date_last_inspection'],
			'date_p_inspection'=>$report_details['date_p_inspection'],
			'approved_chemist'=>$report_details['approved_chemist'],
			'properly_equipped'=>$report_details['properly_equipped'],
			'is_equipment'=>$report_details['is_equipment'],
			'last_lot_no'=>$report_details['last_lot_no'],
			'dates'=>$report_details['dates'],
			'lab_properly_maintain'=>$report_details['lab_properly_maintain'],
			'eq_working_order'=>$report_details['eq_working_order'],
			'fwd_concerned_offices'=>$report_details['concerned_offices'],
			'short_noticed'=>$report_details['short_noticed'],
			'suggestions'=>$report_details['suggestions'],
			'signature'=>$report_details['signature'],
			'signature_name'=>$report_details['signature_name'],
			'p_analytical_reg'=>$report_details['p_analytical_reg'],
			'suggestion_during_last'=>$report_details['suggestion_during_last'],
			'commodity'=>$report_details['commodity'],
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