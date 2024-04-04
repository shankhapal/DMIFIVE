<?php

namespace App\Controller;

use Cake\Event\Event;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;

class Code15digitController extends AppController {

        private $DmiCustomerLaboratoryDetails;
        private $DmiStates;
        private $DmiDistricts;
        private $DmiReplicaChargesDetails;
        private $DmiRoOffices;
        private $CommGrade;
        private $MGradeDesc;
        private $DmiAllTblsDetails;
        private $DmiPackingTypes;
        private $DmiReplicaUnitDetails;
        private $DmiCaPpLabMapings;
        private $DmiChemistFinalSubmits;
        private $DmiCaMappingOwnLabDetails;
        private $DmiChemistAllotments;
        private $Dmi15DigitAllotmentDetails;
        private $DmiAdvPaymentTransactions;
        private $Dmi15DigitAllotmentPdfs;
        private $DmiApplWithRoMappings;
        private $DmiChemistRegistrations;
        private $DmiFirms;
        private $MCommodity;

		public function initialize(): void
		{
			parent::initialize();

            // added by shankhpal on 27-03-2024
            $components = [
                'RequestHandler',
                'AqcmsWrapper',
            ];

            foreach($components as $component){
                $this->loadComponent($component);
            }

			$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->viewBuilder()->setLayout('secondary_customer');


            // Call the loadAllModels method to load necessary models.
            $this->loadAllModels();

		}



        /**
         * Loads all necessary models for the current controller.
         * This method is responsible for loading models required for the controller's functionality.
         * Author: Shankhpal Shende
         * Date: 28-03-2024
         */
        private function loadAllModels(): void {

            $this->DmiCustomerLaboratoryDetails = $this->AqcmsWrapper->customeLoadModel('DmiCustomerLaboratoryDetails');
            $this->DmiStates = $this->AqcmsWrapper->customeLoadModel('DmiStates');
            $this->DmiDistricts = $this->AqcmsWrapper->customeLoadModel('DmiDistricts');
            $this->DmiReplicaChargesDetails = $this->AqcmsWrapper->customeLoadModel('DmiReplicaChargesDetails');
            $this->DmiRoOffices = $this->AqcmsWrapper->customeLoadModel('DmiRoOffices');
            $this->CommGrade = $this->AqcmsWrapper->customeLoadModel('CommGrade');
            $this->MGradeDesc = $this->AqcmsWrapper->customeLoadModel('MGradeDesc');
            $this->DmiAllTblsDetails = $this->AqcmsWrapper->customeLoadModel('DmiAllTblsDetails');
            $this->DmiPackingTypes = $this->AqcmsWrapper->customeLoadModel('DmiPackingTypes');
            $this->DmiReplicaUnitDetails = $this->AqcmsWrapper->customeLoadModel('DmiReplicaUnitDetails');
            $this->DmiCaPpLabMapings = $this->AqcmsWrapper->customeLoadModel('DmiCaPpLabMapings');
            $this->DmiChemistFinalSubmits = $this->AqcmsWrapper->customeLoadModel('DmiChemistFinalSubmits');
            $this->DmiCaMappingOwnLabDetails = $this->AqcmsWrapper->customeLoadModel('DmiCaMappingOwnLabDetails');
            $this->DmiChemistAllotments = $this->AqcmsWrapper->customeLoadModel('DmiChemistAllotments');
            $this->Dmi15DigitAllotmentDetails = $this->AqcmsWrapper->customeLoadModel('Dmi15DigitAllotmentDetails');
            $this->DmiAdvPaymentTransactions = $this->AqcmsWrapper->customeLoadModel('DmiAdvPaymentTransactions');
            $this->Dmi15DigitAllotmentPdfs = $this->AqcmsWrapper->customeLoadModel('Dmi15DigitAllotmentPdfs');
            $this->DmiApplWithRoMappings = $this->AqcmsWrapper->customeLoadModel('DmiApplWithRoMappings');
            $this->DmiChemistRegistrations = $this->AqcmsWrapper->customeLoadModel('DmiChemistRegistrations');
            $this->DmiFirms = $this->AqcmsWrapper->customeLoadModel('DmiFirms');
            $this->MCommodity = $this->AqcmsWrapper->customeLoadModel('MCommodity');

        }

