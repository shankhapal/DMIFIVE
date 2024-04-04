<?php
	echo $this->Html->css('../multiselect/jquery.multiselect');
	echo $this->Html->script('../multiselect/jquery.multiselect');
?>

<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<label class="col-form-label">PAO/DDO Email ID <span class="cRed">*</span></label>
			<?php echo $this->Form->control('pao_email_id', array('type'=>'text', 'id'=>'pao_email_id', 'value'=>base64_decode($selected_pao_email_id['email']), 'label'=>false, 'readonly'=>true, 'class'=>'form-control')); //for email encoding ?>
			<span id="error_pao_email_id" class="error invalid-feedback"></span>
		</div>
		<div class="col-md-6">
			<label class="col-form-label">PAO/DDO Alias Name <span class="cRed">*</span></label>
			<?php echo $this->Form->control('pao_alias_name', array('type'=>'text', 'id'=>'pao_alias_name', 'value'=>$pao_alias_name, 'label'=>false, 'class'=>'form-control'/*'readonly'=>true*/)); ?>
			<span id="error_pao_alias_name" class="error invalid-feedback"></span>
		</div>
		<div class="clearfix"></div>

		<div class="col-md-6">
			<label class="col-form-label">Allocate State List <span class="cRed">*</span></label>
			<?php echo $this->Form->control('state_list', array('type'=>'select', 'id'=>'state_list', 'value'=>$selected_state_list, 'options'=>$all_states, 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control')); ?>
			<span id="error_district_list" class="error invalid-feedback"></span>
		</div>
		<div class="col-md-6" id="update_district_div">
			<label class="col-form-label">Allocate District List <span class="cRed">*</span></label>
			<?php echo $this->Form->control('district_list', array('type'=>'select', 'id'=>'district_list', 'value'=>$selected_district_list, 'options'=>$district_name_list, 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control')); ?>
			<?php echo $this->Form->control('district_option', array('type'=>'hidden', 'id'=>'district_option', 'label'=>false,)); ?>
			<span id="error_district_list" class="error invalid-feedback"></span>
		</div>
	</div>
</div>

<?php echo $this->Html->script('element/masters_management_elements/edit_master_elements/edit_pao'); ?>
