<label class="col-md-3 col-form-label">Business Type	<span class="cRed">*</span></label>
<div class="col-md-6">
	<?php echo $this->Form->control('business_type', array('type'=>'text', 'id'=>'business_type','label'=>false, 'placeholder'=>'Enter Business Type Here','class'=>'form-control','required'=>true)); ?>
	<span id="error_business_type" class="error invalid-feedback"></span>
</div>