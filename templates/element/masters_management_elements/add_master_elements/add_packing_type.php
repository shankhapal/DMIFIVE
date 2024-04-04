<label class="col-md-3 col-form-label">Packing Type <span class="cRed">*</span></label>
<div class="col-md-7">
	<?php echo $this->Form->control('packing_type', array('type'=>'text', 'id'=>'packing_type','label'=>false, 'placeholder'=>'Enter Packing Type Here','class'=>'form-control','required'=>true)); ?>
	<span id="error_packing_type" class="error invalid-feedback"></span>
</div>