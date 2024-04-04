<label class="col-md-3 col-form-label">Education Type   <span class="cRed">*</span></label>
<div class="col-md-7">	
	<?php echo $this->Form->control('education_type', array('type'=>'text', 'id'=>'education_type', 'label'=>false, 'value'=>$record_details['edu_type'],'placeholder'=>'Enter Eduaction Type','class'=>'form-control')); ?>
	<span id="error_education_type" class="error invalid-feedback"></span>
</div>