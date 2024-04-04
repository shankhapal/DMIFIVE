<label class="col-md-3 col-form-label">Machine Type <span class="cRed">*</span></label>
<div class="col-md-7">
	<?php echo $this->Form->control('machine_types', array('type'=>'text', 'id'=>'machine_types','label'=>false,'placeholder'=>'Enter Machine Type Here', 'value'=>$record_details['machine_types'],'class'=>'form-control')); ?>	
	<span id="error_machine_type" class="error invalid-feedback"></span>
</div>
<div class="col-md-12 mt-3">
	<div class="row">
		<div class="col-md-6">
			<label class="col-form-label">Application Type :	</label>
			<?php 					
				$options=array('ca'=>'CA','printing'=>'Printing');
				$attributes=array('legend'=>false, 'value'=>$record_details['application_type'], 'id'=>'application_type');		
				echo $this->form->radio('application_type',$options,$attributes); 
			?>
			<span id="error_application_type" class="error invalid-feedback"></span>
		</div>
	</div>
</div>