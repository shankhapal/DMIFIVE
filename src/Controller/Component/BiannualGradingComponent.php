<?php
namespace app\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class BiannualGradingComponent extends Component {

	public $controller = null;
	public $session = null;

	public function initialize(array $config): void {
		parent::initialize($config);
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();

	}



    // added for biannually grading report module
	// for getting period of biannual
	// written by shankhpal shende on 25/08/2023
	public function computeBiannualPeriod(){

		$currentYear = date('Y');
		$currentMonth = date('m');
		$associative_first_half= array();

		if ($currentMonth >= 4 && $currentMonth <= 9) {
				// Current date is between 1st April and 30th September
				$lastYear = $currentYear - 1;
				$startDate = "Oct-".$lastYear;
				$endDate =  "March-".$currentYear;
				$associative_first_half= array(
					"startDateofAssociativeFH" => "April-".$lastYear,
					"endDateofAssociativeFH" => "Sep-".$lastYear
				);

		} else {
				// Current date is after 30th September, switch to the next period
				$startDate = "April-".$currentYear - 1;
				$endDate = "Sep-".$currentYear - 1;
		}

		$myMap = array(
			"startDate" => $startDate,
			"endDate" => $endDate,
			"associative_first_half" => $associative_first_half
		);


    return $myMap;
	}





    // this function are written by shankhpal shende for bgr module on 06/09/2023
	public function getDetailsReplicaAllotment($customer_id){

		$DmiReplicaAllotmentDetails = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentDetails');

		$last_allotment_counts = $DmiReplicaAllotmentDetails->find('all', array(
			'conditions' => array(
					'customer_id IS' => $customer_id,
					'allot_status' => '1',
					'delete_status IS Null',
					'NOT EXISTS (SELECT 1 FROM dmi_bgr_commodity_reports_addmore bgr WHERE bgr.agmarkreplicafrom = DmiReplicaAllotmentDetails.alloted_rep_from AND bgr.agmarkreplicato = DmiReplicaAllotmentDetails.alloted_rep_to)'
			),
			'order' => 'id asc'
		))->toArray();

		$processedData = array(); // Initialize an array to hold processed data
		if (!empty($last_allotment_counts)) {
			foreach ($last_allotment_counts as $eachdetails) {
					$commodity = $eachdetails['commodity'];
					$grade = $eachdetails['grade'];
					$packaging_material = $eachdetails['packaging_material'];
					$packet_size = $eachdetails['packet_size'];
					$packet_size_unit = $eachdetails['packet_size_unit'];
					$no_of_packets = $eachdetails['no_of_packets'];
					$total_quantity = $eachdetails['total_quantity'];
					$total_label_charges = $eachdetails['total_label_charges'];
					$alloted_rep_from = $eachdetails['alloted_rep_from'];
					$alloted_rep_to = $eachdetails['alloted_rep_to'];
					$grading_lab = $eachdetails['grading_lab'];
					$label_charge = $eachdetails['label_charge'];


					// Processed data for each entry
					$processedData[] = array(
							'commodity' => $commodity,
							'grade' => $grade,
							'packaging_material' => $packaging_material,
							'packet_size' => $packet_size,
							'packet_size_unit' => $packet_size_unit,
							'no_of_packets' => $no_of_packets,
							'total_quantity' => $total_quantity,
							'total_label_charges' => $total_label_charges,
							'alloted_rep_from' => $alloted_rep_from,
							'alloted_rep_to' => $alloted_rep_to,
							'grading_lab' => $grading_lab,
							'label_charge'=>$label_charge,
							'lotno'=>'',
							'datesampling'=>'',
							'dateofpacking'=>'',
							'rpl_qty_quantal'=>'',
							'estimatedvalue'=>'',
							'reportno'=>'',
							'reportdate'=>'',
							'remarks'=>''

					);



			}
		}

		return $processedData;

	}


    // this function are written by shankhpal shende for bgr module on 20-2-2024
	public function bgrAddedTableRecords($customer_id){

		$DmiBgrCommodityReportsAddmore = TableRegistry::getTableLocator()->get('DmiBgrCommodityReportsAddmore');

		$currentPeriodRecord = [];

		if($_SESSION !== 'financialYear'){
			$Perioddata = $this->computeBiannualPeriod();
			$startDate = $Perioddata['startDate'];
			$endDate = $Perioddata['endDate'];
			$financialYear = $startDate . ' - ' . $endDate;
		}else{
			$financialYear = $_SESSION['financialYear'];
		}

		if(isset($financialYear)){
			$dates = explode(" - ", $financialYear);
			$startMonthYear = $dates[0];
			$endMonthYear = $dates[1];

			$subquery = $DmiBgrCommodityReportsAddmore->find()
				->select(['id'])
				// ->distinct(['commodity', 'lotno'])
				->where([
						'customer_id' => $customer_id,
						'delete_status IS NULL',
						'period_from' => $startMonthYear,
						'period_to' => $endMonthYear,
				]);

			$currentPeriodRecord = $DmiBgrCommodityReportsAddmore->find()
				->where(['id IN' => $subquery])
				->toArray();
		}

		return $currentPeriodRecord;
	}