		public function beforeFilter($event) {

			parent::beforeFilter($event);

			$customer_last_login = $this->Customfunctions->customerLastLogin();
        	$this->set('customer_last_login', $customer_last_login);

			// Checked final submit status, on 10-08-2021 by Amol
			//to show "Confirm Replica" and "Replica alloted list" conditional menu from chemist home layout
			$chemist_id = $this->Session->read('username');

			$final_submit_record = $this->DmiChemistFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id),'order'=>'id desc'))->first();

			$final_status = '';
			if(!empty($final_submit_record)){
				$final_status = $final_submit_record['status'];
			}
			$this->set('final_submit_status',$final_status);

		}

		/**
		 * Function Updated for the attachment of own lab
		 * Added some conditions and modify the function for own lab module,
		 * @author shankhpal shende
		 * @version 16th June 2023
		 */
		public function replicaApplication(){

			$this->viewBuilder()->setLayout('replica_appl_layout');
			$customer_id = $this->Session->read('username');

			$message = '';
			$message_theme = '';
			$redirect_to = '';


			//get array
			$attached_lab_data = $this->DmiCaPpLabMapings->find('all', [
				'conditions' => [
						'customer_id IS' => $customer_id,
						'lab_id IS NOT NULL',
						'delete_status IS NULL'
				],
				'order' => ['id' => 'desc'],
				'limit' => 1
			])->first();


			$attached_pp_data = $this->DmiCaPpLabMapings->find('all', [
				'conditions' => [
						'customer_id IS' => $customer_id,
						'pp_id IS NOT NULL',
						'delete_status IS NULL'
				],
				'order' => ['id' => 'desc']
			])->last();

			//first check if this packer have any chemist incharge or not, elae show alert


			$check_che_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('customer_id IS'=>$customer_id,'status'=>1,'incharge'=>'yes')))->first();
			if (empty($check_che_incharge) || empty($attached_pp_data) || empty($attached_lab_data)) {

				$message_var = '<b>Note: Before Replica Self Generation following must be done.</b>
								<br>1. You need to register a Chemist from "Apply For-><b>Chemist Registration</b>" then login with Chemist id, fill forms and submit for approval, Once approved set the chemist incharge.
								<br>2. You need to attach Printing Press and Laboratory from the menu "Apply For-><b>Attach Printing Press/Lab</b>".
								<br>3. You need to Apply for the Advance Payment from the menu "Apply For-><b>Advance Payment</b>".';

				$message = $message_var;
				$message_theme = 'info';
				$redirect_to = '../customers/secondary_home';

			}else{

				//get packer details
				$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

				//generate packer unique id from table id
				$firm_details['ca_unique_no'] = $this->getCaUniqueid($firm_details['id']);

				$this->set('firm_details',$firm_details);

				/**
				 * Added for own lab module, split own lab id and get details from dmi_ca_mapping_own_lab_details
				 * table
				 * @author shankhpal shende
				 * @version 16th June 2023
				 */

				$attached_lab = $this->DmiCaPpLabMapings->find('all', ['keyField' => 'lab_id','valueField' => 'lab_id',
    		'conditions' => ['customer_id IS' => $customer_id,'delete_status IS NULL','map_type'=>'lab'],'order' => 'id desc'])->first();
				$lab_id = $attached_lab['lab_id'];

				//get printing list
				$attached_pp = $this->DmiCaPpLabMapings->find('list', ['keyField' => 'pp_id','valueField' => 'pp_id',
				'conditions' => ['AND' => ['customer_id IS' => $customer_id,'delete_status IS NULL','map_type' => 'pp']],
				'order' => 'id desc'])->toList();

				if (strpos($lab_id, "/Own") !== false) {
					$lab_list = $this->DmiCaMappingOwnLabDetails->find('list',array('keyField'=>'own_lab_id','valueField'=>'lab_name','conditions'=>array('ca_id'=>$customer_id,'delete_status IS NULL')))->toArray();
				} else {
				// added by shankhpal shende for display only attached lab on 18/10/2022
					$lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS NULL','id IN'=>$lab_id),'order'=>'firm_name asc'))->toArray();
				}

				$this->set('lab_list',$lab_list);

				//get packer wise commodity list
				$commodity_ids = explode(',',(string) $firm_details['sub_commodity']); #For Deprecations
				$commodity_list = $this->MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('commodity_code IN'=>$commodity_ids)))->toArray();

				// commented by shankhpal Shende on 26/10/2022 for [On loading Set Grade for selected commodity]
				// $grade_list = $this->MGradeDesc->find('list',array('keyField'=>'grade_code','valueField'=>'grade_desc','conditions'=>array('display'=>'Y'),'order'=>'grade_code asc'))->toArray();

				$tbl_list = $this->DmiAllTblsDetails->find('list',array('keyField'=>'id','valueField'=>'tbl_name','conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS Null'),'order'=>'id asc'))->toArray();
				$packaging_material_list = $this->DmiPackingTypes->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('delete_status IS Null'),'order'=>'id asc'))->toArray();

				//$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();

				$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null','id IN'=>$attached_pp),'order'=>'firm_name asc'))->toArray();

				//fetch last reocrds from table, if empty set default value
				$dataArray = $this->Dmi15DigitAllotmentDetails->getSectionData($customer_id);
				// Added by shankhpal Shende on 26/10/2022 for [On loading Set Grade for selected commodity]
				$get_grade = $this->CommGrade->find('all',array('fields'=>'grade_code','conditions'=>array('commodity_code IN'=>$commodity_ids),'group'=>'grade_code'))->toArray();

				foreach($get_grade as $val)
				{
					$get_grade_desc = $this->MGradeDesc->find('all',array('fields'=>array('grade_code','grade_desc'),'conditions'=>array('grade_code IN'=>$val['grade_code']),'group'=>array('grade_code','grade_desc')))->first();
					$grade_list[$get_grade_desc['grade_code']] = $get_grade_desc['grade_desc'];
				}

				//to show selected lab in list
				if(!empty($dataArray)){

					$selected_lab = $dataArray[0]['grading_lab'];
				}else{
					$selected_lab = '';
				}
				$this->set('selected_lab',$selected_lab);
				$this->set('dataArray',$dataArray);

				//create array as per the column to display in table
				$tableD = array();

				// common add more Table Header Array
					$tableD['label'] = array(
						'0' => array(
							'0' => array(
								'col' 		=> 'Sr.no',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'1' => array(
								'col' 		=> 'Commodity',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'2' => array(
								'col' 		=> 'Grade',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'3' => array(
								'col' 		=> 'TBL',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'4' => array(
								'col' 		=> 'Packaging Material',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'5' => array(
								'col' 		=> 'Authorized Printer',
								'colspan' 	=> '2',
								'rowspan' 	=> '2'
							),
							'6' => array(
								'col' 		=> 'Packet Size',
								'colspan' 	=> '2',
								'rowspan' 	=> '1'
							),
							'7' => array(
								'col' 		=> 'No. of Packets',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'8' => array(
								'col' 		=> 'Total Quantity Gross (Kg/Ltr)',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'9' => array(
								'col' 		=> 'Rate of Label Charge(Rs.) ',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'10' => array(
								'col' 		=> 'Total Label Charges(Rs.)',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'11' => array(
								'col' 		=> 'Balance Agmark Replica No.',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
						),
						'1' => array(
							'0' => array(
								'col' 		=> 'Size',
								'colspan' 	=> '1',
								'rowspan' 	=> '1'
							),
							'1' => array(
								'col' 		=> 'Unit',
								'colspan' 	=> '1',
								'rowspan' 	=> '1'
							)

						)
					);

				//converting list array in required foramt for common add more table
				$commodity_list1 = array();
				$grade_list1 = array();
				$tbl_list1 = array();
				$packaging_material_list1 = array();
				$printers_list1 = array();

				//commodity list array
				foreach($commodity_list as $key => $value){

					$commodity_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}

				//grade list array
				foreach($grade_list as $key => $value){

					$grade_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}

				//TBL list array
				foreach($tbl_list as $key => $value){

					$tbl_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}

				//Packaging material list array
				foreach($packaging_material_list as $key => $value){

					$packaging_material_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}

				//Printers list array
				foreach($printers_list as $key => $value){

					$printers_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}


				// common add more Table Input Array
				foreach($dataArray as $row){

				//	$row = $row['Dmi_replica_allotment_detail'];
					$unit_list1 = array();
					if(!empty($row['packet_size_unit'])){

						$get_unit = $this->DmiReplicaUnitDetails->find('all',array('fields'=>array('unit'),'conditions'=>array('id IS'=>$row['packet_size_unit'])))->first();
						$unit = $get_unit['unit'];

						$unit_list = $this->DmiReplicaUnitDetails->find('list',array('keyField'=>'id','valueField'=>'sub_unit','conditions'=>array('unit IS'=>$unit),'order'=>'id asc'))->toArray();

						foreach($unit_list as $key => $value){

							$unit_list1[] = array(
								'vall' => $key,
								'label' => $value
							);
						}
					}else{
						$unit_list1 = array();
					}

					$tableD['input'][] = array(

							'0' => array(

								'name'		=> null,
								'type'		=> null,
								'valid'		=> null,
								'length'	=> null
							),
							'1' => array(
								'name'		=> 'commodity',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $commodity_list1,
								'selected'	=> $row['commodity'],
								'class'		=> 'form-control commodity',
								'id'		=> 'commodity',

							),
							'2' => array(
								'name'		=> 'grade',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $grade_list1,
								'selected'	=> $row['grade'],
								'class'		=> 'form-control grade',
								'id'		=> 'grade'
							),
							'3' => array(
								'name'		=> 'tbl',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $tbl_list1,
								'selected'	=> $row['tbl'],
								'class'		=> 'form-control tbl',
								'id'		=> 'tbl'
							),
							'4' => array(
								'name'		=> 'packaging_material',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $packaging_material_list1,
								'selected'	=> $row['packaging_material'],
								'class'		=> 'form-control packaging_material',
								'id'		=> 'packaging_material'
							),
							'5' => array(
								'name'		=> 'authorized_printer',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $printers_list1,
								'selected'	=> $row['authorized_printer'],
								'class'		=> 'form-control authorized_printer',
								'id'		=> 'authorized_printer'
							),
							'6' => array(
								'name'		=> 'view_printer',
								'type'		=> 'icon',
								'class'		=> 'mt-2 fa fa-eye view_printer',
								'id'		=> 'view_printer',
								'title'		=> 'View Selected Printer details'
							),
							'7' => array(
								'name'		=> 'packet_size',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['packet_size'],
								'length'	=> '50',
								'class'		=> 'form-control packet_size',
								'id'		=> 'packet_size'

							),
							'8' => array(
								'name'		=> 'packet_size_unit',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $unit_list1,
								'selected'	=> $row['packet_size_unit'],
								'length'	=> '50',
								'class'		=> 'form-control packet_size_unit',
								'id'		=> 'packet_size_unit'

							),
							'9' => array(
								'name'		=> 'no_of_packets',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['no_of_packets'],
								'length'	=> '50',
								'class'		=> 'form-control no_of_packets',
								'id'		=> 'no_of_packets'

							),
							'10' => array(
								'name'		=> 'total_quantity',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['total_quantity'],
								'length'	=> '50',
								'class'		=> 'form-control readonly total_quantity',
								'id'		=> 'total_quantity'

							),
							'11' => array(
								'name'		=> 'label_charge',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['label_charge'],
								'length'	=> '50',
								'class'		=> 'form-control readonly label_charge',
								'id'		=> 'label_charge'

							),
							'12' => array(
								'name'		=> 'total_label_charges',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['total_label_charges'],
								'length'	=> '50',
								'class'		=> 'form-control readonly total_label_charges',
								'id'		=> 'total_label_charges'

							),
							'13' => array(
								'name'		=> 'bal_agmark_replica',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['bal_agmark_replica'],
								'length'	=> '100',
								'class'		=> 'form-control bal_agmark_replica',
								'id'		=> 'bal_agmark_replica'

							),


					);

				}


				$tableForm[] = $tableD;

				$jsonTableForm = json_encode($tableForm);
				$this->set('tableForm',$jsonTableForm);



				//get balance amount for the applicant from transaction table

				$bal_amt = 0;
				$get_bal = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>'balance_amount','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				if(!empty($get_bal)){
					$bal_amt = $get_bal['balance_amount'];
				}
				$this->set('bal_amt',$bal_amt);

				//to save post data
				if (null!==($this->request->getData('save'))){

					//set delete flag for last records with allotment status Null, to show only current added/updated records
					//the alloted records will be status 1, which will not touch, and to be keeped for logs
					$date = date('Y-m-d H:i:s');
					$this->Dmi15DigitAllotmentDetails->updateAll(array('delete_status'=>"yes",'modified'=>"$date"),array('customer_id IS'=>$customer_id,'allot_status IS Null'));

					$cur_year = date('y');
					$cur_month = date('m');

					//get nodal office of the applied CA


					$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);



					$office_code_ar = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'replica_code','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no'),'office_type IN'=>array('RO','SO')),'order'=>'id asc'))->toArray();
					$office_code = $office_code_ar[$get_office['id']];

					//get unique replica code for commodity from chargees table


					$commo_code_ar = $this->DmiReplicaChargesDetails->find('list',array('keyField'=>'commodity_code','valueField'=>'replica_code','conditions'=>array('OR'=>array('delete_status IS NULL'))))->toArray();

					//alphabetic conversion of Thousand digit (1-26 i.e A to Z)
					$thou_ar = array('1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G','8'=>'H','9'=>'I','10'=>'J','11'=>'K',
									'12'=>'L','13'=>'M','14'=>'N','15'=>'O','16'=>'P','17'=>'Q','18'=>'R','19'=>'S','20'=>'T','21'=>'U','22'=>'V','23'=>'W',
									'24'=>'X','25'=>'Y','26'=>'Z');

					$rep_from = array();
					$cur_rep_no_from = array();
					$cur_rep_no_upto = array();
					$i=0;
					$thNum = 1;
					$hdNum = 1;

					//replica range from value, initial stage for first time
					$cur_rep_no_from[$i] = $thou_ar[1].'001';
					$postData = $this->request->getData();
					foreach($postData['commodity'] as $key=>$val){

						//get commodity replica code code for selected commodity code array
						$comm_code = $commo_code_ar[$postData['commodity'][$key]];

						$init_rep = $office_code.$comm_code.$cur_month.$cur_year;

						if($i==0){
							$cur_rep_no_from[$i] = $init_rep.$cur_rep_no_from[$i];//to concate value with commodity code dynamically
						}

						//required no. of packets for each row
						$req_cnt = $postData['no_of_packets'][$key];

						//get last alloment, if exists
						$last_allotment = $this->Dmi15DigitAllotmentDetails->find('all',array('fields'=>'alloted_rep_to','conditions'=>array('customer_id IS'=>$customer_id,'allot_status'=>'1','delete_status IS Null'),'order'=>'id desc'))->first();
						if(!empty($last_allotment)){

							$alloted_rep_to = $last_allotment['alloted_rep_to'];

							//get replica range from value, for first row
							if($i==0){

								//check the month from last allotment,
								//if month not matched then reset the series to initial position, for each year or month
								$last_allt_month = substr($alloted_rep_to,2,2);
								if($cur_month != $last_allt_month){

									$cur_rep_no_from[$i] = $init_rep.$thou_ar[1].'001';

								}else{

									$thNum = array_search(substr($alloted_rep_to,6,1),$thou_ar);//thousand digit
									$hdNum = substr($alloted_rep_to,7);//hundred's digits

									$split_T = explode('T',(string) $hdNum); #For Deprecations
									$hdNum = $split_T[0];

									$hdNum = $hdNum+1;//start from next value

									$cur_rep_no_from[$i] = $this->replicaGenerationLogic($init_rep,1,$thNum,$hdNum,$thou_ar);
								}
							}
						}

						//calculate and get replica for required no. of packets
						$cur_rep_no_upto[$i] = $this->replicaGenerationLogic($init_rep,$req_cnt,$thNum,$hdNum,$thou_ar);

						$last_row_rep_no = $cur_rep_no_upto[$i];

						$i=$i+1;

						$thNum = array_search(substr($last_row_rep_no,6,1),$thou_ar);//thousand digit
						$hdNum = substr($last_row_rep_no,7);//hundred's digits

						$split_T = explode('T',(string) $hdNum); #For Deprecations
						$hdNum = $split_T[0];

						$hdNum = $hdNum+1;//start from next value

						//from $i = 1, and further
						$cur_rep_no_from[$i] = $this->replicaGenerationLogic($init_rep,1,$thNum,$hdNum,$thou_ar);

					}
					$this->Session->delete('init_rep');

					if($this->Dmi15DigitAllotmentDetails->saveFormDetails($postData,$cur_rep_no_from,$cur_rep_no_upto)==true){

						$chemist_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('customer_id IS'=>$customer_id,'status'=>1,'incharge'=>'yes')))->first();
						$chemist_id = $chemist_incharge['chemist_id'];

						#SMS: Applicant registered for 15 Digit Code
						//$this->DmiSmsEmailTemplates->sendMessage(74,$customer_id); #Packer
						//$this->DmiSmsEmailTemplates->sendMessage(75,$chemist_id); #Chemist

						$message = 'The application for 15 Digit Codes is saved successfully. It is now available to Chemist for confirmation';
						$redirect_to = 'replica_application';

					}
				}

			}

			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);
			if(!empty($message)){$this->render('/element/message_boxes');}

		}


	//replica serial number generation logic
	public function replicaGenerationLogic($init_rep,$req_cnt,$thNum,$hdNum,$thou_ar){

		//if session set get from session, set below when range exceeds for same month
		$init_rep_session = $this->Session->read('init_rep');
		if(!empty($init_rep_session)){

			$init_rep = $init_rep_session;
		}

		$replica_number = '';
		$T = '1000';
		$th=$thNum;

		//trim the last three 0 from the req qty no. to get no. to add in hundredth value.
		$qty_to_add = substr($req_cnt, 0, -3);
		$hdNum = (int)$hdNum+((int)$qty_to_add-1);//1 decremented as satrts from 001

		if(strlen($hdNum)==1){
			$hdNum = '00'.$hdNum;
		}elseif(strlen($hdNum)==2){
			$hdNum = '0'.$hdNum;
		}

		$fixed_hundred_digits = substr($hdNum,-3);

		$replica_number = $init_rep.$thou_ar[$th].$hdNum.'T';

		for($i=1;$i<=$hdNum;$i++){

			if($i==$T){

				while($hdNum>=$T){
					$remains_count = $hdNum-$i;//remaining values to be added in initial value of hundresd i.e '001'
					$th = $th+1;
					$hdNum = '001';
					$hdNum = (int)($hdNum)+(int)$remains_count;

				}

				$replica_number = $init_rep.$thou_ar[$th].$fixed_hundred_digits.'T';
			}
		}

		return $replica_number;

	}


	//function to generate CA unique id for replica number
	public function getCaUniqueid($table_id){

		//count the length of table id
		$idLength = strlen($table_id);
		if($idLength==1){
			$table_id = '0000'.$table_id;
		}elseif($idLength==2){
			$table_id = '000'.$table_id;
		}elseif($idLength==3){
			$table_id = '00'.$table_id;
		}elseif($idLength==4){
			$table_id = '0'.$table_id;
		}

		return $table_id;
	}


	//to get charge as per commodity for replica serial number, when unit selected in row
	public function getCommodityWiseCharge(){

		$this->autoRender = false;

		$commodity_id = $_POST['commodity_id'];

		//get charge from table


		$get_charge = $this->DmiReplicaChargesDetails->find('all',array('conditions'=>array('commodity_code IS'=>$commodity_id)))->first();

		if(!empty($get_charge)){

			$charge = $get_charge['charges'];
			$unit = $get_charge['unit'];
			$min_qty = $get_charge['min_qty'];

			//get unit list from table as per commodity list

			$unit_list = $this->DmiReplicaUnitDetails->find('list',array('keyField'=>'id','valueField'=>'sub_unit','conditions'=>array('unit IS'=>$unit),'order'=>'id asc'))->toArray();

			$result = array('charge'=>$charge,'unit_list'=>$unit_list,'min_qty'=>$min_qty);

			echo '~'.json_encode($result).'~';

		}else{
			echo '~No Charge~';
		}
		exit;

	}


	//to get grade as per commodity for replica serial number, when unit selected in row
	// added by shankhpal shende on 26/08/2022
	public function getCommodityWiseGrade() {

		$this->autoRender = false;
		$commodity_id = $_POST['commodity_id'];

		$get_grade = $this->CommGrade->find('all',array('fields'=>'grade_code','conditions'=>array('commodity_code IS'=>$commodity_id),'group'=>'grade_code'))->toArray();

		foreach($get_grade as $val)
		{

			$get_grade_desc = $this->MGradeDesc->find('all',array('fields'=>array('grade_code','grade_desc'),'conditions'=>array('grade_code IN'=>$val['grade_code']),'group'=>array('grade_code','grade_desc')))->first();
	        $desc[$get_grade_desc['grade_code']] = $get_grade_desc['grade_desc'];

		}

		if (!empty($desc)) {

			$result = array('Grade'=>$desc);


			echo '~'.json_encode($result).'~';

		} else {
			echo '~No Grade~';
		}
		exit;


	}


	//to get gross quantity and total charges when enter no. of packets
	public function getGrossQuantityAndTotalCharge(){

		$this->autoRender = false;
		$packet_size = $_POST['packet_size'];
		$sub_unit_id = $_POST['sub_unit_id'];
		$no_of_packets = $_POST['no_of_packets'];
		$label_charge = $_POST['label_charge'];
		$commodity_id = $_POST['commodity_id'];

		//get conversion factor as per sub unit

		$get_factor = $this->DmiReplicaUnitDetails->find('all',array('fields'=>'conversion_factor', 'conditions'=>array('id IS'=>$sub_unit_id)))->first();
		$conversion_factor = $get_factor['conversion_factor'];

		//get min gross quantity for selected commodity to campair

		$get_charge = $this->DmiReplicaChargesDetails->find('all',array('fields'=>'min_qty','conditions'=>array('commodity_code IS'=>$commodity_id)))->first();
		$min_grpss_qty = $get_charge['min_qty'];

		$gross_quantity = ($packet_size*$no_of_packets)/$conversion_factor;

		//if calulated gross qty is more tahn min qty, then get new total, else calculate total with min qty
		if($gross_quantity > $min_grpss_qty){
			$total_charges = $gross_quantity*$label_charge;
		}else{
			$total_charges = $min_grpss_qty*$label_charge;
			$gross_quantity = $min_grpss_qty;
		}

		$result = array('gross_quantity'=>$gross_quantity,'total_charges'=>$total_charges);

		echo '~'.json_encode($result).'~';
		exit;
	}


	//to get gross quantity and total charges when enter no. of packets
	public function getPrinterDetails(){

		$this->autoRender = false;
		$printer_id = $_POST['printer_id'];

		//get conversion factor as per sub unit

		$get_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$printer_id)))->first();
		$name = $get_details['firm_name'];
		$cert_no = $get_details['customer_id'];
		$address = $get_details['street_address'];
		$district_id = $get_details['district'];
		$state_id = $get_details['state'];

		//get district name

		$get_district = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$district_id)))->first();
		$district = $get_district['district_name'];

		//get state name

		$get_state = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$state_id)))->first();
		$state = $get_state['state_name'];

		$msg = "<b>Name:</b> ".$name."<br>";
		$msg .= "<b>Certificate No:</b> ".$cert_no."<br>";
		$msg .= "<b>Address:</b> ".$address."<br>";
		$msg .= "<b>District:</b> ".$district."<br>";
		$msg .= "<b>State:</b> ".$state;

		echo '~'.$msg.'~';
		exit;
	}


	//to get balance amount for ajax call_user_func
	public function checkBalAmt(){

		$this->autoRender = false;

		//get balance amount for the applicant from transaction table

		$bal_amt = 0;
		$get_bal = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>'balance_amount','conditions'=>array('customer_id IS'=>$this->Session->read('username')),'order'=>'id desc'))->first();

		if(!empty($get_bal)){
			$bal_amt = $get_bal['balance_amount'];
		}

		echo '~'.$bal_amt.'~';
		exit;
	}


