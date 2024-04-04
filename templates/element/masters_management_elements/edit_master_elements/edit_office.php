<div class="col-md-12">


	<!--Added below variable to set the message for dupicate office or 15 digit code on 03-12-2021 by AKASH-->
	<?php if (!empty($duplicate_code_msg)) { echo "<div class='alert alert-danger'>".$duplicate_code_msg."</div>"; } ?>
	
	<div class="row">
		<!-- added conditions on 11-05-2021 for RO/SO office type, if required to change the office type -->
		<?php if ($record_details['office_type']=='RO' || $record_details['office_type']=='SO') { ?>

			<div class="col-md-6">
				<label class="col-form-label">Current Office Type: <span class="cRed">*</span>
					<?php echo $this->Form->control('current_type', array('type'=>'text', 'id'=>'current_type', 'value'=>$record_details['office_type'],'label'=>false,'class'=>'form-control rOnly','readonly'=>true)); ?>
				</label>
			</div>

			<div class="col-md-6">
				<label class="col-form-label">Change Type (if required): <span class="cRed">*</span>
					<?php 
						$options=array('RO'=>'RO','SO'=>'SO');
						$attributes=array('legend'=>false, 'value'=>$record_details['office_type'], 'id'=>'office_type');
						echo $this->form->radio('office_type',$options,$attributes); 
					?>
				</label>
			</div>
			<!-- Else for RAL/CAL office type -->
		<?php } else { ?>

			<div class="col-md-6 mt-3">
				<label class="col-form-label">Office Type: <span class="cRed">*</span></label>
				<?php echo $this->Form->control('office_type', array('type'=>'text', 'value'=>$record_details['office_type'],'placeholder'=>'Enter Office Name','label'=>false,'class'=>'form-control rOnly','readonly'=>true)); ?>
			</div>

		<?php } ?>

		<div class="col-md-6 mt-3">
			<label class="col-form-label">Office Name <span class="cRed">*</span></label>
			<?php echo $this->Form->control('ro_office', array('type'=>'text', 'value'=>$record_details['ro_office'], 'id'=>'ro_office', 'label'=>false,'class'=>'form-control')); ?>
			<span id="error_ro_office" class="error invalid-feedback"></span>
		</div>


		<div class="col-md-6 mt-3">
			<label class="col-form-label">Address <span class="cRed">*</span></label>
			<?php echo $this->Form->control('ro_office_address', array('type'=>'text', 'value'=>$record_details['ro_office_address'], 'placeholder'=>'Enter Office Address','id'=>'ro_office_address', 'label'=>false,'class'=>'form-control')); ?>
			<span id="error_ro_office_address" class="error invalid-feedback"></span>
		</div>

		<!-- to show when office type is RO -->
		<?php if ($record_details['office_type']=='RO' || $record_details['office_type']=='SO') { ?>
			<div class="col-md-6 mt-3">
				<label class="col-form-label">Office District Code <span class="cRed">*</span></label>
				<?php echo $this->Form->control('short_code', array('type'=>'text', 'value'=>$record_details['short_code'], 'label'=>false, 'readonly'=>true,'class'=>'form-control rOnly')); ?>
				<span id="error_short_code" class="error invalid-feedback"></span>
			</div>

			<!--added for Office code for 15-digit code entry on 03-12-2021 by AKASH-->	
			<div class="col-md-6 mt-3" id="replica_code_div">
				<label class="col-form-label">Office Code for 15-Digit Code  <span class="cRed">*</span></label>
				<button type="button" class="badge" data-toggle="modal" data-target="#exampleModalLong">Click to check the Assigned Replica Codes</button>
				<?php echo $this->Form->control('replica_code', array('type'=>'text','readonly'=>true,'placeholder'=>'Enter Office Code for 15-Digit Code','value'=>$record_details['replica_code'], 'id'=>'replica_code', 'class'=>'form-control rOnly', 'label'=>false)); ?>
				<span class="error invalid-feedback" id="error_replica_code"></span>
			</div>
		<?php } ?>

		<div class="col-md-6 mt-3">
		<label class="col-form-label">Phone No.</label>

			<?php echo $this->Form->control('ro_office_phone', array('type'=>'text', 'value'=>$record_details['ro_office_phone'], 'id'=>'ro_office_phone', 'label'=>false,'class'=>'form-control')); ?>
			<span id="error_ro_office_phone" class="error invalid-feedback"></span>
		</div>

		<!-- to show when office type is RAL -->
		<?php if($record_details['office_type']=='RAL'){ ?>

			<div id="ral_email_list" class="col-md-6 mt-3">
			<label class="col-form-label">Officer Email Id <span class="cRed">*</span></label>
				<?php echo $this->Form->control('ral_email_id', array('type'=>'select', 'id'=>'ral_email_id', 'label'=>false, 'options'=>$all_ral_list, 'value'=>$record_details['ro_email_id'],'class'=>'form-control')); ?>
				<span id="error_ral_email_id" class="error invalid-feedback"></span>
			</div>

		<?php } ?>

		<!-- added below RO offices dropdown for SO offices-->
		<?php if($record_details['office_type']=='SO' || $record_details['office_type']=='RO'){ ?>

			<div id="ro_office_list" class="col-md-6 mt-3">
				<label class="col-form-label">RO Office <span class="cRed">*</span></label>
				<?php echo $this->Form->control('ro_office_id', array('type'=>'select', 'id'=>'ro_office_id', 'label'=>false, 'options'=>$ro_office_list, 'value'=>$record_details['ro_id_for_so'],'class'=>'form-control')); ?>
				<span id="error_ro_office_id" class="error invalid-feedback"></span>
			</div>

		<?php } ?>

		<!-- to show when office type is RO -->
		<?php if($record_details['office_type']=='RO' || $record_details['office_type']=='SO'){ ?>

			<!-- Show current ro incharge details -->
			<div class="col-md-12 mt-3">
				<h5 class="alert alert-info">Current In-Charge Details</h5>
				<div class="form-horizontal">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="col-form-label">In-charge Email Id <span class="cRed">*</span></label>
								<?php echo $this->Form->control('ro_email_id', array('type'=>'text', 'value'=>base64_decode($record_details['ro_email_id']), 'label'=>false, 'readonly'=>true,'class'=>'form-control rOnly')); //for email encoding ?>
								<span id="error_ro_email_id" class="error invalid-feedback"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group ">
								<label class="col-form-label">In-charge Name <span class="cRed">*</span></label>
								<?php echo $this->Form->control('incharge_name', array('type'=>'text', 'value'=>$ro_incharge_name, 'label'=>false, 'readonly'=>true,'class'=>'form-control rOnly')); ?>
								<span id="error_incharge_name" class="error invalid-feedback"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group ">
								<label class="col-form-label">In-charge Mobile No.<span class="cRed">*</span></label>
								<?php echo $this->Form->control('incharge_mobile_no', array('type'=>'text', 'value'=>base64_decode($ro_incharge_mobile_no), 'label'=>false, 'readonly'=>true,'class'=>'form-control rOnly')); ?>
								<span id="error_incharge_mobile_no" class="error invalid-feedback"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- to show when office type is RO -->
		<?php if($record_details['office_type']=='RO' || $record_details['office_type']=='SO'){ ?>
			<!--  Reallocated the ro incharge to current ro office  (Done by pravin 01-09-2017)-->
			<div class="col-md-12 mt-3">
				<h5 class="alert alert-success">Reallocate In-charge</h5>
				<div class="form-horizontal">
					<div class="row">
						<div class="col-md-7">
							<label class="col-md-6 col-form-label">All In-charge List</label>
							<?php echo $this->Form->control('ro_name_list', array('type'=>'select', 'options'=>$ro_incharge_name_list, 'label'=>false,'class'=>'form-control')); ?>
							<span id="error_ro_name_list" class="error invalid-feedback"></span>
						</div>
						<div class="col-md-4">
							<?php echo $this->Form->submit('Reallocate In-charge', array('name'=>'ro_reallocate', 'id'=>'ro_reallocate_btn','label'=>false,'class'=>'btn btn-success mt33')); ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<!-- This Below Model is added to show the list for the replica codes - Akash[01-03-2023] -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Replica Codes Assigned</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body h391">
				<table class="table table-striped table-bordered table-sm">
					<thead>
						<tr>
						<th>Office</th>
						<th>Code</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($fdcode as $state => $code) : ?>
						<tr>
							<td><?= $state ?></td>
							<td><?= empty($code) ? '<span class="badge">Not Assigned</span>' : $code ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="master_id_for_office" value="<?php echo $masterId; ?>">
<?php echo $this->Html->script('element/masters_management_elements/edit_master_elements/edit_office'); ?>
