<?php
namespace app\Controller\Component;

use Cake\Controller\Controller;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;

use Cake\Routing\Router;
use Cake\Utility\Text;

use App\Controller\CustomersController;

class RejectionHandlerComponent extends Component
{


    public $components = array('Session', 'Customfunctions', 'Randomfunctions');
    public $controller = null;
    public $session = null;

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->Controller = $this->_registry->getController();
        $this->Session = $this->getController()->getRequest()->getSession();
    }

    public function sendNotificationOnRejection($customer_id, $application_type,$message_id)
    {
        $DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get("DmiSmsEmailTemplates");
        $DmiCustomers = TableRegistry::getTableLocator()->get('DmiCustomers');
        $DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
        $DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
        $find_message_record = $DmiSmsEmailTemplates->find('all', array('conditions' => array('id IS' => $message_id, 'status' => 'active')))->first();
        $destination_values = $find_message_record['destination'];
        $destination_array = explode(',', $destination_values);

        $m = 0;
        $e = 0;
        $destination_mob_nos = array();
        $destination_email_ids = array();



        //Applicant
        if (in_array(0, $destination_array)) {
            //checking applicant id pattern ex.102/2017 if primary Applicant added on 23-08-2017 by Amol
            if (preg_match("/^[0-9]+\/[0-9]+$/", $customer_id, $matches) == 1) {

                $fetch_applicant_data = $DmiCustomers->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->first();
                $applicant_mob_no = $fetch_applicant_data['mobile'];
                $applicant_email_id = $fetch_applicant_data['email'];

            } else {

                $fetch_applicant_data = $DmiFirms->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->first();
                $applicant_mob_no = $fetch_applicant_data['mobile_no'];
                $applicant_email_id = $fetch_applicant_data['email'];

            }

            $destination_mob_nos[$m] = '91' . base64_decode($applicant_mob_no); //This is addded on 27-04-2021 for base64decoding by AKASH
            $log_dest_mob_nos[$m] = '91' . $applicant_mob_no;
            $destination_email_ids[$e] = base64_decode($applicant_email_id); //This is addded on 01-03-2022 for base64decoding by AKASH

            $m = $m + 1;
            $e = $e + 1;
        }
        $initiatorEmailID= $_SESSION['username'];
        if (in_array(3, $destination_array)) {

            $fetch_ro_data = $DmiUsers->find('all', array('conditions' => array('email IS' => $initiatorEmailID)))->first();
            $ro_mob_no = $fetch_ro_data['phone'];

            $destination_mob_nos[$m] = '91' . base64_decode($ro_mob_no); //This is addded on 27-04-2021 for base64decoding by AKASH
            $log_dest_mob_nos[$m] = '91' . $ro_mob_no;
            $destination_email_ids[$e] = base64_decode($initiatorEmailID); //This is addded on 01-03-2022 for base64decoding by AKASH

            $m = $m + 1;
            $e = $e + 1;

        }

        $sms_message = $find_message_record['sms_message'];
        $destination_mob_nos_values = implode(',', $destination_mob_nos);

        $email_message = $find_message_record['email_message'];
        $destination_email_ids_values = implode(',', $destination_email_ids);

        $email_subject = $find_message_record['email_subject'];

        $template_id = $find_message_record['template_id']; 

        //replacing dynamic values in the email message
        $sms_message = $this->replaceDynamicValuesFromMessage($customer_id, $sms_message);

        //replacing dynamic values in the email message
        $email_message = $this->replaceDynamicValuesFromMessage($customer_id, $email_message);
        $CustomersController = new CustomersController;

        $getLogTable = $CustomersController->SmsEmail->getLogTable($application_type);
        $smsLogTable = $getLogTable['sms_log_table'];
        $emailLogTable = $getLogTable['email_log_table'];

        //To send SMS on list of mobile nos.
        if (!empty($sms_message)) {

            $CustomersController->SmsEmail->sendSms($message_id, $destination_mob_nos_values, $sms_message, $template_id, $smsLogTable);
        }

        //To send Email on list of Email ids.
        if (!empty($email_message)) {

            $CustomersController->SmsEmail->sendEmail($message_id, $email_message, $destination_email_ids_values, $email_subject, $template_id, $emailLogTable);
        }
    }

    	//this function is created on 08-07-2017 by Amol to replace dynamic values in message
	public function replaceDynamicValuesFromMessage($customer_id,$message) {

		//getting count before execution
		$total_occurrences = substr_count($message,"%%");

		while($total_occurrences > 0){

			$matches = explode('%%',$message);//getting string between %% & %%

			if (!empty($matches[1])) {

				switch ($matches[1]) {

					case "firm_name":

						$message = str_replace("%%firm_name%%",(string) $this->getReplaceDynamicValues('firm_name',$customer_id),$message);
						break;

					case "premises_id":

						$message = str_replace("%%premises_id%%",(string) $customer_id,$message);
						break;

					case "ro_name":

						$message = str_replace("%%ro_name%%",(string) $this->getReplaceDynamicValues('ro_name',$customer_id),$message);
						break;

					case "application_type":

						$message = str_replace("%%application_type%%",(string) $this->getReplaceDynamicValues('application_type',$customer_id),$message);
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
		
	
		//Load Models
		$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
		

		if ($application_type != null) {
			#Added the type cast INT on below query to resolve the boolean problem - Akash[24-11-2022]
			$get_application_type = $DmiApplicationTypes->find('all')->select(['application_type'])->where(['id IS'=>(int) $application_type,'delete_status IS NULL'])->first();
			$application_type_text = $get_application_type['application_type'];
		}else{
			$application_type_text = '';
		}


		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');


			$firm_data = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
            $initiatorEmailID= $_SESSION['username'];
			$ro_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$initiatorEmailID)))->first();

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
			case "application_type":
				return $application_type_text;
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

?>