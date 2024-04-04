<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Filesystem\File;
use Cake\Routing\Router;

use Cake\Utility\Text;

class DmiBgrSmsEmailTemplateTable extends Table{

	var $name = "DmiBgrSmsEmailTemplate";

	public $validate = array(

		'sms_message'=>array(

					'rule' => 'notBlank',
				),
		'email_message'=>array(

					'rule' => 'notBlank',
				),
		'description'=>array(

				'rule' => 'notBlank',
			),
		'template_for'=>array(
				'rule'=>array('maxLength',20),
				'allowEmpty'=>false,
			),
		'email_subject'=>array(
				'rule'=>array('maxLength',200),
				'allowEmpty'=>false,
			),

	);


    public function sendMessage($message_id,$customer_id) {

		if (!isset($_SESSION['application_type'])){
			$_SESSION['application_type']=null;
		}

		$application_type = $_SESSION['application_type'];

		//This Session ID is Applied for the temporary Application Type is not present.
		if(Router::getRequest()->getParam('controller') == 'Dashboard'){
			if ($application_type == null) {
				$application_type = $_SESSION['application_type_temp'];
			}
		}



        //Load Models
        $DmiCustomers = TableRegistry::getTableLocator()->get('DmiCustomers');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');

		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
        $DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
        $DmiSentSmsLogs = TableRegistry::getTableLocator()->get('DmiSentSmsLogs');
		$DmiSentEmailLogs = TableRegistry::getTableLocator()->get('DmiSentEmailLogs');
        $DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');

        $find_message_record = $this->find('all',array('conditions'=>array('id IS'=>$message_id, 'status'=>'active')))->first();//'status'condition inserted on 24-07-2018

        //added this if condition on 24-07-2018 by Amol
		if (!empty($find_message_record)) {

            $destination_values = $find_message_record['destination'];
	        $destination_array = explode(',',$destination_values);
            if($_SESSION['application_dashboard'] == 'chemist'){
                $ro_email_id = '';
            }else{
                $ro_email_id = $this->getRoName($customer_id);
            }




            $m=0;
			$e=0;
			$destination_mob_nos = array();
			$log_dest_mob_nos = array();
			$destination_email_ids = array();

             //for Chemist User
			if (in_array(10,$destination_array)) {

                if($_SESSION['current_level'] == 'level_3'){
                    $DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');

                    $chemist_incharge = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$_SESSION['customer_id'],'incharge'=>'yes')))->first();

                    if (!empty($chemist_incharge)) {

                        $chemist_id = $chemist_incharge['chemist_id'];
                    }
                }else{
                    $chemist_id = $_SESSION['username'];
                }


