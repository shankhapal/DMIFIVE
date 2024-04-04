<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiChangeApplDetailsTable extends Table{

		var $name = "DmiChangeApplDetails";
		
		public $validate = array();
		
		
		
		// Fetch form section all details
		public function sectionFormDetails($customer_id)
		{
			
			$CustomersController = new CustomersController; 
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
			$form_fields = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id desc'))->first();
					
			if($form_fields != null){		
				$form_fields_details = $form_fields;
				$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
				$DistList = $DmiDistricts->find('list',array('keyField'=>'id','valueField'=>'district_name','conditions'=>array('state_id IS'=>$form_fields['premise_state'],'delete_status IS NULL'),'order'=>'district_name asc'),)->toArray();
				$form_fields_details['dist_list'] = $DistList;
				
			}else{
				$form_fields_details = Array ( 'id'=>"", 'firm_name' =>"",'premise_state'=>"", 'premise_street' => "", 'premise_city' => "", 'premise_pin' => "", 'const_of_firm' => "",
											   'mobile_no' => "", 'email_id' => "", 'phone_no' => "", 'comm_category' => "", 'commodity' =>"", 'lab_type' =>"",
											   'lab_name' =>"", 'lab_consent_docs' =>"", 'lab_equipped_docs' =>"", 'chemist_details_docs' =>"", 'packing_types' =>"", 'created' => "", 'modified' =>"", 'customer_id' => "", 'reffered_back_comment' => "",
											   'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'approved_date' => "",
											   'user_email_id' => "", 'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "", 'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											   'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
											   'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",'dist_list'=>"",'business_type'=>"",'rel_doc'=>"",'commodity_fssai_no'=>"",'commodity_fssai_doc'=>"",'premises_fssai_doc'=>"",
											   'premises_gst_doc'=>"",'premises_ownership_doc'=>"",'premises_map_doc'=>"",'premises_machineries_doc'=>"",'tbl_proforma_a2_doc'=>""); 
				
			}

			//below two lines are intensionally added to fetch selected commodities and packing types values as string
			//store in custom field name to get file saving referredback comment between RO/SO and MO
			//on 27-03-2023
			$form_fields_details['selected_comm'] = $form_fields_details['commodity'];
			$form_fields_details['selected_pack'] = $form_fields_details['packing_types'];
			
			//for firm details
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$firm_details = $DmiFirms->firmDetails($customer_id);

			$CustomersController = new CustomersController;
			
			$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
			
			//this condition added on 04-04-2023 by Amol to fetch commodity details from firm table. if not in change appl.
			if (empty($form_fields_details['commodity'])) { $form_fields_details['commodity'] = $firm_details['sub_commodity']; }
			
			$commOrPackingTypeResult = $this->getChangeCommodityDetails($form_fields_details,$firm_type);
			$form_fields_details['packing_types'] = $commOrPackingTypeResult[2];
			$form_fields_details['commodity'] = $commOrPackingTypeResult[1];
			$form_fields_details['comm_category_list'] = $commOrPackingTypeResult[0];
			$form_fields_details['commodity_list'] = $commOrPackingTypeResult[3];
			

			//premises details
			/*if($firm_type==1){
				$PremisesProfilesTable = TableRegistry::getTableLocator()->get('DmiCustomerPremisesProfiles');
			}else{
				$PremisesProfilesTable = TableRegistry::getTableLocator()->get('DmiPrintingPremisesProfiles');
			}*/		
			
			//$premises_details = $PremisesProfilesTable->find('all',array('fields'=>array('street_address','state','district','postal_code'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			
			//show last premises details from firm details table, as lab dont have premises section, and if want to change.
			//updated on 20-04-2023	
			$premises_details['street_address'] =  $firm_details['street_address'];
			$premises_details['state'] =  $firm_details['state'];
			$premises_details['district'] =  $firm_details['district'];
			$premises_details['postal_code'] =  $firm_details['postal_code'];
			
			//Firm details fro business type
			if($firm_type==1){
				$firmProfilesTable = TableRegistry::getTableLocator()->get('DmiCustomerFirmProfiles');
			}elseif($firm_type==2){
				$firmProfilesTable = TableRegistry::getTableLocator()->get('DmiPrintingFirmProfiles');
			}else{
				$firmProfilesTable = TableRegistry::getTableLocator()->get('DmiLaboratoryFirmDetails');
			}
			
			$fetchFirmProfileDetails = $firmProfilesTable->find('all',array('fields'=>array('business_type'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$getBusinessTypes = $CustomersController->Mastertablecontent->allBusinessType();
			$businessTypes = array($getBusinessTypes,$fetchFirmProfileDetails);
			
			//tbl details
			$DmiChangeAllTblsDetails = TableRegistry::getTableLocator()->get('DmiChangeAllTblsDetails');
			$checkChangeTbl = $DmiChangeAllTblsDetails->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			//for first time only
			if(empty($checkChangeTbl)){
				//fetch last details
				$DmiAllTblsDetails = TableRegistry::getTableLocator()->get('DmiAllTblsDetails');
				$getLastTbls = $DmiAllTblsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id asc'))->toArray();
				$dataArr = array();
				foreach($getLastTbls as $each){
					$dataArr[] = array(
						'customer_id'=>$customer_id,
						'tbl_name'=>$each['tbl_name'],
						'tbl_registered'=>$each['tbl_registered'],
						'tbl_registered_no'=>$each['tbl_registered_no'],
						'tbl_registration_docs'=>$each['tbl_registration_docs'],
						'delete_status'=>$each['delete_status'],
						'created'=>$CustomersController->Customfunctions->changeDateFormat($each['created']),
						'modified'=>$CustomersController->Customfunctions->changeDateFormat($each['modified'])
					);
				}
				//save last details in change tbl table
				$ChangeTblsEntity = $DmiChangeAllTblsDetails->newEntities($dataArr);
				foreach($ChangeTblsEntity as $each){
					$DmiChangeAllTblsDetails->save($each);
				}
			}
			$added_tbls_details = $DmiChangeAllTblsDetails->tblsDetails();
			
			//for director details
			$DmiChangeDirectorsDetails = TableRegistry::getTableLocator()->get('DmiChangeDirectorsDetails');
			$checkChangeDirector = $DmiChangeDirectorsDetails->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			//for first time only
			if(empty($checkChangeDirector)){
				//fetch last details
				$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
				$getLastDirector = $DmiAllDirectorsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id asc'))->toArray();
				$dataArr = array();
				foreach($getLastDirector as $each){
					$dataArr[] = array(
						'customer_id'=>$customer_id,
						'user_email_id'=>$each['user_email_id'],
						'd_name'=>$each['d_name'],
						'd_address'=>$each['d_address'],
						'delete_status'=>$each['delete_status'],
						'created'=>$CustomersController->Customfunctions->changeDateFormat($each['created']),
						'modified'=>$CustomersController->Customfunctions->changeDateFormat($each['modified'])
					);
				}
				//save last details in change tbl table
				$ChangeDirectorEntity = $DmiChangeDirectorsDetails->newEntities($dataArr);
				foreach($ChangeDirectorEntity as $each){
					$DmiChangeDirectorsDetails->save($each);
				}
			}
			$added_directors_details = $DmiChangeDirectorsDetails->allDirectorsDetail($customer_id);			
					
			//loboratory details
			$DmiCustomerLaboratoryDetails = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
			$laboratory_types = $CustomersController->Mastertablecontent->allLaboratoryType();
			$fetchlabDetails = $DmiCustomerLaboratoryDetails->find('all',array('fields'=>array('laboratory_name','laboratory_type','consent_letter_docs','chemist_detail_docs','lab_equipped_docs'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$labDetails = array($laboratory_types,$fetchlabDetails);

			//machinery details
			$DmiChangeAllMachinesDetails = TableRegistry::getTableLocator()->get('DmiChangeAllMachinesDetails');
			$checkChangeMachine = $DmiChangeAllMachinesDetails->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			//for first time only
			if(empty($checkChangeMachine)){
				//fetch last details
				$DmiAllMachinesDetails = TableRegistry::getTableLocator()->get('DmiAllMachinesDetails');
				$getLastMachines = $DmiAllMachinesDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id asc'))->toArray();
				$dataArr = array();
				foreach($getLastMachines as $each){
					$dataArr[] = array(
						'customer_id'=>$customer_id,
						'machine_name'=>$each['machine_name'],
						'machine_type'=>$each['machine_type'],
						'machine_no'=>$each['machine_no'],
						'machine_capacity'=>$each['machine_capacity'],
						'delete_status'=>$each['delete_status'],
						'created'=>$CustomersController->Customfunctions->changeDateFormat($each['created']),
						'modified'=>$CustomersController->Customfunctions->changeDateFormat($each['modified'])
					);
				}
				//save last details in change tbl table
				$ChangeMachinesEntity = $DmiChangeAllMachinesDetails->newEntities($dataArr);
				foreach($ChangeMachinesEntity as $each){
					$DmiChangeAllMachinesDetails->save($each);
				}
			}
			$added_machines_details = $DmiChangeAllMachinesDetails->machineDetails($customer_id);
					
			return array($form_fields_details,$firm_details,$premises_details,$added_tbls_details,$added_directors_details,$labDetails,$added_machines_details,$businessTypes);
				
		}		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;
	
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
				$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
				$selectedValues = $selectedfields[0];
				
				$firm_type = $CustomersController->Customfunctions->firmType($customer_id);

				// If applicant have referred back on give section				
				if($section_form_details[0]['form_status'] == 'referred_back'){
					
					$max_id = $section_form_details[0]['id'];
					$htmlencoded_reply = htmlentities($forms_data['customer_reply'], ENT_QUOTES);
					$customer_reply_date = date('Y-m-d H:i:s');
					
					if(!empty($forms_data['cr_comment_ul']->getClientFilename())){				
						
						$file_name = $forms_data['cr_comment_ul']->getClientFilename();
						$file_size = $forms_data['cr_comment_ul']->getSize();
						$file_type = $forms_data['cr_comment_ul']->getClientMediaType();
						$file_local_path = $forms_data['cr_comment_ul']->getStream()->getMetadata('uri');
						
						$cr_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
					}else{ $cr_comment_ul = null; }
						
				}else{ 			
						$htmlencoded_reply = ''; 
						$max_id = ''; 
						$customer_reply_date = '';
						$cr_comment_ul = null;	
				}

				if(empty($section_form_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
				//added date function on 31-05-2021 by Amol to convert date format, as saving null
				else{ $created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']); }
				
				
				
				$dataArray = array();
				//data array for selected fields only
				if(in_array(1,$selectedValues)){
					$dataArray = array_merge($dataArray,array('firm_name'=>htmlentities($forms_data['firm_name'], ENT_QUOTES)));
				}
				if(in_array(2,$selectedValues)){
					
					if(!empty($forms_data['phone_no'])){
						$dataArray = array_merge($dataArray,array(
							'mobile_no'=>base64_encode(htmlentities($forms_data['mobile_no'], ENT_QUOTES)),
							'email_id'=>base64_encode(htmlentities($forms_data['email_id'], ENT_QUOTES)),
							'phone_no'=>base64_encode(htmlentities($forms_data['phone_no'], ENT_QUOTES)),
						));
					}else{
						$dataArray = array_merge($dataArray,array(
							'mobile_no'=>base64_encode(htmlentities($forms_data['mobile_no'], ENT_QUOTES)),
							'email_id'=>base64_encode(htmlentities($forms_data['email_id'], ENT_QUOTES)),
						));
					}
					
				}
				//added on 18-05-2023 for upload, required in TBL change
				if(in_array(3,$selectedValues)){
					if(!empty($forms_data['tbl_proforma_a2_doc']->getClientFilename())){
						$file_name = $forms_data['tbl_proforma_a2_doc']->getClientFilename();
						$file_size = $forms_data['tbl_proforma_a2_doc']->getSize();
						$file_type = $forms_data['tbl_proforma_a2_doc']->getClientMediaType();
						$file_local_path = $forms_data['tbl_proforma_a2_doc']->getStream()->getMetadata('uri');												
						$tbl_proforma_a2_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					}else{ $tbl_proforma_a2_doc = $section_form_details[0]['tbl_proforma_a2_doc']; }

					$dataArray = array_merge($dataArray,array(
						'tbl_proforma_a2_doc'=>$tbl_proforma_a2_doc,
					));
				}
				if(in_array(5,$selectedValues)){

					//added new fields for uploads required for premises change, on 17-05-2023
					//added $firm_type==1 cond. on 15-07-2023 as said fssai upload will be only for CA
					if($firm_type==1 && !empty($forms_data['premises_fssai_doc']->getClientFilename())){
						$file_name = $forms_data['premises_fssai_doc']->getClientFilename();
						$file_size = $forms_data['premises_fssai_doc']->getSize();
						$file_type = $forms_data['premises_fssai_doc']->getClientMediaType();
						$file_local_path = $forms_data['premises_fssai_doc']->getStream()->getMetadata('uri');												
						$premises_fssai_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					}else{ $premises_fssai_doc = $section_form_details[0]['premises_fssai_doc']; }

					if(!empty($forms_data['premises_gst_doc']->getClientFilename())){
						$file_name = $forms_data['premises_gst_doc']->getClientFilename();
						$file_size = $forms_data['premises_gst_doc']->getSize();
						$file_type = $forms_data['premises_gst_doc']->getClientMediaType();
						$file_local_path = $forms_data['premises_gst_doc']->getStream()->getMetadata('uri');												
						$premises_gst_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					}else{ $premises_gst_doc = $section_form_details[0]['premises_gst_doc']; }

					if(!empty($forms_data['premises_ownership_doc']->getClientFilename())){
						$file_name = $forms_data['premises_ownership_doc']->getClientFilename();
						$file_size = $forms_data['premises_ownership_doc']->getSize();
						$file_type = $forms_data['premises_ownership_doc']->getClientMediaType();
						$file_local_path = $forms_data['premises_ownership_doc']->getStream()->getMetadata('uri');												
						$premises_ownership_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					}else{ $premises_ownership_doc = $section_form_details[0]['premises_ownership_doc']; }

					if(!empty($forms_data['premises_map_doc']->getClientFilename())){
						$file_name = $forms_data['premises_map_doc']->getClientFilename();
						$file_size = $forms_data['premises_map_doc']->getSize();
						$file_type = $forms_data['premises_map_doc']->getClientMediaType();
						$file_local_path = $forms_data['premises_map_doc']->getStream()->getMetadata('uri');												
						$premises_map_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					}else{ $premises_map_doc = $section_form_details[0]['premises_map_doc']; }

					if(!empty($forms_data['premises_machineries_doc']->getClientFilename())){
						$file_name = $forms_data['premises_machineries_doc']->getClientFilename();
						$file_size = $forms_data['premises_machineries_doc']->getSize();
						$file_type = $forms_data['premises_machineries_doc']->getClientMediaType();
						$file_local_path = $forms_data['premises_machineries_doc']->getStream()->getMetadata('uri');												
						$premises_machineries_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					}else{ $premises_machineries_doc = $section_form_details[0]['premises_machineries_doc']; }

					$dataArray = array_merge($dataArray,array(
						'premise_street'=>htmlentities($forms_data['premise_street'], ENT_QUOTES),
						'premise_state'=>htmlentities($forms_data['premise_state'], ENT_QUOTES),
						'premise_city'=>htmlentities($forms_data['premise_city'], ENT_QUOTES),
						'premise_pin'=>htmlentities($forms_data['premise_pin'], ENT_QUOTES),
						'premises_fssai_doc'=>$premises_fssai_doc,
						'premises_gst_doc'=>$premises_gst_doc,
						'premises_ownership_doc'=>$premises_ownership_doc,
						'premises_map_doc'=>$premises_map_doc,
						'premises_machineries_doc'=>$premises_machineries_doc,
					));
				}
				if(in_array(6,$selectedValues)){
					
					if(!empty($forms_data['chemist_details_docs']->getClientFilename())){

						$file_name = $forms_data['chemist_details_docs']->getClientFilename();
						$file_size = $forms_data['chemist_details_docs']->getSize();
						$file_type = $forms_data['chemist_details_docs']->getClientMediaType();
						$file_local_path = $forms_data['chemist_details_docs']->getStream()->getMetadata('uri');
													
						$chemist_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $chemist_details_docs = $section_form_details[0]['chemist_details_docs']; }
					
					if(!empty($forms_data['lab_equipped_docs']->getClientFilename())){

						$file_name = $forms_data['lab_equipped_docs']->getClientFilename();
						$file_size = $forms_data['lab_equipped_docs']->getSize();
						$file_type = $forms_data['lab_equipped_docs']->getClientMediaType();
						$file_local_path = $forms_data['lab_equipped_docs']->getStream()->getMetadata('uri');
													
						$lab_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $lab_equipped_docs = $section_form_details[0]['lab_equipped_docs']; }
					
					if(!empty($forms_data['lab_consent_docs']->getClientFilename())){

						$file_name = $forms_data['lab_consent_docs']->getClientFilename();
						$file_size = $forms_data['lab_consent_docs']->getSize();
						$file_type = $forms_data['lab_consent_docs']->getClientMediaType();
						$file_local_path = $forms_data['lab_consent_docs']->getStream()->getMetadata('uri');
													
						$lab_consent_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $lab_consent_docs = $section_form_details[0]['lab_consent_docs']; }
					
					$dataArray = array_merge($dataArray,array(
						'lab_name'=>htmlentities($forms_data['lab_name'], ENT_QUOTES),
						'lab_type'=>htmlentities($forms_data['lab_type'], ENT_QUOTES),
						'chemist_details_docs'=>$chemist_details_docs,
						'lab_equipped_docs'=>$lab_equipped_docs,
						'lab_consent_docs'=>$lab_consent_docs,
					));
				}
				if(in_array(7,$selectedValues)){
				
					if ($firm_type==1 || $firm_type==3) {
						
						$selected_commodity = implode(',',$forms_data['selected_commodity']);

						//added FFSAI fields on 17-05-2023
						if(!empty($forms_data['commodity_fssai_doc']->getClientFilename())){
							$file_name = $forms_data['commodity_fssai_doc']->getClientFilename();
							$file_size = $forms_data['commodity_fssai_doc']->getSize();
							$file_type = $forms_data['commodity_fssai_doc']->getClientMediaType();
							$file_local_path = $forms_data['commodity_fssai_doc']->getStream()->getMetadata('uri');												
							$commodity_fssai_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
						}else{ $commodity_fssai_doc = $section_form_details[0]['commodity_fssai_doc']; }
						
						$dataArray = array_merge($dataArray,array(
							'comm_category'=>htmlentities($forms_data['comm_category'], ENT_QUOTES),
							'commodity'=>htmlentities($selected_commodity, ENT_QUOTES),
							'commodity_fssai_no'=>htmlentities($forms_data['commodity_fssai_no'], ENT_QUOTES),
							'commodity_fssai_doc'=>$commodity_fssai_doc,
						));

						
					} elseif ($firm_type==2) {
						
						$selected_packing_types = implode(',',$forms_data['selected_packing_types']);
						$dataArray = array_merge($dataArray,array(
							'packing_types'=>htmlentities($selected_packing_types, ENT_QUOTES),
						));
						
					}
				}
				if(in_array(9,$selectedValues)){
					$dataArray = array_merge($dataArray,array('business_type'=>htmlentities($forms_data['business_type'], ENT_QUOTES)));
				}
				
				//added new field for relevant document uploading
				//on 03-05-2023 by Amol
				if(!empty($forms_data['rel_doc']->getClientFilename())){

					$file_name = $forms_data['rel_doc']->getClientFilename();
					$file_size = $forms_data['rel_doc']->getSize();
					$file_type = $forms_data['rel_doc']->getClientMediaType();
					$file_local_path = $forms_data['rel_doc']->getStream()->getMetadata('uri');
												
					$rel_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $rel_doc = $section_form_details[0]['rel_doc']; }
				
				//common required fields
				$commonArr = array(				
					'id'=>$max_id,
					'customer_id'=>$customer_id,
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'),
					'rel_doc'=>$rel_doc //added on 03-05-2023
				);
				
				$dataArray = array_merge($dataArray,$commonArr);
				
				$newEntity = $this->newEntity($dataArray);
				
				if ($this->save($newEntity)){ 			
					
					return 1;
					
				};
				
			}else{	return false; }	
			
					
		}
				
		
		
		// To save 	RO/SO referred back  and MO reply comment
		public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{			
			// Import another model in this model	
			
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];
			
			$CustomersController = new CustomersController;
			
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
			$created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);
			
			$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
			
			$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
			$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
			$selectedValues = $selectedfields[0];
			
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
				
			}elseif($reffered_back_to == 'Level3ToLevel'){
				
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
			
			$dataArray = array();
			//data array for selected fields only
			if(in_array(1,$selectedValues)){
				$dataArray = array_merge($dataArray,array('firm_name'=>htmlentities($forms_data['firm_name'], ENT_QUOTES)));
			}
			if(in_array(2,$selectedValues)){
				$dataArray = array_merge($dataArray,array(
					'mobile_no'=>htmlentities($forms_data['mobile_no'], ENT_QUOTES),
					'email_id'=>htmlentities($forms_data['email_id'], ENT_QUOTES),
					'phone_no'=>htmlentities($forms_data['phone_no'], ENT_QUOTES),
				));
			}
			//added on 18-05-2023 for upload, required in TBL change
			if(in_array(3,$selectedValues)){
				$dataArray = array_merge($dataArray,array(
					'tbl_proforma_a2_doc'=>$forms_data['tbl_proforma_a2_doc'],
				));
			}
			if(in_array(5,$selectedValues)){
				$dataArray = array_merge($dataArray,array(
					'premise_street'=>htmlentities($forms_data['premise_street'], ENT_QUOTES),
					'premise_state'=>htmlentities($forms_data['premise_state'], ENT_QUOTES),
					'premise_city'=>htmlentities($forms_data['premise_city'], ENT_QUOTES),
					'premise_pin'=>htmlentities($forms_data['premise_pin'], ENT_QUOTES),
					//new fields for uploads added on 17-05-2023 for premises change
					'premises_fssai_doc'=>$forms_data['premises_fssai_doc'],
					'premises_gst_doc'=>$forms_data['premises_gst_doc'],
					'premises_ownership_doc'=>$forms_data['premises_ownership_doc'],
					'premises_map_doc'=>$forms_data['premises_map_doc'],
					'premises_machineries_doc'=>$forms_data['premises_machineries_doc'],
				));
			}
			if(in_array(6,$selectedValues)){
				
				
				
				/*if(!empty($forms_data['chemist_details_docs']->getClientFilename())){

					$file_name = $forms_data['chemist_details_docs']->getClientFilename();
					$file_size = $forms_data['chemist_details_docs']->getSize();
					$file_type = $forms_data['chemist_details_docs']->getClientMediaType();
					$file_local_path = $forms_data['chemist_details_docs']->getStream()->getMetadata('uri');
												
					$chemist_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $chemist_details_docs = $section_form_details[0]['chemist_details_docs']; }*/
				
				
				
				/*if(!empty($forms_data['lab_equipped_docs']->getClientFilename())){

					$file_name = $forms_data['lab_equipped_docs']->getClientFilename();
					$file_size = $forms_data['lab_equipped_docs']->getSize();
					$file_type = $forms_data['lab_equipped_docs']->getClientMediaType();
					$file_local_path = $forms_data['lab_equipped_docs']->getStream()->getMetadata('uri');
												
					$lab_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $lab_equipped_docs = $section_form_details[0]['lab_equipped_docs']; }*/
				
				
				
				/*if(!empty($forms_data['lab_consent_docs']->getClientFilename())){

					$file_name = $forms_data['lab_consent_docs']->getClientFilename();
					$file_size = $forms_data['lab_consent_docs']->getSize();
					$file_type = $forms_data['lab_consent_docs']->getClientMediaType();
					$file_local_path = $forms_data['lab_consent_docs']->getStream()->getMetadata('uri');
												
					$lab_consent_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $lab_consent_docs = $section_form_details[0]['lab_consent_docs']; }*/
				
				//added these lines on 10-05-2023, as issue noticed on UAT while MO reply to RO
				//and commented above file upload related code, not required in referred back process
				
				$chemist_details_docs = $forms_data['chemist_details_docs'];
				$lab_equipped_docs = $forms_data['lab_equipped_docs'];
				$lab_consent_docs = $forms_data['lab_consent_docs'];
				
				$dataArray = array_merge($dataArray,array(
					'lab_name'=>htmlentities($forms_data['lab_name'], ENT_QUOTES),
					'lab_type'=>htmlentities($forms_data['lab_type'], ENT_QUOTES),
					'chemist_details_docs'=>$chemist_details_docs,
					'lab_equipped_docs'=>$lab_equipped_docs,
					'lab_consent_docs'=>$lab_consent_docs,
				));
			}
			if(in_array(7,$selectedValues)){
			
				if ($firm_type==1 || $firm_type==3) {
					$dataArray = array_merge($dataArray,array(
						'comm_category'=>htmlentities($forms_data['comm_category'], ENT_QUOTES),
						'commodity'=>htmlentities($forms_data['selected_comm'], ENT_QUOTES),
						'commodity_fssai_no'=>htmlentities($forms_data['commodity_fssai_no'], ENT_QUOTES),
						'commodity_fssai_doc'=>$forms_data['commodity_fssai_doc'],
					));
					
				} elseif ($firm_type==2) {
					$dataArray = array_merge($dataArray,array(
						'packing_types'=>htmlentities($forms_data['selected_pack'], ENT_QUOTES),
					));
					
				}
			}
			if(in_array(9,$selectedValues)){
				$dataArray = array_merge($dataArray,array('business_type'=>htmlentities($forms_data['business_type'], ENT_QUOTES)));
			}
			
			//added new field for relevant document uploading
			//on 03-05-2023 by Amol
			/*if(!empty($forms_data['rel_doc']->getClientFilename())){

				$file_name = $forms_data['rel_doc']->getClientFilename();
				$file_size = $forms_data['rel_doc']->getSize();
				$file_type = $forms_data['rel_doc']->getClientMediaType();
				$file_local_path = $forms_data['rel_doc']->getStream()->getMetadata('uri');
											
				$rel_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
			
			}else{ $rel_doc = $section_form_details[0]['rel_doc']; }*/
			
			//added these lines on 10-05-2023, as issue noticed on UAT while MO reply to RO
			//and commented above file upload related code, not required in referred back process
			$rel_doc = $forms_data['rel_doc'];
			
			//common required fields
			$commonArr = array(				

				'customer_id'=>$customer_id,
				'created'=>$created_date,
				'modified'=>date('Y-m-d H:i:s'),
				'form_status'=>$form_status,
				'reffered_back_comment'=>$reffered_back_comment,
				'reffered_back_date'=>$reffered_back_date,
				'rb_comment_ul'=>$rb_comment_ul,
				'user_email_id'=>$_SESSION['username'],
				'current_level'=>$current_level,
				'ro_current_comment_to'=>$ro_current_comment_to,	
				'mo_comment'=>$mo_comment,
				'mo_comment_date'=>$mo_comment_date,
				'mo_comment_ul'=>$mo_comment_ul,
				'ro_reply_comment'=>$ro_reply_comment,
				'ro_reply_comment_date'=>$ro_reply_comment_date,
				'rr_comment_ul'=>$rr_comment_ul,
				'rel_doc'=>$rel_doc //added on 03-05-2023
			);
			
			$dataArray = array_merge($dataArray,$commonArr);
			
			$newEntity = $this->newEntity($dataArray);
			
			if($this->save($newEntity)){ 
			
				return true; 
			}

		}


		public function postDataValidation($customer_id,$forms_data){
		
			$returnValue = true;
			$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
			$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
			$selectedValues = $selectedfields[0];
			
			$CustomersController = new CustomersController;
			$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
						
			if(in_array(1,$selectedValues) && empty($forms_data['firm_name'])){ $returnValue = null ; }
			
			if(in_array(2,$selectedValues)){ 
			
				if(empty($forms_data['mobile_no']) || empty($forms_data['email_id'])/* || empty($forms_data['phone_no'])*/){
					$returnValue = null ; 
				}
			
			}	
			if(in_array(5,$selectedValues)){ 
			
				if(empty($forms_data['premise_street']) || empty($forms_data['premise_state']) || empty($forms_data['premise_city']) || empty($forms_data['premise_pin'])){
					$returnValue = null ; 
				}
			
			}	
			if(in_array(6,$selectedValues)){ 
			
				if(empty($forms_data['lab_name']) || empty($forms_data['lab_type'])){
					
					if($forms_data['lab_type']==1){
						if(empty($forms_data['lab_equipped_docs']->getClientFilename())){ $returnValue = null ; }
						if(empty($forms_data['chemist_details_docs']->getClientFilename())){ $returnValue = null ; }
					}else{
						if(empty($forms_data['lab_consent_docs']->getClientFilename())){ $returnValue = null ; }				
					}

				}
			
			}
			if(in_array(7,$selectedValues)){ 
			
				if ($firm_type==1 || $firm_type==3) {
					if(empty($forms_data['comm_category']) || empty($forms_data['selected_commodity'])){
						$returnValue = null ; 
					}
				}
				if ($firm_type==2) {
					if(empty($forms_data['selected_packing_types'])){
						$returnValue = null ; 
					}
					
				}
				
			
			}
			if(in_array(9,$selectedValues)){
				if(empty($forms_data['business_type'])){
					$returnValue = null ; 
				}
			}
			
			return $returnValue;
			
		}
		
		
		//thie is created to display firm change commodity details,
		//on 02-07-2021 by Amol
		public function getChangeCommodityDetails($firm_details,$firm_type){

			//load models
			$categoryTable = TableRegistry::getTableLocator()->get('MCommodityCategory');
			$commodityTable = TableRegistry::getTableLocator()->get('MCommodity');
			$packingTypeTable = TableRegistry::getTableLocator()->get('DmiPackingTypes');

			$category_list = array();
			$selected_commodities = array();
			$selected_packing_types = array();
			$selected_category_commodities = array();
			
			if($firm_type==1 && !empty($firm_details['commodity'])){

				//in CA to show only already selected category list, to avoid payment amount conflict
				$commodity_array = explode(',',$firm_details['commodity']);

				$i=0;
				foreach($commodity_array as $commodity_id)
				{
					$fetch_commodity_id = $commodityTable->find('all',array('fields'=>'category_code','conditions'=>array('commodity_code IS'=>(int) $commodity_id)))->first();
					$category_id[$i] = $fetch_commodity_id['category_code'];
					$sub_commodity_data[$i] =  $fetch_commodity_id;
					$i=$i+1;
				}

				$category_id_list = array_unique($category_id);

				$category_list = $categoryTable->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('category_code IN'=>$category_id_list)))->toArray();

				$sub_comm_id = explode(',',$firm_details['commodity']);
				
				if(!empty($sub_comm_id)){
					
					$selected_commodities = $commodityTable->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=> $sub_comm_id)))->toArray();
				}

				
			}elseif($firm_type==2 && !empty($firm_details['packing_types'])){

				$packing_types = $packingTypeTable->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('delete_status IS Null')))->toArray();

				$packaging_type_id = explode(',',$firm_details['packing_types']);

				if(!empty($packaging_type_id)){
					
					$selected_packing_types = $packingTypeTable->find('list',array('keyField'=>'id','valueField'=>'packing_type', 'conditions'=>array('id IN'=> $packaging_type_id)))->toArray();
				}
				
			}elseif($firm_type==3 && !empty($firm_details['commodity'])){

				$category_list = $categoryTable->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('display'=>'Y')))->toArray();

				$sub_comm_id = explode(',',$firm_details['commodity']);

				if(!empty($sub_comm_id)){
					
					$selected_commodities = $commodityTable->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=> $sub_comm_id)))->toArray();
				}


			}
			
			if(!empty($category_id_list)){
				
				$selected_category_commodities = $commodityTable->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('category_code IN'=>$category_id_list)))->toArray();
			}
			
			return array($category_list,$selected_commodities,$selected_packing_types,$selected_category_commodities);
		}


		//to update all changed details to original tables after grant.
		//to reflect the change in overall application once grant.
		//this method is called after grant esigned while creating grant pdf, but before entry in grant table
		//on 20-04-2023
		public function updateChangeDetailsAftergrant($customer_id){
			
			$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
			$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
			$selectedValues = $selectedfields[0];
			
			$DmiChangeApplDetails = TableRegistry::getTableLocator()->get('DmiChangeApplDetails');
			$DmiChangeGrantedLogs = TableRegistry::getTableLocator()->get('DmiChangeGrantedLogs');
			
			$getChangeApplDetails = $DmiChangeApplDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$getChangeGrantedLogs = $DmiChangeGrantedLogs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$version = 1;
			if(!empty($getChangeGrantedLogs)){
				$version = $getChangeGrantedLogs['version']+1;
			}
			
			if(in_array(1,$selectedValues)){
				//get last firm name from table to store in log table
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
				$getFirmName = $DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				
				//save details in logs table first				
				$result = $this->saveRecordGrantLogs($customer_id,'Firm Name',$getFirmName['firm_name'],$getChangeApplDetails['firm_name'],$version);
				//to update details in firm table
				if($result==true){					
					$DmiFirms->updateAll(array('firm_name'=>$getChangeApplDetails['firm_name'],'modified'=>date('Y-m-d H:i:s')),array('customer_id'=>$customer_id));
				}
				
			}
			if(in_array(2,$selectedValues)){
				
				//get last firm name from table to store in log table
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
				$getFirmdetails = $DmiFirms->find('all',array('fields'=>array('email','mobile_no','fax_no'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				//save details in logs table first
				$changedFields = 'Firm Contact (Mobile, Email, Phone)';
				$prevValue = $getFirmdetails['email'].', '.$getFirmdetails['mobile_no'].', '.$getFirmdetails['fax_no'];
				$newValue = $getChangeApplDetails['email_id'].', '.$getChangeApplDetails['mobile_no'].', '.$getChangeApplDetails['phone_no'];
				//to update details in firm table
				$result = $this->saveRecordGrantLogs($customer_id,$changedFields,$prevValue,$newValue,$version);
				if($result==true){					
					$DmiFirms->updateAll(array('email'=>$getChangeApplDetails['email_id'],'mobile_no'=>$getChangeApplDetails['mobile_no'],
											'fax_no'=>$getChangeApplDetails['phone_no'],'modified'=>date('Y-m-d H:i:s')),
										array('customer_id'=>$customer_id));
				}
			}
			if(in_array(5,$selectedValues)){
				
				//get last firm name from table to store in log table
				//to update premises details in firm table
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
				$getFirmdetails = $DmiFirms->find('all',array('fields'=>array('street_address','state','district','postal_code'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();				
				//save details in logs table first
				$changedFields = 'Premise/Location in firm table';
				$prevValue = $getFirmdetails['street_address'].', '.$getFirmdetails['state'].', '.$getFirmdetails['district'].', '.$getFirmdetails['postal_code'];
				$newValue = $getChangeApplDetails['premise_street'].', '.$getChangeApplDetails['premise_state'].', '.$getChangeApplDetails['premise_city'].', '.$getChangeApplDetails['premise_pin'];
				
				$result = $this->saveRecordGrantLogs($customer_id,$changedFields,$prevValue,$newValue,$version);	
				if($result==true){
					
					$DmiFirms->updateAll(array('street_address'=>$getChangeApplDetails['premise_street'],'state'=>$getChangeApplDetails['premise_state'],
												'district'=>$getChangeApplDetails['premise_city'],'postal_code'=>$getChangeApplDetails['premise_pin'],
												'modified'=>date('Y-m-d H:i:s')),
										array('customer_id'=>$customer_id));
				}
				
				//to update in premises profile table for CA and PP
				$CustomersController = new CustomersController;
				$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
				if($firm_type==1){
					$PremisesProfilesTable = TableRegistry::getTableLocator()->get('DmiCustomerPremisesProfiles');
				}else{
					$PremisesProfilesTable = TableRegistry::getTableLocator()->get('DmiPrintingPremisesProfiles');
				}
				
				if($firm_type==1 || $firm_type==2){
					$getPremisesetails = $PremisesProfilesTable->find('all',array('fields'=>array('id','street_address','state','district','postal_code'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
					//save details in logs table first
					$changedFields = 'Premise/Location in premises profile table';
					$prevValue = $getPremisesetails['street_address'].', '.$getPremisesetails['state'].', '.$getPremisesetails['district'].', '.$getPremisesetails['postal_code'];
					//save details in logs table first
					$result2 = $this->saveRecordGrantLogs($customer_id,$changedFields,$prevValue,$newValue,$version);	
					if($result2==true){
						
						$PremisesProfilesTable->updateAll(array('street_address'=>$getChangeApplDetails['premise_street'],'state'=>$getChangeApplDetails['premise_state'],
													'district'=>$getChangeApplDetails['premise_city'],'postal_code'=>$getChangeApplDetails['premise_pin'],
													'modified'=>date('Y-m-d H:i:s')),
											array('id'=>$getPremisesetails['id'],'customer_id'=>$customer_id));
											
					}
				}
				

			}
			if(in_array(6,$selectedValues)){
				
				$DmiCustomerLaboratoryDetails = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
				$getLabDetails = $DmiCustomerLaboratoryDetails->find('all',array('fields'=>array('id','laboratory_type','laboratory_name','consent_letter_docs','lab_equipped_docs','chemist_detail_docs'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				//save details in logs table first
				$changedFields = 'Laboratory Details';
				$prevValue = $getLabDetails['laboratory_type'].', '.$getLabDetails['laboratory_name'].', '.$getLabDetails['consent_letter_docs'].', '.$getLabDetails['lab_equipped_docs'].', '.$getLabDetails['chemist_detail_docs'];
				$newValue = $getChangeApplDetails['lab_type'].', '.$getChangeApplDetails['lab_name'].', '.$getChangeApplDetails['lab_consent_docs'].', '.$getChangeApplDetails['lab_equipped_docs'].', '.$getChangeApplDetails['chemist_details_docs'];
				//to update details Ca lab details table
				$result = $this->saveRecordGrantLogs($customer_id,$changedFields,$prevValue,$newValue,$version);	
				if($result==true){
					
					$DmiCustomerLaboratoryDetails->updateAll(array('laboratory_type'=>$getChangeApplDetails['lab_type'],'laboratory_name'=>$getChangeApplDetails['lab_name'],
												'consent_letter_docs'=>$getChangeApplDetails['lab_consent_docs'],'lab_equipped_docs'=>$getChangeApplDetails['lab_equipped_docs'],
												'chemist_detail_docs'=>$getChangeApplDetails['chemist_details_docs'],'modified'=>date('Y-m-d H:i:s')),
										array('id'=>$getLabDetails['id'],'customer_id'=>$customer_id));
										
				}
			}
			if(in_array(7,$selectedValues)){

				//get last category and commodities from table to store in log table
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
				$getFirmDetails = $DmiFirms->find('all',array('fields'=>array('commodity','sub_commodity','packaging_materials'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				
				$CustomersController = new CustomersController;
				$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
				if ($firm_type==1 || $firm_type==3) {
					
					$prevValue = 'category:'.$getFirmDetails['commodity'].' commodities:'.$getFirmDetails['sub_commodity'];
					$newValue = 'category:'.$getChangeApplDetails['comm_category'].' commodities:'.$getChangeApplDetails['commodity'];
					//save details in logs table first				
					$result = $this->saveRecordGrantLogs($customer_id,'Category/Commodity',$prevValue,$newValue,$version);
					//to update details in firm table				
					if($result==true){					
						$DmiFirms->updateAll(array('commodity'=>$getChangeApplDetails['comm_category'],'sub_commodity'=>$getChangeApplDetails['commodity'],'modified'=>date('Y-m-d H:i:s')),array('customer_id'=>$customer_id));
					}

					
				} elseif ($firm_type==2) {

					$prevValue = $getFirmDetails['packaging_materials'];
					$newValue = $getChangeApplDetails['packing_types'];
					//save details in logs table first				
					$result = $this->saveRecordGrantLogs($customer_id,'Packing type',$prevValue,$newValue,$version);
					//to update details in firm table				
					if($result==true){					
						$DmiFirms->updateAll(array('packaging_materials'=>$getChangeApplDetails['packing_types'],'modified'=>date('Y-m-d H:i:s')),array('customer_id'=>$customer_id));
					}
				}
			}
			if(in_array(9,$selectedValues)){
				
				$CustomersController = new CustomersController;
				$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
				
				if($firm_type==1){
					$firmProfilesTable = TableRegistry::getTableLocator()->get('DmiCustomerFirmProfiles');
				}elseif($firm_type==2){
					$firmProfilesTable = TableRegistry::getTableLocator()->get('DmiPrintingFirmProfiles');
				}else{
					$firmProfilesTable = TableRegistry::getTableLocator()->get('DmiLaboratoryFirmDetails');
				}
				
				$getBusinessType = $firmProfilesTable->find('all',array('fields'=>array('id','business_type'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				$prevValue = $getBusinessType['business_type'];
				$newValue = $getChangeApplDetails['business_type'];
				
				//save details in logs table first				
				$result = $this->saveRecordGrantLogs($customer_id,'Business type',$prevValue,$newValue,$version);
				//to update details in profile table table				
				if($result==true){					
					$firmProfilesTable->updateAll(array('business_type'=>$getChangeApplDetails['business_type'],'modified'=>date('Y-m-d H:i:s')),array('id'=>$getBusinessType['id'],'customer_id'=>$customer_id));
				}
				
			}
			
			
			
		}
		
		
		//to save record in cafter grant log table
		//on 25-04-2023
		public function saveRecordGrantLogs($customer_id,$changed_field,$prev_value,$new_value,$version){
			
			$DmiChangeGrantedLogs = TableRegistry::getTableLocator()->get('DmiChangeGrantedLogs');
			
			$dataArray = array(			
				'customer_id'=>$customer_id,
				'grant_by'=>$_SESSION['username'],
				'changed_field'=>$changed_field,
				'prev_value'=>$prev_value,
				'new_value'=>$new_value,
				'created'=>date('Y-m-d H:i:s'),
				'version'=>$version		
			);
			
			$grantedLogsEntity = $DmiChangeGrantedLogs->newEntity($dataArray);
			if($DmiChangeGrantedLogs->save($grantedLogsEntity)){	
				return true; 
			}
			return false;
		}
		

} ?>