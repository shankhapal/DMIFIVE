<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\ConnectionManager;

class DmiRoutineInspectionPpReportsTable extends Table{

	var $name = "DmiRoutineInspectionPpReports";

	public $validate = array(

		'referred_back_comment'=>array(
				'rule'=>array('maxLength',200),
			),
		'io_reply'=>array(
				'rule'=>array('maxLength',200),
			),
	);

	public function sectionFormDetails($customer_id){

		// select record for customer has approved or not
		$DmiRtiFinalReports = TableRegistry::getTableLocator()->get('DmiRtiFinalReports');
		$DmiRtiAllocations = TableRegistry::getTableLocator()->get('DmiRtiAllocations');
		$CustomersController = new CustomersController;
			
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		$added_sample_details = [];
		// get approve record
		$approved_record = $DmiRtiFinalReports->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'status'=>'approved'),'order'=>'id desc'))->first();
			
		if(!empty($approved_record)){
			$allocated_record = $DmiRtiAllocations->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'date(created) > '=>$approved_record['created']),'order'=>'id desc'))->first();
				
		}
		if(!empty($approved_record) && !empty($allocated_record)){
			
			$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);
		
			$last_report_details = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'version'=>$current_version)))->first();

			if(!empty($last_report_details)){
				$form_fields_details = $last_report_details;
			}else{
				$latest_id = false;
			}
			
		}
		if($latest_id != null){
			
			$report_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();
			$form_fields_details = $report_fields;
			
		}else{

			$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);
			$version2 = $current_version - 1;
			$last_report_details = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'version'=>$version2)))->first();
			
			$allocated_record = $DmiRtiAllocations->find('all', ['conditions' => ['customer_id' => $customer_id],
			'order' => 'id desc'])->toArray();

			if(!empty($last_report_details)){
				$last_insp_suggestion = $last_report_details['last_insp_suggestion'];
				$approved_date = $last_report_details['approved_date']; // if record is last apprive then fetch apprive date from this table 
				$suggestions_last_ins_yes_no = $last_report_details['suggestions_last_ins_yes_no'];
			}else{
				$Dmi_grant_certificates_pdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
				$get_last_grant_date = $Dmi_grant_certificates_pdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
				$approved_date = $get_last_grant_date['date'];
			}

			$form_fields_details = Array (
				'id' =>"",
				'customer_id' =>"",
				'date_last_inspection'=>isset($approved_date)?$approved_date:"",
				'date_p_inspection'=>"",
				'email'=>"",
				'mobile_no'=>"",
				'packaging_material'=>"",
				'valid_upto'=>"",
				'street_address'=>"",
				'registered_office'=>"",
				'press_premises'=>"",
				'physical_check'=>"",
				'is_printing'=>"",
				'storage_facilities'=>"",
				'lab_properly_equipped'=>"",
				'maintains_proper'=>"",
				'right_quality_of_printing'=>"",
				'press_is_marking_logo'=>"",
				'suggestions_last_ins_yes_no'=>isset($suggestions_last_ins_yes_no)?$suggestions_last_ins_yes_no:"",
				'last_insp_suggestion'=>isset($last_insp_suggestion)?$last_insp_suggestion:"",
				'short_obserd'=>"",'if_any_sugg'=>"",
				'signature'=>"",
				'signature_name'=>"",
				'io_reply_once_no' =>"", 
				'user_email_id' =>"",
				'user_once_no' =>"", 
				'referred_back_comment' =>"", 
				'referred_back_date' =>"", 
				'io_reply' =>"", 
				'io_reply_date' =>"", 
				'form_status' =>"",
				'approved_date' =>"",
				'time_p_inspection'=>"", // added new field by shankhpal on 27/06/2023
				'referred_back_by_email' =>"", 
				'referred_back_by_once' =>"", 
				'current_level' =>"",
				'constituent_oil_mill_docs' =>"",
			  'separate_pipe_lines' =>"no", 
			  'delete_ro_referred_back' =>"");
		}
				
		$user_email_id = $_SESSION['username'];

		$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');

		$conn = ConnectionManager::get('default');

		// added DISTINCT for fetch unique record added by shankhpal shende on 19/05/2023
		// updated query on 19/05/2023 by shankhpal shende
		//This query selectrecord of those ca has attached printing press
		$users = "SELECT DISTINCT map.customer_id, dff.firm_name,dff.sub_commodity
		FROM dmi_firms AS df
		INNER JOIN dmi_ca_pp_lab_mapings AS map ON map.pp_id=df.id::varchar
		INNER JOIN dmi_firms AS dff ON dff.customer_id = map.customer_id
		WHERE df.customer_id = '$customer_id' AND map.pp_id IS NOT NULL AND map.map_type = 'pp'";

		$q = $conn->execute($users);
		$all_packers_records = $q->fetchAll('assoc');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');

		$i=0;
		$all_packers_value=array();
	
		foreach($all_packers_records as $value){ // use for show list of CA id's

			$packers_customer_id = $value['customer_id'];
			$all_packers_value[$i]['customer_id'] = $value['customer_id'];
			$all_packers_value[$i]['firm_name'] = $value['firm_name'];
		
			$Dmi_grant_certificates_pdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
			$get_last_grant_date = $Dmi_grant_certificates_pdfs->find('all',array('conditions'=>array('customer_id IS'=>$value['customer_id']),'order'=>array('id desc')))->first();
		
			$last_grant_date = $get_last_grant_date['date'];
		
			$CustomersController = new CustomersController;
			$certificate_valid_upto = $CustomersController->Customfunctions->getCertificateValidUptoDate($value['customer_id'],$last_grant_date);
	
			$all_packers_value[$i]['validupto'] = $certificate_valid_upto;
			
			$DmiAllTblsDetails = TableRegistry::getTableLocator()->get('DmiAllTblsDetails');
			// query updated by shankhpal on 19/05/2023
			$tbl_list = $DmiAllTblsDetails->find('list',array('keyField'=>'id','valueField'=>'tbl_name', 'conditions'=>array('customer_id IN'=>$packers_customer_id,'delete_status IS NULL')))->toList();

			$all_packers_value[$i]['tbl_name'] = $tbl_list;
		
			$sub_commodity_value = $MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>explode(',',$value['sub_commodity']))))->toList();
			$all_packers_value[$i]['sub_commodity'] = $sub_commodity_value;

			$i=$i+1;
		}

		$firm_data = $DmiFirms->find('all',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('customer_id IN'=> $customer_id)))->first(); // updated query toArray to first on 19/05/2023
		$firm_id = $firm_data['id'];

		$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();				
		$added_firm_field = $added_firms[0];
		//taking id of multiple Packaging Materials types to show names in list	
		$packaging_type_id = explode(',',(string) $added_firm_field['packaging_materials']); #For Deprecations
	
		$DmiPackingTypes = TableRegistry::getTableLocator()->get('DmiPackingTypes');
		$packaging_materials_value = $DmiPackingTypes->find('list',array('valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_type_id)))->toList();

		$registered_office_address = $firm_data['street_address']; // added for Registered office address by shankhpal 19/05/2023
	
		// load model DmiPrintingPremisesProfiles on 19/05/2023
		$DmiPrintingPremisesProfiles = TableRegistry::getTableLocator()->get('DmiPrintingPremisesProfiles');
		$premises_data = $DmiPrintingPremisesProfiles->find('all', array('valueField'=>'street_address', 'conditions'=>array('customer_id IS'=>$customer_id)))->first();
		
		$printing_premises_address = $premises_data['street_address'];  
	
		$find_ca_list = $DmiCaPpLabMapings->find('list',array('keyField'=>'customer_id','valueField'=>'customer_id', 'conditions'=>array('pp_id'=>$firm_id)))->toArray();
			
		//added on 19/05/2023 by shankhpal to get valid upto date
		$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		$get_last_grant_date = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		
		$last_grant_date = $get_last_grant_date['date'];
		// load component
		$CustomersController = new CustomersController;
		//added on 19/05/2023 by Shankhpal shende to get valid upto date
		$certificate_valid_upto = $CustomersController->Customfunctions->getCertificateValidUptoDate($customer_id,$last_grant_date);


		$DmiRtiPackerDetails = TableRegistry::getTableLocator()->get('DmiRtiPackerDetails');
		$packaging_details = $DmiRtiPackerDetails->packagingDetails();	
	
		$added_packaging_details = $packaging_details[1];

		$total_approved = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'current_level'=>'level_3')))->toArray();
		
		$total_suggestions = array();

		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');

		foreach ($total_approved as $approved) {
			$approved_date = $approved['approved_date'];
			$allocated_record = $DmiRtiAllocations->find('all', array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
			foreach ($allocated_record as $each_alloc) {
				$io_email_id = $each_alloc['current_level'];
			}
			$find_user_belongs = $DmiUsers->find('all',array('conditions'=>array('email IN'=>$io_email_id,'status'=>'active')))->first();
			$io_user_name = $find_user_belongs['f_name'].' '.$find_user_belongs['l_name'];
			$split_created = explode(' ', (string) $approved_date); // Convert into array 
			$date = $split_created[0]; // Hold only the date 
			$total_suggestions[] = array(
				'io_user_name'=>$io_user_name,
				'last_insp_suggestion' => $approved->last_insp_suggestion,
				'approved_date' => $date,
			);
		}

		// this will be return hours and minutes dropdown
		$time_array = ['' => 'Hour : Minute'];

    for ($hour = 10; $hour <= 18; $hour++) {
				for ($minute = 0; $minute <= 59; $minute++) {
						$formattedHour = sprintf('%02d', $hour);
						$formattedMinute = sprintf('%02d', $minute);
						$time12HourFormat = date('h:i A', strtotime("$formattedHour:$formattedMinute"));
						$time_array["$formattedHour:$formattedMinute"] = "$time12HourFormat";
				}
		}

		return array($form_fields_details,$added_packaging_details,$find_ca_list,$all_packers_value,$registered_office_address,$printing_premises_address,$certificate_valid_upto,$packaging_materials_value,$total_suggestions,$time_array);
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
		/* Comment
		Reason : Updated function as per change request 
		Name of person : shankhpal shende
		Date: 23-05-2023
		*/
		$htmlencoded_date_last_inspection = htmlentities($forms_data['date_last_inspection'], ENT_QUOTES);
		$htmlencoded_date_p_inspection = htmlentities($forms_data['date_p_inspection'], ENT_QUOTES);
		$htmlencoded_printing_press_name = htmlentities($forms_data['printing_press'], ENT_QUOTES);
		$htmlencoded_street_address = htmlentities($forms_data['street_address'], ENT_QUOTES);
		$htmlencoded_mobile_no = base64_encode($forms_data['mobile_no']);
		$htmlencoded_email = base64_encode($forms_data['email']);
		$htmlencoded_physical_check = htmlentities($forms_data['physical_check'], ENT_QUOTES);
		$htmlencoded_is_printing = htmlentities($forms_data['is_printing'], ENT_QUOTES);
		$htmlencoded_storage_facilities = htmlentities($forms_data['storage_facilities'], ENT_QUOTES);
		$htmlencoded_maintains_proper = htmlentities($forms_data['maintains_proper'], ENT_QUOTES);
		$htmlencoded_right_quality_of_printing = htmlentities($forms_data['right_quality_of_printing'], ENT_QUOTES);
		$htmlencoded_valid_upto = htmlentities($forms_data['valid_upto'],ENT_QUOTES);
		$htmlencoded_press_is_marking_logo = htmlentities($forms_data['press_is_marking_logo'], ENT_QUOTES);
		$htmlencoded_suggestions_last_ins_yes_no = htmlentities($forms_data['suggestions_last_ins_yes_no'], ENT_QUOTES);
		$htmlencoded_last_insp_suggestion = htmlentities($forms_data['last_insp_suggestion'], ENT_QUOTES);
		$htmlencoded_shortcomings_noticed = htmlentities($forms_data['shortcomings_noticed'], ENT_QUOTES);
		$htmlencoded_if_any_sugg = htmlentities($forms_data['if_any_sugg'], ENT_QUOTES);
		$htmlencoded_name_of_inspecting_officer = htmlentities($forms_data['name_of_inspecting_officer'], ENT_QUOTES);
				
		$time_p_inspection = htmlentities($forms_data['time_p_inspection'], ENT_QUOTES); // time_p_inspection added on 27/06/2023 by shankhpal

		if(!empty($forms_data['shortcomings_noticed_docs']->getClientFilename())){

			$file_name = $forms_data['shortcomings_noticed_docs']->getClientFilename();
			$file_size = $forms_data['shortcomings_noticed_docs']->getSize();
			$file_type = $forms_data['shortcomings_noticed_docs']->getClientMediaType();
			$file_local_path = $forms_data['shortcomings_noticed_docs']->getStream()->getMetadata('uri');

			$shortcomings_noticed_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$shortcomings_noticed_docs = null;
		}

		if(!empty($forms_data['signnature_io_docs']->getClientFilename())){

			$file_name = $forms_data['signnature_io_docs']->getClientFilename();
			$file_size = $forms_data['signnature_io_docs']->getSize();
			$file_type = $forms_data['signnature_io_docs']->getClientMediaType();
			$file_local_path = $forms_data['signnature_io_docs']->getStream()->getMetadata('uri');

			$signnature_io_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$signnature_io_docs = null;
		}

		// Added condition for update images or file  -> shankhpal shende 23-05-2023
		//check if new file is selected	while reply if not save file path from db
		if(!empty($section_form_details[0]['id'])){
			if(empty($shortcomings_noticed_docs)){
				$shortcomings_noticed_docs = $section_form_details[0]['shortcomings_noticed_docs'];
			}
			//check if new file is selected	while reply if not save file path from db
			if(!empty($section_form_details[0]['id'])){
				if(empty($signnature_io_docs)){
					$signnature_io_docs = $section_form_details[0]['signnature_io_docs'];
				}
			}
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

		$CustomersController = new CustomersController;
		$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);

		$formSavedEntity = $this->newEntity(array(
			'id'=>$section_form_details[0]['id'],
			'customer_id'=>$customer_id,
			'user_email_id'=>$_SESSION['username'],
			'user_once_no'=>$_SESSION['once_card_no'],
			'date_last_inspection'=>	$htmlencoded_date_last_inspection,
			'date_p_inspection'=> $htmlencoded_date_p_inspection,
			'printing_press'=> $htmlencoded_printing_press_name,
			'street_address'=>$htmlencoded_street_address,
			'mobile_no'=> $htmlencoded_mobile_no,
			'email'=> $htmlencoded_email,
			'physical_check'=> $htmlencoded_physical_check,
			'is_printing'=> $htmlencoded_is_printing,
			'storage_facilities'=> $htmlencoded_storage_facilities,
			'maintains_proper'=> $htmlencoded_maintains_proper,
			'right_quality_of_printing' => $htmlencoded_right_quality_of_printing,
			'valid_upto'=>$htmlencoded_valid_upto,
			'press_is_marking_logo'=> $htmlencoded_press_is_marking_logo,
			'suggestions_last_ins_yes_no' => $htmlencoded_suggestions_last_ins_yes_no,
			'last_insp_suggestion'=> $htmlencoded_last_insp_suggestion,
			'shortcomings_noticed'=>$htmlencoded_shortcomings_noticed,
			'if_any_sugg'=>$htmlencoded_if_any_sugg,
			'shortcomings_noticed_docs'=>$shortcomings_noticed_docs,
			'name_of_inspecting_officer'=> $htmlencoded_name_of_inspecting_officer,
			'signnature_io_docs'=>$signnature_io_docs,
			'version'=>$current_version,
			'time_p_inspection'=>$time_p_inspection, // added on 27/06/2023 by shankhpal
			'form_status'=>'saved',
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s')
		));
		if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }
	}

	/* Comment
	Reason : Updated function as per change request 
	Name of person : shankhpal shende
	Date: 24-05-2023
*/
	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
			
		$CustomersController = new CustomersController;
		$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);

		$formSavedEntity = $this->newEntity(array(
			'customer_id'=>$customer_id,
			'user_email_id'=>$report_details['user_email_id'],
			'user_once_no'=>$report_details['user_once_no'],
			'date_last_inspection'=> $report_details['date_last_inspection'],
			'date_p_inspection'=> $report_details['date_p_inspection'],
			'printing_press'=> $report_details['printing_press'],
			'street_address'=> $report_details['street_address'],
			'mobile_no'=> $report_details['mobile_no'],
			'email'=> $report_details['email'],
			'physical_check'=> $report_details['physical_check'],
			'is_printing'=> $report_details['is_printing'],
			'storage_facilities'=> $report_details['storage_facilities'],
			'maintains_proper'=> $report_details['maintains_proper'],
			'right_quality_of_printing' => $report_details['right_quality_of_printing'],
			'valid_upto'=> $report_details['valid_upto'],
			'press_is_marking_logo'=> $report_details['press_is_marking_logo'],
			'suggestions_last_ins_yes_no' => $report_details['suggestions_last_ins_yes_no'],
			'last_insp_suggestion'=> $report_details['last_insp_suggestion'],
			'shortcomings_noticed'=> $report_details['shortcomings_noticed'],
			'if_any_sugg'=> $report_details['if_any_sugg'],
			'shortcomings_noticed_docs'=> $report_details['shortcomings_noticed_docs'],
			'name_of_inspecting_officer'=> $report_details['name_of_inspecting_officer'],
			'signnature_io_docs'=> $report_details['signnature_io_docs'],
			'referred_back_comment'=>$reffered_back_comment,
			'rb_comment_ul'=>$rb_comment_ul,
			'referred_back_date'=>date('Y-m-d H:i:s'),
			'referred_back_by_email'=>$_SESSION['username'],
			'referred_back_by_once'=>$_SESSION['once_card_no'],
			'version'=>$current_version,
			'time_p_inspection'=>$report_details['time_p_inspection'], // added on 27/06/2023 by shankhpal
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
