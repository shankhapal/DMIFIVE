<?php
	$customer_id = $_SESSION['username'];

	$show_grant_table = null;

	//check if primary application approved
	if ($final_submit_status == 'approved' && $final_submit_level == 'level_3') {
		
		//check if old application
		if ($is_already_granted == 'yes') {

			//check if old application online renewal granted
			if ($renewal_final_submit_status == 'approved' && $renewal_final_submit_level == 'level_3') {
				$show_grant_table = 'yes';
			} else {
				$show_grant_table = 'no';
			}

		} else { //if new application

			$show_grant_table = 'yes';
		}

	}
?>
<div class="content-wrapper">
	<section id="applicanthome" class="content">
		<div class="applhome container-fluid">

			<!-- This new block is added on 28-04-2023 to show In-process Application Message by Amol -->
			<?php if (!empty($InprocessMsg)) { ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-light">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<?php echo "<b> Note : ". $InprocessMsg ."</b>"; ?>
						</div>
					</div>
				</div>

			<?php } ?>

			<div id="accordion">
				<div class="card bsc">
					<div class="card-header" id="headingOne">
						<h5 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							Information
							</button>
						</h5>
					</div>

					<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
						<div class="card-body">
							
							<?php 
								//this if cond. is added under MSEO updates, to show BEVO to MSEO on renewal msg.
								if(!empty($checkMseologs)){ ?>
								<div class="alert alert-info alert-dismissible">
									<h5><i class="icon fas fa-info"></i> Please Note !</h5>
									<p>Regarding MSEO commodity updates.<br>
										The last certificate was granted with "Blended Edible Vegetable Oil (BEVO)" commodity.<br>
										The renewal of certificate will be granted with "Multi Source Edible Oil (MSEO)" commodity as of now.
									</p>
								</div>
							<?php }
							
										#check the application is surrendered . if it is surrender block all the things on Secondary Dashboard
										if ($soc_final_submit_status == 'approved' && $soc_final_submit_level == 'level_3') {
											echo $this->element('customer_elements/dash_messages/surrender_appl_msg');
										} else {

											#For Advance Payment - Dashboard Messages
											if (!empty($advance_payment_status)) {
												echo $this->element('customer_elements/dash_messages/advance_payment');
											}

											#For Surrender - Dashboard Messages & Application PDF
											if ($soc_final_submit_status != 'no_final_submit') {
												echo $this->element('customer_elements/dash_messages/surrender_appl_msg');
											}

											#For New or Old Applications - Messages
											if ($final_submit_status == 'no_final_submit') {
												echo $this->element('customer_elements/dash_messages/new_or_old');
											} else {

												if ($is_already_granted == 'no') {

													if($is_appl_rejected != null){
														#This Below Block is added to Show the Message when the application is rejected -= Akash [25-11-2022]
														echo $this->element('customer_elements/dash_messages/for_rejected');
														// For Displaying Rejected Application
														echo $this->element('customer_elements/pdf_table_view/application/reject_application');
													}
										
													#For Displaying the Application PDF Table#
													echo $this->element('customer_elements/pdf_table_view/application/general_application');
											
													//For Appeal application pdf table
													if($final_apl_submit_status != 'no_final_submit'){
														echo $this->element('customer_elements/pdf_table_view/application/appeal_application');
													}

													if (empty($renewal_final_submit_status)) {
														//message for new application applied : Akash Thakre [22-09-2023]
														echo $this->element('customer_elements/dash_messages/new_application_applied');
													}



												} elseif (!($final_submit_status == 'approved' && $final_submit_level == 'level_3')) {

													#This Below Block is added to Show the Message when the application is rejected - Akash [25-11-2022]
													if($is_appl_rejected != null){
														echo $this->element('customer_elements/dash_messages/for_rejected');
															// For Displaying Rejected Application - Joshi, Akash
														echo $this->element('customer_elements/pdf_table_view/application/reject_application');
													}else{
														echo $this->element('customer_elements/dash_messages/for_old_appl_saved');
													}
													if($final_apl_submit_status != 'no_final_submit'){
														echo $this->element('customer_elements/pdf_table_view/application/appeal_application');
													}

												}

											}


											# Message to show for the new application is final granted : Akash Thakre [22-09-2023]
											if ($show_grant_table == 'yes') {
												echo $this->element('customer_elements/dash_messages/new_application_grant');
											}



											#To Displaying Message of renewal status
											if ($is_already_granted == 'yes' && $show_grant_table == 'no' && empty($renewal_final_submit_details)) {

												echo $this->element('customer_elements/dash_messages/for_renewal_stats');

												//this condition is added to show table for old appl esigned certificate pdf, if generated by RO/SO
												//on 21-06-2026 by Amol
												if(!empty($checkOldCertEsigned)){
													echo $this->element('customer_elements/pdf_table_view/application/old_form_esigned');
												}

											}

											// Display a message if renewal is applied for and $show_renewal_btn is 'yes'
											if (!empty($renewal_final_submit_status) && $show_renewal_btn == 'yes') {
												echo $this->element('customer_elements/dash_messages/if_renewal_applied');
											}


											if ($show_applied_to_popup == 'yes') {
												echo $this->element('firm_applying_to_view/applying_to_view');
											}
									
								}	
							?>
						</div>
					</div>
				</div>

				<?php if (empty($surrender_grant_certificate)) { ?>

					<?php  if($application_pdfs){ ?>
						<div class="card bsc">
							<div class="card-header" id="headingTwo">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									New Application
									</button>
								</h5>
							</div>
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body">
									<?php
										if ($final_submit_status != 'no_final_submit') {
											#For Displaying the Application PDF Table#
											echo $this->element('customer_elements/pdf_table_view/application/general_application');
										}

										#For Displaying the Grant PDF Table#
										if ($show_grant_table == 'yes') {
											echo $this->element('customer_elements/pdf_table_view/grant/gen_grant');
										}
									?>
								</div>
							</div>
						</div>
					<?php } ?>
					<!--For New / Old Applications by Akash [20-09-2023]-->


					<!--For Renewal Applications by Akash [20-09-2023]-->
					<?php if (!empty($renewal_final_submit_details) || !empty($renewal_application_pdfs)) { ?>

						<div class="card bsc">
							<div class="card-header" id="headingThree">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									Renewal Application
									</button>
								</h5>
							</div>
							<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
								<div class="card-body">
									<?php
										#For Displaying the Renewal Application PDF Table#
										echo $this->element('customer_elements/pdf_table_view/application/renewal_application');
										#For Displaying the Grant PDF Table#
										if ($renewal_final_submit_status == 'approved' && $renewal_final_submit_level == 'level_3') {
											echo $this->element('customer_elements/pdf_table_view/grant/renewal');
										}
									?>
								</div>
							</div>
						</div>

					<?php } ?>

					<!--For 15 Digit Applications-->
					<?php if(!empty($appl_15_digit_pdfs) || !empty($cert_15_digit_pdfs)) { ?>

						<div class="card bsc">
							<div class="card-header" id="headingFive">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
									15-Digit Code Application
									</button>
								</h5>
							</div>
							<div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
								<div class="card-body">
									<?php
										#For 15-Digit Code Application PDF Table View - Amol [2022]
										if(!empty($appl_15_digit_pdfs)) {
											echo $this->element('customer_elements/pdf_table_view/application/fdc_application');
										}

										#For 15-Digit Code Grant PDF Table View - Amol [2022]
										if(!empty($cert_15_digit_pdfs)) {
											echo $this->element('customer_elements/pdf_table_view/grant/fdc_grant');
										}
									?>
								</div>
							</div>
						</div>

					<?php } ?>

					<!--For E Code Applications-->
					<?php if(!empty($appl_e_code_pdfs) || !empty($cert_e_code_pdfs)) { ?>

						<div class="card bsc">
							<div class="card-header" id="headingSix">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
									E-Code Application
									</button>
								</h5>
							</div>
							<div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
								<div class="card-body">
									<?php
										#For E-Code Application PDF Table View - Amol [2022]
										if(!empty($appl_e_code_pdfs)) {
											echo $this->element('customer_elements/pdf_table_view/application/ecode_application');
										}

										#For E-Code Grant PDF Table View - Amol [2022]
										if(!empty($cert_e_code_pdfs)) {
											echo $this->element('customer_elements/pdf_table_view/grant/ecode_grant');
										}
									?>
								</div>
							</div>
						</div>

					<?php } ?>

					<!--For ADP Applications-->
					<?php if(!empty($appl_adp_pdfs_records) || !empty($appl_adp_grant_pdfs)) { ?>

						<div class="card bsc">
							<div class="card-header" id="headingSeven">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
									Approval of Designated Person Application
									</button>
								</h5>
							</div>
							<div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
								<div class="card-body">
									<?php
										#For ADP Application PDF Table View - Shankhpal [18/11/2022]
										if(!empty($appl_adp_pdfs_records)) {
											echo $this->element('customer_elements/pdf_table_view/application/adp_application');
										}

										#For ADP Grant PDF Table View - Shankhpal [18/11/2022]
										if(!empty($appl_adp_grant_pdfs)) {
											echo $this->element('customer_elements/pdf_table_view/grant/adp_grant');
										}
									?>
								</div>
							</div>
						</div>

					<?php } ?>

					<!--For Change Applications-->
					<?php if(!empty($appl_change_records) || !empty($appl_change_grant_pdfs)) { ?>

						<div class="card bsc">
							<div class="card-header" id="headingNine">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
									Change Request Application
									</button>
								</h5>
							</div>
							<div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion">
								<div class="card-body">
									<?php
										if(!empty($appl_change_records)) {
											echo $this->element('customer_elements/pdf_table_view/application/mod_application');
										}

										if(!empty($appl_change_grant_pdfs)) {
											echo $this->element('customer_elements/pdf_table_view/grant/mod_grant');
										}
									?>
								</div>
							</div>
						</div>

					<?php } ?>

					<!--For Routine Applications
					/*Comment: Added as per suggestion:
					Suggestion: One Copy of inspection report needs to be sent to
					packer for information and compliance of shortcomings after submission by inspection Officer.
					Author: Shankhpal Shende
					Date:17/05/2023*/
					-->
					<?php if(!empty($approved_routine_inspection_pdf)) { ?>

						<div class="card bsc">
							<div class="card-header" id="headingTen">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
									Routine Inspection
									</button>
								</h5>
							</div>
							<div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
								<div class="card-body">
									<?php echo $this->element('customer_elements/pdf_table_view/grant/rti_grant'); ?>
								</div>
							</div>
						</div>

					<?php } ?>

				<?php } ?>




				<!--For Surrender Applications by Akash [16-08-2023]-->
				<?php if(!empty($soc_pdfs_records) || !empty($surrender_grant_certificate)) { ?>

					<div class="card bsc">
						<div class="card-header" id="headingFour">
							<h5 class="mb-0">
								<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
								Surrender Application
								</button>
							</h5>
						</div>
						<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
							<div class="card-body">
								<?php
									#For Surrender Application PDF Table View - Akash [2022]
									if(!empty($soc_pdfs_records)) {
										echo $this->element('customer_elements/pdf_table_view/application/surrender_application_pdf');
									}

									#For Surrender Grant PDF Table View - Akash [2022]
									if(!empty($surrender_grant_certificate)) {
										echo $this->element('customer_elements/pdf_table_view/grant/surr_grant');
									}
								?>
							</div>
						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</section>
</div>

<?php echo $this->element('line_track'); ?>