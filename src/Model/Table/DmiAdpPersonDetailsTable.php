<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

	class DmiAdpPersonDetailsTable extends Table{

		var $name = "DmiAdpPersonDetails";
			
		public function laboratoryPersonDetails(){

			 
				if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
					$customer_id = $_SESSION['customer_id'];
				}else{
					$customer_id = $_SESSION['username'];
				}
				
				if(isset($_SESSION['edit_person_id'])){
					
					$hide_edit_id = array('id !='=>$_SESSION['edit_person_id']);
					$edit_id = $_SESSION['edit_person_id'];
				}else{
					$hide_edit_id = array('id IS NOT NULL');
					$edit_id = '';
				}

				//$added_chemist_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id, 'customer_id IS'=>$customer_id,'delete_status IS NULL','by_renewal_form IS NULL'),'order'=>'id'))->toArray();
				$added_person_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id, 'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();
            
				$find_person_details = $this->find('all',array('conditions'=>array('id IS'=>$edit_id)))->first();
					
				return array($added_person_details,$find_person_details);

		}

	    
		
        //this function used to save add more details by shankhpal shende on 10/11/2022
		public function savePersonDetails($customer_id,$forms_data){
              
			$CustomersController = new CustomersController;
			$customer_once_no = $_SESSION['once_card_no'];
           
			if(isset($_SESSION['edit_person_id'])){  $hide_edit_id = $_SESSION['edit_person_id']; }else{ $hide_edit_id = '';  }


			$edit_person_row_data = $this->find('all', array('conditions'=>array('id IS'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->first();

			$person_name = htmlentities($forms_data['person_name'], ENT_NOQUOTES);
			$person_qualification = htmlentities($forms_data['qualification'], ENT_NOQUOTES);
			$person_experience = htmlentities($forms_data['experience'], ENT_NOQUOTES);
			$any_criminal_record = htmlentities($forms_data['any_criminal_record'], ENT_NOQUOTES);
			$is_responsible = htmlentities($forms_data['is_responsible'], ENT_NOQUOTES);
			$designation = htmlentities($forms_data['designation'], ENT_NOQUOTES);

			if(!empty($forms_data['person_qualifi_details_doc']->getClientFilename())){
                   
				$file_name = $forms_data['person_qualifi_details_doc']->getClientFilename();
				$file_size = $forms_data['person_qualifi_details_doc']->getSize();
				$file_type = $forms_data['person_qualifi_details_doc']->getClientMediaType();
				$file_local_path = $forms_data['person_qualifi_details_doc']->getStream()->getMetadata('uri');
				$person_qualifi_details_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				

			}else{ $person_qualifi_details_doc = $edit_person_row_data['qualifi_docs']; }
			if(!empty($forms_data['person_exp_details_doc']->getClientFilename())){
                   
				$file_name = $forms_data['person_exp_details_doc']->getClientFilename();
				$file_size = $forms_data['person_exp_details_doc']->getSize();
				$file_type = $forms_data['person_exp_details_doc']->getClientMediaType();
				$file_local_path = $forms_data['person_exp_details_doc']->getStream()->getMetadata('uri');
				$person_exp_details_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				

			}else{ $person_exp_details_doc = $edit_person_row_data['exp_docs']; }
			if(!empty($forms_data['profile_pic']->getClientFilename())){
                   
				$file_name = $forms_data['profile_pic']->getClientFilename();
				$file_size = $forms_data['profile_pic']->getSize();
				$file_type = $forms_data['profile_pic']->getClientMediaType();
				$file_local_path = $forms_data['profile_pic']->getStream()->getMetadata('uri');
				$profile_pic = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			}else{ $profile_pic = $edit_person_row_data['profile_pic'];
			    
			}
			if(!empty($forms_data['signature_docs']->getClientFilename())){
                   
				$file_name = $forms_data['signature_docs']->getClientFilename();
				$file_size = $forms_data['signature_docs']->getSize();
				$file_type = $forms_data['signature_docs']->getClientMediaType();
				$file_local_path = $forms_data['signature_docs']->getStream()->getMetadata('uri');
				$signature_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
               
			}
			else{ $signature_doc = $edit_person_row_data['signature_docs']; }


			
			$newEntity = $this->newEntity(array(
          
				'id'=>$hide_edit_id,
				'customer_id'=>$customer_id,
				'person_name'=>$person_name,
				'qualification'=>$person_qualification,
				'experience'=>$person_experience,
				'qualifi_docs'=>$person_qualifi_details_doc,
				'exp_docs'=>$person_exp_details_doc,
				'profile_pic'=>$profile_pic,
				'signature_docs'=>$signature_doc,
				'is_responsible'=>$is_responsible,
				'any_criminal_record'=>$any_criminal_record,
				'designation'=>$designation,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')

			));
			
			if($this->save($newEntity)){ return true; }

			
		}

		public function deletePersonDetails($record_id){
           
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			));

			if($this->save($newEntity)){
				return true;
			}
		}

		//methods for laboratory renewal application to save chemist details

		public function renewalChemistDetails($customer_id){

			$M_commodity = TableRegistry::getTableLocator()->get('MCommodity');

			if(isset($_SESSION['edit_chemist_id'])){
				$hide_edit_id = array('id !='=>$_SESSION['edit_chemist_id']);
				$edit_id = $_SESSION['edit_chemist_id'];
			}else{
				$hide_edit_id = array('id IS NOT NULL');
				$edit_id = '';
			}

			$show_chemist_commodity_types = $M_commodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code'))->toArray();

			$chemist_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id, 'delete_status IS NULL',
																											'user_email_id IS NULL', /*'customer_once_no'=>null,*/ 'by_renewal_form IS NOT NULL'),'order'=>'id'))->toArray();
			$chemist_commodity_value=array();

			$i=1;
			foreach($chemist_details as $chemist_detail)
			{
				$chemist_commodity_details = explode(',',$chemist_detail['commodity']);
				$chemist_details_values = $M_commodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$chemist_commodity_details)))->toArray();
				$chemist_commodity_value[$i] =implode(',',$chemist_details_values);

				$i=$i+1;
			}

			// Code start if application side record want to display (by pravin 13/05/2017)
			$application_side_chemist_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id, 'customer_id IS'=>$customer_id, 'delete_status IS NULL', 'by_renewal_form IS NULL', 'add_in_renewal IS NULL'),'order'=>'id'))->toArray();

			$app_side_chemist_commodity_details=array();

			$i=1;
			foreach($application_side_chemist_details as $chemist_detail)
			{
				$app_side_chemist_commodity_details[$i] = explode(',',$chemist_detail['commodity']);
				//$app_chemist_details[$i] = $M_commodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$app_chemist_commodity)))->toArray();
				//$app_side_chemist_commodity_details[$i] =implode(',',$app_chemist_details[$i]);

				$i=$i+1;
			}

			// Code End if application side record want to display (by pravin 13/05/2017)

			// Find latest Chemist details id and check the chemist details choice value (by pravin 20/05/2017)
			$chemist_details_choice_id = $this->find('list', array('conditions'=>array('customer_id IS'=>$customer_id, 'delete_status IS NULL')))->toArray();

			$chemist_details_choice_value = $this->find('all',array('conditions'=>array('id'=>max($chemist_details_choice_id))))->first();
			$chemist_details_choice = $chemist_details_choice_value['chemist_details_choice'];

			if($chemist_details_choice != 1){

				$chemist_details_choice == '';
			}

			$find_chemist_details = $this->find('all',array('conditions'=>array('id IS'=>$edit_id)))->first();
			if(!empty($find_chemist_details)){
				$commodity_value_edit = explode(',',$find_chemist_details['commodity']);
			}else{
				$commodity_value_edit = "";
			}

			return array($chemist_details,$chemist_commodity_value,$show_chemist_commodity_types,$application_side_chemist_details,$app_side_chemist_commodity_details,
							$chemist_details_choice,$find_chemist_details,$commodity_value_edit);
		}


		// Save the new variable chemist_details_choice value to laboratory chemist table (by pravin 13/05/2017)
		public function saveRenewalChemistDetails($customer_id,$forms_data){

			$chemist_details_choices = $forms_data['chemist_details_choice'];

			// if chemist details choice checkbox not selected then save null value (by pravin 15/11/2017)
			if(!empty($chemist_details_choices)){
				$chemist_details_choice = $chemist_details_choices[0];    // Save the new variable chemist_details_choice value to laboratory chemist table (by pravin 13/05/2017)
			}else{
				$chemist_details_choice = null;
			}

			$CustomersController = new CustomersController;
			$customer_once_no = $_SESSION['once_card_no'];

			if(isset($_SESSION['edit_chemist_id'])){ $hide_edit_id = $_SESSION['edit_chemist_id']; }else{ $hide_edit_id = '';  }


			$edit_chemist_row_data = $this->find('all', array('conditions'=>array('id IS'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->first();

			$chemist_name = htmlentities($forms_data['chemist_name'], ENT_NOQUOTES);
			$chemist_qualification = htmlentities($forms_data['qualification'], ENT_NOQUOTES);
			$chemist_experience = htmlentities($forms_data['experience'], ENT_NOQUOTES);
			$chemist_commodity = $forms_data['commodity'];
			$chemist_commodity_value=implode(', ',$chemist_commodity);

			if(!empty($forms_data['chemists_details_docs']->getClientFilename())){

				$file_name = $forms_data['chemists_details_docs']->getClientFilename();
				$file_size = $forms_data['chemists_details_docs']->getSize();
				$file_type = $forms_data['chemists_details_docs']->getClientMediaType();
				$file_local_path = $forms_data['chemists_details_docs']->getStream()->getMetadata('uri');

				$chemists_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			}else{ $chemists_details_docs = $edit_chemist_row_data['chemists_details_docs']; }

			$newEntity = $this->newEntity(array(
				'id'=>$hide_edit_id,
				'customer_id'=>$customer_id,
				'customer_once_no'=>$_SESSION['once_card_no'],
				'by_renewal_form'=>'yes',
				'chemist_name'=>$chemist_name,
				'qualification'=>$chemist_qualification,
				'experience'=>$chemist_experience,
				'commodity'=>$chemist_commodity_value,
				'chemist_details_choice'=>$chemist_details_choice,
				'chemists_details_docs'=>$chemists_details_docs,
				'created'=>date('Y-m-d H:i:s')
			));

			if($this->save($newEntity)){
				return true;
			}
		}


		public function saveformschemistDetailsAtRenewal($customer_id,$forms_data){

			$CustomersController = new CustomersController;

			$i = str_replace("oldrecordsave-","",$forms_data['old_record_id']);

			$chemist_details = $this->find('all',array('conditions'=>array('id'=>$i)))->first();

			$chemist_details_choices = $forms_data['chemist_details_choice'];
			$chemist_details_choice = $chemist_details_choices[0];
			$chemist_id = htmlentities($forms_data['application_side_chemist_id'.$i], ENT_NOQUOTES);
			$chemist_name = htmlentities($forms_data['application_side_chemist_name'.$i], ENT_NOQUOTES);
			$chemist_qualification = htmlentities($forms_data['application_side_qualification'.$i], ENT_NOQUOTES);
			$chemist_experience = htmlentities($forms_data['application_side_experience'.$i], ENT_NOQUOTES);
			$chemist_commodity = $forms_data['application_side_commodity'.$i];
			$chemist_commodity_value=implode(', ',$chemist_commodity);

					//file uploading
			if(!empty($forms_data['chemists_details_docs'.$i]->getClientFilename())){


				$file_name = $forms_data['chemists_details_docs'.$i]->getClientFilename();
				$file_size = $forms_data['chemists_details_docs'.$i]->getSize();
				$file_type = $forms_data['chemists_details_docs'.$i]->getClientMediaType();
				$file_local_path = $forms_data['chemists_details_docs'.$i]->getStream()->getMetadata('uri');


				$chemists_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			}else{
				$chemists_details_docs = $chemist_details['chemists_details_docs'];
			}

			$newEntity = $this->newEntity(array(
				'customer_id'=>$customer_id,
				'customer_once_no'=>$_SESSION['once_card_no'],
				'by_renewal_form'=>'yes',
				'chemist_name'=>$chemist_name,
				'qualification'=>$chemist_qualification,
				'experience'=>$chemist_experience,
				'commodity'=>$chemist_commodity_value,
				'chemist_details_choice'=>$chemist_details_choice,
				'chemists_details_docs'=>$chemists_details_docs,
				'created'=>date('Y-m-d H:i:s')
			));

			if($this->save($newEntity)){

				$entity = $this->newEntity(array(
					'id'=>$chemist_id,
					'add_in_renewal'=>'yes',
					'modified'=>date('Y-m-d H:i:s')
				));

				if($this->save($entity)){
					return true;
				}
			}

		}


	}

?>
