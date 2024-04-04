<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0 text-dark"></h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));?></a></li>
                        <li class="breadcrumb-item active">Chemist Registration</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

	<?php if (!empty($primary_registered)) { ?>
        <div class="container">
            <h4 class="h4_tag_class">
                <div class="marTop">
                <span class="font_class">Congratulations... your details has been saved and your id is</span> <span  class="id_font"><?php echo $new_customer_id ; ?></span>.
                    <br>
                    <span class="encodedEmail"><?php
                        echo " A link has been sent to your email id ".$htmlencodedemail ;
                    ?>.</span>
                </div>
            </h4>
        </div>
	<?php } else { ?>

    <section class="content form-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'reg_customer_form', 'enctype'=>'multipart/form-data')); ?>
                        <div class="card card-purple" id="form_outer_main">
                            <div class="card-header"><h3 class="card-title-new">Chemist Registration</h3></div>
                            <div class="col-sm-6 offset-7 mt-2"><span class="badge badge-danger">Name should be same as in Aadhar card.</span></div>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label class="youmail">First Name <span class="cRed">*</span></label>
                                            <?php echo $this->Form->control('chemist_fname', array('label'=>false, 'id'=>'chemist_fname', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter first name')); ?>
                                            <span class="error invalid-feedback" id="error_f_name"></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="youmail">Last Name <span class="cRed">*</span></label>
                                            <?php echo $this->Form->control('chemist_lname', array('label'=>false, 'id'=>'chemist_lname', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter last name')); ?>
                                            <span class="error invalid-feedback" id="error_lname"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label class="youmail">Email <span class="cRed">*</span></label>
                                            <?php echo $this->Form->control('email', array('label'=>false, 'id'=>'email', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter email id')); ?>
                                            <span class="error invalid-feedback" id="error_email"></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="youpasswd">Mobile No <span class="cRed">*</span></label>
                                            <?php echo $this->Form->control('mobile', array('type'=>'tel', 'escape'=>false, 'id'=>'mobile', 'label'=>false, 'class'=>'input-field form-control', 'minlength'=>'10', 'maxlength'=>'10','placeholder'=>'Please enter mobile no.')); ?>
                                            <span class="error invalid-feedback" id="error_mobile"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label class="uname">Date of Birth <span class="cRed">*</span></label>
                                            <?php echo $this->Form->control('dob', array('type'=>'text', 'escape'=>false, 'id'=>'dob', 'label'=>false, 'class'=>'form-control input-field','placeholder'=>'Enter Date of Birth')); ?>
                                            <span class="error invalid-feedback" id="error_dob"></span>
                                        </div>
										<!-- is chemist new/ old added by laxmi on 12-12-22-->
										<div class="col-sm-6">
										 <label for="newoldsignup" class="uapproved">Is training completed?  <span class="cRed">*</span></label><br>

										 <input type="radio" name="is_training_completed" id="approved" value="yes">Training Completed
										 <input type="radio" name="is_training_completed" id="approved" value="no" checked>Apply for training

										  <span class="error invalid-feedback" id="error_is_training_completed"></span>
										</div>
									</div>

                                   <!-- to select commodity  added below code by laxmi on 10-07-2023-->
                                    <div class="col-sm-12">
                                        <div id="commodity_box" class ="card-secondary">
									        <div class="card-header card-header"><h3 class="card-title"><i class="fa fa-tree"></i> Approved Commodities Only</h3></div>
									        <div class="form-horizontal">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label">Approved Category <span class="cRed">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <?php echo $this->Form->control('commodity', array('type'=>'select', 'id'=>'commodity_category', 'empty'=>'Select Category', 'options'=>$commodity_categories, 'label'=>false, 'class'=>'form-control')); ?>
                                                                    <input type="hidden" id="chemist_commodity_select" class="chemist_comoditites" value="application_type_4">
                                                                    <span id="error_commodity_category" class="error invalid-feedback"></span>
                                                                </div>
                                                            </div>
                                                            <div id="selected_bevo_nonbevo_msg"></div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label">Commodities <span class="cRed">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <?php echo $this->Form->control('sub_commodity', array('type'=>'select', 'empty'=>'Select Commodity', 'id'=>'commodity','options'=>array(), 'label'=>false, 'class'=>'form-control')); ?>
                                                                    <span id="error_commodity" class="error invalid-feedback"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label">Selected Commodities </label>
                                                                <div class="col-sm-9">
                                                                    <?php echo $this->Form->control('selected_commodity', array('type'=>'select', 'id'=>'selected_commodity', 'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
                                                                    <span id="error_selected_commodity" class="error invalid-feedback"></span>
                                                                </div>
                                                            </div>
                                                            <p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
                                                        </div>
                                                    </div>
                                                </div>
									        </div>
								        </div>
                                    </div>
								</div>
                                <div class="card-body cardFooterBackground">
                                    <?php echo $this->Form->control('Register', array('type'=>'button', 'id'=>'add_chemist', 'label'=>false, 'class'=>'btn btn-success mtminus12pb7')); ?>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

    <?php echo $this->Html->script('chemist_module_validations'); ?>
    <!-- below scripts and links added by laxmi for select commodities on 10-07-2023 -->
   <?php  echo $this->Html->css('multiselect/jquery.multiselect');
	echo $this->Html->script('multiselect/jquery.multiselect');
    echo $this->Html->script('forms/add_firms');?>
<?php } ?>
