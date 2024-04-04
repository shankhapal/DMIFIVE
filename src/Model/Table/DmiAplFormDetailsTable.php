<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
    use App\Controller\CustomersController;

    //  enum AppealStatus: string
    // {
    //     case InProcess = 'In Process';
    //     case Granted  ='granted';
    //     case Rejected = 'rejected';
    // }

	class DmiAplFormDetailsTable extends Table{

	var $name = "DmiAplFormDetails";

	public function applicationCurrentUsers($customer_id)
	{
		$fetch_data = $this->find('all',array('fields'=>'current_user_email_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id DESC')))->first();

		return $fetch_data;
	}



	// public function userCurrentApplications($user_email_id)
	// {
	// 	$fetch_data = $this->find('all',array('conditions'=>array('current_user_email_id IS'=>$user_email_id)))->toArray();

	// 	return $fetch_data;
	// }


	// public function currentUserEntry($customer_id,$user_email_id,$current_level)
	// {
	// 	$Entity = $this->newEntity(array(
	// 		'customer_id'=>$customer_id,
	// 		'current_level'=>$current_level,
	// 		'current_user_email_id'=>$user_email_id,
	// 		'created'=>date('Y-m-d H:i:s')
	// 	 ));
	// 	 $this->save($Entity);

	// }


	// public function currentUserUpdate($customer_id,$user_email_id,$current_level)
	// {

	// 	$find_row_id = $this->find('all',array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id DESC')))->first();
	// 	$row_id = $find_row_id['id'];

	// 	$newEntity = $this->newEntity(array(
	// 		'id'=>$row_id,
	// 		'current_level'=>$current_level,
	// 		'current_user_email_id'=>$user_email_id,
	// 		'modified'=>date('Y-m-d H:i:s')
	// 	 ));

	// 	 $this->save($newEntity);
	// 	return true;
	// }


	public function getLatestAppeal($customer_id)
	{
	//	$condition=array('customer_id IS'=>$customer_id,'status NOT IN'=>['Granted','Rejected']);	
	$condition=array('customer_id IS'=>$customer_id);
	 return $this->find('all', array('conditions'=>$condition,'order'=>'id desc'))->first();     
	}

    	// Fetch form section all details
	public function sectionFormDetails($customer_id){


		//Joshi, Akash:- Specific condition for appeal, as single customer can have multiple appeals...[28-08-2023]
		$isApplicant = !empty($_SESSION['associated_rejected_app_type']);
		$condition = ['customer_id IS' => $customer_id];

		//Joshi, Akash:- If request comes from Applicant side then pull associated_appl_type to filter out the data.
		if ($isApplicant) {
    		$condition['associated_appl_type'] = $_SESSION['associated_rejected_app_type'];
		}

		$form_fields_details = $this->find('all', array('conditions'=>$condition,'order'=>'id desc'))->first();

		if(empty($form_fields_details)){
			$form_fields_details = Array ( 'id'=>"",'created' => "", 'modified' =>"", 'customer_id' => "", 'reffered_back_comment' => "",
											'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"",
											'approved_date' => "",'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "",
											'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
											'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",
											'reason' =>"",'appeal_id'=>"",'supported_document'=>"",'status'=>"",'associated_appl_type'=>"",'is_final_submitted'=>""

										);

		}
		elseif (!isset($_SESSION['appeal_id']) && isset($form_fields_details['appeal_id'])) {
			$_SESSION['appeal_id'] = $form_fields_details['appeal_id'];
		}
		
		return array($form_fields_details);

	}

    // save or update form data and comment reply by applicant
	public function saveFormDetails($customer_id,$forms_data){

		if ($this->postDataValidation($customer_id,$forms_data)) {

			$CustomersController = new CustomersController;
			$firmType = $CustomersController->Customfunctions->firmType($customer_id);
			$section_form_details = $this->sectionFormDetails($customer_id);
			//Fields details to save
			$reason = htmlentities($forms_data['reason'], ENT_QUOTES);

			if(!empty($forms_data['supported_document']->getClientFilename())){

				$file_name = $forms_data['supported_document']->getClientFilename();
				$file_size = $forms_data['supported_document']->getSize();
				$file_type = $forms_data['supported_document']->getClientMediaType();
				$file_local_path = $forms_data['supported_document']->getStream()->getMetadata('uri');
				$required_document = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			} else { $required_document = $section_form_details[0]['supported_document'];}


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

			$appealStatus = $section_form_details[0]['status'] ?: "In Process";

            

			$associated_rejected_app_type =  $section_form_details[0]['associated_appl_type'];
			$associated_rejected_app_type =  empty($associated_rejected_app_type)?$_SESSION['associated_rejected_app_type']:$associated_rejected_app_type;

            $appealId=$section_form_details[0]['appeal_id'];
            $appealId=empty($appealId)?$this->generateAppealID($customer_id, $associated_rejected_app_type):$appealId;
            $_SESSION['appeal_id']=$appealId;
            //In Case of Update, Need to put ID, at above lines we are fetching Max ID in case of referred back,
            //however we have to keep track of id for update case as well.
            if(empty($max_id) &&  !(empty($section_form_details) || empty($section_form_details[0])))
            {
                $max_id= $section_form_details[0]['id'];
            }
            $newEntity = $this->newEntity(array(

				'id'=>$max_id,
				'customer_id'=>$customer_id,
				'reason'=>$reason,
				'supported_document'=>$required_document,
				'form_status'=>'saved',
				'customer_reply'=>$htmlencoded_reply,
				'customer_reply_date'=>$customer_reply_date,
				'cr_comment_ul'=>$cr_comment_ul,
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s'),
                'appeal_id'=>$appealId,
                'status'=>$appealStatus,
				'associated_appl_type' =>$associated_rejected_app_type
			));

			if ($this->save($newEntity)) {
                $rejectApplicationDetails = $CustomersController->Customfunctions->isApplicationRejected($customer_id,$associated_rejected_app_type);
                if (!empty($rejectApplicationDetails)) {
					$firstRejectedApplication = reset($rejectApplicationDetails);
				if(empty($firstRejectedApplication['appeal_id']))
                {
                return $this->addAppealInfoInRejectLogTable($firstRejectedApplication['id'],$appealId)?1:0;
                }
				
                }
                    return 1;
                }
		} else {
         return false;
        }

	}
    //Joshi, Akash, below method will check in-process existing Appeal
	public function postDataValidation($customer_id,$forms_data){
		$returnValue = true;
		return $returnValue;

	}

    private function addAppealInfoInRejectLogTable($rejection_id,$appeal_id){
        $dmiRejectedApplLogs = TableRegistry::getTableLocator()->get('DmiRejectedApplLogs');
        $newEntity = $dmiRejectedApplLogs->newEntity(array(
            'id'=>$rejection_id,
            'appeal_id'=>$appeal_id
        ));
        return $dmiRejectedApplLogs->save($newEntity);
    }
    public function generateAppealID($customer_id,$associated_rejected_app_type){
        return 'APL-'.$customer_id.'-'.$associated_rejected_app_type;
    }


    public function updateAppealStatus($appealID, $status)
    {
        $newEntity = $this->newEntity(array(
            'id'=>$appealID,
            'status'=>$status,
            'modified'=>date('Y-m-d H:i:s')
        ));
        return $this->save($newEntity);
    }


	public function markAppealSubmitted($customer_id, $associated_appl_type)
    {
		$form_fields_details = $this->find('all', array('conditions'=>['customer_id'=>$customer_id, 'associated_appl_type'=>$associated_appl_type],'order'=>'id desc'))->first();
        $newEntity = $this->newEntity(array(
            'id'=>$form_fields_details['id'],
            'is_final_submitted'=>'yes'
        ));
        return $this->save($newEntity);
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
			'reffered_back_comment'=>$reffered_back_comment,
			'reffered_back_date'=>$reffered_back_date,
			'form_status'=>$form_status,
			'rb_comment_ul'=>$rb_comment_ul,
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
            'appeal_id' =>$forms_data['appeal_id'],
            'status' => $forms_data['status'],
            'supported_document' => $forms_data['supported_document'],
			'associated_appl_type' =>$forms_data['associated_appl_type']
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

}
