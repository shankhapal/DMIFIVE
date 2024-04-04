<?php if ($soc_final_submit_status == 'approved' && $soc_final_submit_level == 'level_3') { ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5><i class="icon fas fa-info"></i> Please Note !</h5>
				<?php 
					$split_customer_id = explode('/',$_SESSION['username']); 
					if ($split_customer_id[1] == 1) {

						echo "Your Application for Surrender is Approved, and this Certificate of Authorisation is cancelled by the competent authority dated <b>" . htmlspecialchars($isSurrender) . "</b>. 
							Applicant should not grade and mark '<span class=\"badge badge-dark\">" . htmlspecialchars($commoditiesDetails) . "</span>' 
							commodity/ies under AGMARK. If a violation is observed, action shall be taken as per APGM Act and GGM Rule.";

					} elseif ($split_customer_id[1] == 2) {

						echo "Your Application for Surrender is Approved, and this Permission to Printing Press is cancelled by the competent authority dated <b>" . htmlspecialchars($isSurrender) . ".</b>\n\n" . 
						"Applicant should submit the balance printed material and make a declaration that they will not print under Agmark.\n\n" .
						"If a violation is observed, action shall be taken as per APGM Act and GGM Rule.";
	
					} elseif ($split_customer_id[1] == 3) {
						echo "Your Application for Surrender is Approved, and this Approval of Laboratory is cancelled by the competent authority dated <b>" . htmlspecialchars($isSurrender) . "</b>. 
						Laboratory should be issue NOC to associated packer to migrate to another Laboratory for
						commodity/ies under AGMARK. If a violation is observed, action shall be taken as per APGM Act and GGM Rule.";
					}
				?> 
			</div>
		</div>  
	</div>

<?php } elseif ($soc_final_submit_status == 'referred_back') { ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5><i class="icon fas fa-info"></i> Please Note !</h5>
				Your Application for Surrender is referred back from DMI . Please Check and Reply . Go from <b>Apply For -> Surrender</b>. Thank You.
			</div>
		</div>  
	</div>

<?php } elseif ($soc_final_submit_status == 'replied') { ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5><i class="icon fas fa-info"></i> Please Note !</h5>
				Your Application for Surrender is referred back to DMI . Thank You.
			</div>
		</div>  
	</div>


<?php } else {  ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5><i class="icon fas fa-info"></i> Please Note !</h5>
				Your Application for Surrender is successfully submitted to AGMARK. Thank You.
			</div>
		</div>  
	</div>

<?php } ?>