				$find_chemist_user= $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$chemist_id),'order'=>'id desc'))->first();

				if (!empty($find_chemist_user)) {

					$chemist_id =  $find_chemist_user['chemist_id'];
					$chemist_mob_no = $find_chemist_user['mobile'];
					$chemist_email = $find_chemist_user['email'];

					$destination_mob_nos[$m] = '91'.base64_decode($chemist_mob_no);
					$log_dest_mob_nos[$m] = '91'.$chemist_mob_no;
					$destination_email_ids[$e] = base64_decode($chemist_email);

				} else {

					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
			}



            //Applicant
			if (in_array(0,$destination_array)) {
				//checking applicant id pattern ex.102/2017 if primary Applicant added on 23-08-2017 by Amol
				if (preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1) {

					$fetch_applicant_data = $DmiCustomers->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
					$applicant_mob_no = $fetch_applicant_data['mobile'];
					$applicant_email_id = $fetch_applicant_data['email'];

				} else {

					$fetch_applicant_data = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
					$applicant_mob_no = $fetch_applicant_data['mobile_no'];
					$applicant_email_id = $fetch_applicant_data['email'];

				}

				$destination_mob_nos[$m] = '91'.base64_decode($applicant_mob_no); //This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$applicant_mob_no;
				$destination_email_ids[$e] = base64_decode($applicant_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;
			}

            //RO/SO
			if (in_array(3,$destination_array)) {

                if($_SESSION['application_dashboard'] == 'chemist'){
                    $packer_id = $_SESSION['packer_id'];
                    $DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');

		            $find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);


                    $ro_email_id = $find_ro_email_id['ro_email_id'];
                }

                    $fetch_ro_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ro_email_id)))->first();

                    $ro_mob_no = $fetch_ro_data['phone'];

                    $destination_mob_nos[$m] = '91'.base64_decode($ro_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
                    $log_dest_mob_nos[$m] = '91'.$ro_mob_no;
                    $destination_email_ids[$e] = base64_decode($ro_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

                    $m=$m+1;
                    $e=$e+1;



			}


            $sms_message = $find_message_record['sms_message'];

			$destination_mob_nos_values = implode(',',$destination_mob_nos);

			$destination_email_ids_values = implode(',',$destination_email_ids);

			$email_subject = $find_message_record['email_subject'];

			$template_id = $find_message_record['template_id'];//added on 12-05-2021 by Amol, new field

            //replacing dynamic values in the email message
			$sms_message = $this->replaceDynamicValuesFromMessage($sms_message,$customer_id);

			//replacing dynamic values in the email message
			$email_message = $this->replaceDynamicValuesFromMessage($sms_message,$customer_id);

            $CustomersController = new CustomersController;

			$smsLogTable = 'DmiBgrSentSmsLogs';
			$emailLogTable = 'DmiBgrSentEmailLogs';

			//To send SMS on list of mobile nos.
			if (!empty($sms_message)) {
				$CustomersController->SmsEmail->sendSms($message_id,$destination_mob_nos_values,$sms_message,$template_id,$smsLogTable);
			}

			//To send Email on list of Email ids.
			if (!empty($email_message)) {

				$CustomersController->SmsEmail->sendEmail($message_id,$email_message,$destination_email_ids_values,$email_subject,$template_id,$emailLogTable);
			}



        }


	}


    private function getRoName($customer_id){

        if($_SESSION['current_level'] == 'level_3'){
            $customer_id = $_SESSION['customer_id'];
        }

		//Load Model
		$DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');

		$find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);


        $ro_email_id = $find_ro_email_id['ro_email_id'];
		return $ro_email_id;
	}

	//this function is created on 08-07-2017 by Amol to replace dynamic values in message
	public function replaceDynamicValuesFromMessage($message,$customer_id) {

        if (isset($_SESSION['packerid'])) {
            $packer_id = $_SESSION['packerid'];
            // Use $packer_id as needed
        }

        if($_SESSION['current_level'] == 'level_3'){

            $packer_id = $_SESSION['customer_id'];
            $DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
            $customer_id = $_SESSION['customer_id'];
            $chemist_incharge = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'incharge'=>'yes')))->first();

            if (!empty($chemist_incharge)) {

                $chemist_id = $chemist_incharge['chemist_id'];
            }

        }else{
            $chemist_id = $customer_id;

        }



		//getting count before execution
		$total_occurrences = substr_count($message,"%%");
        // Hello %%ro_name%%, The Firm : %%firm_name%% having ID : %%premises_id%% has successfully submitted the  Biannually Grading Report to AGMARK -AGMARK

		while($total_occurrences > 0){

            $matches = explode('%%',$message);//getting string between %% & %%

			if (!empty($matches[1])) {

                switch ($matches[1]) {

                    case "chemist_name":

						$message = str_replace("%%chemist_name%%",(string) $this->getReplaceDynamicValues('chemist_name',$chemist_id),$message);
						break;

                    case "chemist_id":

                        $message = str_replace("%%chemist_id%%",(string) $this->getReplaceDynamicValues('chemist_id',$chemist_id),$message);
                        break;

					case "firm_name":

						$message = str_replace("%%firm_name%%",(string) $this->getReplaceDynamicValues('firm_name',$packer_id),$message);
						break;


					case "premises_id":

						$message = str_replace("%%premises_id%%",(string) $customer_id,$message);
						break;

					case "ro_name":

						$message = str_replace("%%ro_name%%",(string) $this->getReplaceDynamicValues('ro_name',$packer_id),$message);
						break;

					default:

						$message = $this->replaceBetween($message, '%%', '%%', '');
						$default_value = 'yes';
						break;
				}

			}

			if (empty($default_value)) {
				$total_occurrences = substr_count($message,"%%");//getting count after execution
			} else {
				$total_occurrences = $total_occurrences - 1;
			}

		}
