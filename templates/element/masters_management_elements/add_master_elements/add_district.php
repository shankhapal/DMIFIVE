<?php if (!empty($duplicate_district_name)) { echo "<div class='alert alert-danger'>".$duplicate_district_name."</div>"; } ?>
<div class="col-md-12">
	<div class="form-horizontal">
		<div class="row">
			<div class="col-md-6">
				<label class="col-form-label">State <span class="cRed">*</span></label>
				<?php echo $this->Form->control('state_list', array('type'=>'select', 'id'=>'state_list', 'options'=>$state_list, 'label'=>false,'empty'=>'--Select State--','class'=>'form-control')); ?>
				<span id="error_state_list" class="error invalid-feedback"></span>
			</div>
			<div class="col-md-6">
				<label class="col-form-label">District  <span class="cRed">*</span></label>
				<?php echo $this->Form->control('district_name', array('type'=>'text', 'id'=>'district_name', 'label'=>false, 'placeholder'=>'Enter District Name Here','class'=>'form-control','required'=>true)); ?>
				<span id="error_district_name" class="error invalid-feedback"></span>
			</div>
			<div class="col-md-6 mt-3">
				<label class="col-form-label">District Office :</label>
				<?php
					$options=array('RO'=>'RO','SO'=>'SO');
					$attributes=array('legend'=>false, 'value'=>'RO', 'id'=>'dist_office_type');
					echo $this->Form->radio('dist_office_type',$options,$attributes);
				?>
			</div>

			<!-- Added below radio button block on 10-08-2018 FOR optional RO/SO office (one mandatory)-->
			<div id="ro_list_div" class="col-md-6 mt-3">
				<label class="col-form-label">RO Office <span class="cRed">*</span></label>
				<?php echo $this->Form->control('ro_offices_list', array('type'=>'select', 'id'=>'ro_offices_list', 'options'=>$ro_offices_list, 'empty'=>'--Select RO--','label'=>false,'class'=>'form-control')); ?>
				<span id="error_ro_offices_list" class="error invalid-feedback"></span>
			</div>

			<!-- added on 06-03-2018 by Amol added id on 10-08-2018-->
			<div id="so_list_div" class="col-md-6">
				<label class="col-form-label">SO Office <span class="cRed">*</span></label>
				<?php  echo $this->Form->control('so_offices_list', array('type'=>'select', 'id'=>'so_offices_list', 'options'=>$so_offices_list, 'empty'=>'--Select SO--','label'=>false,'class'=>'form-control')); ?>
				<span id="error_so_offices_list" class="error invalid-feedback"></span>
			</div>
		</div>
	</div>
</div>

<?php echo $this->Html->script('element/masters_management_elements/add_master_elements/add_district'); ?>
