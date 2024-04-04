<label class="col-md-3 col-form-label">Business Type <span class="cRed">*</span></label>
<div class="col-md-7">
	<?php echo $this->Form->control('business_type', array('type'=>'text', 'id'=>'business_type','placeholder'=>'Enter Business Type Here','label'=>false, 'value'=>$record_details['business_type'],'class'=>'form-control')); ?>	
	<span id="error_business_type" class="error invalid-feedback"></span>
</div>