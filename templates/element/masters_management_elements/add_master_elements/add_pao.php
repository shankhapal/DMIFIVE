<?php
	echo $this->Html->css('../multiselect/jquery.multiselect');
	echo $this->Html->script('../multiselect/jquery.multiselect');
?>

<div class="col-md-12">
	<div class="row">
		<div class="col-md-4">
			<label class="col-form-label">PAO/DDO Email ID <span class="cRed">*</span></label>
			<?php echo $this->Form->control('pao_email_id', array('type'=>'select', 'id'=>'pao_email_id', 'options'=>$pao_email_id_list,'class'=>'form-control',  'label'=>false)); ?>
			<span id="error_pao_email_id" class="error invalid-feedback"></span>
		</div>
		<div class="col-md-4">
			<label class="col-form-label">PAO/DDO Alias Name <span class="cRed">*</span></label>
			<?php echo $this->Form->control('pao_alias_name', array('type'=>'text', 'id'=>'pao_alias_name', 'class'=>'form-control',  'label'=>false, 'placeholder'=>'Enter PAO/DDO Alias Name Here','required'=>true)); ?>	
			<span id="error_pao_alias_name" class="error invalid-feedback"></span>
		</div>
		<div class="clearfix"></div>
		
		<div class="col-md-4">
			<label class="col-form-label">Allocate State List <span class="cRed">*</span></label>
			<?php echo $this->Form->control('state_list', array('type'=>'select', 'id'=>'state_list', 'options'=>$all_states, 'multiple'=>'multiple',  'label'=>false)); ?>
			<span id="error_district_list" class="error invalid-feedback"></span>
		</div>
		<div class="col-md-4" id="update_district_div">
			<label class="col-form-label">Allocate District List <span class="cRed">*</span></label>
			<?php echo $this->Form->control('district_list', array('type'=>'select', 'id'=>'district_list', /*'options'=>$district_name_list,*/ 'multiple'=>'multiple',  'label'=>false)); ?>
			<?php echo $this->Form->control('district_option', array('type'=>'hidden', 'id'=>'district_option', 'label'=>false,)); ?>
			<span id="error_district_list" class="error invalid-feedback"></span>
		</div>
		<div class="col-md-3">
			<!--Check pao user and district name availability (Done By pravin 25/10/2017)-->
			<?php if(empty($pao_email_id_list)){ ?>
				<label class="badge badge-info"> User with role PAO/DDO are all set </label>
			<?php } ?>
			
			<?php if(empty($district_name_list)){ ?>
				<label class="badge badge-info"> No district remaining to set for PAO/DDO </label>
			<?php } ?>
		</div>
	</div>
</div>

<?php echo $this->Html->script('element/masters_management_elements/add_master_elements/add_pao'); ?>
