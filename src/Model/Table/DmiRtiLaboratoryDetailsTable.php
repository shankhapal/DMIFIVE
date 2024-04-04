<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\ConnectionManager;  //added by shankhpal shende on 25/05/2023

class DmiRtiLaboratoryDetailsTable extends Table{
	
	var $name = "DmiRtiLaboratoryDetails";
	
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
        'date_last_inspection' => isset($approved_date)?$approved_date:"",
        'date_p_inspection' => "",
        'name_of_lab' =>"",
        'street_address' => "",
        'email' => "",
        'mobile_no' => "",
        'sub_commodity' => "",
        'approved_chemist' => "",
        'present_time_of_inspection' => "",
        'is_lab_well_lighted' => "",
        'is_properly_equipped' => "",
        'eq_working_order' => "",
        'is_analytical_reg_maintained' => "",
        'are_up_to_date' => "",
        'being_forwarded' => "",
        'last_lot_no' => "",
        'lat_lot_date' => "",
        'commodity' => "",
        'name_of_packers' => "",
        'p_analytical_reg' => "",
        'e_briefly_suggestions_radio' =>isset($e_briefly_suggestions_radio)?$e_briefly_suggestions_radio: "",
        'enumerate_briefly_suggestions' => isset($enumerate_briefly_suggestions)?$enumerate_briefly_suggestions:"",'shortcomings_noticed' => "",
        'suggestions' => "",
        'authorized_persion_name' => "",
        'name_of_inspecting_officer' => "",
        'designation_inspecting_officer' => "",
        'authorized_signature_docs' => "",
        'signnature_of_inspecting_officer_docs' => "",
        'io_reply_once_no' =>"", 
        'user_email_id' =>"", 
        'user_once_no'=>"",
        'referred_back_comment' =>"",
        'referred_back_date' =>"",
        'io_reply' =>"",
        'io_reply_date' =>"",

		    'time_p_inspection'=>"", // added new field by shankhpal on 27/06/2023
        'form_status' =>"",

