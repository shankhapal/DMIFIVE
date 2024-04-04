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
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities List <span class="cRed">*</span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('types_of_sub_commodities',  array('type'=>'select', 'id'=>'types_of_sub_commodities', 'options'=>$section_form_details[1], 'multiple'=>'multiple', 'escape'=>false,'disabled'=>true, 'label'=>false, 'class'=>'form-control input-field')); ?>
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

					<div class="card-header"><h3 class="card-title">Prerequisites for Surrender</h3></div> 
					<div class="form-horizontal">
						<div class="card-body">		
							<div class="row">
								<div class="col-sm-10 d-inline-block">
									<ol>
										<b>NOTE:</b>
										<li>This is for to request surrender of the Certificate.</li>
										<li>Completely filled form with relevant documents and attached them.</li>
										<li>E-sign the application , if submitted without E-sign in which case a signed and stamped physical form is also to be submitted.</li>
										<li>Packer to agree that surrender to be published in two news papers (National and Local) at the places where his produce is marketed.</li>
										<li>Submission of CA book, balance replica numbers and declaration that he will not pack under Agmark</li>
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
							<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Surrender to be published in two news papers (National and Local)  </p></div>
							<div class="row">
								<div class="col-sm-6 d-inline-block mb-3">
									<?php $is_responsible = $section_form_details[0]['is_surrender_published'];
											if($is_responsible == 'yes'){
												$checked_yes = 'checked';
												$checked_no = '';
											} else {
												$checked_yes = '';
												$checked_no = 'checked';
											}
									?>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_surrender_published" checked="" id="is_surrender_published-yes" value="yes" <?php echo $checked_yes; ?>>
										<label class="marginL9" for="is_surrender_published-yes">Yes</label>
									</div>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_surrender_published" id="is_surrender_published-no" value="no" <?php  echo $checked_no; ?>>
										<label class="marginL9" for="is_surrender_published-no">No</label>
									</div>										
									<span id="error_is_surrender_published" class="error invalid-feedback"></span>
								</div>
					
								<div class="col-sm-6 d-inline-block">
									<div class="form-group row" id="is_surrender_published_docs_block">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Related Document: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['is_surrender_published_docs'])){?>
												<a id="is_surrender_published_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['is_surrender_published_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['is_surrender_published_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										
										<div class="custom-file col-sm-9">
											<input type="file" name="is_surrender_published_docs" class="custom-file-input" id="is_surrender_published_docs", multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_is_surrender_published_docs" class="error invalid-feedback"></span>
											<span id="error_size_is_surrender_published_docs" class="error invalid-feedback"></span>
											<span id="error_type_is_surrender_published_docs" class="error invalid-feedback"></span>
										</div>
										<p class="lab_form_note mt-3 lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
							



							<!-------------------------------------------------------------------------------------------------------------------------------------
								The Below upload and radio button option is commented as the UAT Suggestion provided by the DMI .. That the CA book is irrelevant in 
								Online system . So this options are hide.
								- Akash [12-05-2023]
								<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Is Packer Submitted CA Book? </p></div>
								<div class="row">
									<div class="col-sm-6 d-inline-block mb-3">
										<?php
										/* $is_responsible = $section_form_details[0]['is_cabook_submitted'];
											if($is_responsible == 'yes'){
												$checked_yes = 'checked';
												$checked_no = '';
											} else {
												$checked_yes = '';
												$checked_no = 'checked';
											}
										*/ 
										?>
										<div class="icheck-primary d-inline">
											<input type="radio" name="is_cabook_submitted" checked="" id="is_cabook_submitted-yes" value="yes" <?php // echo $checked_yes; ?>>
											<label class="marginL9" for="is_cabook_submitted-yes">Yes</label>
										</div>
										<div class="icheck-primary d-inline">
											<input type="radio" name="is_cabook_submitted" id="is_cabook_submitted-no" value="no" <?php //echo $checked_no; ?>>
											<label class="marginL9" for="is_cabook_submitted-no">No</label>
										</div>										
										<span id="error_is_cabook_submitted" class="error invalid-feedback"></span>
									</div>
									<div class="col-sm-6 d-inline-block">
										<div class="form-group row" id="is_cabook_submitted_docs_block">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Related Document: <span class="cRed">*</span>
												<?php // if(!empty($section_form_details[0]['is_cabook_submitted_docs'])){ ?>
													<a id="is_cabook_submitted_docs_value" target="blank" href="<?php //echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['is_cabook_submitted_docs']); ?>">
													<? // =$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['is_cabook_submitted_docs'])), -1))[0],23); ?></a>
												<?php //} ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="is_cabook_submitted_docs" class="custom-file-input" id="is_cabook_submitted_docs", multiple='multiple'>
												<label class="custom-file-label" for="customFile">Choose file</label>
												<span id="error_is_cabook_submitted_docs" class="error invalid-feedback"></span>
												<span id="error_size_is_cabook_submitted_docs" class="error invalid-feedback"></span>
												<span id="error_type_is_cabook_submitted_docs" class="error invalid-feedback"></span>
											</div>
											<p class="lab_form_note mt-3 lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
							------------------------------------------------------------------------------------------------------------------------------------------------------>



							<div class="col-sm-12 mb-3"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Is Packer Have applied for the Replica ? </p></div>
							<div class="col-sm-12">
								<div class="col-sm-6 d-inline-block mb-3">
									<?php $is_responsible = $section_form_details[0]['is_ca_have_replica'];
										if($is_responsible == 'yes'){
											$checked_yes = 'checked';
											$checked_no = '';
										} else {
											$checked_yes = '';
											$checked_no = 'checked';
										}
									?>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_ca_have_replica" checked="" id="is_ca_have_replica-yes" value="yes" <?php echo $checked_yes; ?>>
										<label class="marginL9" for="is_ca_have_replica-yes">Yes</label>
									</div>
									<div class="icheck-primary d-inline">
										<input type="radio" name="is_ca_have_replica" id="is_ca_have_replica-no" value="no" <?php  echo $checked_no; ?>>
										<label class="marginL9" for="is_ca_have_replica-no">No</label>
									</div>										
									<span id="error_is_ca_have_replica" class="error invalid-feedback"></span>
								</div>
							</div>

							<div class="col-sm-12" id="is_ca_have_replica_block">
								<div class="row">
									<div class="col-sm-6 d-inline-block mb-3">
										<label for="inputEmail3" class="col-sm-6 col-form-label">Is Packer submitted the balance replica numbers and declaration ?</label>
										<?php $is_responsible = $section_form_details[0]['is_replica_submitted'];
											if($is_responsible == 'yes'){
												$checked_yes = 'checked';
												$checked_no = '';
											} else {
												$checked_yes = '';
												$checked_no = 'checked';
											}
										?>
										<div class="icheck-primary d-inline">
											<input type="radio" name="is_replica_submitted" checked="" id="is_replica_submitted-yes" value="yes" <?php echo $checked_yes; ?>>
											<label class="marginL9" for="is_replica_submitted-yes">Yes</label>
										</div>
										<div class="icheck-primary d-inline">
											<input type="radio" name="is_replica_submitted" id="is_replica_submitted-no" value="no" <?php  echo $checked_no; ?>>
											<label class="marginL9" for="is_replica_submitted-no">No</label>
										</div>										
										<span id="error_is_replica_submitted" class="error invalid-feedback"></span>
									</div>

									<div class="col-sm-6 d-inline-block">
										<div class="form-group row" id="is_replica_submitted_docs_block">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Related Document: <span class="cRed">*</span>
												<?php if(!empty($section_form_details[0]['is_replica_submitted_docs'])){?>
													<a id="is_replica_submitted_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['is_replica_submitted_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['is_replica_submitted_docs'])), -1))[0],23);?></a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="is_replica_submitted_docs" class="custom-file-input" id="is_replica_submitted_docs", multiple='multiple'>
												<label class="custom-file-label" for="customFile">Choose file</label>
												<span id="error_is_replica_submitted_docs" class="error invalid-feedback"></span>
												<span id="error_size_is_replica_submitted_docs" class="error invalid-feedback"></span>
												<span id="error_type_is_replica_submitted_docs" class="error invalid-feedback"></span>
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
	</div>
</section>
<?php echo $this->Html->script('element/application_forms/surrender/ca_consent_form'); ?>