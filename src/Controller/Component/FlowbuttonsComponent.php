<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;	
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;

/**
 * Description of FlowbuttonComponent
 *
 * @author Acer
 */
class FlowbuttonsComponent extends Component {
    public $components= array('Session','Customfunctions');
    public $controller = null;
    public $session = null;
		

    public function initialize(array $config): void{
            parent::initialize($config);
            $this->Controller = $this->_registry->getController();
            $this->Session = $this->getController()->getRequest()->getSession();
					
    }

	//Get Forward Button display status after inspection
	public function ShowNodalLevelForwardBtnAfterInsp($customerId,$applicationType,$sectionDetails,$allSectionDetails){
		
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customerId);
		
		$ForwarBtn = null;
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customerId);
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customerId);
		$firm_type = $this->Customfunctions->firmType($customerId);
		$office_type = $this->Customfunctions->getApplDistrictOffice($customerId);
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_ho_allocation_model = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($applicationType,'ho_level_allocation');
		$Dmi_allocation_model = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($applicationType,'allocation');
		
		$Dmi_ho_allocation = TableRegistry::getTableLocator()->get($Dmi_ho_allocation_model);			
		$check_ho_allocation = $Dmi_ho_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customerId, $grantDateCondition)))->first();
			
		$Dmi_allocation = TableRegistry::getTableLocator()->get($Dmi_allocation_model);			
		$check_allocation = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customerId, 'level_4_ro IS NOT NULL',$grantDateCondition)))->first();
		
		$inspection_report_section = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$all_report_status = $inspection_report_section->reportSectionApproveStatus($customerId,$allSectionDetails);
		
		$hoInspectionExist = $this->HoInspectionExist($customerId);
		$showForwardBtn = $sectionDetails['forward_btn'];
		
		if(!empty($showForwardBtn) && $all_report_status == 'true'){
			
			if($applicationType==1 || $applicationType==3){//added $applicationType==3 on 13-04-2023
				
				if($office_type == 'RO' && $hoInspectionExist=='yes' && empty($check_ho_allocation)){
					
					$ForwarBtn = 'HO';
					
				}elseif($office_type == 'SO' && empty($check_allocation)){
					
					if($firm_type !=1){
						
						$ForwarBtn = 'RO';
						
					}elseif($firm_type ==1 /*&& $ca_bevo_applicant=='yes'*/){//commented CA BEVO condition on 23-09-2021 by Amol, CA need to forward to RO (for approval or Grant)
						
						//condition added on 01-02-2023 by Amol, 
						//to hide forward button if appl is CA Non BEVO and SO office have multiple officer
						if ($firm_type ==1 && $ca_bevo_applicant=='yes') {							
							$ForwarBtn = 'RO';							
						
						} else {							
							$username = $_SESSION['username'];
							$officerCount = $this->Customfunctions->findOfficerCountInoffice($username);//get officer count in office
							//if single officer in office then need forward, else can grant
							if ($officerCount <= 1) {
								$ForwarBtn = 'RO';
							}
						}
						
					}
				}
				
			}elseif($applicationType==2){
				
				if($office_type == 'SO' && empty($check_allocation)){
					
					if($firm_type !=1){
						
						$ForwarBtn = 'RO';
						
					}elseif($firm_type ==1 && $ca_bevo_applicant=='yes'){
						
						$ForwarBtn = 'RO';
					}				
				}
				
			}elseif($applicationType==3){
				//commented on 13-04-2023
				/*if($office_type == 'SO'){
					
					if($firm_type ==2){
						
						$ForwarBtn = 'RO';
					}
				}*/
			}elseif($applicationType == 5){//added on 18-11-2021 for 15 digit flow

				if($office_type == 'SO'){
					$ForwarBtn = 'RO';
				}

			}elseif($applicationType == 6){//added on 19-11-2021 for E-Code

				if($office_type == 'RO' && $hoInspectionExist=='yes' && empty($check_ho_allocation)){
					
					$ForwarBtn = 'HO';
					
				}elseif($office_type == 'SO' && empty($check_allocation)){
					
					$ForwarBtn = 'RO';
				}

			}
		}
		
		return $ForwarBtn;
	}

	//Get Grant Button display status after inspection
	public function ShowNodalLevelGrantBtnAfterInsp($customerId,$applicationType,$sectionDetails,$allSectionDetails){
		
		$GrantBtn = null;
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_ama_approved_model = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($applicationType,'ama_approved_application');
		$Dmi_ama_approved_application = TableRegistry::getTableLocator()->get($Dmi_ama_approved_model);

		//added on 24-04-2023
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customerId,$applicationType);
		//updated condition on 24-04-2023 for "grantdatecondition" on ama approved records
		$check_ama_approval = $Dmi_ama_approved_application->find('all',array('conditions'=>array('customer_id IS'=>$customerId,$grantDateCondition)))->first();
		
		$inspection_report_section = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$all_report_status = $inspection_report_section->reportSectionApproveStatus($customerId,$allSectionDetails);
		
		$hoInspectionExist = $this->HoInspectionExist($customerId);
		$showGrantBtn = $sectionDetails['final_grant_btn'];
		
		$office_type = $this->Customfunctions->getApplDistrictOffice($customerId);
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customerId);
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customerId);
		$firm_type = $this->Customfunctions->firmType($customerId);
		
		if(!empty($showGrantBtn) && $all_report_status == 'true'){
			// $applicationType == 5 added for 15-digit code application  Grant 
			// added by shankhpal shende on 16/06/2023
			//$application_type = 6 added on 20-10-2023 by Amol
			if($applicationType==1 || $applicationType==3 || $applicationType == 5 || $applicationType == 6){//added $applicationType == 3 on 13-04-2023
				
				if($office_type=='RO'){
					
					if(!empty($check_ama_approval) && $hoInspectionExist=='yes'){
						
						$GrantBtn = 'yes';
						
					}elseif($hoInspectionExist=='no'){
						
						$GrantBtn = 'yes';
					}
					
				}elseif($office_type=='SO'){
					
					//commented condition on 23-09-2021 by Amol, CA need to forward to RO (for approval or Grant)
					/*if($firm_type ==1 && $ca_bevo_applicant!='yes'){
						
						$GrantBtn = 'yes';
					}*/
					
					//condition applied on 01-02-2023 by Amol,
					//to show grant button if appl is CA Non BEVO and SO officer have multiple officer
					if($firm_type ==1 && $ca_bevo_applicant!='yes'){
						
						$username = $_SESSION['username'];
						$officerCount = $this->Customfunctions->findOfficerCountInoffice($username);//get officer count in office
						
						//if appl CA Non BEVO and Multiple officers in office then can grant without RO approval
						if ($officerCount > 1) {
							$GrantBtn = 'yes';
						}
						
					}
				}
				
			}elseif($applicationType==2){
				
				if($office_type=='RO'){
					
					$GrantBtn = 'yes';
					
				}elseif($office_type=='SO'){
					
					if($firm_type ==1 && $ca_bevo_applicant!='yes'){
						
						$GrantBtn = 'yes';
					}
				}
			}
			//commented on 13-04-2023
			/*elseif($applicationType==3){
				
				if($office_type=='RO'){
					
					$GrantBtn = 'yes';
					
				}elseif($office_type=='SO'){
					
					if($firm_type !=2 ){
						
						$GrantBtn = 'yes';
					}
				}
				
			
			}*/
			//added condition n 08-08-2023 by Amol
			//to show grant button to RO for RO jurisdiction FDC appl.
			elseif($applicationType==5){
				
				if($office_type=='RO'){
					
					$GrantBtn = 'yes';
					
				}
			}
			
			
		}
		
		return $GrantBtn;
	}
	
	//Get Accept Button display status after inspection
	public function ShowNodalLevelAcceptBtnAfterInsp($customerId,$applicationType,$sectionDetails,$allSectionDetails){
		
		$AcceptBtn = null;
		$inspection_report_section = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$all_report_status = $inspection_report_section->reportSectionApproveStatus($customerId,$allSectionDetails);
		
		$showAcceptBtn = $sectionDetails['accept_btn'];
		
		if(!empty($showAcceptBtn) && $all_report_status != 'true'){
			
			$AcceptBtn = 'yes';
		}
		
		return $AcceptBtn;
	}
	
	//Get Forward Button display status after scrutinize
	public function ShowNodalLevelForwardBtnAfterScru($customerId,$applicationType,$sectionDetails,$allSectionDetails){
		
		$ForwarBtn = null;

// to fetch all section of chemist application status(if 2 is approved) added by laxmi on 12-01-2023
         $allSectionStatus = $this->Customfunctions->formStatusValue($allSectionDetails,$customerId);
		
		// pravin bhakare 28-09-2021
		if($applicationType != 4 ){

			// if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
			$allSectionStatus = $this->Customfunctions->formStatusValue($allSectionDetails,$customerId);	
			$firm_type = $this->Customfunctions->firmType($customerId);
			$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customerId);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customerId);
			$form_type = $this->Customfunctions->checkApplicantFormType($customerId);
			
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customerId);
			
			
			
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_allocation_model = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($applicationType,'allocation');
			$Dmi_allocation = TableRegistry::getTableLocator()->get($Dmi_allocation_model);			
			$check_allocation = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customerId, 'level_2 IS NULL', 'level_4_ro IS NULL', $grantDateCondition)))->first();
					
			if($applicationType == 3 && $allSectionStatus == 2){
				
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customerId);
				$inspection = $this->Customfunctions->inspRequiredForChangeApp($customerId,$applicationType);
				
				if($office_type == 'SO'){
					
					if($form_type == 'C'){
						
						$ForwarBtn = 'RO';					
					
					}if($inspection == 'no' && $firm_type ==2){
						
						$ForwarBtn = 'RO';
						
					//to check if CA with BEVO then forward to RO, added on 24-05-2023 by Amol
					}if($inspection == 'no' && $firm_type ==1 && $ca_bevo_applicant == 'yes'){
						$ForwarBtn = 'RO';										
					}
					
				}elseif($office_type == 'RO'){
					
					if($form_type == 'C'){
						
						$ForwarBtn = 'HO';					
					}
				}
			
			}elseif($applicationType == 2 && $allSectionStatus == 2){
				
				if($office_type == 'SO'){
					
					if($form_type == 'C'){
						
						$ForwarBtn = 'RO';
						
					}elseif(!empty($check_allocation) && $firm_type ==3){
						
						$ForwarBtn = 'RO';
						
					}elseif(!empty($check_allocation) && $firm_type ==1 && $ca_bevo_applicant == 'yes'){
						
						$ForwarBtn = 'RO';
					}
					
				}elseif($office_type == 'RO'){
					
					if($form_type == 'C'){
						
						$ForwarBtn = 'HO';					
					}
				}			
				
			}elseif($applicationType == 1 && $allSectionStatus == 2){
				
				if($office_type == 'SO'){
					
					if($form_type == 'C'){
						
						$ForwarBtn = 'RO';					
					}
					
				}elseif($office_type == 'RO'){
					
					if($form_type == 'C'){
						
						$ForwarBtn = 'HO';					
					}
				}
			}

		}
		
		return $ForwarBtn;
	}
	
	//Get Grant Button display status after scrutinize
	public function ShowNodalLevelGrantBtnAfterScru($customerId,$applicationType,$sectionDetails,$allSectionDetails){
		
		$GrantBtn = null;
//fetch all section details status (if 2 is approved) added by laxmi On 12-01-23
        $allSectionStatus = $this->Customfunctions->formStatusValue($allSectionDetails,$customerId);
		
		// pravin bhakare 28-09-2021
		if($applicationType != 4 ){
		
			// if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
			$allSectionStatus = $this->Customfunctions->formStatusValue($allSectionDetails,$customerId);	
			$firm_type = $this->Customfunctions->firmType($customerId);
			$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customerId);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customerId);
			
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customerId);
			
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_allocation_model = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($applicationType,'allocation');
			$Dmi_allocation = TableRegistry::getTableLocator()->get($Dmi_allocation_model);			
			$check_allocation = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customerId, 'level_2 IS NULL', $grantDateCondition)))->first();
		
			if($applicationType == 2 && $allSectionStatus == 2){
				
				if($office_type == 'SO'){
					
					if(!empty($check_allocation) && $firm_type ==1 && $ca_bevo_applicant != 'yes'){
						
						$GrantBtn = 'yes';
					}				
					
				}if($office_type == 'RO'){
					
					if(!empty($check_allocation) && $firm_type !=2 ){
						
						$GrantBtn = 'yes';
					}
					
				}
				
			}if($applicationType == 3 && $allSectionStatus == 2){
				
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customerId);
				$inspection = $this->Customfunctions->inspRequiredForChangeApp($customerId,$applicationType);
				
				if($office_type == 'SO'){
					
					//added CA Bevo condition on 24-05-2023 by Amol
					if($inspection == 'no' && $firm_type ==1 && $ca_bevo_applicant != 'yes'){
						
						$GrantBtn = 'yes';
					}
					
				}if($office_type == 'RO'){
					
					if($inspection == 'no' && ($firm_type ==1 || $firm_type ==2 || $firm_type ==3)){
						
						$GrantBtn = 'yes';
					}
					
				}
			
			}

			//This below Block of code is added to show the Final Grant Button after scrutiny for surrender flow - Akash[13-04-2023]
			if($applicationType == 9 && $allSectionStatus == 2){
				
				$inspection = $this->Customfunctions->inspRequiredForChangeApp($customerId,$applicationType);
				
				if($office_type == 'SO'){
					
					if($inspection == 'no' && $firm_type ==1 ){
						
						$GrantBtn = 'yes';
					}
					
				}if($office_type == 'RO'){
					//added the firm_type 3 on the 05-12-2023 
					if($inspection == 'no' && ($firm_type ==1 || $firm_type ==2 || $firm_type ==3)){
						
						$GrantBtn = 'yes';
					}
					
				}
			
			}

		//else condtion added by laxmi to show grant btn after ro side training completed on 12-01-23
		}elseif((!empty($_SESSION['is_training_completed']) && !empty($_SESSION['trainingCompleteAtRo'])) && ($applicationType == 4 && $allSectionStatus == 2 && $_SESSION['is_training_completed'] == 'no' && $_SESSION['trainingCompleteAtRo'] == 1)){

			$firm_type = $this->Customfunctions->firmType($customerId); 
			$office_type = $this->Customfunctions->getApplDistrictOffice($customerId);
	
			$inspection = $this->Customfunctions->inspRequiredForChangeApp($customerId,$applicationType);

			if($office_type == 'RO'){
					
				if($inspection == 'no' && $firm_type ==1){
					
					$GrantBtn = 'yes';
				}
					
			}elseif($office_type = 'SO'){
	 
				if($inspection == 'no' && $firm_type ==1){
					
					$GrantBtn = 'yes';
				}
	 
			}
        }
		
		return $GrantBtn;
	}
	
	//Check HO ispection is available or not for current application
	public function HoInspectionExist($customerId){
		
		$firm_type = $this->Customfunctions->firmType($customerId);
		$HoInspectionExist = 'no';
		
		$DmiPrintingFirmProfiles = TableRegistry::getTableLocator()->get('DmiPrintingFirmProfiles');
		$DmiPrintingUnitDetails = TableRegistry::getTableLocator()->get('DmiPrintingUnitDetails');//added on 09-06-2021 by Amol
		
		$printingFirmProfileDetails = $DmiPrintingFirmProfiles->sectionFormDetails($customerId);
		$printingBusinessYear = $printingFirmProfileDetails[0]['business_years'];
		
		//added on 09-06-2021 by Amol, for checking fabrication unit is own or tie up with other unit
		$printingUnitDetails = $DmiPrintingUnitDetails->sectionFormDetails($customerId);
		$fabricationUnit = $printingUnitDetails[0]['proper_fabrication'];
			
		$DmiPrintingInspectionDetails = TableRegistry::getTableLocator()->get('DmiPrintingSiteinspectionReports');
		$printingInspectionDetails = $DmiPrintingInspectionDetails->sectionFormDetails($customerId);		
		$PackerPrintingUnit = $printingInspectionDetails[0]['is_press_authorised'];
		
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customerId);
		
		$applicationType = $_SESSION['application_type'];
		
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');		
		$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$applicationType)))->first();
		
		$ho_allocation_table = $flow_wise_table['ho_level_allocation'];
		
		$hoAllocationTable = TableRegistry::getTableLocator()->get($ho_allocation_table);	
		
		$ho_allocation = $hoAllocationTable->find('all',array('conditions'=>array('customer_id IS'=>$customerId)))->first();
		
		//if(empty($ho_allocation)){
			
			if($applicationType == 1 || $applicationType == 3){//added appl type 3 condition on 13-04-2023 by Amol
				
				if($firm_type==2){

					//added below line on 14-12-2023 by Amol to use in below cond.
					$isPressSponsored = $printingInspectionDetails[0]['is_press_sponsored'];
					
					//changed conditions on 09-06-2021 by Amol, added two new conditions to forward PP appl to HO
					//added new cond. '$isPressSponsored=='no'' which required with '$PackerPrintingUnit' to show/hide forward to HO btn on 14-12-2023 by Amol
					if($printingBusinessYear <= 3 || ($isPressSponsored=='no' && $PackerPrintingUnit=='yes') || $fabricationUnit=='no'){
				
						$HoInspectionExist = 'yes';
						
					}
	
				}elseif($firm_type==3){
					
					$HoInspectionExist = 'yes';
					
				}
			//commented this condition on 18-05-2021 by Amol
			//now the CA Bevo will not be forwarded to HO, and grant by SO/RO only
			
			//uncommented below code on 10-10-2022 as per new order on 10-10-2022
			//that the CA BEVO application should approved through HO
				elseif($ca_bevo_applicant == 'yes' && $firm_type==1){
					
					$HoInspectionExist = 'yes';
					
				}
			
			//for E-Code application, HO approval required
			//added on 22-11-2021 by Amol
			//For Appeal HO approval required - Joshi, Akash 08-Aug-2023
			}elseif($applicationType == 6 || $applicationType == 12){

				$HoInspectionExist = 'yes';
			}		
		//}
		
		return $HoInspectionExist;
	}

		
    
}
