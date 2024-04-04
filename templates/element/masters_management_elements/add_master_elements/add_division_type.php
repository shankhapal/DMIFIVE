<label class="col-md-3 col-form-label">Division Grade <span class="cRed">*</span></label>
<div class="col-md-7">
    <?php echo $this->Form->control('division_type', array('type'=>'text', 'id'=>'division_type', 'label'=>false, 'placeholder'=>'Enter Division Type Here' ,'class'=>'form-control', 'required'=>true)); ?>
    <span id="error_division_type" class="error invalid-feedback"></span>
</div>