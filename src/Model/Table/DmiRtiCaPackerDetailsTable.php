<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

class DmiRtiCaPackerDetailsTable extends Table{

	var $name = "DmiRtiCaPackerDetails";

	public $validate = array(

			'referred_back_comment'=>array(
					'rule'=>array('maxLength',200),
				),
			'io_reply'=>array(
					'rule'=>array('maxLength',200),
				),


	);


	/* Comment
	Reason : Updated function as per change request
	Name of person : shankhpal shende
	Date: 11-05-2023
*/
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
					$enumerate_briefly_suggestions = $last_report_details['enumerate_briefly_suggestions'];
					$approved_date = $last_report_details['approved_date']; // if record is last apprive then fetch apprive date from this table
					$e_briefly_suggestions_radio = $last_report_details['e_briefly_suggestions_radio'];
				}else{
					$Dmi_grant_certificates_pdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
					$get_last_grant_date = $Dmi_grant_certificates_pdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
					$approved_date = $get_last_grant_date['date'];
				}

				$form_fields_details = Array (
					'id' =>"",'customer_id' =>"",
					'io_reply_once_no' =>"",
					'referred_back_comment' =>"",
					'referred_back_date' =>"",
					'date_last_inspection' =>isset($approved_date)?$approved_date:"",
					'date_p_inspection' =>"",
					'name_authorized_packer' =>"",
					'street_address' =>"",
					'email' =>"",
					'mobile_no' =>"",
					'certificate_no' =>"",
					'valid_upto' =>"",
					'commodity' =>"",
					'grading_lab' =>"",
					'printing_press' =>"",
					'record_of_invice' =>"",
					'chemist_incharge' =>"",
					'present_time_of_inspection' =>"",
					'premises_adequately' =>"",
					'lab_properly_equipped' =>"",
					'are_you_upto_date' =>"",
					'concerned_offices' =>"",
					'last_lot_no' =>"",
					'last_lot_date' =>"",
					'analytical_results' =>"",
					'month_upto' =>"",
					'enumerate_briefly_suggestions'=>isset($enumerate_briefly_suggestions)?$enumerate_briefly_suggestions:"",'e_briefly_suggestions_radio'=>isset($e_briefly_suggestions_radio)?$e_briefly_suggestions_radio:"",
					'replica_account_correct' =>"",
					'discrepancies_replica_aco' =>"",
					'fssai_approved ' =>"",
					'io_reply' =>"",
					'io_reply_date' =>"",
					'form_status' =>"",
					'referred_back_by_email' =>"",
					'referred_back_by_once' =>"",
					'current_level' =>"",
					'delete_ro_referred_back' =>"",
					'analytical_result_docs'=>"",
					'time_p_inspection'=>"", // added new field by shankhpal on 27/06/2023
					'quantity'=>"", // added new field by shankhpal on 27/06/2023
					'grade_units'=>"" // added new field by shankhpal on 27/06/2023
				);

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

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// issue on attaching ownlab query updated also updated logic for domestic lab and ownlab
	// solve by : sjhankhpal shende on date 03/11/2023
		$attached_lab = $DmiCaPpLabMapings->find('list', array(
			'keyField' => 'id',
			'valueField' => 'lab_id',
			'conditions' => ['customer_id' => $customer_id,'delete_status IS'=>null],
			'order' => ['id' => 'DESC'] // Order by 'id' in descending order
		))->first();

		$lab_list = [];
		$laboratory_id = '';
		$DmiCustomerLaboratoryDetails = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
		if(!empty($attached_lab)){

			$laboratory_id = [];
			$laboratory_id['grading_lab_id'] = $attached_lab;

			if (strpos($attached_lab, "/Own") !== false) {

				$recordidArray = explode("/", $attached_lab);

 				$ownlabId = $recordidArray[0];

				$lab_list = $DmiCustomerLaboratoryDetails->find()->select(['laboratory_name'])->where(['id' => $ownlabId])->toArray();

			}else{
				$lab_list = $DmiFirms->find('all',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS NULL','id IN'=>$attached_lab),'order'=>'firm_name asc'))->toArray();
			}

		}
	///////////////////////////////////////////////////////////////////////////////////////////////
		$attached_pp = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'pp_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();

		// printers_list query updated list->all method are use -> shankhpal shende 12-05-2023
		$printers_list = [];
		if(!empty($attached_pp)){
			$printers_list = $DmiFirms->find('all',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null','id IN'=>$attached_pp),'order'=>'firm_name asc'))->toArray();
		}

		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');

		$self_registered_chemist = $DmiChemistRegistrations->find('all',array('conditions'=>array('created_by IS'=>$customer_id)))->toArray();

		$check_if_exist = $this->find()->select(['enumerate_briefly_suggestions'])->where(['customer_id IS' => $customer_id])->order('id desc')->first();

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
					'enumerate_briefly_suggestions' => $approved->enumerate_briefly_suggestions,
					'approved_date' => $date,

			);
		}
		//added for unit dropdown
		//Unit Weight of Parcel
		$MUnitWeight = TableRegistry::getTableLocator()->get('MUnitWeight');
		$grade_units =$MUnitWeight->find('list',array('keyField'=>'unit_weight','valueField'=>'unit_weight','conditions' => array('display' => 'Y'),'order'=>'unit_id asc'))->toArray();
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


		return array($form_fields_details,$added_sample_details,$certificate_valid_upto,$sub_commodity_value,$lab_list,$printers_list,$self_registered_chemist,$total_suggestions,$grade_units,$time_array,$laboratory_id);

	}


	/* Comment
	Reason : Updated saveFormDetails function as per change request
	Name of person : shankhpal shende
	Date: 13-05-2023
  */
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

		    // for saving printing press and grading laboratory
				$DmiCaPpLabMapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');

				$attached_pp = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'pp_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();

				$printers_list = [];
				if(!empty($attached_pp)){

					$printers_list = $DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null','id IN'=>$attached_pp),'order'=>'firm_name asc'))->toArray();
				}

				$attached_lab = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'lab_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();

				$lab_list = [];

					/////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// issue on attaching ownlab query updated also updated logic for domestic lab and ownlab
					// solve by : sjhankhpal shende on date 03/11/2023
						$attached_lab = $DmiCaPpLabMapings->find('list', array(
							'keyField' => 'id',
							'valueField' => 'lab_id',
							'conditions' => ['customer_id' => $customer_id,'delete_status IS'=>null],
							'order' => ['id' => 'DESC'] // Order by 'id' in descending order
						))->first();

						$lab_list = [];

						$DmiCustomerLaboratoryDetails = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
						if(!empty($attached_lab)){

							if (strpos($attached_lab, "/Own") !== false) {

								$recordidArray = explode("/", $attached_lab);

								$ownlabId = $recordidArray[0];

								$lab_list = $DmiCustomerLaboratoryDetails->find()->select(['laboratory_name'])->where(['id' => $ownlabId])->toArray();

							}else{
								$lab_list = $DmiFirms->find('all',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS NULL','id IN'=>$attached_lab),'order'=>'firm_name asc'))->toArray();
							}

						}


				// Extract the keys as a comma-separated string
				$printing_press_keys = implode(",", array_keys($printers_list));
				// Extract the keys as a comma-separated string
				$grading_lab_keys = implode(",", array_keys($lab_list));

				// end

				// for commodity start work
				$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');

				$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

				$added_firm_field = $added_firms[0];

				//taking id of multiple sub commodities	to show names in list
				$sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations

				$sub_commodity_value = $MCommodity->find('all',array('keyField'=>'id','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toArray();

				// Extract the keys as a comma-separated string
				$commodity_value = implode(",", array_keys($sub_commodity_value));
				// end

				// change variables -> shankhpal shende 13-05-2023
				//html encoding post data before saving

		   	    $date_last_inspection  =  htmlentities($forms_data['date_last_inspection'], ENT_QUOTES);
				$date_p_inspection  = htmlentities($forms_data['date_p_inspection'], ENT_QUOTES);
				$name_authorized_packer =   htmlentities($forms_data['name_authorized_packer'], ENT_QUOTES);
				$street_address = htmlentities($forms_data['street_address'], ENT_QUOTES);
				$email =  base64_encode($forms_data['email']); //for email encoding

				$mobile_no =  base64_encode($forms_data['mobile_no']); //for mobile encoding
				$certificate_no =   htmlentities($forms_data['certificate_no'], ENT_QUOTES);
				$valid_upto  = htmlentities($forms_data['valid_upto'], ENT_QUOTES);
				$record_of_invice = htmlentities($forms_data['record_of_invice'], ENT_QUOTES);
				$chemist_incharge  = isset($forms_data['chemist_incharge'])?$forms_data['chemist_incharge']:null;
				$present_time_of_inspection = htmlentities($forms_data['present_time_of_inspection'], ENT_QUOTES);
				$premises_adequately = htmlentities($forms_data['premises_adequately'], ENT_QUOTES);
				$lab_properly_equipped = htmlentities($forms_data['lab_properly_equipped'], ENT_QUOTES);
				$are_you_upto_date  = htmlentities($forms_data['are_you_upto_date'], ENT_QUOTES);
				$concerned_offices = htmlentities($forms_data['concerned_offices'], ENT_QUOTES);
				$last_lot_no  = htmlentities($forms_data['last_lot_no'], ENT_QUOTES);
				$last_lot_date  = htmlentities($forms_data['last_lot_date'], ENT_QUOTES);
				$month_upto  = htmlentities($forms_data['month_upto'], ENT_QUOTES);
				$replica_account_correct = htmlentities($forms_data['replica_account_correct'], ENT_QUOTES);
				$discrepancies_replica_aco =  htmlentities($forms_data['discrepancies_replica_aco'], ENT_QUOTES);
				$fssai_approved = htmlentities($forms_data['fssai_approved'], ENT_QUOTES);
				$e_briefly_suggestions_radio = htmlentities($forms_data['e_briefly_suggestions_radio'], ENT_QUOTES);
				$enumerate_briefly_suggestions =  htmlentities($forms_data['enumerate_briefly_suggestions'], ENT_QUOTES);
				$shortcomings_noticed  = htmlentities($forms_data['shortcomings_noticed'], ENT_QUOTES);
				$suggestions  = htmlentities($forms_data['suggestions'], ENT_QUOTES);
				$name_packer_representative = htmlentities($forms_data['name_packer_representative'], ENT_QUOTES);
				$name_of_inspecting_officer  = htmlentities($forms_data['name_of_inspecting_officer'], ENT_QUOTES);
				$designation_inspecting_officer =   htmlentities($forms_data['designation_inspecting_officer'], ENT_QUOTES);
				$analytical_results = htmlentities($forms_data['analytical_results'], ENT_QUOTES);
				$quantity = htmlentities($forms_data['quantity'], ENT_QUOTES); // quantity added on 27/06/2023 by shankhpal
				$grade_units = htmlentities($forms_data['grade_units'], ENT_QUOTES); // grade_units added on 27/06/2023 by shankhpal
				$time_p_inspection = htmlentities($forms_data['time_p_inspection'], ENT_QUOTES); // time_p_inspection added on 27/06/2023 by shankhpal
				$grading_laboratory = htmlentities($forms_data['grading_laboratory'], ENT_QUOTES);

				if(!empty($forms_data['analytical_result_docs']->getClientFilename())){

					$file_name = $forms_data['analytical_result_docs']->getClientFilename();
					$file_size = $forms_data['analytical_result_docs']->getSize();
					$file_type = $forms_data['analytical_result_docs']->getClientMediaType();
					$file_local_path = $forms_data['analytical_result_docs']->getStream()->getMetadata('uri');

					$analytical_result_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				}else{
					$analytical_result_docs = null;
				}

				if(!empty($forms_data['shortcomings_noticed_docs']->getClientFilename())){

					$file_name = $forms_data['shortcomings_noticed_docs']->getClientFilename();
					$file_size = $forms_data['shortcomings_noticed_docs']->getSize();
					$file_type = $forms_data['shortcomings_noticed_docs']->getClientMediaType();
					$file_local_path = $forms_data['shortcomings_noticed_docs']->getStream()->getMetadata('uri');

					$shortcomings_noticed_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				}else{
					$shortcomings_noticed_docs = null;
				}

				if(!empty($forms_data['signnature_of_packer_docs']->getClientFilename())){

					$file_name = $forms_data['signnature_of_packer_docs']->getClientFilename();
					$file_size = $forms_data['signnature_of_packer_docs']->getSize();
					$file_type = $forms_data['signnature_of_packer_docs']->getClientMediaType();
					$file_local_path = $forms_data['signnature_of_packer_docs']->getStream()->getMetadata('uri');

					$signnature_of_packer_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				}else{
					$signnature_of_packer_docs = null;
				}

				if(!empty($forms_data['signnature_of_inspecting_officer_docs']->getClientFilename())){

					$file_name = $forms_data['signnature_of_inspecting_officer_docs']->getClientFilename();
					$file_size = $forms_data['signnature_of_inspecting_officer_docs']->getSize();
					$file_type = $forms_data['signnature_of_inspecting_officer_docs']->getClientMediaType();
					$file_local_path = $forms_data['signnature_of_inspecting_officer_docs']->getStream()->getMetadata('uri');

					$signnature_of_inspecting_officer_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				}else{
					$signnature_of_inspecting_officer_docs = null;
				}

        // Added condition for update images or file  -> shankhpal shende 13-05-2023

				//check if new file is selected	while reply if not save file path from db
				if(!empty($section_form_details[0]['id'])){
					if(empty($analytical_result_docs)){

						$analytical_result_docs = $section_form_details[0]['analytical_result_docs'];
					}
					//check if new file is selected	while reply if not save file path from db
					if(!empty($section_form_details[0]['id'])){
						if(empty($shortcomings_noticed_docs)){

							$shortcomings_noticed_docs = $section_form_details[0]['shortcomings_noticed_docs'];
						}
					}
					//check if new file is selected	while reply if not save file path from db
					if(!empty($section_form_details[0]['id'])){
						if(empty($signnature_of_packer_docs)){

							$signnature_of_packer_docs = $section_form_details[0]['signnature_of_packer_docs'];
						}
					}
				//check if new file is selected	while reply if not save file path from db
				if(!empty($section_form_details[0]['id'])){
					if(empty($signnature_of_inspecting_officer_docs)){

						$signnature_of_inspecting_officer_docs = $section_form_details[0]['signnature_of_inspecting_officer_docs'];
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

            $dataValidatation = $this->postDataValidation($forms_data);

			if($dataValidatation == 1 ){

		     // Updated saving code as per new variabls -> shankhpal shende 15-05-2023
                $formSavedEntity = $this->newEntity(array(

                        'id'=>$section_form_details[0]['id'],
                        'customer_id'=>$customer_id,
                        'user_email_id'=>$_SESSION['username'],
                        'user_once_no'=>$_SESSION['once_card_no'],
                        'date_last_inspection' => $date_last_inspection,
                        'date_p_inspection'  => $date_p_inspection,
                        'name_authorized_packer'  => $name_authorized_packer,
                        'street_address' => $street_address,
                        'email' => $email,
                        'mobile_no' => $mobile_no,
                        'certificate_no' => $certificate_no,
                        'valid_upto' => $valid_upto,
                        'commodity'=>null,
                        'grading_lab' => $grading_lab_keys,
                        'printing_press' => $printing_press_keys,
                        'record_of_invice' => $record_of_invice,
                        'chemist_incharge'  => $chemist_incharge,
                        'present_time_of_inspection' => $present_time_of_inspection,
                        'premises_adequately' => $premises_adequately,
                        'lab_properly_equipped' => $lab_properly_equipped,
                        'are_you_upto_date'  => $are_you_upto_date,
                        'concerned_offices' => $concerned_offices,
                        'last_lot_no' => $last_lot_no,
                        'last_lot_date'  => $last_lot_date,
                        'month_upto'  => $month_upto,
                        'replica_account_correct'  => $replica_account_correct,
                        'discrepancies_replica_aco' => $discrepancies_replica_aco,
                        'fssai_approved' => $fssai_approved,
                        'e_briefly_suggestions_radio' => $e_briefly_suggestions_radio,
                        'enumerate_briefly_suggestions' => $enumerate_briefly_suggestions,
                        'analytical_results' => $analytical_results,
                        'shortcomings_noticed' => $shortcomings_noticed,
                        'suggestions' => $suggestions,
                        'name_packer_representative' => $name_packer_representative,
                        'name_of_inspecting_officer' => $name_of_inspecting_officer,
                        'designation_inspecting_officer' => $designation_inspecting_officer,
                        'analytical_result_docs'=>$analytical_result_docs,
                        'shortcomings_noticed_docs'=>$shortcomings_noticed_docs,
                        'signnature_of_packer_docs'=>$signnature_of_packer_docs,
                        'signnature_of_inspecting_officer_docs'=>$signnature_of_inspecting_officer_docs,

                        'version'=>$current_version,
                        'grading_lab'=>$grading_laboratory, // added on 03/11/2023
                        'quantity'=>$quantity, // added on 27/06/2023 by shankhpal
                        'grade_units' => $grade_units, // added on 27/06/2023 by shankhpal
                        'time_p_inspection'=>$time_p_inspection, // added on 27/06/2023 by shankhpal
                        'form_status'=>'saved',
                        'created'=>date('Y-m-d H:i:s'),
                        'modified'=>date('Y-m-d H:i:s')
                ));

		    if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }
        }else{
            return false;
        }
	}



    public function postDataValidation($forms_data){
        $shortcommingString = $forms_data['shortcomings_noticed'];
        $characterCount = strlen($shortcommingString);

        if($characterCount > 300){
            return null;
        }

        return true;
    }
	/* Comment
	Reason : Updated saveReferredBackComment function as per change request and
	// suggestion for UAT module after test run
	Name of person : shankhpal shende
	Date: 16-05-2023
  */
	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){

		$CustomersController = new CustomersController;
		$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);

		$formSavedEntity = $this->newEntity(array(
			'customer_id'=>$customer_id,
			'user_email_id'=>$report_details['user_email_id'],
			'user_once_no'=>$report_details['user_once_no'],
			'date_last_inspection' => $report_details['date_last_inspection'],
			'date_p_inspection'  => $report_details['date_p_inspection'],
			'name_authorized_packer'  => $report_details['name_authorized_packer'],
			'street_address' => $report_details['street_address'],
			'email' => $report_details['email'],
			'mobile_no' => $report_details['mobile_no'],
			'certificate_no' => $report_details['certificate_no'],
			'valid_upto' => $report_details['valid_upto'],
			'grading_lab' => $report_details['grading_lab'],
			'printing_press' => $report_details['printing_press'],
			'record_of_invice' => $report_details['record_of_invice'],
			'chemist_incharge'  => $report_details['chemist_incharge'],
			'present_time_of_inspection' => $report_details['present_time_of_inspection'],
			'premises_adequately' => $report_details['premises_adequately'],
			'lab_properly_equipped' => $report_details['lab_properly_equipped'],
			'are_you_upto_date'  => $report_details['are_you_upto_date'],
			'concerned_offices' => $report_details['concerned_offices'],
			'last_lot_no' => $report_details['last_lot_no'],
			'last_lot_date'  => $report_details['last_lot_date'],
			'month_upto'  => $report_details['month_upto'],
			'replica_account_correct'  => $report_details['replica_account_correct'],
			'discrepancies_replica_aco' => $report_details['discrepancies_replica_aco'],
			'fssai_approved' => $report_details['fssai_approved'],
			'e_briefly_suggestions_radio' => $report_details['e_briefly_suggestions_radio'],
			'enumerate_briefly_suggestions' => $report_details['enumerate_briefly_suggestions'],
			'shortcomings_noticed' => $report_details['shortcomings_noticed'],
			'suggestions' => $report_details['suggestions'],
			'name_packer_representative' => $report_details['name_packer_representative'],
			'name_of_inspecting_officer' => $report_details['name_of_inspecting_officer'],
			'designation_inspecting_officer' => $report_details['designation_inspecting_officer'],
			'analytical_results' => $report_details['analytical_results'],
			'analytical_result_docs'=> $report_details['analytical_result_docs'],
			'shortcomings_noticed_docs'=> $report_details['shortcomings_noticed_docs'],
			'signnature_of_packer_docs'=> $report_details['signnature_of_packer_docs'],
			'signnature_of_inspecting_officer_docs'=> $report_details['signnature_of_inspecting_officer_docs'],
			'quantity'=>$report_details['quantity'],  // added on 27/06/2023 by shankhpal
			'grade_units' => $report_details['grade_units'], // added on 27/06/2023 by shankhpal
			'time_p_inspection'=>$report_details['time_p_inspection'], // added on 27/06/2023 by shankhpal
			'referred_back_comment'=>$reffered_back_comment,
			'rb_comment_ul'=>$rb_comment_ul,
			'referred_back_date'=>date('Y-m-d H:i:s'),
			'referred_back_by_email'=>$_SESSION['username'],
			'referred_back_by_once'=>$_SESSION['once_card_no'],
			'grading_lab'=>$report_details['grading_laboratory'], // added on 03/11/2023
			'form_status'=>'referred_back',
			'version'=>$current_version,
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
