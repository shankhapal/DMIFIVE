
<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<label class="col-form-label">Manual Name <span class="cRed">*</span></label>
			<?php echo $this->Form->control('manual_name', array('type'=>'text', 'id'=>'manual_name', 'value' => $record_details['manual_name'],'label'=>false, 'class'=>'form-control','placeholder'=>'Enter manual name')); ?>
			<span class="error invalid-feedback" id="error_manual_name"></span>
		</div>
		<div class="col-md-6">
			<label class="col-form-label">Manual For <span class="cRed">*</span></label>
			<select name="for" id="for" class="form-control">
				<option value="0">Select</option>c
				<option value="applicant"<?php if ($selected_unit == 'applicant') echo ' selected="selected"'; ?>>Applicant</option>
				<option value="users"<?php if ($selected_unit == 'users') echo ' selected="selected"'; ?>>DMI Users</option>
				<option value="lims"<?php if ($selected_unit == 'lims') echo ' selected="selected"'; ?>>LIMS Users</option>
			</select>
			<span class="error invalid-feedback" id="error_manual_for"></span>
		</div>
		<div class="col-md-6">
			<label class="col-form-label">Upload Manual PDF <span class="cRed">*</span></label>
			<?php if(!empty($selected_file)){?>
				<a id="link_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$selected_file); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$selected_file)), -1))[0],23);?></a>
			<?php } ?>
			<div class="custom-file col-sm-9">
				<input type="file" name="link" class="custom-file-input" id="link", multiple='multiple'>
				<label class="custom-file-label" for="customFile">Choose file</label>
				<span id="error_link" class="error invalid-feedback"></span>
				<span id="error_size_link" class="error invalid-feedback"></span>
				<span id="error_type_link" class="error invalid-feedback"></span>
			</div>
			<span class="error invalid-feedback" id="error_manual_name"></span>
		</div>
	</div>
</div>