<?php foreach ($is_appl_rejected as $each_record) {

	//Joshi, Akash, Adding below logic to stop alert for those rejected applictions for which appeal 
	//has been raised. [28-08-2023]
	if(!empty($appealMap) && !empty($each_record['appeal_id'])){
	  $appealDetailInfo=$appealMap[$each_record['appeal_id']];
	  $form_status=$appealDetailInfo['form_status'];
	  if($form_status =='referred_back' || (!empty($appealDetailInfo) && !empty($appealDetailInfo['is_final_submitted']) && $appealDetailInfo['is_final_submitted'] =='yes')){
		  continue;
	  }
	} ?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h5><i class="icon fas fa-info"></i> Please Note !</h5>
			<?php
                $date = $each_record['created'];
                $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $date);
                $cancelled_date = $dateTime->format('d/m/Y');
                $appl_type= $each_record['appl_type'];
				if($appl_type == 12){
					$appSpecificMsg = 'The decision regarding the appeal is considered as definitive and binding, and as a result, no further appeals can be initiated to contest this decision. ';
				}
				elseif($appl_type ==1 ){
					$appSpecificMsg ='Applicant can appeal within 30 Days of rejection of their New Application.';	
				}
				else{
					$appSpecificMsg ='';	
				}
				$message = <<<EOD
					Application for $appl_mapping[$appl_type]  is rejected by the competent Agmark Authority
					For the reason of  <b>{$each_record['remark']}</b>
					on dated <b>{$cancelled_date}</b>.
					$appSpecificMsg
					EOD;

				echo $message;
			?>
		</div>
	</div>
</div>
<?php } ?>