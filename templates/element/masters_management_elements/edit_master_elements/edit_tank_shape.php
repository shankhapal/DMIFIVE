<label class="col-md-3 col-form-label">Tank Shape <span class="cRed">*</span></label>
<div class="col-md-7">
	<?php echo $this->Form->control('tank_shapes', array('type'=>'text', 'id'=>'tank_shapes','label'=>false,'placeholder'=>'Enter Tank Shape Here', 'value'=>$record_details['tank_shapes'],'class'=>'form-control')); ?>	
	<span id="error_tank_shape" class="error invalid-feedback"></span>
</div>