    	// added for biannually grading report module
	// for calculate progressive revenue
	// written by shankhpal shende on 05/09/2023
	public function calculateProgressiveReveneve($customer_id){

        $DmiBgrCommodityReports = TableRegistry::getTableLocator()->get('DmiBgrCommodityReports');

        $time_period_map = $this->computeBiannualPeriod();

        $startDate = $time_period_map['startDate'];
        $endDate = $time_period_map['endDate'];
        $associative_first_half = $time_period_map['associative_first_half'];

        $progressive_revenue = 0;

        if (!empty($associative_first_half)) {

                $startDateofAssociativeFH = $associative_first_half['startDateofAssociativeFH'];
                $endDateofAssociativeFH = $associative_first_half['endDateofAssociativeFH'];

                $associative_first = $DmiBgrCommodityReports
                        ->find()
                        ->select(['total_revenue'])
                        ->where([
                                'customer_id' => $customer_id,
                                'period_from' => $startDateofAssociativeFH,
                                'period_to' => $endDateofAssociativeFH,
                        ])
                        ->order(['id' => 'desc'])
                        ->first();

                $current_period = $DmiBgrCommodityReports
                        ->find()
                        ->select(['total_revenue'])
                        ->where([
                                'customer_id' => $customer_id,
                                'period_from' => $startDate,
                                'period_to' => $endDate,
                        ])
                        ->order(['id' => 'desc'])
                        ->first();

                if (!empty($associative_first)) {
                        $progressive_revenue = $associative_first['total_revenue'] + $current_period['total_revenue'];
                }
                if (!empty($current_period)) {
                    $progressive_revenue += $current_period['total_revenue'];
                }


        }

        return $progressive_revenue;
}



// added for biannually grading report module
	// for check record is available or not
	// written by shankhpal shende on 29/08/2023
	public function bgrReportData($customer_id){

		$DmiBgrCommodityReportsAddmoreTable = TableRegistry::getTableLocator()->get('DmiBgrCommodityReportsAddmore');

		// Find the latest report IDs for the given customer
    $latest_ids = $DmiBgrCommodityReportsAddmoreTable->find()
        ->where(['customer_id' => $customer_id])
        ->order(['id' => 'DESC'])
        ->limit(1)
        ->extract('id')
        ->toArray();

		if (!empty($latest_ids)) {
        $latest_id = reset($latest_ids);
        // Retrieve the report fields using the latest ID
        $report_fields = $DmiBgrCommodityReportsAddmoreTable->get($latest_id);

    } else {
        $report_fields = null;
    }

		return !empty($report_fields) ? 1 : 0;
	}



    // This function are written by shankhpal on 06/09/2023
	// are use to add entry in grant pdf table
	public function bgrGrantTableEntry($customer_id){

		$DmiBgrGrantCertificatePdfsTable = TableRegistry::getTableLocator()->get('DmiBgrGrantCertificatePdfs');
		//check applicant last record version to increment
		$list_id = $DmiBgrGrantCertificatePdfsTable->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

		if(!empty($list_id))
		{
			$max_id = $DmiBgrGrantCertificatePdfsTable->find('all', array('fields'=>'pdf_version', 'conditions'=>array('id'=>max($list_id))))->first();
			$last_pdf_version 	=	$max_id['pdf_version'];

		}else{	$last_pdf_version = 0;	}

		$current_pdf_version = $last_pdf_version+1; //increment last version by 1

		$pdfPrefix = 'BGR-';
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations

		$rearranged_id = $pdfPrefix.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];

		$this->Session->write('pdf_file_name',$rearranged_id.'('.$current_pdf_version.')'.'.pdf');

		$file_path = '/writereaddata/DMI/temp/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';

		$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
		$current_level = 'applicant';

		$folderName = $this->getFolderName($customer_id);

		$file_name = $rearranged_id.'('.$current_pdf_version.')'.'.pdf';
		$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
		$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/applications/'.$folderName.'/';


		if($this->moveFileforbgr($file_name,$source,$destination)==1){

			//changed file path from temp to files
			$file_path = '/writereaddata/DMI/applications/'.$folderName.'/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';
			$Perioddata = $this->computeBiannualPeriod();
			$startDate = $Perioddata['startDate'];
			$endDate = $Perioddata['endDate'];

			$Dmi_app_pdf_record_entity = $DmiBgrGrantCertificatePdfsTable->newEntity(array(

				'customer_id'=>$customer_id,
				'pdf_file'=>$file_path,
				'user_email_id'=>$_SESSION['username'],
				'date'=>date('Y-m-d H:i:s'),
				'pdf_version'=>$current_pdf_version,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s'),
				'status'=>'Granted',
				'period_from' => $startDate,
				'period_to' => $endDate
			));

			$DmiBgrGrantCertificatePdfsTable->save($Dmi_app_pdf_record_entity);

		}

	}


    public function getFolderName($customer_id) {

		$split_customer_id = explode('/', $customer_id);
		$folderName = "";

		switch ($split_customer_id[1]) {
			case 1:
				$folderName = "CA";
				break;
			case 2:
				$folderName = "PP";
				break;
			case 3:
				$folderName = "LAB";
				break;
			default:
				if ($split_customer_id[0] == 'CHM') {
					$folderName = "CHM";
				}
				break;
		}

		return $folderName;
	}


    //function added by shankhpal on 06/09/2023 for BGR module
	public function moveFileforbgr($file_name,$source,$destination){

		// If we copied this successfully, mark it for deletion
		if (copy($source.$file_name, $destination.$file_name)) {
			$delete_path = $source.$file_name;
			unlink($delete_path);
			return true;
		}else{
			//this if condition added on 01-04-2019 by Amol
			//to try the moving of file for 2nd attempt, because many times it was not moved in 1st attempt.
			if (copy($source.$file_name, $destination.$file_name)) {
				$delete_path = $source.$file_name;
				unlink($delete_path);
				return true;
			}else{

				if (file_exists($source.$file_name)) {//added this new condition on 15-01-2020
					return false;
				}
			}
		}
	}

}