//below functions are for chemist dashboard for approval of applications

	public function replicaApplList(){

		$this->viewBuilder()->setLayout('chemist_home_layout');


		$this->Session->delete('ca_unique_no');

		//get packers for which the chemist alloted
		$chemist_id = $this->Session->read('username');
		$chemist_packer_list = $this->DmiChemistAllotments->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array('chemist_id IS'=>$chemist_id,'status'=>1,'incharge'=>'yes'),'group'=>'id,customer_id'))->toArray();

		//get replica applications list for this chemist
		$replica_appl_list = array();
		if(!empty($chemist_packer_list)){
			$replica_appl_list = $this->Dmi15DigitAllotmentDetails->find('all',array('fields'=>array('customer_id','ca_unique_no'),'conditions'=>array('customer_id IN'=>$chemist_packer_list,'allot_status IS Null','delete_status IS Null'),'group'=>'customer_id,ca_unique_no'))->toArray();
		}
		$this->set('replica_appl_list',$replica_appl_list);

	}

	//get ca unique no and redirect to application page
	public function replicaApplListId($ca_unique_no){

		$this->Session->write('ca_unique_no',$ca_unique_no);
		$this->redirect('/Code15digit/replicaApplicationApproval');
	}

	//method to show replica application on chemist side for verification

	/**
	 * function updated for attachment of own lab module
	 * modify some condition's and added new logic as needed
	 * @author shankhpal shende
	 * @version 16 th June 2023
	 */
	public function replicaApplicationApproval(){

		$this->viewBuilder()->setLayout('replica_appl_approval_layout');



		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();
		$this->set('firm_details',$firm_details);

		$customer_id = $firm_details['customer_id'];

		//list of authorized laboratory
		$lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS Null'),'order'=>'firm_name asc'));
		$this->set('lab_list',$lab_list);

		//get packer wise commodity list
	//	$commodity_ids = explode(',',$firm_details['sub_commodity']);
	//	$commodity_list = $this->M_commodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('commodity_code IN'=>$commodity_ids)))->toArray();
	//	$grade_list = $this->MGradeDesc->find('list',array('keyField'=>'grade_code','valueField'=>'grade_desc','conditions'=>array('display'=>'Y'),'order'=>'grade_code asc'))->toArray();
	//	$tbl_list = $this->Dmi_all_tbls_detail->find('list',array('keyField'=>'id','valueField'=>'tbl_name','conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS Null'),'order'=>'id asc'))->toArray();
	//	$packaging_material_list = $this->Dmi_packing_type->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('delete_status IS Null'),'order'=>'id asc'))->toArray();
	//	$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();


		$this->set('ca_unique_no',$ca_unique_no);

		$this->Session->write('replica_for','code15digit');//to get controller name in esign consent & sey url in response function

	//fetch last reocrds from table, if empty set default value
		$dataArray = $this->Dmi15DigitAllotmentDetails->getSectionData($customer_id);

		$tableRowData = array();
		$overall_charges = 0;
		$i=0;
		foreach($dataArray as $each){

			$eachrow = $each;

			/**
			 * Added for own lab module, split own lab id and get details from dmi_ca_mapping_own_lab_details
			 * table
			 * @author shankhpal shende
			 * @version 16th June 2023
			 */


			if (strpos($eachrow['grading_lab'], "/Own") !== false) {
            // Get selected lab name
            $lab_details = $this->DmiCaMappingOwnLabDetails->find()
                ->select(['lab_name'])
                ->where(['own_lab_id' => $eachrow['grading_lab']])
                ->first();
            $lab_name = $lab_details['lab_name'];
            $tableRowData[$i]['lab_name'] = $lab_name;
            }else{
			//get selected lab name
			$lab_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['grading_lab'])))->first();
			$lab_name = $lab_details['firm_name'];
			$tableRowData[$i]['lab_name'] = $lab_name;
			}

			//get selected commodity
			$commodity_details = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code IS'=>$eachrow['commodity'])))->first();
			$commodity_name = $commodity_details['commodity_name'];
			$tableRowData[$i]['commodity_name'] = $commodity_name;

			//get selected grade
			$grade_details = $this->MGradeDesc->find('all',array('fields'=>'grade_desc','conditions'=>array('grade_code IS'=>$eachrow['grade'])))->first();
			$grade_name = $grade_details['grade_desc'];
			$tableRowData[$i]['grade_name'] = $grade_name;

			//get selected tbl
			$tbl_details = $this->DmiAllTblsDetails->find('all',array('fields'=>'tbl_name','conditions'=>array('id IS'=>$eachrow['tbl'])))->first();
			$tbl_name = $tbl_details['tbl_name'];
			$tableRowData[$i]['tbl_name'] = $tbl_name;

			//get selected packaging material
			$packing_details = $this->DmiPackingTypes->find('all',array('fields'=>'packing_type','conditions'=>array('id IS'=>$eachrow['packaging_material'])))->first();
			$packing_type = $packing_details['packing_type'];
			$tableRowData[$i]['packing_type'] = $packing_type;

			//get selected printer
			$printer_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['authorized_printer'])))->first();
			$printer_name = $printer_details['firm_name'];
			$tableRowData[$i]['printer_name'] = $printer_name;

			//get packet size
			$tableRowData[$i]['packet_size'] = $eachrow['packet_size'];

			//get selected printer
			$get_unit = $this->DmiReplicaUnitDetails->find('all',array('fields'=>array('sub_unit'),'conditions'=>array('id'=>$eachrow['packet_size_unit'])))->first();
			$packet_size_unit = $get_unit['sub_unit'];
			$tableRowData[$i]['packet_size_unit'] = $packet_size_unit;

			//get no of packets
			$tableRowData[$i]['no_of_packets'] = $eachrow['no_of_packets'];

			//get total quantity gross
			$tableRowData[$i]['total_quantity'] = $eachrow['total_quantity'];

			//get label charge
			$tableRowData[$i]['label_charge'] = $eachrow['label_charge'];

			//get total label charges
			$tableRowData[$i]['total_label_charges'] = $eachrow['total_label_charges'];

			//get bal replica no
			$tableRowData[$i]['bal_agmark_replica'] = $eachrow['bal_agmark_replica'];

			//get alloted replica "From" number
			$tableRowData[$i]['alloted_rep_from'] = $eachrow['alloted_rep_from'];

			//get alloted replica "To" number
			$tableRowData[$i]['alloted_rep_to'] = $eachrow['alloted_rep_to'];

			//calculate over all charges
			$overall_charges = $overall_charges+$eachrow['total_label_charges'];

			$i=$i+1;
		}

		$this->set('tableRowData',$tableRowData);
		$this->set('overall_charges',$overall_charges);
	}

	/**
	 * function updated for attachment of own lab module
	 * modify some condition's and added new logic as needed
	 * @author shankhpal shende
	 * @version 16 th June 2023
	 */
	//function to create view of pdf document for replica allotment letter
	public function replicaAllotmentPdfView(){

		$this->viewBuilder()->setLayout('pdf_layout');



		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();
		$firm_details = $firm_details;
		$this->set('firm_details',$firm_details);

		//get district and state name
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$firm_details['district'], 'OR'=>array('delete_status IS Null','delete_status'=>'no'))))->first();
		$this->set('firm_district_name',$fetch_district_name['district_name']);

		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS '=>$firm_details['state'], 'OR'=>array('delete_status IS Null','delete_status'=>'no'))))->first();
		$this->set('firm_state_name',$fetch_state_name['state_name']);

		//replica application details
		$dataArray = $this->Dmi15DigitAllotmentDetails->getSectionData($firm_details['customer_id']);

		$appl_date = \DateTime::createFromFormat('d/m/Y H:i:s', $dataArray[0]['modified'])->format('d/m/Y'); //This line is changed to show only the date in the pdf : Akash [13-09-2023]
		$this->set('appl_date',$appl_date);


		$tableRowData = array();
		$printerArray = array();
		$seenIds = [];

		$overall_charges = 0;
		$i=0;
		foreach($dataArray as $each){

			$eachrow = $each;

			/**
			 * Added for own lab module, split own lab id and get details from dmi_ca_mapping_own_lab_details
			 * table
			 * @author shankhpal shende
			 * @version 15th June 2023
			 */
			// updated function by shankhpal on 19/09/2023 for own lab

			if (strpos($eachrow['grading_lab'], "/Own") !== false) {
            // Get selected lab name

				$id_explode = explode('/Own', $dataArray[0]['grading_lab']);
				$lab_id = $id_explode[0];


				$lab_details = $this->DmiCustomerLaboratoryDetails
										->find()
										->where(['id' => $lab_id])
										->first();
				$laboratory_name = $lab_details['$lab_details'];

    	    }else{
			//get selected lab name
			$lab_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$eachrow['grading_lab'])))->first(); // updated query on 19/09/2023 by shankhpal

			$lab_name = $lab_details['firm_name'];
			$tableRowData[$i]['lab_name'] = $lab_name;
			}


			//get selected commodity
			$commodity_details = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code IS'=>$eachrow['commodity'])))->first();
			$commodity_name = $commodity_details['commodity_name'];
			$tableRowData[$i]['commodity_name'] = $commodity_name;

			//get selected grade
			$grade_details = $this->MGradeDesc->find('all',array('fields'=>'grade_desc','conditions'=>array('grade_code IS'=>$eachrow['grade'])))->first();
			$grade_name = $grade_details['grade_desc'];
			$tableRowData[$i]['grade_name'] = $grade_name;

			//get selected tbl
			$tbl_details = $this->DmiAllTblsDetails->find('all',array('fields'=>'tbl_name','conditions'=>array('id IS'=>$eachrow['tbl'])))->first();
			$tbl_name = $tbl_details['tbl_name'];
			$tableRowData[$i]['tbl_name'] = $tbl_name;

			//get selected packaging material
			$packing_details = $this->DmiPackingTypes->find('all',array('fields'=>'packing_type','conditions'=>array('id IS'=>$eachrow['packaging_material'])))->first();
			$packing_type = $packing_details['packing_type'];
			$tableRowData[$i]['packing_type'] = $packing_type;

			//get selected printer
			$printer_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['authorized_printer'])))->first();
			$printer_name = $printer_details['firm_name'];
			$tableRowData[$i]['printer_name'] = $printer_name;

			//get packet size
			$tableRowData[$i]['packet_size'] = $eachrow['packet_size'];

			//get selected printer
			$get_unit = $this->DmiReplicaUnitDetails->find('all',array('fields'=>array('sub_unit'),'conditions'=>array('id IS'=>$eachrow['packet_size_unit'])))->first();
			$packet_size_unit = $get_unit['sub_unit'];
			$tableRowData[$i]['packet_size_unit'] = $packet_size_unit;

			//get no of packets
			$tableRowData[$i]['no_of_packets'] = $eachrow['no_of_packets'];

			//get total quantity gross
			$tableRowData[$i]['total_quantity'] = $eachrow['total_quantity'];

			//get label charge
			$tableRowData[$i]['label_charge'] = $eachrow['label_charge'];

			//get total label charges
			$tableRowData[$i]['total_label_charges'] = $eachrow['total_label_charges'];

			//get bal replica no
			$tableRowData[$i]['bal_agmark_replica'] = $eachrow['bal_agmark_replica'];

			//get alloted replica "From" number
			$tableRowData[$i]['alloted_rep_from'] = $eachrow['alloted_rep_from'];

			//get alloted replica "To" number
			$tableRowData[$i]['alloted_rep_to'] = $eachrow['alloted_rep_to'];

			//calculate over all charges
			$overall_charges = $overall_charges+$eachrow['total_label_charges'];

			//get printer name and address this code is added to display the printer on the pdf - Akash Thakre [12-09-2023]
			$printer_details = $this->DmiFirms->find()->where(['id IS'=>$eachrow['authorized_printer']])->first();
			if(!empty($printer_details)){

				$printerId = $printer_details['id'];
				if (!in_array($printerId, $seenIds)) {

					$printerArray[$i] = array(
						'id' => $printer_details['id'],
						'firm_name' => $printer_details['firm_name'],
						'street_address' => $printer_details['street_address'],
						'district' => $this->DmiDistricts->getDistrictNameById($printer_details['district']),
						'state' => $this->DmiStates->getStateNameById($printer_details['state']),
						'postal_code' =>  $printer_details['postal_code']

					);

					// Add the 'id' to the seenIds array
					$seenIds[] = $printerId;
				}
			}

			$i=$i+1;
		}


		//get transaction id for payment to be deducted

		$tran_details = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>array('trans_id','created'),'conditions'=>array('customer_id IS'=>$firm_details['customer_id']),'order'=>'id desc'))->first();
		$last_trans_id = $tran_details['trans_id'];
		$trans_date = \DateTime::createFromFormat('d/m/Y H:i:s', $tran_details['created'])->format('d/m/Y'); //This line is changed to show only the date in the pdf : Akash [13-09-2023]

		$cur_trans_id =  substr($last_trans_id,-4)+1;
		$cur_trans_id = 'ADP/'.date('m').'/'.$cur_trans_id;
		$this->set(compact('cur_trans_id','trans_date'));

		//get chemist name
		$chemist_id = $this->Session->read('username');

		$chemistdetails = $this->DmiChemistRegistrations->find('all',array('fields'=>array('chemist_fname','chemist_lname'),'conditions'=>array('chemist_id IS'=>$chemist_id)))->first();
		$chemist_name = $chemistdetails['chemist_fname'].' '.$chemistdetails['chemist_lname'];

		$this->set(compact('chemist_name','tableRowData','overall_charges'));

		if(!empty($lab_details)){
			$labArray = array(
				'firm_name' => $lab_details['firm_name'] ?? $lab_details['laboratory_name'],
				'street_address' => $lab_details['street_address'],
				'district' => $this->DmiDistricts->getDistrictNameById($lab_details['district']),
				'state' => $this->DmiStates->getStateNameById($lab_details['state']),
				'postal_code' =>  $lab_details['postal_code']

			);

		} else {
			$labArray = array();
		}
		$this->set(compact('labArray','printerArray'));


		//to eb used this session after successful esigned, to updated transaction table
		$this->Session->write('overall_total_chrg',$overall_charges);

		//added by shankhpal shende on 19/08/2022 for implimenting QR code for replica EsignedChemist
		//get nodal office of the applied CA

		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($firm_details['customer_id']);
		$region = $get_office['ro_office'];

		$pdf_date = date('d-m-Y');

		//added code to get validity date for next 3 months from issue
		//on 21-08-2023 by Amol
		$validUptoDate = date('d/m/Y', strtotime("+3 months", strtotime($pdf_date)));
		$this->set('validUptoDate',$validUptoDate);

		//added by shankhpal shende on 14/10/2022 for implimenting QR code for replica EsignedChemist
		$data = [$chemist_name,$firm_details['customer_id'],$firm_details['firm_name'],$pdf_date,$region];
		//$data = [$chemist_name,$firm_details['firm_name']];
		$result_for_qr = $this->Customfunctions->getQrCode($data,'CHM');

		$this->set('result_for_qr',$result_for_qr);
		//end for QR code

		$this->generateReplicaAllotmentPdf();

	}


	//generate replica allotment letter pdf
	public function generateReplicaAllotmentPdf(){

		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();
		$customer_id = $firm_details['customer_id'];

	//	$view = new View($this, false);
	//	$view->layout = null;
		$pdf_data = $this->render('/Code15digit/replica_allotment_pdf_view');

		//check applicant last record version to increment
		$pdf_list = $this->Dmi15DigitAllotmentPdfs->find('all', array('fields'=>'pdf_version', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
		$last_pdf_version = 0;
		if(!empty($pdf_list))
		{
			$last_pdf_version =	$pdf_list['pdf_version'];
		}

		$current_pdf_version = $last_pdf_version+1; //increment last version by 1

		//creating filename and file path to save
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$rearranged_id = 'Rep-FDC-'.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];
		$filename = $rearranged_id.'('.$current_pdf_version.')'.'.pdf';
		$file_path = '/writereaddata/DMI/temp/'.$filename;

		$this->Session->write('pdf_file_name',$filename);

		$pdfinst = new ApplicationformspdfsController();
		$pdfinst->callTcpdf($pdf_data,'I',$customer_id,'replica');
		$pdfinst->callTcpdf($pdf_data,'F',$customer_id,'replica');
	}


	//this function will be called from esign controller, when document esigned successful
	public function afterReplicaAllotmentEsigned(){

		$this->viewBuilder()->setLayout('chemist_home_layout');

		$message='';
		$redirect_to='';

		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();
		$customer_id = $firm_details['customer_id'];

		//add record for deduction of payment in transaction table

		//fetch last transaction details, to get bal amount
		$bal_details = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>array('balance_amount'),'conditions'=>array('customer_id iS'=>$customer_id),'order'=>'id desc'))->first();
		$last_bal_amt = $bal_details['balance_amount'];

		//get last transaction id in the table
		$trans_id_details = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>array('trans_id'),'order'=>'id desc'))->first();
		$last_trans_id = $trans_id_details['trans_id'];

		$cur_total_amt = $this->Session->read('overall_total_chrg');
		$cur_bal_amt = $last_bal_amt - $cur_total_amt;
		$split_trans_id =  explode('/',(string) $last_trans_id);//substr($last_trans_id,-4)+1; #For Deprecations
		$cur_trans_id = $split_trans_id[2]+1;
		$cur_trans_id = 'ADP/'.date('m').'/'.$cur_trans_id;


		//Move esigned file from temp folder to Main "Replica" folder and enter record in allotment pdf table

		$filename = $this->Session->read('pdf_file_name');
		$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
		$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/replica-allotments/';

		//calling custome function to move file
		$pdfinst = new ApplicationformspdfsController();
		if($pdfinst->moveFile($filename,$source,$destination)==1){

			//changed file path from temp to "replica-allotments"
			$file_path = '/writereaddata/DMI/replica-allotments/'.$filename;

			//check applicant last record version to increment
			$pdf_list = $this->Dmi15DigitAllotmentPdfs->find('all', array('fields'=>'pdf_version', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$last_pdf_version = 0;
			if(!empty($pdf_list))
			{
				$last_pdf_version =	$pdf_list['pdf_version'];
			}

			$current_pdf_version = $last_pdf_version+1; //increment last version by 1

			$Dmi15DigitAllotmentPdfsEntity = $this->Dmi15DigitAllotmentPdfs->newEntity(array(

				'customer_id'=>$customer_id,
				'chemist_id'=>$this->Session->read('username'),
				'pdf_file'=>$file_path,
				'date'=>date('Y-m-d'),
				'pdf_version'=>$current_pdf_version,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')

			));

			$this->Dmi15DigitAllotmentPdfs->save($Dmi15DigitAllotmentPdfsEntity);


			//save debit entry in transaction table
			$DmiAdvPaymentTransactionsEntity = $this->DmiAdvPaymentTransactions->newEntity(array(

				'customer_id'=>$customer_id,
				'payment_for'=>'1',
				'trans_type'=>'debited',
				'trans_amount'=>$cur_total_amt,
				'balance_amount'=>$cur_bal_amt,
				'trans_id'=>$cur_trans_id,
				'created'=>date('Y-m-d H:i:s')

			));
			$this->DmiAdvPaymentTransactions->save($DmiAdvPaymentTransactionsEntity);

			//update allotment status to 1 in replica allotment table


			$date = date('Y-m-d H:i:s');

			//added code to get validity date for next 3 months from issue
			//on 21-08-2023 by Amol
			$validUptoDate = date('Y-m-d', strtotime("+3 months", strtotime($date)));

			//updated query with new field "valid_upto" on 21-08-2023 by Amol
			$this->Dmi15DigitAllotmentDetails->updateAll(array('allot_status'=>"1",'modified'=>"$date",'version'=>"$current_pdf_version",'valid_upto'=>"$validUptoDate"),array('customer_id IS'=>$customer_id,'allot_status IS Null','delete_status IS Null'));


		}

		//delete session variables
		$this->Session->delete('pdf_file_name');
		$this->Session->delete('overall_total_chrg');
		$this->Session->delete('replica_for');




		//get chemist in-charge id to send SMS/email

		$chemist_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('customer_id IS'=>$customer_id,'status'=>1,'incharge'=>'yes')))->first();
		$chemist_id = $chemist_incharge['chemist_id'];

		#SMS: Approve and Allotment of  15 Digit  Code
		//$this->DmiSmsEmailTemplates->sendMessage(76,$customer_id); #Packer
		//$this->DmiSmsEmailTemplates->sendMessage(77,$chemist_id); #Chemist
		//$this->DmiSmsEmailTemplates->sendMessage(78,$customer_id); #RO/SO

		//get lab and printer for last allotment to send SMS/email
		$get_allotments = $this->Dmi15DigitAllotmentDetails->find('all',array('fields'=>array('authorized_printer','grading_lab'),'conditions'=>array('version IS'=>$current_pdf_version)))->toArray();

		$i=0;
		foreach($get_allotments as $each){

			$each = $each;

			$lab_id = $each['grading_lab'];
			$printer_id[$i] = $each['authorized_printer'];

			$i=$i+1;
		}
		//get lab details
		/**
		 * Added for own lab module, split own lab id and get details from dmi_ca_mapping_own_lab_details
		 * table
		 * @author shankhpal shende
		 * @version 20 June 2023
		 */

		if (strpos($lab_id, "/Own") !== false) {
			$lab_details = $this->DmiCaMappingOwnLabDetails->find('all',array('fields'=>'ca_id','conditions'=>array('own_lab_id IS'=>$lab_id)))->first();
			$lab_cust_id = $lab_details['ca_id'];
		} else {
		$lab_details = $this->DmiFirms->find('all',array('fields'=>'customer_id','conditions'=>array('id IS'=>$lab_id)))->first();
		$lab_cust_id = $lab_details['customer_id'];
		}

		#SMS: Approve and Allotment of  15 Digit  Code
		//$this->DmiSmsEmailTemplates->sendMessage(79,$lab_cust_id); #Laboratory

		//check if multiple printers selected, to send SMS/email
		$id='';
		foreach($printer_id as $each){

			if($id != $each){

				//get printer details
				$printer_details = $this->DmiFirms->find('all',array('fields'=>'customer_id','conditions'=>array('id IS'=>$each)))->first();
				$printer_cust_id = $printer_details['customer_id'];

				#SMS: Approve and Allotment of  15 Digit  Code
				//$this->DmiSmsEmailTemplates->sendMessage(80,$printer_cust_id); #Printer
			}

			$id = $each;
		}


		$message = 'The Replica 15 Digit Code is Approved and Alloted Successfully';
		$redirect_to = '../chemist/alloted15DigitList';
		$message_theme = 'success';


		$this->set('message_theme',$message_theme);
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);
		if (!empty($message)) {$this->render('/element/message_boxes');}
	}


	//function to create view of pdf document for application for replica
	public function replicaApplicationPdfView(){

		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		$this->generateReplicaApplicationPdf();
	}

	//generate application pdf
	public function generateReplicaApplicationPdf(){

		$customer_id = $this->Session->read('username');
		//$view = new View($this, false);

		//$view->layout = null;

		$pdf_data = $this->render('/Code15digit/replicaApplicationPdfView');

		$pdfinst = new ApplicationformspdfsController();
		$pdfinst->callTcpdf($pdf_data,'I',$customer_id,'replica');
	}


