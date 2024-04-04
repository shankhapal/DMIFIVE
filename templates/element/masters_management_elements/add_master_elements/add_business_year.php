<div class="col-md-6">
	<div class="form-group">
		<label class="col-form-label">Business Years <span class="cRed">*</span></label>
		<?php echo $this->Form->control('business_years', array('type'=>'text', 'id'=>'business_years','label'=>false, 'placeholder'=>'Enter value Here','class'=>'form-control','required'=>true)); ?>
		<span id="error_business_year" class="error invalid-feedback"></span>
	</div>
</div>
<div class="col-md-6">
	<label class="col-form-label">Select Business Years for: <span class="cRed">*</span></label>
	<?php echo $this->Form->control('business_years_for', array('type'=>'select', 'id'=>'business_years_for','label'=>false, 'options'=>array('CA','Printing Press','Crushing & Refining'),'class'=>'form-control')); ?>
	<span id="error_business_years_for" class="error invalid-feedback"></span>
</div>

<?php echo $this->Html->script('element/masters_management_elements/add_master_elements/add_business_year'); ?>
