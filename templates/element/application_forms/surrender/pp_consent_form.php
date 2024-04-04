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

					<div class="card-header"><h3 class="card-title">Surrender Reason Details</h3></div> 
					<div class="form-horizontal">
						<div class="card-body">		
							<div class="row">
								<div class="col-sm-10 d-inline-block">
									<ol>
										<b>NOTE:</b>
										<li>This is for to request surrender of the permission of printing.</li>
										<li>Completely filled form with relevant documents and attached them.</li>
										<li>E-sign the application , if submitted without E-sign in which case a signed and stamped physical form is also to be submitted.</li>
										<li>Submission of balance printed material and declaration that he will not print under Agmark.</li>
										<li>Associated packers must be conveyed.</li>
									</ol>
								</div>
							</div>
						</div>
					</div>

					<div class="card-header"><h3 class="card-title"> Reason for Surrender</h3></div> 
					<div class="form-horizontal">
						<div class="card-body">		
							<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Give the Reason for the Surrender   </p>
								<div class="row">
									<div class="col-sm-6 d-inline-block">
										<?php echo $this->Form->control('reason', array('type'=>'textarea', 'id'=>'reason','escape'=>false,'value'=>$section_form_details[0]['reason'],'class'=>'form-control','label'=>false)); ?>
										<span id="error_reason" class="error invalid-feedback"></span>
									</div>
								
									<div class="col-sm-6 d-inline-block">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Undertaking doc: <span class="cRed">*</span>
												<?php if(!empty($section_form_details[0]['required_document'])){?>
													<a id="required_document_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['required_document']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['required_document'])), -1))[0],23);?></a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="required_document" class="custom-file-input" id="required_document", multiple='multiple'>
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

					<div class="card-header"><h3 class="card-title">Documents and Consents</h3></div> 
					<div class="form-horizontal">
						<div class="card-body">		
							<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Submission of balance printed material.  </p></div>
							<div class="row">
								<div class="col-sm-6 d-inline-block mb-3">
									<?php $is_responsible = $section_form_details[0]['is_balance_printing_submitted'];
											if($is_responsible == 'yes'){
												$checked_yes = 'checked';
												$checked_no = '';
											} else {
												$checked_yes = '';
												$checked_no = 'checked';
											}
									?>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_balance_printing_submitted" checked="" id="is_balance_printing_submitted-yes" value="yes" <?php echo $checked_yes; ?>>
										<label class="marginL9" for="is_balance_printing_submitted-yes">Yes</label>
									</div>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_balance_printing_submitted" id="is_balance_printing_submitted-no" value="no" <?php  echo $checked_no; ?>>
										<label class="marginL9" for="is_balance_printing_submitted-no">No</label>
									</div>										
									<span id="error_is_balance_printing_submitted" class="error invalid-feedback"></span>
								</div>
					
								<div class="col-sm-6 d-inline-block">
									<div class="form-group row" id="is_balance_printing_submitted_docs_block">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Related Document: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['is_balance_printing_submitted_docs'])){?>
												<a id="is_balance_printing_submitted_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['is_balance_printing_submitted_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['is_balance_printing_submitted_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										
										<div class="custom-file col-sm-9">
											<input type="file" name="is_balance_printing_submitted_docs" class="custom-file-input" id="is_balance_printing_submitted_docs", multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_is_balance_printing_submitted_docs" class="error invalid-feedback"></span>
											<span id="error_size_is_balance_printing_submitted_docs" class="error invalid-feedback"></span>
											<span id="error_type_is_balance_printing_submitted_docs" class="error invalid-feedback"></span>
										</div>
										<p class="lab_form_note mt-3 lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						
							<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Declaration of not to print under Agmark. </p></div>
							<div class="row">
								<div class="col-sm-6 d-inline-block mb-3">
									<?php $is_responsible = $section_form_details[0]['printing_declaration'];
										if($is_responsible == 'yes'){
											$checked_yes = 'checked';
											$checked_no = '';
										} else {
											$checked_yes = '';
											$checked_no = 'checked';
										}
									?>
									<div class="icheck-primary d-inline">
										<input type="radio" name="printing_declaration" checked="" id="printing_declaration-yes" value="yes" <?php echo $checked_yes; ?>>
										<label class="marginL9" for="printing_declaration-yes">Yes</label>
									</div>
									<div class="icheck-primary d-inline">
										<input type="radio" name="printing_declaration" id="printing_declaration-no" value="no" <?php  echo $checked_no; ?>>
										<label class="marginL9" for="printing_declaration-no">No</label>
									</div>										
									<span id="error_printing_declaration" class="error invalid-feedback"></span>
								</div>
								<div class="col-sm-6 d-inline-block">
									<div class="form-group row" id="printing_declaration_docs_block">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Related Document: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['printing_declaration_docs'])){?>
												<a id="printing_declaration_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['printing_declaration_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['printing_declaration_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="printing_declaration_docs" class="custom-file-input" id="printing_declaration_docs", multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_printing_declaration_docs" class="error invalid-feedback"></span>
											<span id="error_size_printing_declaration_docs" class="error invalid-feedback"></span>
											<span id="error_type_printing_declaration_docs" class="error invalid-feedback"></span>
										</div>
										<p class="lab_form_note mt-3 lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>

							<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Is Associated packers conveyed ? </p></div>
							<div class="row">
								<div class="col-sm-6 d-inline-block mb-3">
									<?php $is_responsible = $section_form_details[0]['is_packers_conveyed'];
										if($is_responsible == 'yes'){
											$checked_yes = 'checked';
											$checked_no = '';
										} else {
											$checked_yes = '';
											$checked_no = 'checked';
										}
									?>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_packers_conveyed" checked="" id="is_packers_conveyed-yes" value="yes" <?php echo $checked_yes; ?>>
										<label class="marginL9" for="is_packers_conveyed-yes">Yes</label>
									</div>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_packers_conveyed" id="is_packers_conveyed-no" value="no" <?php  echo $checked_no; ?>>
										<label class="marginL9" for="is_packers_conveyed-no">No</label>
									</div>										
									<span id="error_is_packers_conveyed" class="error invalid-feedback"></span>
								</div>
								<div class="col-sm-6 d-inline-block">
									<div class="form-group row" id="is_packers_conveyed_docs_block">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Related Document: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['is_packers_conveyed_docs'])){?>
												<a id="is_packers_conveyed_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['is_packers_conveyed_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['is_packers_conveyed_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="is_packers_conveyed_docs" class="custom-file-input" id="is_packers_conveyed_docs", multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_is_packers_conveyed_docs" class="error invalid-feedback"></span>
											<span id="error_size_is_packers_conveyed_docs" class="error invalid-feedback"></span>
											<span id="error_type_is_packers_conveyed_docs" class="error invalid-feedback"></span>
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
	</div>
</section>
<?php echo $this->Html->script('element/application_forms/surrender/pp_consent_form'); ?>