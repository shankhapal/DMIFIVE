<h3 class="card-title-new"> Printing Firm Profile</h3>
<div id="form_outer_main" class="col-md-12 form-middle">
	<?php echo $this->Form->create(); ?>
		<div class="card card-success">
			<div class="card-header"><h3 class="card-title">Initial Details</h3></div>
			<div class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('firm_name', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
					
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Firm Status <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('firm_status', array('type'=>'text', 'escape'=>false, 'value'=>$business_type[$section_form_details[0]['business_type']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Firm in Business <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('firm_status', array('type'=>'text', 'escape'=>false, 'value'=>$all_printing_business_year[$section_form_details[0]['business_years']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-header"><h3 class="card-title">Firm Address</h3></div>
			<div class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'value'=>$firm_details['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$firm_details['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$firm_details['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-header"><h3 class="card-title">Premises Address</h3></div>
			<div class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'value'=>$section_form_details[1][0]['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
								<div class="col-sm-9">
								<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$section_form_details[1][0]['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
								<div class="col-sm-9">
								<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$section_form_details[1][0]['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
								<div class="col-sm-9">
								<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer cardFooterBackground">
				<div class="form-buttons">
					<a  class="btn bg-cyan" href="<?php echo $this->request->getAttribute('webroot'); ?>inspections/section/2" >Start Inspection</a>
				</div>
			</div>
	</div>
</div>

<?php echo $this->Html->script('element/siteinspection_forms/new/printing/firm_profile'); ?>
