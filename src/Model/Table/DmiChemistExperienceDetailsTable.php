<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
use App\Controller\CustomersController;
use Cake\I18n\Time;

class DmiChemistExperienceDetailsTable extends Table{

	var $name = "DmiChemistExperienceDetails";
	var $useTable = 'dmi_chemist_experience_details';

	public function sectionFormDetails($chemist_id) {

		$result = array();

		$result = $this->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'is_latest'=>1),'order'=>'id asc'))->toArray();
		//to get last record for status, as above query get order in ASC. on 28-04-2022 by Amol
		if(!empty($result)){
			$getLastStatus = $this->find('all',array('fields'=>'form_status','conditions'=>array('customer_id IS'=>$chemist_id,'is_latest'=>1),'order'=>'id desc'))->first();
			$result[0]['form_status'] = $getLastStatus['form_status'];
		}


		 if(empty($result)){

			$result = array();
			$result[0]['id'] = '';
			$result[0]['customer_id'] = '';
			$result[0]['name_of_institute'] = '';
			$result[0]['post_held'] = '';
			$result[0]['job_description'] = '';
			$result[0]['from_dt'] = '';
			$result[0]['to_dt'] = '';
			$result[0]['total'] = '';
			$result[0]['monthly_remuneration'] = '';
			$result[0]['form_status'] = '';
			$result[0]['created'] = '';
			$result[0]['modified'] = '';
			$result[0]['is_latest'] = '';
			$result[0]['customer_reply'] = '';
			$result[0]['current_level'] = '';
			$result[0]['mo_comment'] = '';
			$result[0]['ro_reply_comment'] = '';
			$result[0]['delete_mo_comment'] = '';
			$result[0]['customer_reply_date'] = '';
			$result[0]['exp_document'] = '';
			$result[0]['exp_in_year'] = ''; //This field is added to year save the in database seprately - Akash[16-05-2023]
			$result[0]['exp_in_month'] = ''; //This field is added to month save the in database seprately - Akash[16-05-2023]
			$result[0]['exp_in_days']=''; //This field is added to month save the in database seprately - Akash[16-05-2023]
		}else{

       //added contion for chemist home withdraw application to section id null by laxmi B. on 29-05-2023
			if(!empty($_SESSION['section_id'])){
			    $section_id = $_SESSION['section_id'];
            }else{
					$section_id = null;
			}

		}
		//Below code is moved from above else condition to here out of the cond.
		//to solve the issue for referred back on empty section, and unable to reply and final submit.
		//on 17-10-2023 by Amol
		$section_id = 3;//set section id default as per section no.
		$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');
		$commentDetails = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'section_id IS'=>$section_id,'is_latest'=>1)))->first();

		if(!empty($commentDetails)){
			$reffered_back_comment = $commentDetails['comments'];
			$reffered_back_date = $commentDetails['comment_dt'];
		}else{
			$reffered_back_comment = '';
			$reffered_back_date = '';
		}
		$result[0]['reffered_back_comment'] = $reffered_back_comment;
		$result[0]['reffered_back_date'] = $reffered_back_date;
		//till here

		// common add more Table Header Array
		$tableD['label'] = array(
			'0' => array(
				'0' => array(
					'col' 		=> 'Sr.no',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'1' => array(
					'col' 		=> 'Name Of Institution',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'2' => array(
					'col' 		=> 'Post Held',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'3' => array(
					'col' 		=> 'Job Description',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'4' => array(
					'col' 		=> 'Duration Of Job',
					'colspan' 	=> '3',
					'rowspan' 	=> '1'
				),
				'5' => array(
					'col' 		=> 'Monthly Remuneration',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'6' => array(
					'col' 		=> 'Experience Certificate',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				)
			),
			'1' => array(
				'0' => array(
					'col' 		=> 'From',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'1' => array(
					'col' 		=> 'To',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'2' => array(
					'col' 		=> 'Total (in Years)',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				)
			)
		);


		$loopC = "0";
		foreach($result as $row){

			$row = $row;

			$tableD['input'][$loopC] = array(

				'0' => array(
					'name'		=> null,
					'type'		=> null,
					'valid'		=> null,
					'length'	=> null
				),
				'1' => array(
					'name'		=> 'name_of_institute',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '200',
					'value'		=> $row['name_of_institute'],
					'class'		=> 'cvOn cvNotReq cvAlphaNum cvMaxLen',
					'id'		=> 'name_of_institute'
				),
				'2' => array(
					'name'		=> 'post_held',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '200',
					'value'		=> $row['post_held'],
					'class'		=> 'cvOn cvNotReq cvAlphaNum cvMaxLen',
					'id'		=> 'post_held'
				),
				'3' => array(
					'name'		=> 'job_description',
					'type'		=> 'textarea',
					'valid'		=> 'text',
					'value'		=> $row['job_description'],
					'class'		=> 'cvOn cvNotReq cvAlphaNum cvMaxLen',
					'id'		=> 'job_description'
				),
				'4' => array(

					'name'			=>	'from_dt',
					'type'			=>	'date',
					//the below line is updated towards the new HTML 5 Format Datpicker applied to the from_date field to retrive the data from the database and populate the datepicker in yyyy-mm-dd format - Akash[17-05-2023]
					'value' 		=> 	isset($row['from_dt']) ? (date_create_from_format('d/m/Y H:i:s', $row['from_dt']) !== false ? date_create_from_format('d/m/Y H:i:s', $row['from_dt'])->format('Y-m-d') : null) : null,
					'class'			=>	'form-control input-field cvcalyear cvOn cvNotReq', // added class cvcalyear by shankhpal
					'id'			=>	'from_dt',

				),
				'5' => array(
					'name'		=> 'to_dt',
					'type'		=> 'date',
					//the below line is updated towards the new HTML 5 Format Datpicker applied to the to_date field to retrive the data from the database and populate the datepicker in yyyy-mm-dd format - Akash[17-05-2023]
					'value'		=> isset($row['to_dt']) ? (date_create_from_format('d/m/Y H:i:s', $row['to_dt']) !== false ? date_create_from_format('d/m/Y H:i:s', $row['to_dt'])->format('Y-m-d') : null) : null,
					'class'		=> 'form-control input-field cvcalyear cvOn cvNotReq', // added class cvcalyear by shankhpal
					'id'		=> 'to_dt'
				),
				'6' => array(
					'name'		=> 'total',
					'type'		=> 'text',
					'valid'		=> 'text',
					'value'		=> $row['total'],
					'class'		=> 'cvOn cvNotReq tot cvcalyear', // added class cvcalyear by shankhpal
					'id'		=> 'total'
				),
				'7' => array(
					'name'		=> 'monthly_remuneration',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '200',
					'value'		=> $row['monthly_remuneration'],
					'class'		=> 'cvOn cvNotReq cvAlphaNum cvMaxLen',
					'id'		=> 'monthly_remuneration'
				),
				'8' => array(
					'name'		=> 'exp_document',
					'type'		=> 'file',
					'valid'		=> 'file',
					'value'		=> $row['exp_document'],
					'class'		=> 'cvOn cvReq cvFile',
					'id'		=> 'exp_document'
				)
			);
			$loopC++;

		}


		$tableForm[] = $tableD;
		$jsonTableForm = json_encode($tableForm);
		$resultIndex = count($result);
		 return array($result[$resultIndex-$resultIndex],$jsonTableForm);
	}




	public function saveFormDetails($chemist_id,$forms_data) {

		$result = false;
		$dataValidatation = $this->postDataValidation($forms_data);
		$date = date('Y-m-d H:i:s');

		if($dataValidatation == 1 ){

			$section_form_details = $this->sectionFormDetails($chemist_id);
			$status = 'saved';
			$created = date('Y-m-d H:i:s');
			$CustomersController = new CustomersController;
			$row_count = count($forms_data['name_of_institute']);

			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');

			$currCommentRecord =  $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id'=>$chemist_id,'section_id'=>$section_id,'is_latest'=>'1')))->first();
			if(!empty($currCommentRecord)){
				$commentid = $currCommentRecord['id'];
				$reply_to = $currCommentRecord['comment_by'];
			}else{
				$commentid ='';
				$reply_to = '';
			}


			if(!empty($reply_to))
			{
				$comment = htmlentities($forms_data['reffered_back_comment'], ENT_QUOTES);

				$newEntity = $Dmi_chemist_comment->newEntity(array(
					'id'=>$commentid,
					'reply_by'=>$chemist_id,
					'reply_to'=>$reply_to,
					'reply_comment'=>$comment,
					'reply_dt'=>date('Y-m-d H:i:s')
				));
				$Dmi_chemist_comment->save($newEntity);

			}


			$this->deleteAll(array('customer_id'=>$chemist_id,'is_latest'=>1));

			for ($i=0;$i<$row_count;$i++) {

				$name_of_institute = htmlentities($forms_data['name_of_institute'][$i], ENT_QUOTES);
				$post_held = htmlentities($forms_data['post_held'][$i], ENT_QUOTES);
				$job_description = htmlentities($forms_data['job_description'][$i], ENT_QUOTES);

				// -> This below both from_dt and to_dt library function is changed from "changeDateFormat" to "changeDateFormatNew"
				// Reason : to handle the expection for the use of HTML5 Date input -Akash [17-05-2023]

				$from_dt = $CustomersController->Customfunctions->changeDateFormatNew($forms_data['from_dt'][$i]);
				$to_dt = $CustomersController->Customfunctions->changeDateFormatNew($forms_data['to_dt'][$i]);
				$monthly_remuneration = htmlentities($forms_data['monthly_remuneration'][$i], ENT_QUOTES);
				$total = htmlentities($forms_data['total'][$i], ENT_QUOTES);

				if($forms_data['exp_document'][$i]->getClientFilename() != null) {

					$file_name = $forms_data['exp_document'][$i]->getClientFilename();
					$file_size = $forms_data['exp_document'][$i]->getSize();
					$file_type = $forms_data['exp_document'][$i]->getClientMediaType();
					$file_local_path = $forms_data['exp_document'][$i]->getStream()->getMetadata('uri');

					$uploadedfile = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				}else {
					$uploadedfile = $section_form_details[0]['exp_document'];
				}


				 // Split the string into whole years and months
                 list($years, $months) = explode('.', $total);

                 // Convert to integers
                 $exp_in_year = (int)$years;
                 $exp_in_month = (int)$months;

                 // Calculate total days (assuming 1 year = 365 days, 1 month = 30 days)
                 $exp_in_days = ($exp_in_year * 365) + ($exp_in_month * 30);


                 // save the total in text year and month in database : akash thakre [15-03-2024]
                 $duration = "";
                 if ($exp_in_year > 0) {
                     $duration .= "$exp_in_year year" . ($exp_in_year > 1 ? 's' : ''); // Add 's' if more than 1 year
                 }
                 if ($exp_in_month > 0) {
                     $duration .= ($duration != '' ? ', ' : '') . "$exp_in_month month" . ($exp_in_month > 1 ? 's' : ''); // Add 's' if more than 1 month
                 }



				//Save the Data
				$DmiChemistExperienceDetailsEntity = $this->newEntity(array(

					'customer_id'=>$chemist_id,
					'name_of_institute'=>$name_of_institute,
					'post_held'=>$post_held,
					'job_description'=>$job_description,
					'from_dt'=>$from_dt,
					'to_dt'=>$to_dt,
					'total'=>$duration,
					'monthly_remuneration'=>$monthly_remuneration,
					'form_status'=>$status,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'),
					'is_latest'=>1,
					'exp_document'=>$uploadedfile,
					'exp_in_year'=>$exp_in_year, // this new field is added to store the total exp in integer field seprately - Akash [17-06-2023]
					'exp_in_month'=>$exp_in_month, // this new field is added to store the total exp in integer field seprately - Akash [17-06-2023]
					'exp_in_days'=>$exp_in_days // this new field is added to store the days in iintegre seprately - Akash [17-06-2023]
				));

				if($this->save($DmiChemistExperienceDetailsEntity)){
					$return = "true";
				}
			}

		} else {

			$return = "false";
		}

		if($return = "true"){

			return true;

		}else{
			return false;
		}



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

		$newEntity = $this->newEntity(array(

			'customer_id'=>$customer_id,
			'name_of_institute'=>$forms_data['name_of_institute'],
			'post_held'=>$forms_data['post_held'],
			'job_description'=>$forms_data['job_description'],
			'from_dt'=>$forms_data['from_dt'],
			'to_dt'=>$forms_data['to_dt'],
			'total'=>$forms_data['total'],
			'monthly_remuneration'=>$forms_data['monthly_remuneration'],
			'form_status'=>$forms_data['form_status'],
			'is_latest'=>$forms_data['is_latest'],
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
			'exp_document'=>$forms_data['exp_document'],
			'exp_in_year'=>$forms_data['exp_in_year'], // this new field is added to store the total exp in integer field seprately - Akash [17-06-2023]
			'exp_in_month'=>$forms_data['exp_in_month'], // this new field is added to store the total exp in integer field seprately - Akash [17-06-2023]
			'exp_in_days'=>$forms_data['exp_in_days'] // this new field is added to store the days in iintegre seprately - Akash [17-06-2023]

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

	public function postDataValidation($forms_data){

			return true;

	}

} ?>
