<label class="col-md-3 col-form-label">Feedback Type <span class="cRed">*</span></label>
<div class="col-md-7">
	<?php echo $this->Form->control('title', array('type'=>'text', 'id'=>'title','label'=>false, 'value'=>$record_details['title'],'class'=>'form-control', 'required'=>true)); ?>
	<span id="error_title" class="error invalid-feedback"></span>
</div>