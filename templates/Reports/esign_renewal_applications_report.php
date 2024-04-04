
<?php echo $this->Html->css('Reports/approved_applications_report'); ?>

	<div class="content-wrapper bg-bg">
		<div class="content-header page-header" id="page-load">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h6 class="m-0 ml-3">
							<!-- <//?php if($approved_application_type == 'renewal') 
							{ echo 'Approved Renewal Applications Report'; } 
							else { echo 'Approved New and Old Applications Report';	} ?> -->
							<?php echo $report_heading;?>	
						</h6>
					</div>
					<div class="col-sm-6 my-auto">
						<ol class="breadcrumb float-sm-right">
							<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
							<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
							<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
								
								<?php echo $report_heading;?>		
								</span>
							</span>
						</ol>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>

	  
		<div class="bg-bg">
			

			<section class="content form-middle">
				<div class="container-fluid border rounded-lg border-light bg-light p-3 shadow">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive report-table-format">
								<table class="table table-sm rounded table-bordered" id="approved_applications_report_table">
									<thead class="table-light">
										<tr class="rounded">
											<th><span class="table-heading">S.No</span></th>
											
											<th><span class="table-heading">Application ID</span></th>
											<th class="text-right"><span class="table-heading">Application Type</th>
											<th><span class="table-heading">Application Form</th>
											<th class="text-right"><span class="table-heading">Approved Office</span></th>
											<!-- <th><span class="table-heading">User ID</span></th> -->
											
											<th class="text-right"><span class="table-heading">Date</span></th>
											<th><span class="table-heading">Time</span></th>
											<th><span class="table-heading">Firm Details</span></th>
											<th><span class="table-heading">TBL Details</span></th>
											<th><span class="table-heading">Laboratory Details</span></th>
										</tr>
									</thead>
									<tbody class="">
										<?php 
											if(!empty($date)) {
												$sr_no = 1 ; 
											}
											for ($i=0; $i<sizeof($date); $i++) { ?>

												<tr id="table_row" class="row-hover border border-light">
													<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
													
													<td><span class="badge subtitle borderless"><?php echo $application_customer_id[$i]; ?></span></td>
													<td class="text-right"><?php $explode_app_type = explode('(',$application_type[$i]); ?>
														<span class="badge title borderless"><?php echo $explode_app_type[0]; ?> </span>
													</td>
													<td><?php $explode_app_type = explode('(',$application_type[$i]); ?>
														<span class="badge sybtitle borderless"><?php $explode_form = explode(')',$explode_app_type[1]);
														echo $explode_form[0]; ?></span>
													</td>
													<td class="text-right"><span class="badge subtitle borderless"><?php echo $user_office[$i]; ?></span></td>
													<!-- <td><span class="badge title borderless"><//?php echo $application_user_email_id[$i]; //for email encoding ?></span></td> -->
													
													<?php $explode_date = explode(' ',$date[$i]); ?>
													<td class="text-right"><?php if($date[$i] == null) { echo $date[$i]; } else { ?>
														<span class="badge title mb-1 borderless"><?php echo $explode_date[0]; } ?></span>
													<td><?php if($date[$i] == null) { echo $date[$i]; } else { ?>
														<span class="badge subtitle subtitle-2 rounded px-1 borderless"><?php echo $explode_date[1]; } ?></span>
													</td>

													<td><span class="badge borderless">Name : <?php echo $name_of_the_firm[$i];?></span> </br>
														<span class="badge borderless">Address : <?php echo $address_of_the_firm[$i];?></span></br>
														<span class="badge borderless">Contact : <?php echo $contact_details_of_the_firm[$i];?></span>
													</td>
													
													<?php if ($approved_TBL_details_tbl_name[$i][0] == '--') { ?>
														<td>--</td>
													<?php }else{ ?>
														<?php $countTbl = count($approved_TBL_details_tbl_name[$i]); ?>

														<td>
															<?php for($j=0;$j<$countTbl;$j++){  ?>
																<span class="badge">TBL Name: <?php echo $approved_TBL_details_tbl_name[$i][$j];?></span>
																</br>
																<span class="badge">TBL Reg No: <?php echo $approved_TBL_details_tbl_registered_no[$i][$j];?></span>
																</br></br>
															<?php } ?>
														</td>

													<?php } ?>

													<?php if ($laboratory_details_name[$i] == '--') { ?>
														<td>--</td>
													<?php }else{ ?>
														<td><span class="badge">Name : <?php echo $laboratory_details_name[$i];?></span>
															</br>
															<span class="badge">Address : <?php echo $laboratory_details_address[$i];?></span>
														</td>
													<?php } ?>
												</tr>
											<?php $sr_no++; }

											if(empty($date)) { ?>
												<tr>
													<td colspan="8" class="fs-4"><?php echo "NO Records Available"; ?></td>
												</tr>
											<?php } ?>
									</tbody>
									
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>

			<div class="ml-3 mt-3">
				<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn" role="button">Back to All Reports</a>
					<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
				</h5>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('Reports/approved_applications_report'); ?>
