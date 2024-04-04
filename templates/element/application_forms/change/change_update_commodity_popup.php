
	<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">
		<?php if ($firm_type==1 || $firm_type==3) { ?> Category/Commodities Details <?php } ?>
		<?php if ($firm_type==2) { ?> Packing Types Details <?php } ?>
	</div></div></div>

	<?php if ($firm_type==1 || $firm_type==3) { ?>

		<!-- fields for new change value-->
		<div class="col-md-6">
			<p><b>New Details</b></p>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Category <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->form->control('comm_category', array('type'=>'select', 'id'=>'category', 'empty'=>'Select Category', 'options'=>$section_form_details[0]['comm_category_list'], 'value'=>$section_form_details[0]['comm_category'], 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_comm_category" class="error invalid-feedback"></span>
				</div>
			</div>
			
			<div id="selected_bevo_nonbevo_msg"></div>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->form->control('commodity', array('type'=>'select', 'empty'=>'Select Commodity', 'id'=>'commodity','options'=>$section_form_details[0]['commodity_list'], 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_commodity" class="error invalid-feedback"></span>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Commodities </label>
				<div class="col-sm-9">
					<?php echo $this->form->control('selected_commodity', array('type'=>'select', 'id'=>'selected_commodity', 'options'=>$section_form_details[0]['commodity'], 'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control mH260')); ?>
					<span id="error_selected_commodity" class="error invalid-feedback"></span>
				</div>
			</div>
			<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>

			<!-- New fields for FSSAI no. and document is added on 17-05-2023 by Amol -->
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-4 col-form-label">FSSAI No. <span class="cRed">*</span></label>
				<div class="custom-file col-sm-8">
					<?php echo $this->form->control('commodity_fssai_no', array('type'=>'text', 'id'=>'commodity_fssai_no', 'escape'=>false, 'value'=>$section_form_details[0]['commodity_fssai_no'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter FSSAI No.')); ?>
					<span id="error_commodity_fssai_no" class="error invalid-feedback"></span>
				</div>
			</div>
			<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>FSSAI Revelant Document</p>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-2 col-form-label"> Attach File: <span class="cRed">*</span></label>
					<?php if(!empty($section_form_details[0]['commodity_fssai_doc'])){?>
						<a id="commodity_fssai_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['commodity_fssai_doc']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['commodity_fssai_doc'])), -1))[0],23);?></a>
					<?php } ?>
				
				<div class="custom-file col-sm-9">
					<input type="file" name="commodity_fssai_doc" class="form-control" id="commodity_fssai_doc", multiple='multiple'>
					<span id="error_commodity_fssai_doc" class="error invalid-feedback"></span>
					<span id="error_type_commodity_fssai_doc" class="error invalid-feedback"></span>
					<span id="error_size_commodity_fssai_doc" class="error invalid-feedback"></span>
				</div>
			</div>
			<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
		</div>
		
		<!-- fields for Last value-->
		<div class="col-md-6 last_details_change">
			<p><b>Last Details</b></p>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Category <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('category_last', array('type'=>'select', 'options'=>$category_list,'label'=>false, 'class'=>'form-control')); ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities </label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('selected_commodity', array('type'=>'select','options'=>$selected_commodities, 'multiple'=>true, 'label'=>false, 'class'=>'form-control mH260')); ?>
				</div>
			</div>

		</div>


	<?php } elseif ($firm_type==2) { ?>

		<!-- fields for new change value-->
		<div class="col-md-6">
			<p><b>New Details</b></p>
			<div class="form-group row">
			<label for="inputEmail3" class="col-sm-3 col-form-label">Packing Types <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('packing_types', array('type'=>'select', 'id'=>'packing_types', 'empty'=>'Select', 'options'=>$packing_types, 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_packing_types" class="error invalid-feedback"></span>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packing Types </label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('selected_packing_types', array('type'=>'select', 'id'=>'selected_packing_types', 'options'=>$section_form_details[0]['packing_types'],'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_selected_packing_types" class="error invalid-feedback"></span>
				</div>
			</div>
			<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
		</div>
		
		<!-- fields for last value-->
		<div class="col-md-6 last_details_change">
			<p><b>Last Details</b></p>
			<div class="form-group row">
				<!--<label for="inputEmail3" class="col-sm-3 col-form-label">Packing Types <span class="cRed">*</span></label>
					<div class="col-sm-9">
						<?php //echo $this->Form->control('packing_types_last', array('type'=>'select', 'empty'=>'Select', 'options'=>$packing_types, 'label'=>false, 'class'=>'form-control')); ?>
					</div>
				</div>-->

				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packing Types </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('selected_packing_types_last', array('type'=>'select', 'options'=>$selected_packing_types,'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
					</div>
				</div>
				<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
			</div>
		</div>

	<?php } ?>



<?php echo $this->Html->script('forms/update_commodity_option'); ?>
