<label class="col-md-3 col-form-label">Laboratory Type <span class="cRed">*</span></label>
<div class="col-md-7">
	<?php echo $this->Form->control('laboratory_type', array('type'=>'text', 'id'=>'laboratory_type','label'=>false,'placeholder'=>'Enter Laboratory Type Here', 'value'=>$record_details['laboratory_type'],'class'=>'form-control')); ?>	
	<span id="error_laboratory_type" class="error invalid-feedback"></span>
</div>