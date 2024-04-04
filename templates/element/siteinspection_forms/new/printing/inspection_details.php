<?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'siteinspection_report', 'enctype'=>'multipart/form-data')); ?>
<h3 class="card-title-new">Printing Firm Site Inspection Report</h3>
<div id="form_outer_main" class="col-md-12 form-middle">
	<div id="form_inner_main" class="card card-success">
		
		<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="tank_table">
					<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Assessed Purpose</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>	Is Assessed for the purpose of Income Tax, Sales Tax etc.?</p>
						<label for="field3">
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'is_assessed_for', 'value'=>$section_form_details[1][0]['have_vat_cst_no'], 'label'=>true,'disabled'=>'disabled');
								echo $this->form->radio('is_assessed_for',$options,$attributes);
							?>
						</label>
					</div>
					<div class="col-md-6">
						<div id="hide_is_assessed_for">
							<div class="form-group row">
								<!--Changed the name before the name was 'assessed_for_gst_no' as it was not defined in the database and not used otherwise so chaged to already added filed named 'assessed_for_tax_no' -> Akash [09-05-2023]-->
								<label class="col-sm-3 col-form-label">GST NO. <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('assessed_for_tax_no', array('type'=>'text', 'value'=>$section_form_details[1][0]['gst_no'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Earlier Permitted</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>	Is the premises earlier permitted for Agmark replica?</p>
						<label for="field3">
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'earlier_permitted', 'value'=>$section_form_details[2][0]['earlier_approved'], 'label'=>true,'disabled'=>'disabled');
								echo $this->form->radio('earlier_permitted',$options,$attributes);
							?>
						</label>
					</div>
					<div class="col-md-6" id="hide_earlier_permitted">
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Reason Of Withdrawal <span class="cRed">*</span></label>
							<div class="col-sm-9">
								<?php echo $this->Form->control('reason_of_withdrawal', array('type'=>'textarea', 'id'=>'reason_of_withdrawal', 'value'=>$section_form_details[0]['reason_of_withdrawal'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter reason of withdrawal','class'=>'form-control')); ?>
								<span id="error_earlier_permitted" class="error invalid-feedback"></span>
								<span id="error_reason_of_withdrawal" class="error invalid-feedback"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Are Machines Requisite ?</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>	Whether the printing press is having the requisite machinery for printing of Agmark replica ?</p>
						<label>
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'machines_requisite', 'value'=>$section_form_details[0]['machines_requisite'], 'label'=>true);
								echo $this->form->radio('machines_requisite',$options,$attributes);
							?>
						</label>
						<div id="error_machines_requisite"></div>
					</div>

					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Give Details <span class="cRed">*</span></label>
							<div class="col-sm-9">
								<?php echo $this->Form->control('machines_requisite_details', array('type'=>'textarea', 'id'=>'machines_requisite_details', 'value'=>$section_form_details[0]['machines_requisite_details'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Give details with number and capacity','class'=>'form-control')); ?>
								<span id="error_machines_requisite_details" class="error invalid-feedback"></span>
							</div>
						</div>
					</div>

					<div class="col-sm-6" id="are_machines_requisite">
						<div class="form-group row">
							<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
								<?php if(!empty($section_form_details[0]['machines_requisite_docs'])){?>
										<a target="blank" id="machines_requisite_docs_value" href="<?php echo $section_form_details[0]['machines_requisite_docs']; ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['machines_requisite_docs'])), -1))[0],23);?></a>
								<?php } ?>
							</label>
							<div class="custom-file col-sm-9">
								<?php  echo $this->Form->control('machines_requisite_docs', array('type'=>'file', 'id'=>'machines_requisite_docs','multiple'=>'multiple', 'label'=>false, 'class'=>'form-control'));  ?>
								<span id="error_machines_requisite_docs" class="error invalid-feedback"></span> 
								<span id="error_size_machines_requisite_docs" class="error invalid-feedback"></span>
								<span id="error_type_machines_requisite_docs" class="error invalid-feedback"></span>
							</div>
						</div>
						<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">In House Storage Facility</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether proper In house storage facilities exists?</p>
						<label>
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'in_house_storage_facility', 'value'=>$section_form_details[0]['in_house_storage_facility'], 'label'=>true);
								echo $this->form->radio('in_house_storage_facility',$options,$attributes);
							?>
						</label>
						<span id="error_in_house_storage_facility" class="error invalid-feedback"></span>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Is Account Maintained ?</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether printing press maintain account for printing orders received?</p>
						<label>
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'account_maintained', 'value'=>$section_form_details[0]['account_maintained'], 'label'=>true);
								echo $this->form->radio('account_maintained',$options,$attributes);
							?>
						</label>
						<span id="error_account_maintained" class="error invalid-feedback"></span>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Fabrication Facilities (for tin containers)</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether fabrication facilities available ?</p>
						<label>
							<?php
								// add new radio button value (by pravin 31/10/2017)
								$options=array('yes'=>'Yes','no'=>'No','n/a'=>'Not Applicable');
								$attributes=array('legend'=>false, 'id'=>'fabrication_facility', 'value'=>$section_form_details[0]['fabrication_facility'], 'label'=>true);
								echo $this->form->radio('fabrication_facility',$options,$attributes);
							?>
						</label>
					</div>
					<div class="col-sm-6"  id="hide_fabrication_facility">
						<p>Upload the details of tie up arrangement</p>	
						<div class="form-group row">
							<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
								<?php if(!empty($section_form_details[0]['fabrication_facility_docs'])){?>
									<a target="blank" id="fabrication_facility_docs_value" href="<?php echo $section_form_details[0]['fabrication_facility_docs']; ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['fabrication_facility_docs'])), -1))[0],23);?></a>
								<?php } ?>
							</label>
							<div class="custom-file col-sm-9">
								<?php echo $this->Form->control('fabrication_facility_docs',array('type'=>'file', 'id'=>'fabrication_facility_docs',   'multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
								<span id="error_fabrication_facility_docs" class="error invalid-feedback"></span>
								<span id="error_size_fabrication_facility_docs" class="error invalid-feedback"></span>
								<span id="error_type_fabrication_facility_docs" class="error invalid-feedback"></span>
							</div>
						</div>
						<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Given Declaration ?</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the press has given declaration of right quality ink and use of food grade material?</p>
							<label for="field3">
								<?php
									$options=array('yes'=>'Yes','no'=>'No');
									$attributes=array('legend'=>false, 'id'=>'declaration_given', 'value'=>$section_form_details[0]['declaration_given'], 'label'=>true);
									echo $this->form->radio('declaration_given',$options,$attributes);
								?>
							</label>
						<div id="error_declaration_given"></div>
					</div>
					<div class="col-sm-6">
						<div class="form-group row">
							<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
								<?php if(!empty($section_form_details[0]['ink_declaration_docs'])){?>
										<a target="blank" id="ink_declaration_docs_value" href="<?php echo $section_form_details[0]['ink_declaration_docs']; ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['ink_declaration_docs'])), -1))[0],23);?></a>
								<?php } ?>
							</label>
							<div class="custom-file col-sm-9">
								<?php echo $this->Form->control('ink_declaration_docs',array('type'=>'file', 'id'=>'ink_declaration_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
								<span id="error_ink_declaration_docs" class="error invalid-feedback"></span> 
								<span id="error_size_ink_declaration_docs" class="error invalid-feedback"></span> 
								<span id="error_type_ink_declaration_docs" class="error invalid-feedback"></span> 
							</div>
						</div>
						<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Is Press Sponsored ?</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the press has sponsored by the authorized packers?</p>
						<label for="field3">
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'is_press_sponsored', 'value'=>$section_form_details[0]['is_press_sponsored'], 'label'=>true);
								echo $this->form->radio('is_press_sponsored',$options,$attributes);
							?>
						</label>
					</div>
					<div class="col-md-6" id="hide_press_authorised">
						<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the press is owned by any Authorised/ Packer’s Printing unit?</p>
						<label class="float-left">
							<?php
								$options=array('yes'=>'Yes','no'=>'No');
								$attributes=array('legend'=>false, 'id'=>'is_press_authorised', 'value'=>$section_form_details[0]['is_press_authorised'], 'label'=>true);
								echo $this->form->radio('is_press_authorised',$options,$attributes);
							?>
						</label>
					</div>
					<div class="col-sm-6" id="hide_press_sponsored">
						<div class="form-group row">
							<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
								<?php if(!empty($section_form_details[0]['press_sponsored_docs'])){?>
								<a target="blank" id="press_sponsored_docs_value" href="<?php echo $section_form_details[0]['press_sponsored_docs']; ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['press_sponsored_docs'])), -1))[0],23);?></a>
								<?php } ?>
							</label>
							<div class="custom-file col-sm-9">
								<?php echo $this->Form->control('press_sponsored_docs',array('type'=>'file', 'id'=>'press_sponsored_docs','multiple'=>'multiple', 'label'=>false, 'class'=>'form-control'));  ?>
								<span id="error_press_sponsored_docs" class="error invalid-feedback"></span>
								<span id="error_size_press_sponsored_docs" class="error invalid-feedback"></span>
								<span id="error_type_press_sponsored_docs" class="error invalid-feedback"></span>
							</div>
						</div>
						<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Remarks</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group row">
							<label  class="col-sm-3 col-form-label">Remarks, if any <span class="cRed">*</span></label>
							<div class="col-sm-9">
								<?php echo $this->Form->control('any_other_point', array('type'=>'textarea', 'id'=>'any_other_point', 'value'=>$section_form_details[0]['any_other_point'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter your points here','class'=>'form-control')); ?>
								<span id="error_any_other_point" class="error invalid-feedback"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header"><h3 class="card-title">Recommendations</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Given recommendations<span class="cRed">*</span></label>
							<div class="col-sm-9">
								<?php echo $this->Form->control('recommendations', array('type'=>'textarea', 'id'=>'recommendations', 'value'=>$section_form_details[0]['recommendations'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Recommendations','class'=>'form-control')); ?>
								<span id="error_recommendations" class="error invalid-feedback"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">
<?php echo $this->Html->script('element/siteinspection_forms/new/printing/inspection_details'); ?>