// pr($message);die;
        return $message;
	}




	// This function find and return the value of replace variable value that are used in sms/email message templete
	// Created By Pravin on 24-08-2017
	public function getReplaceDynamicValues($replace_variable_value,$customer_id){


        if (!isset($_SESSION['application_type'])) { $_SESSION['application_type']=null; }

		$application_type = $_SESSION['application_type'];

		//This Session ID is Applied for the temporary Application Type is not present.
		if(Router::getRequest()->getParam('controller') == 'Dashboard'){
			if ($application_type == null) {
				$application_type = $_SESSION['application_type_temp'];
			}
		}



        $DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
		$DmiCustomers = TableRegistry::getTableLocator()->get('DmiCustomers');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');
		$DmiCertificateTypes = TableRegistry::getTableLocator()->get('DmiCertificateTypes');
		$DmiFinalSubmits = TableRegistry::getTableLocator()->get('DmiFinalSubmits');
        $dmi_replica_allotment_pdfs = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentPdfs');


        if (preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1) {

			$fetch_applicant_data = $DmiCustomers->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

		} else {



            // $get_commodity_id = explode(',',$fetch_firm_data['sub_commodity']);
            // $firm_certification_type_id = $firm_data['certification_type'];
            // $firm_certification_type = $DmiCertificateTypes->find('all',array('conditions'=>array('id IS'=>$firm_certification_type_id)))->first();

            // $final_submit_data = $DmiFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'pending'),'order' => array('id' => 'desc')))->first();


            if($replace_variable_value != 'chemist_name' || $_SESSION['current_level'] == 'level_3'){

                if($_SESSION['current_level'] == 'level_3'){

                    $fetch_firm_data = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$_SESSION['customer_id'])))->first();

                    $firm_data = $fetch_firm_data;

                    $DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');
					$customer_id = $_SESSION['customer_id'];

                    $find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);
                    $ro_email_id = $find_ro_email_id['ro_email_id'];

                    $ro_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ro_email_id)))->first();
                    $ro_user_data = $ro_user_data;


                }else{
                    $fetch_firm_data = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

                    $firm_data = $fetch_firm_data;

                    $DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');
                    $find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);
                    $ro_email_id = $find_ro_email_id['ro_email_id'];

                    $ro_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ro_email_id)))->first();
                    $ro_user_data = $ro_user_data;
                }

            }

            // echo $customer_id;die;
            #CHEMIS

            if($_SESSION['current_level'] == 'level_3' || $_SESSION['application_dashboard'] == 'chemist'){
				$DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
                if(isset($_SESSION['customer_id'])){
                    $packer_id = $_SESSION['customer_id'];
                }else{
                    $packer_id = $_SESSION['packer_id'];
                }

                $chemist_incharge = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$packer_id,'incharge'=>'yes')))->first();

                if (!empty($chemist_incharge)) {

                    $chemist_id = $chemist_incharge['chemist_id'];
                    $get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$chemist_id,'delete_status IS NULL'),'order'=>'id desc'))->first();
                }

			}else{
                $get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id desc'))->first();

            }



            if (!empty($get_chemist_name)) {

				// $get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id desc'))->first();
				$chemist_name = $get_chemist_name['chemist_fname']." ".$get_chemist_name['chemist_lname'];
				$chemist_id = $get_chemist_name['chemist_id'];

			}else{

				$get_chemist_id = $dmi_replica_allotment_pdfs->find('all')->select(['chemist_id'])->where(['customer_id IS' => $customer_id])->first();

				if(!empty($get_chemist_id)){
					$get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$get_chemist_id['chemist_id'],'delete_status IS NULL'),'order'=>'id desc'))->first();
					$chemist_name = $get_chemist_name['chemist_fname']." ".$get_chemist_name['chemist_lname'];
					$chemist_id = $get_chemist_name['chemist_id'];
				}
			}


            if ($application_type != null) {
                #Added the type cast INT on below query to resolve the boolean problem - Akash[24-11-2022]
                $get_application_type = $DmiApplicationTypes->find('all')->select(['application_type'])->where(['id IS'=>(int) $application_type,'delete_status IS NULL'])->first();
                $application_type_text = $get_application_type['application_type'];
            }else{
                $application_type_text = '';
            }

		}

		switch ($replace_variable_value) {

			case "premises_id":

				$premises_id = $firm_data['customer_id'];
				return $premises_id;
				break;

			case "firm_name":

				//This new truncate function is applied to the below line in order to trim down the charateer that exceeds the 34 Character - Akash [19-05-2023]
				$firm_name = Text::truncate($firm_data['firm_name'], 34, ['ellipsis' => '', 'exact' => true]);
				return $firm_name;
				break;


			case "ro_name":

				//This new truncate function is applied to the below line in order to trim down the charateer that exceeds the 34 Character - Akash [19-05-2023]
				$ro_name = Text::truncate($ro_user_data['f_name']." ".$ro_user_data['l_name'], 34, ['ellipsis' => '', 'exact' => true]);
				return $ro_name;
				break;

            case "ro_office":

                    $ro_office = $find_ro_email_id['ro_office'];
                    return $ro_office;
                    break;
			//for replica
			case "chemist_name":

				//This new truncate function is applied to the below line in order to trim down the charateer that exceeds the 34 Character - Akash [19-05-2023]
				return Text::truncate($chemist_name, 34, ['ellipsis' => '', 'exact' => true]);
				break;

			case "chemist_id":

				return $chemist_id;
				break;


			default:

			$message = '%%';
			break;

		}

		//Destroy the Application Type Session
		$_SESSION['application_type']=null;


	}


	// This function replace the value between two character  (Done By pravin 9-08-2018)
	function replaceBetween($str, $needle_start, $needle_end, $replacement) {

		$pos = strpos($str, $needle_start);
		$start = $pos === false ? 0 : $pos + strlen($needle_start);

		$pos = strpos($str, $needle_end, $start);
		$end = $start === false ? strlen($str) : $pos;

		return substr_replace($str,$replacement,$start);
	}

	//This function is created for convert the month no to month name
	function getMonthName($value){
		$monthName = date("F", mktime(0, 0, 0, $value, 10));
		return $monthName;
	}

}
