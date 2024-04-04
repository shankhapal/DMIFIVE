<?php 
// Note :: This file is modified for the compact code , and code refactor also the loops are handled with turnary operators for better readilbilty ,
// So the whole file may seems changed , but worked the same as before : Akash Thakre [18-09-2023]
echo $this->Form->create(null, array()); 
?>
<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title-new">
			List of <?php echo $appl_type; ?> Granted Applications <?php echo (!empty($from_dt) && !empty($to_dt)) ? "from $from_dt to $to_dt" : "for Last 1 Month"; ?>
		</h3>
	</div>

	<div class="card-body">
		<div class="form-group row pd10_0_0">
			<div class="col-md-2">
				<?php echo $this->Form->control('from_dt',array('type'=>'text','id'=>'from_dt','placeholder'=>'From Date','label'=>false,'class'=>'form-control','readonly'=>true)); ?>
			</div>
			<div class="col-md-2">
				<?php echo $this->Form->control('to_dt',array('type'=>'text','id'=>'to_dt','placeholder'=>'To Date','label'=>false,'class'=>'form-control','readonly'=>true)); ?>
			</div>
			<div class="col-md-2">
				<?php echo $this->Form->control('Get Logs',array('type'=>'submit','id'=>'search','label'=>false,'class'=>'btn btn-success')); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
								
	<table id="ho_granted_appl_list" class="table m-0 table-bordered table-striped table-hover">
		<thead class="tablehead">
			<tr>
				<th>Sr. No.</th>
				<th>Cert. Type</th>
				<th>Firm Name</th>
				<th>Applicant Id</th>
				<th>Grant Date</th>
				<th><?php echo (!empty($_SESSION['is_old']) && $_SESSION['is_old'] == 'old') ? 'Old Cert. Pdf' : 'Cert. Pdf'; ?></th>

				<!-- added condition on 16-09-2021, as per new order -->
				<?php if ($appl_type != 'Renewal') { ?>
					<th>Appl. | Pdf</th>
					<th>Report. | Pdf</th>
					<!-- added code/conditions to show btn to generate esigned cert. for old appl. OR show esigned certificate pdf link
						on 20-06-2023 by Amol -->
					<?php if(!empty($_SESSION['is_old']) && $_SESSION['is_old']=='old') { ?>
						<?php if(empty($checkAlreadyOldEsigned)){ ?>
							<th>Action</th>
						<?php }else{ ?>
							<th>Esigned Cert. Pdf</th>
						<?php } ?>
					<?php } 
				
				} else { ?>
					<th>Action</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i=0;
			if (!empty($appl_array)) {

				foreach ($appl_array as $each) { ?>

				<tr>
					<td><?php echo $i+1;?></td>
					<td><?php echo $each['cert_type'];?></td>
					<td><?php echo $each['firm_name'];?></td>			  
					<td id="customer_id<?php echo $i; ?>"><?php echo $each['customer_id'];?></td>
					<td><?php echo $each['grant_date'];?></td>
					<td>
						<?php 
						$cert_pdf_path = explode("/",$each['cert_pdf']);
						$cert_pdf_name = $cert_pdf_path[count($cert_pdf_path) - 1]; 
						?>
						<a id="pdf_link<?php echo $i; ?>" target="_blank" href="<?php echo $each['cert_pdf']; ?>"><?php echo 'Certificate'; ?></a>
					</td>

					<!-- added condition on 16-09-2021, as per new order -->
					<?php if ($appl_type != 'Renewal') { ?>

						<td>
							<?php 
							$appl_pdf_path = explode("/",$each['appl_pdf']);
							$appl_pdf_name = $appl_pdf_path[count($appl_pdf_path) - 1]; 
							?>

							<a target="_blank" href="<?php echo $each['appl_form']; ?>"><?php echo 'View'; ?></a>
							|
							<a target="_blank" href="<?php echo $each['appl_pdf']; ?>"><?php echo 'Pdf'; ?></a>
						</td>
						<td>
							<!-- added below condtion for chemist appl_type 4 to show report N/A by laxmi on 29-05-2023  -->
							<?php if( $appl_type != 'Chemist Approval') { ?>

								<?php 
								$report_pdf_path = explode("/",$each['report_pdf']);
								$report_pdf_name = $report_pdf_path[count($report_pdf_path) - 1];
								?>
								<?php #this below conditin block is added to hide the repoert links if the report isnt filed - Akash[03-02-2023]
								if ($each['report_pdf'] == '1' || $appl_type == 'Surrender' || $appl_type == 'Laboratory Export Renewal') {
									echo "N/A";
								}else{ ?>
									<a target="_blank" href="<?php echo $each['report_form']; ?>"><?php echo 'View'; ?></a>
									|
									<a target="_blank" href="<?php echo $each['report_pdf']; ?>"><?php echo 'Pdf'; ?></a>
								<?php } ?>
								
							<?php } else { ?>
								<p>N/A</p>
							<?php } ?>
							<!-- End Laxmi B. -->
						</td>
						
						<!-- added code/conditions to show btn to generate esigned cert. for old appl. OR show esigned certificate pdf link on 20-06-2023 by Amol -->
						<?php if(!empty($_SESSION['is_old']) && $_SESSION['is_old']=='old') { ?>
							
							<?php if(empty($checkAlreadyOldEsigned)){ ?>
								<?php if($each['show_gen_old_cert_btn']=='yes'){ ?>
									<td><a href="#" id="gen_old_cert_btn<?php echo $i; ?>" class="btn btn-primary">Get Esigned Certificate</a></td>
								<?php }else{ ?>
									<td>Renewal Inprocess OR Cert. Available</td>
								<?php } ?>
							<?php }else{ ?>
								<td>
									<a target="_blank" href="<?php echo $each['AlreadyOldEsigned']; ?>"><?php echo 'Esigned Cert.'; ?></a>
								</td>
							<?php } ?>
						<?php } ?>
			

				<?php } elseif ($appl_type == 'Renewal') {
					if ($each['show_esign_btn']=='yes') { ?> 
						<td><a href="#" id="renewal_esign_btn<?php echo $i; ?>" class="btn btn-primary">Esign Certificate</a></td>
					<?php } elseif ($each['show_esign_btn']=='No Grant Role') { ?>
						<?php if ($each['status'] == 'esigned') { ?>
							<td><strong>Certificate Esigned</strong></td>
						<?php } else { ?>
							<td><strong>No Grant Role</strong></td>
						<?php } ?>
					<?php } else { ?>
						<td><strong>Certificate Esigned</strong></td>
					<?php }
				} ?>
			</tr>
			<?php	$i=$i+1; } } ?>
		</tbody>
	</table>
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->element('esign_views/renewal_grant_declaration_message'); ?>
<!-- added new script call on 20-06-2023 by Amol, for old appl cert. esign -->
<?php echo $this->element('esign_views/generate_cert_for_old_esign_popup'); ?>
<input type="hidden" id="i-value" value="<?php echo $i; ?>">
<?php echo $this->Html->script('dashboard/granted_appl_list-js'); ?>