//function to get details from replica serial no.
	public function getReplicaNumberDetails($rep_serial_no){

		//not useful in Ecode search.

	}


	//ajax function for search replica and show in popup
	public function searchReplica(){

		$this->autoRender = false;
		$msg = '';
		$rep_ser_no = $_POST['rep_ser_no'];

		//$officeCode = substr($rep_ser_no, 0, 1);
		//$commCode = substr($rep_ser_no, 0, 2);
		$intiPart = substr($rep_ser_no, 0, 6);



		$code15DigitDetails = $this->Dmi15DigitAllotmentDetails->find('all',array('fields'=>array('customer_id','created','commodity'),'conditions'=>array('alloted_rep_from LIKE'=>$intiPart.'%','allot_status'=>1,'delete_status IS Null'),'order'=>'id asc'))->first();

		if(!empty($code15DigitDetails)){


			$firmDetails = $this->DmiFirms->find('all',array('fields'=>array('firm_name'),'conditions'=>array('customer_id IS'=>$code15DigitDetails['customer_id'])))->first();

			//get commodity name

			$getCommodity = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code'=>$code15DigitDetails['commodity'])))->first();

			$msg .= "<tr><td><b>Firm Name:</b></td><td>".$firmDetails['firm_name']."</td></tr>";
			$msg .= "<tr><td><b>Certificate No:</b></td><td>".$code15DigitDetails['customer_id']."</td></tr>";
			$msg .= "<tr><td><b>Commodity:</b></td><td>".$getCommodity['commodity_name']."</td></tr>";
			$msg .= "<tr><td><b>Issued On.</b></td><td>".$code15DigitDetails['created']."</td></tr>";

		}else{

			$msg = "<tr><td>Sorry, The 15-Digit-Code you have searched is not valid.</td></tr>";
		}


		echo '~'.$msg.'~';
		exit;
	}


}



?>