        'referred_back_by_email' =>"",
        'referred_back_by_once' =>"",
        'current_level' =>"",   
        'delete_ro_referred_back' =>""
      ); 
    }

    $user_email_id = $_SESSION['username'];
    $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
    $MCommodity = TableRegistry::getTableLocator()->get('MCommodity');

    $added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
    $added_firm_field = [];		
    if(!empty($added_firms)){
        $added_firm_field = $added_firms[0];		
    }
			
    //taking id of multiple sub commodities	to show names in list	
    $sub_comm_id = explode(',',(string) $added_firm_field['sub_commodity']); #For Deprecations
    $sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();
    
    // added for fetching registered chemist incharge
    $DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
    
    $chemist_register = $DmiChemistRegistrations->find('all',array('conditions'=>array('created_by IS'=>$customer_id)))->toArray();

    $DmiChemistFinalSubmits = TableRegistry::getTableLocator()->get('DmiChemistFinalSubmits');

    # This sql query are use to fetch approved registered chemist list 
    # Condition : if chemist training is completed or null and status = "approved" AND current_level = 'level_1' OR chemist training not completed AND status = 'approved' AND current_level = 'level_3'
    # both condition are use to display chemist list 
    # Condition 1 : if chemist are approve on scrutiny level 1 then it display
    # condition 2: If chemist are approved with level 3 then it will be display
    # addded by shankhpal on 25/05/2023
    $conn = ConnectionManager::get('default');

    $approved_chemist = "SELECT  cr.chemist_fname, cr.chemist_lname, cr.chemist_id,cr. created_by
    FROM dmi_chemist_registrations AS cr
    INNER JOIN dmi_chemist_final_submits AS cfs ON cfs.customer_id = cr.chemist_id
    WHERE cr.created_by = '$customer_id' AND 
    (((cr.is_training_completed IS NULL OR cr.is_training_completed='yes') AND status = 'approved' AND current_level = 'level_1')
    OR (cr.is_training_completed='no' AND status = 'approved' AND current_level = 'level_3'))";

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
    $list_of_packer = [];
    if(!empty($firm_data)){
					
      $lab_tabl_id = $firm_data['id'];
			# To get the list of attached lab with ca packer
			$attached_lab_with_ca = $DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array('lab_id IS'=>$lab_tabl_id),'order'=>'id asc'))->toList();
        
      if(!empty($attached_lab_with_ca)){
        # To get name of packers
      
        $packer_data = $DmiFirms->find('all',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id IN'=>$attached_lab_with_ca),'order'=>'firm_name asc'))->toArray();
      
        foreach ($packer_data as $each_data) {
          $packer_name = $each_data['firm_name'];
          $list_of_packer[$packer_name] = $packer_name;
        }
      }
		}

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


   $time_array = ['' => 'Hour : Minute'];
    for ($hour = 10; $hour <= 18; $hour++) {
				for ($minute = 0; $minute <= 59; $minute++) {
						$formattedHour = sprintf('%02d', $hour);
						$formattedMinute = sprintf('%02d', $minute);
						$time12HourFormat = date('h:i A', strtotime("$formattedHour:$formattedMinute"));
						$time_array["$formattedHour:$formattedMinute"] = "$time12HourFormat";
				}
		}

    
		return array($form_fields_details,$sub_commodity_value,$chemist_full_name,$list_of_packer,$total_suggestions,$time_array);			
	}
	
	
	public function saveFormDetails($customer_id,$forms_data){
	 
    $CustomersController = new CustomersController;			
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
	
    $htmlentities_date_last_inspection = htmlentities($forms_data['date_last_inspection'],ENT_QUOTES);
    $htmlentities_date_p_inspection =  htmlentities($forms_data['date_p_inspection'],ENT_QUOTES);
    $htmlentities_name_of_lab =  htmlentities($forms_data['name_of_lab'],ENT_QUOTES);
    $htmlentities_street_address =  htmlentities($forms_data['street_address'],ENT_QUOTES);
    $base64_encode_email =  base64_encode($forms_data['email']);
    $base64_encode_mobile_no =  base64_encode($forms_data['mobile_no']);
    $htmlentities_sub_commodity =  htmlentities($forms_data['sub_commodity'],ENT_QUOTES);
    $htmlentities_approved_chemist =  htmlentities($forms_data['approved_chemist'],ENT_QUOTES);
    
    $htmlentities_is_lab_well_lighted =  htmlentities($forms_data['is_lab_well_lighted'],ENT_QUOTES);
    $htmlentities_is_properly_equipped =  htmlentities($forms_data['is_properly_equipped'],ENT_QUOTES);
    $htmlentities_eq_working_order =  htmlentities($forms_data['eq_working_order'],ENT_QUOTES);
    $htmlentities_is_analytical_reg_maintained =  htmlentities($forms_data['is_analytical_reg_maintained'],ENT_QUOTES);
    $htmlentities_are_up_to_date =  htmlentities($forms_data['are_up_to_date'],ENT_QUOTES);
    $htmlentities_being_forwarded =  htmlentities($forms_data['being_forwarded'],ENT_QUOTES);
    $htmlentities_last_lot_no =  htmlentities($forms_data['last_lot_no'],ENT_QUOTES);
    $htmlentities_lat_lot_date =  htmlentities($forms_data['lat_lot_date'],ENT_QUOTES);
    $htmlentities_commodity =  htmlentities($forms_data['commodity'],ENT_QUOTES);
    $htmlentities_name_of_packers =  htmlentities($forms_data['name_of_packers'],ENT_QUOTES);
    $htmlentities_p_analytical_reg =  htmlentities($forms_data['p_analytical_reg'],ENT_QUOTES);
    $htmlentities_e_briefly_suggestions_radio =  htmlentities($forms_data['e_briefly_suggestions_radio'],ENT_QUOTES);
    $htmlentities_enumerate_briefly_suggestions =  htmlentities($forms_data['enumerate_briefly_suggestions'],ENT_QUOTES);
    $htmlentities_shortcomings_noticed =  htmlentities($forms_data['shortcomings_noticed'],ENT_QUOTES);
    $htmlentities_suggestions =  htmlentities($forms_data['suggestions'],ENT_QUOTES);
    $htmlentities_authorized_persion_name =  htmlentities($forms_data['authorized_persion_name'],ENT_QUOTES);
    $htmlentities_name_of_inspecting_officer =  htmlentities($forms_data['name_of_inspecting_officer'],ENT_QUOTES);
    $htmlentities_designation_inspecting_officer =  htmlentities($forms_data['designation_inspecting_officer'],ENT_QUOTES);
 
    $htmlentities_present_time_of_inspection = '';

    if (is_array($forms_data['present_time_of_inspection'])) {
        $htmlentities_present_time_of_inspection = implode(",", $forms_data['present_time_of_inspection']);
    }

    $time_p_inspection = htmlentities($forms_data['time_p_inspection'], ENT_QUOTES); // time_p_inspection added 

    if(!empty($forms_data['analytical_result_docs']->getClientFilename())){

      $file_name = $forms_data['analytical_result_docs']->getClientFilename();
      $file_size = $forms_data['analytical_result_docs']->getSize();
      $file_type = $forms_data['analytical_result_docs']->getClientMediaType();
      $file_local_path = $forms_data['analytical_result_docs']->getStream()->getMetadata('uri');

      $analytical_result_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

    }else{	
      $analytical_result_docs = null;
    }

    if(!empty($forms_data['authorized_signature_docs']->getClientFilename())){

      $file_name = $forms_data['authorized_signature_docs']->getClientFilename();
      $file_size = $forms_data['authorized_signature_docs']->getSize();
      $file_type = $forms_data['authorized_signature_docs']->getClientMediaType();
      $file_local_path = $forms_data['authorized_signature_docs']->getStream()->getMetadata('uri');

      $authorized_signature_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

    }else{	
      $authorized_signature_docs = null;
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
                
    //check if new file is selected	while reply if not save file path from db
    if(!empty($section_form_details[0]['id'])){
      if(empty($analytical_result_docs)){
        $analytical_result_docs = $section_form_details[0]['analytical_result_docs'];
      }
    }      
    //check if new file is selected	while reply if not save file path from db
    if(!empty($section_form_details[0]['id'])){
      if(empty($authorized_signature_docs)){

        $authorized_signature_docs = $section_form_details[0]['authorized_signature_docs'];
      }
    }
    //check if new file is selected	while reply if not save file path from db
    if(!empty($section_form_details[0]['id'])){
      if(empty($signnature_of_inspecting_officer_docs)){

        $signnature_of_inspecting_officer_docs = $section_form_details[0]['signnature_of_inspecting_officer_docs'];
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
      'date_last_inspection' => $htmlentities_date_last_inspection,       
      'date_p_inspection' => $htmlentities_date_p_inspection,
      'name_of_lab' => $htmlentities_name_of_lab,
      'street_address' => $htmlentities_street_address,
      'email' => $base64_encode_email, 
      'mobile_no' => $base64_encode_mobile_no,
      'sub_commodity' => $htmlentities_sub_commodity,
      'approved_chemist' => $htmlentities_approved_chemist,
      'present_time_of_inspection' => $htmlentities_present_time_of_inspection,
      'is_lab_well_lighted' => $htmlentities_is_lab_well_lighted,
      'is_properly_equipped' => $htmlentities_is_properly_equipped,
      'eq_working_order' => $htmlentities_eq_working_order,
      'is_analytical_reg_maintained' => $htmlentities_is_analytical_reg_maintained,
      'are_up_to_date' => $htmlentities_are_up_to_date,
      'being_forwarded' => $htmlentities_being_forwarded,
      'last_lot_no' => $htmlentities_last_lot_no,
      'lat_lot_date' => $htmlentities_lat_lot_date,
      'commodity' => $htmlentities_commodity,
      'name_of_packers' => $htmlentities_name_of_packers,
      'p_analytical_reg' => $htmlentities_p_analytical_reg,
      'e_briefly_suggestions_radio' => $htmlentities_e_briefly_suggestions_radio,
      'enumerate_briefly_suggestions' => $htmlentities_enumerate_briefly_suggestions,
      'shortcomings_noticed' => $htmlentities_shortcomings_noticed,
      'suggestions' => $htmlentities_suggestions,
      'authorized_persion_name' => $htmlentities_authorized_persion_name,
      'name_of_inspecting_officer' => $htmlentities_name_of_inspecting_officer,
      'designation_inspecting_officer' => $htmlentities_designation_inspecting_officer, 
      'authorized_signature_docs' => $authorized_signature_docs,
      'signnature_of_inspecting_officer_docs' => $signnature_of_inspecting_officer_docs,
      'analytical_result_docs' => $analytical_result_docs,
      'user_email_id'=>$_SESSION['username'],
      'user_once_no'=>$_SESSION['once_card_no'],
      'version'=>$current_version,
	    'time_p_inspection'=>$time_p_inspection, // added on 27/06/2023 by shankhpal

      'form_status'=>'saved',
      'created'=>date('Y-m-d H:i:s'),
      'modified'=>date('Y-m-d H:i:s')));	

    if($this->save($formSavedEntity)){
       return $message_id; 
    }else{ 
      $message_id = ""; 
      return $message_id;
    }  		
	}

  # Reason : As per Comments and suggestion updated function
	# Name of person : shankhpal shende
	# Date: 26/05/2023
	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
	
    $CustomersController = new CustomersController;
		$current_version = $CustomersController->Customfunctions->currentVersion($customer_id);

		$formSavedEntity = $this->newEntity(array(			
			'customer_id'=>$customer_id,
			'user_email_id'=>$report_details['user_email_id'],
			'user_once_no'=>$report_details['user_once_no'],
      'date_last_inspection' => $report_details['date_last_inspection'],
      'date_p_inspection' => $report_details['date_p_inspection'],
      'name_of_lab' => $report_details['name_of_lab'],
      'street_address' => $report_details['street_address'],
      'email' => $report_details['email'],
      'mobile_no' => $report_details['mobile_no'],
      'sub_commodity' => $report_details['sub_commodity'],
      'approved_chemist' => $report_details['approved_chemist'],
      'present_time_of_inspection' => $report_details['present_time_of_inspection'],
      'is_lab_well_lighted' => $report_details['is_lab_well_lighted'],
      'is_properly_equipped' => $report_details['is_properly_equipped'],
      'eq_working_order' => $report_details['eq_working_order'],
      'is_analytical_reg_maintained' => $report_details['is_analytical_reg_maintained'],
      'are_up_to_date' => $report_details['are_up_to_date'],
      'being_forwarded' => $report_details['being_forwarded'],
      'last_lot_no' => $report_details['last_lot_no'],
      'lat_lot_date' => $report_details['lat_lot_date'],
      'commodity' => $report_details['commodity'],
      'name_of_packers' => $report_details['name_of_packers'],
      'p_analytical_reg' => $report_details['p_analytical_reg'],
      'e_briefly_suggestions_radio' => $report_details['e_briefly_suggestions_radio'],
      'enumerate_briefly_suggestions' => $report_details['enumerate_briefly_suggestions'],
      'shortcomings_noticed' => $report_details['shortcomings_noticed'],
      'suggestions' => $report_details['suggestions'],
      'authorized_persion_name' => $report_details['authorized_persion_name'],
      'name_of_inspecting_officer' => $report_details['name_of_inspecting_officer'],
      'designation_inspecting_officer' => $report_details['designation_inspecting_officer'],
      'authorized_signature_docs' => $report_details['authorized_signature_docs'],
      'signnature_of_inspecting_officer_docs' => $report_details['signnature_of_inspecting_officer_docs'],
      'analytical_result_docs' => $report_details['analytical_result_docs'],
	    'time_p_inspection'=>$report_details['time_p_inspection'], // added on 27/06/2023 by shankhpal

			'referred_back_comment'=>$reffered_back_comment,
			'rb_comment_ul'=>$rb_comment_ul,
			'referred_back_date'=>date('Y-m-d H:i:s'),
			'referred_back_by_email'=>$_SESSION['username'],
			'referred_back_by_once'=>$_SESSION['once_card_no'],
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