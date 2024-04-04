<label class="col-md-3 col-form-label">Division Grade <span class="cRed">*</span></label>
<div class="col-md-7">	
	<?php echo $this->Form->control('division_type', array('type'=>'text', 'id'=>'division_type', 'label'=>false, 'value'=>$record_details['division'],'placeholder'=>'Enter Division Grade','class'=>'form-control')); ?>
	<span id="error_education_type" class="error invalid-feedback"></span>
</div>