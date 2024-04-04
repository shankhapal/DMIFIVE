
	<div class="col-md-12">
		<div class="row">
<div class="col-md-6">
<label>Firm Type 	<span class="cRed">*</span></label>
				<?php echo $this->Form->control('firm_type', array('type'=>'select', 'id'=>'firm_type', 'options'=>$certificate_type, 'value'=>$period_details['firm_type'], 'label'=>false,'empty'=>'--Select Firm Type--','class'=>'form-control')); ?>

<div id="error_firm_type"></div>
</div>

<div class="col-md-6">
<label>Period <span class="cRed">*</span></label>
					<?php echo $this->Form->control('period', array('type'=>'select', 'id'=>'period', 'options'=>$period_rti,'value'=>$period_details['period'], 'label'=>false,'empty'=>'--Select Period--','class'=>'form-control')); ?>
<span id="error_period" class="error invalid-feedback"></span>
</div>

			
		<div class="col-md-2 mt-2">
			<!-- // commented for duplication of edit button commented by shankhpal shende 17/05/2023 -->
			<?php //echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
		</div>
		<?php //echo $this->Html->script('element/masters_management_elements/edit_district'); ?>
