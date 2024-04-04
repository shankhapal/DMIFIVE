<label class="col-md-3 col-form-label">Document Type <span class="cRed">*</span></label>
<div class="col-md-6">
	<?php echo $this->Form->control('document_name', array('type'=>'text', 'id'=>'document_name','value'=>$record_details['document_name'],'label'=>false, 'placeholder'=>'Enter document Name Type Here','class'=>'form-control','required'=>true)); ?>
	<span id="error_document_name" class="error invalid-feedback"></span>
</div>