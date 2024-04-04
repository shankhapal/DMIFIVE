<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Reset Renewal Application</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-10">
					<?php echo $this->Form->create(null,array('class'=>'form-group','id'=>'reset_renewal')); ?>
						<div class="card card-info">
							<div class="card-header"><h3 class="card-title-new">Reset Renewal Application</h3></div>
							<div class="form-horizontal">
								<p class="alert alert-info">
									Note: <br>
									1. This module is available only for renewal applications that were not properly migrated during Phase II and provided in the dropdown list. Its purpose is to facilitate the proper processing of renewals.<br>
									2. For applications pending renewal since Phase 1, where payment has been made to DMI but further processing has not occurred and they could not be tracked, this module will take action. <br>
									3. These applications will be forwarded to the PAO/DDO of the respective application's jurisdiction office. The DDO will then re-verify the application.
								</p>
								<div class="card-body">
									<div class="row">
										<div class="col-md-5">
											<label class="">Application ID: </label>
											<?php echo $this->Form->control('customer_id', array('type'=>'select', 'id'=>'customer_id', 'escape'=>false, 'options'=>$appl_list, 'empty'=>'---Select---', 'label'=>false,'class'=>'form-control')); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div id="firm_details"></div><!--This is For the Firm Details -->
									</div>
								</div>
							</div>
							
							<div class="col-md-6" id="concent">
								<?php echo $this->Form->control('renewal_consent', array('type'=>'checkbox', 'id'=>'renewal_consent', 'label'=>'	I confirm the changes and proceed','escape'=>false)); ?>
							</div>
						
							<div class="card-footer cardFooterBackground mt-2">
								<?php echo $this->Form->submit('Submit', array('name'=>'submit', 'label'=>false,'class'=>'btn btn-success float-left','id'=>'submit')); ?>
								<?php echo $this->Html->link('Back', array('controller' => 'masters', 'action'=>'masters_home'),array('class'=>'add_btn btn btn-secondary float-right')); ?>
							</div>
						</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
	
	<?php if (!empty($all_records)) { ?>
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-10">
						<div class="card card-info">
							<div class="card-header"><h3 class="card-title-new">List of applications taken back to the PAO/DDO for re-verification</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<table class="table table-sm table-bordered" id="reset_table">
											<thead class="tableHead">
												<tr>
													<th>SR.No</th>
													<th>Application ID</th>
													<th>Taken back to</th>
													<th>Previous Position</th>
													<th>Done By</th>
													<th>Date</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$sr_no=1;
													foreach ($all_records as $each_record) { ?>
													<tr>
														<td><?php echo $sr_no; ?></td>
														<td><?php echo $each_record['customer_id'];?></td>
														<td><?php echo base64_decode($each_record['pao_email']); ?></td>
														<td><?php echo base64_decode($each_record['last_current_position_email']); ?></td>
														<td><?php echo base64_decode($each_record['done_by']); ?></td>
														<td><?php echo $each_record['created']; ?></td>
													</tr>
												<?php $sr_no++; }  ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } ?>
</div>



<?php echo $this->Html->script('othermodules/renewal_application_reset'); ?>
