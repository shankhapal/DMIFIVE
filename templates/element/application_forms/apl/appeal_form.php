<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-dark">
					<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
					<div class="form-horizontal mb-3">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('firm_email_id', array('type'=>'text', 'id'=>'firm_email_id', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); //for email encoding ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'disabled'=>true, 'label'=>false,'class'=>'form-control')); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'disabled'=>true, 'label'=>false, 'class'=>'form-control')); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('firm_mobile_no', array('type'=>'text', 'id'=>'firm_mobile_no', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Phone No.</label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('firm_fax_no', array('type'=>'text', 'id'=>'firm_fax_no', 'escape'=>false, 'value'=>base64_decode($firm_details['fax_no']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="card-header"><h3 class="card-title"> Appeal Form Details </h3></div>
					 
						<div class="form-horizontal">
						<div class="card-body">
							<div class="col-sm-12 mb-3"><i class="fa fa-info-circle"></i> Give the Reason for the Appeal <span class="cRed">*</span>
								<div class="row">
									<div class="col-sm-6 d-inline-block">
										<?php echo $this->Form->control('reason', array('type'=>'textarea', 'id'=>'reason','escape'=>false,'value'=>$section_form_details[0]['reason'],'class'=>'form-control','label'=>false, 'required'=>true)); ?>
										<span id="error_reason" class="error invalid-feedback"></span>
									</div>

									<div class="col-sm-6 d-inline-block">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Supporting doc: <span class="cRed">*</span>
												<?php if(!empty($section_form_details[0]['supported_document'])){?>
													<a id="required_document_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['supported_document']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['supported_document'])), -1))[0],23);?></a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" <?php if(empty($section_form_details[0]['supported_document'])){echo "required";} ?> name="supported_document" class="custom-file-input" id="supported_document", multiple='multiple'>
												<label class="custom-file-label" for="customFile">Choose file</label>
												<span id="error_required_document" class="error invalid-feedback"></span>
												<span id="error_size_required_document" class="error invalid-feedback"></span>
												<span id="error_type_required_document" class="error invalid-feedback"></span>

											</div>
										</div>
										<p class="lab_form_note mt-3 lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</section>
<?php echo $this->Html->script('element/application_forms/surrender/ca_consent_form'); ?>
