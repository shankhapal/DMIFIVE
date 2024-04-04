<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
		<h5 class="mt-1 mb-2">Renewal Details</h5>
		<div class="row">
			<div class="col-md-12">
				<div class="card card-success">
					<div class="card-header"><h3 class="card-title">Previous with NABL Details</h3></div>
					<div class="form-horizontal" id="is_accreditated_attached">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-6 col-form-label">Previous NABL Accreditated upto <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('previous_nabl_accreditated_upto', array('type'=>'text', 'id'=>'previous_nabl_accreditated_upto', 'escape'=>false, 'value'=>$section_form_details[1], 'label'=>false, 'placeholder'=>'Select date upto which the NABL is accreditated', 'class'=>'form-control rOnly','readonly'=>true)); ?>
											<span id="previous_error_nabl_accreditated_upto" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-header"><h3 class="card-title">Accreditation with NABL</h3></div>
					<div class="form-horizontal" id="is_accreditated_attached">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Accreditation Number <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('accreditation_no', array('type'=>'text', 'id'=>'accreditation_no', 'escape'=>false, 'value'=>$section_form_details[0]['accreditation_no'], 'label'=>false, 'placeholder'=>'Please Enter accreditation Number', 'class'=>'form-control')); ?>
											<span id="error_accreditation_no" class="error invalid-feedback"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Scope <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('accreditation_scope', array('type'=>'textarea', 'id'=>'accreditation_scope', 'escape'=>false, 'value'=>$section_form_details[0]['accreditation_scope'], 'label'=>false, 'placeholder'=>'Please Enter accreditation Scope', 'class'=>'form-control')); ?>
											<span id="error_accreditation_scope" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">NABL Accreditated upto <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('nabl_accreditated_upto', array('type'=>'text', 'id'=>'nabl_accreditated_upto', 'escape'=>false, 'value'=>$section_form_details[0]['nabl_accreditated_upto'], 'label'=>false, 'placeholder'=>'Select date upto which the NABL is accreditated', 'class'=>'form-control')); ?>
											<span id="error_nabl_accreditated_upto" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<p class="bg-info pl-2 p-1 rounded text-sm">Document for NABL certificate & scope</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['nabl_cert_docs'])){ ?>
												<a id="nabl_cert_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['nabl_cert_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['nabl_cert_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="nabl_cert_docs" class="custom-file-input" id="nabl_cert_docs" multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_nabl_cert_docs" class="error invalid-feedback"></span>
											<span id="error_type_nabl_cert_docs" class="error invalid-feedback"></span>
											<span id="error_size_nabl_cert_docs" class="error invalid-feedback"></span>
										</div>
									</div>
									<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 5 MB</p>
								</div>

								<div class="col-sm-6">
									<p class="bg-info pl-2 p-1 rounded text-sm">Document for APEDA certificate & scope</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['apeda_cert_docs'])){ ?>
												<a id="apeda_cert_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['apeda_cert_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['apeda_cert_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="apeda_cert_docs" class="custom-file-input" id="apeda_cert_docs" multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_apeda_cert_docs" class="error invalid-feedback"></span>
											<span id="error_type_apeda_cert_docs" class="error invalid-feedback"></span>
											<span id="error_size_apeda_cert_docs" class="error invalid-feedback"></span>
										</div>
									</div>
									<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 5 MB</p>
								</div>

								<div class="col-sm-6">
									<p class="bg-info pl-2 p-1 rounded text-sm">Document for Grading details during validity period</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
											<?php if(!empty($section_form_details[0]['grading_details_docs'])){ ?>
												<a id="grading_details_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['grading_details_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['grading_details_docs'])), -1))[0],23);?></a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="grading_details_docs" class="custom-file-input" id="grading_details_docs" multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
											<span id="error_grading_details_docs" class="error invalid-feedback"></span>
											<span id="error_type_grading_details_docs" class="error invalid-feedback"></span>
											<span id="error_size_grading_details_docs" class="error invalid-feedback"></span>
										</div>
									</div>
									<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 5 MB</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<input type="hidden" id="export_unit_status_id" value="<?php echo $export_unit_status; ?>">
<?php echo $this->Html->script('element/application_forms/lab_export_renewal/lab_renewal_details'); ?>
