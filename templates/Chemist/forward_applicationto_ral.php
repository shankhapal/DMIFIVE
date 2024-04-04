<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Chemist Training</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
						<li class="breadcrumb-item active">Forward The Chemist To RAL</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
                    <?php  echo $this->Form->create(null, array( 'enctype'=>'multipart/form-data', 'id'=>'ro_toral','class'=>'form_name'));  ?>
                        <div class="card card-purple">
                            <div class="card-header"><h2 class="card-title-new"><b>Forward The Chemist To RAL for Training</b></h2></div>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 row">
                                            <div class="col-md-2">
                                                <label for="field3"><span><?php if(!empty($_SESSION['level_3_for'])){ echo $_SESSION['level_3_for']; }?> In-charge First Name <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('ro_first_name', array('type'=>'text', 'id'=>'rofirstname', 'escape'=>false, 'value'=>$ro_fname, 'placeholder'=>'Enter First Name', 'class'=>'cvOn cvReq cvAlphaNum form-control', 'maxlength'=>255, 'readonly'=>true, 'label'=>false)); ?>
                                                <div class="err_cv"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="field3"><span>Last Name <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('ro_last_name', array('type'=>'text', 'id'=>'rolastname', 'escape'=>false, 'value'=>$ro_lname, 'placeholder'=>'Enter Last Name', 'class'=>'cvOn cvReq cvAlphaNum form-control', 'maxlength'=>255, 'readonly'=>true, 'label'=>false)); ?>
                                                <div class="err_cv"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 row">
                                            <div class="col-md-2">
                                                <label for="field3"><span>Chemist First Name <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('chemist_first_name', array('type'=>'text', 'id'=>'chemistfirstname', 'escape'=>false, 'value'=>$chemist_fname, 'placeholder'=>'Enter First Name', 'class'=>'cvOn cvReq cvAlphaNum form-control', 'maxlength'=>255, 'readonly'=>true, 'label'=>false)); ?>
                                                <div class="err_cv"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="field3"><span>Chemist Last Name <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('chemist_last_name', array('type'=>'text', 'id'=>'chemistlastname', 'escape'=>false, 'value'=>$chemist_lname, 'placeholder'=>'Enter Last Name', 'class'=>'cvOn cvReq cvAlphaNum form-control', 'maxlength'=>255, 'readonly'=>true, 'label'=>false)); ?>
                                                <div class="err_cv"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 row">
                                            <div class="col-md-2">
                                                <label for="field3"><span>Chemist Id <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('chemist_id', array('type'=>'text', 'id'=>'chemistId', 'escape'=>false, 'value'=>$chemist_id, 'placeholder'=>'Enter First Name', 'class'=>'cvOn cvReq cvAlphaNum form-control', 'maxlength'=>255, 'readonly'=>true, 'label'=>false)); ?>
                                                <div class="err_cv"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="field3"><span>RAL<span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="ro_office" class="dropdown form-control" id="roOffice" value="">
                                                    <option disabled selected value="">--Select RAL Office--</option>
                                                    <?php
                                                    foreach ($ral_details as $key => $ral) {
                                                    echo "<option value='".$ral['id']."' class='form-control'> ".$ral['ro_office']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <div class="err_cv_ro_office text-red"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 row">
                                            <div class="col-md-2">
                                                <label for="field3"><span>Remark </span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('remark', array('type'=>'textarea', 'id'=>'rej_remark', 'escape'=>false,  'placeholder'=>'Enter Remark', 'class'=>'cvOn cvReq cvAlphaNum form-control',   'label'=>false)); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="field3"><span>Upload </span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('document', array('type'=>'file', 'id'=>'document', 'escape'=>false, 'value'=>'yes','label'=>false)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 row">
                                            <div class="col-md-2">
                                                <label for="field3"><span>Schedule Training From Dates <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('shedule_from',['class' => 'form-control datepicker-here', 'label' => false,'id' => 'sheduleFrom', 'type' => 'Text',]); ?>
                                                <div class="err_cv_shedule_from text-red"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="field3"><span>Schedule Training To Dates <span class="cRed">*</span></span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <?php echo $this->Form->control('shedule_to',['class' => 'form-control datepicker-here', 'label' => false,'id' => 'sheduleTo', 'type' => 'Text']); ?>
                                                <div class="err_cv_shedule_to text-red"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-2">
                                    <?php echo $this->Form->submit(' Forwarded To RAL', array('name'=>'Forward', 'id'=>'btnSubmit', 'class'=>'btn btn-success','title'=>'Be sure all fields and details are properly filled for application', 'label'=>false));?>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php echo $this->Html->script('chemist/forward_applicationto_ral');?>
