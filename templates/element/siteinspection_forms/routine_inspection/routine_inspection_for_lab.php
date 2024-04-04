
<!--    Comment:This file updated as per change and suggestions for UAT module after test run
	    Reason: updated as per change and suggestions for UAT module after test run
	    Name of person : shankhpal shende
	    Date: 24-05-2023
*/ -->
<?php
echo $this->Html->css('../multiselect/jquery.multiselect');
echo $this->Html->script('../multiselect/jquery.multiselect');
echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
<section id="form_outer_main" class="content form-middle">
<div class="container-fluid">
<h5 class="mt-1 mb-2">Routine Inspection Report (Approved Laboratory)</h5>
<div id='form_inner_main'>
<div class="row">
<div class="col-md-12">
<div class="card card-success">
<!-- Initial Details -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">Initial Details</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Date of Last Inspection <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <?php echo $this->Form->control('date_last_inspection', array('type'=>'text', 'id'=>'date_last_inspection', 'value'=>$section_form_details[0]['date_last_inspection'], 'class'=>'form-control input-field', 'placeholder'=>'Enter DD/MM/YYYY', 'label'=>false)); ?>
                <span id="error_date_last_inspection" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
<div class="form-horizontal">
    <div class="card-body ">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Date & Time of present Inspection <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-3">
                <?php echo $this->Form->control('date_p_inspection', array('type'=>'text', 'id'=>'date_p_inspection', 'value'=>$section_form_details[0]['date_p_inspection'], 'class'=>'form-control input-field', 'placeholder'=>'Enter DD/MM/YYYY', 'label'=>false)); ?>
                <span id="error_date_p_inspection" class="error invalid-feedback"></span>
            </div>
            <div class="col-md-3">
                <!-- added time field as per change req and suggestions on date 27/06/2023
                added by shankhpal shende -->
                <?php
                    echo $this->Form->control('time_p_inspection', [
                        'type' => 'select',
                        'id' => 'time_p_inspection',
                        'options' => $section_form_details[5],
                        'default' => $section_form_details[0]['time_p_inspection'],
                        'class' => 'form-control input-field',
                        'label' => false
                    ]);
                    ?>
                    <span id="error_time_p_inspection" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
<!-- 1. Name and addres of the laboratory Contact details: -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">1. Name and addres of the laboratory Contact details :</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Name of the laboratory</label>
                </div>
            </div>
            <div class="col-md-3">
                <?php echo $this->Form->control('name_of_lab', array('type'=>'text', 'id'=>'name_of_lab','value'=>$firm_details['firm_name'],'class'=>'form-control readOnly','label'=>false)); ?>
                <span id="error_name_of_lab" class="error invalid-feedback"></span>
            </div>
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Addres of the laboratory </label>
                </div>
            </div>
            <div class="col-md-3">
                <?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address','value'=>$firm_details['street_address'],'placeholder'=>'Enter street address', 'class'=>'form-control input-field readOnly', 'label'=>false)); ?>
                <span id="error_street_address" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
<!-- address -->
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Email Id </label>
                </div>
            </div>
            <div class="col-md-4">
                <?php echo $this->Form->control('email', array('type'=>'text', 'placeholder'=>'Enter firm email id','value'=>base64_decode($firm_details['email']),'id'=>'email', 'class'=>'form-control input-field readOnly', 'label'=>false)); ?>
                <span id="error_email" class="error invalid-feedback"></span>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Mobile No.</label>
                </div>
            </div>
            <div class="col-md-4">
               <?php echo $this->Form->control('mobile_no', array('type'=>'text', 'placeholder'=>'Enter mobile no. here', 'id'=>'mobile_no','value'=>base64_decode($firm_details['mobile_no']),'class'=>'form-control input-field readOnly', 'label'=>false)); ?>
                <span id="error_mobile_no" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
<!-- 2. Commodities for which approved -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">2. Commodities for which approved</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Commodities </label>
                </div>
            </div>
            <div class="col-md-6">
                <?php echo $this->Form->control('sub_commodity', array('type'=>'select', 'options'=>$section_form_details[1], 'multiple'=>'multiple', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
            </div>
        </div>
    </div>
</div>
<!-- 3. Name of the approved chemist Present at the time of inspection -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">3. Name of the approved chemist</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Name of the approved Chemist : <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <?php echo $this->Form->control('approved_chemist', array('type'=>'select', 'options'=>$section_form_details[2], 'multiple'=>'multiple', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>

                 <?php if (empty($section_form_details[2])) : ?>
                    <ol class="badge">
                      <a target="_blank" href="/writereaddata/DMI/manuals/applicant/Chemist Registration.pdf">Manual for Chemist Registration</a>
                    </ol>
                <?php endif; ?>
                <span id="error_approved_chemist" class="error invalid-feedback"></span>

            </div>
        </div>
    </div>
</div>
<!-- 4.  Present at the time of inspection -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">4.Name of the approved chemist Present at the time of inspection </h3></div>
<div class="form-horizontal">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Present at the time of Inspection<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
          <?php
                $chemst = $section_form_details[2];

                $selectedOptions = explode(',', $section_form_details[0]['present_time_of_inspection']);

                $selectedValues = [];
                if(!empty($chemst)){
                    foreach ($selectedOptions as $option) {
                        foreach ($chemst as $key => $value) {
                            if ($option == $key) {
                                $selectedValues[] = $option;
                                break; // Exit the inner loop since the value has been found
                            }
                        }
                    }
                }

                echo $this->Form->control(
                    'present_time_of_inspection',
                    [
                        'type' => 'select',
                        'options' => $chemst,
                        'default' => $selectedValues,
                        'multiple' => 'multiple',
                        'escape' => false,
                        'id' => 'present_time_of_inspection',
                        'label' => false,
                        'class' => 'form-control wd120'
                    ]
                );
                ?>
            </div>

        </div>
    </div>
</div>
<!-- 5. Is the laboratory well lighted Ventilated and hygienic -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">5. Is the laboratory well lighted Ventilated and hygienic</h3></div>
<div class="form-horizontal">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Is the laboratory well lighted Ventilated and hygienic<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                 <?php
                        $i=1;
                        $is_lab_well_lighted = isset($section_form_details[0]['is_lab_well_lighted'])?$section_form_details[0]['is_lab_well_lighted']:"";

                        if($is_lab_well_lighted == 'yes'){
                            $checked_yes = 'checked';
                            $checked_no = '';
                        } else {

                            $checked_yes = '';
                            $checked_no = 'checked';
                        }
                        $i++;
                    ?>
                <div class="col-sm-6">
                    <div class=" d-inline">
                        <input type="radio" name="is_lab_well_lighted" checked="" id="is_lab_well_lighted-yes" value="yes" <?php echo $checked_yes; ?>>
                        <label for="is_lab_well_lighted-yes">Yes</label>
                    </div>
                    <div class=" d-inline">
                        <input type="radio" name="is_lab_well_lighted" id="is_lab_well_lighted-no" value="no" <?php echo $checked_no; ?>>
                        <label for="is_lab_well_lighted-no">No</label>
                    </div>
                    <span id="error_is_lab_well_lighted" class="error invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 6. Is the laboratory properly equipped for the granding of the commodities -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">6. Is the laboratory properly equipped for the granding of the commodities</h3></div>
<div class="form-horizontal">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Is the laboratory properly equipped for the granding of the commodities<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <?php
                        $i=1;
                        $is_properly_equipped = isset($section_form_details[0]['is_properly_equipped'])?$section_form_details[0]['is_properly_equipped']:"";
                        if($is_properly_equipped == 'yes'){
                            $checked_yes = 'checked';
                            $checked_no = '';
                        } else {
                            $checked_yes = '';
                            $checked_no = 'checked';
                        }
                        $i++;
                    ?>
                <div class="col-sm-9">
                    <div class=" d-inline">
                        <input type="radio" name="is_properly_equipped" checked="" id="is_properly_equipped-yes" value="yes"<?php echo $checked_yes; ?>>
                        <label for="is_properly_equipped-yes">Yes</label>
                    </div>
                    <div class=" d-inline">
                        <input type="radio" name="is_properly_equipped" id="is_properly_equipped-no" value="no" <?php echo $checked_no; ?>>
                        <label for="is_properly_equipped-rn">No</label>
                    </div>
                    <span id="is_properly_equipped-rn" class="error invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 7. Is the equipment is in working order -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">7. Is the equipment is in working order</h3></div>
<div class="form-horizontal">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Is the equipment is in working order<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                 <?php
                    $i=1;
                    $eq_working_order = $section_form_details[0]['eq_working_order'];
                    if($eq_working_order == 'yes'){
                        $checked_yes = 'checked';
                        $checked_no = '';
                    } else {
                        $checked_yes = '';
                        $checked_no = 'checked';
                    }
                    $i++;
                ?>
                <div class=" d-inline">
                        <input type="radio" name="eq_working_order" checked="" id="eq_working_order-yes" value="yes"<?php echo $checked_yes; ?>>
                        <label for="eq_working_order-yes">Yes</label>
                    </div>
                    <div class=" d-inline">
                        <input type="radio" name="eq_working_order" id="eq_working_order-no" value="no" <?php echo $checked_no; ?>>
                        <label for="eq_working_order-no">No</label>
                    </div>
                    <span id="error_eq_working_order" class="error invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 8. Is the analytical register properly Maintained -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">8. Is the analytical register properly Maintained</h3></div>
<div class="form-horizontal">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Is the analytical register properly Maintained<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <?php
                        $i=1;
                        $is_analytical_reg_maintained = isset($section_form_details[0]['is_analytical_reg_maintained'])?$section_form_details[0]['is_analytical_reg_maintained']:"";
                        if($is_analytical_reg_maintained == 'yes'){
                            $checked_yes = 'checked';
                            $checked_no = '';
                        } else {
                            $checked_yes = '';
                            $checked_no = 'checked';
                        }
                        $i++;
                    ?>
                    <div class=" d-inline">
                        <input type="radio" name="is_analytical_reg_maintained" checked="" id="is_analytical_reg_maintained-yes" value="yes" <?php echo $checked_yes; ?>>
                        <label for="is_analytical_reg_maintained-yes">Yes</label>
                    </div>
                    <div class=" d-inline">
                        <input type="radio" name="is_analytical_reg_maintained" id="is_analytical_reg_maintained-no" value="no"<?php echo $checked_no; ?>>
                        <label for="is_analytical_reg_maintained-no">No</label>
                    </div>
                    <span id="error_is_analytical_reg_maintained" class="error invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 9. Grading records -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">9. Grading records</h3></div>
<div class="form-horizontal">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">1) Are the up to date ?<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group row">
                    <?php
                        $i=1;
                        $are_up_to_date = isset($section_form_details[0]['are_up_to_date'])?$section_form_details[0]['are_up_to_date']:"";
                        if($are_up_to_date == 'yes'){
                            $checked_yes = 'checked';
                            $checked_no = '';
                        } else {
                            $checked_yes = '';
                            $checked_no = 'checked';
                        }
                        $i++;
                    ?>
                    <div class=" d-inline">
                        <input type="radio" name="are_up_to_date" checked="" id="are_up_to_date-yes" value="yes"<?php echo $checked_yes; ?>>
                        <label for="are_up_to_date-yes">Yes</label>
                    </div>
                    <div class=" d-inline">
                        <input type="radio" name="are_up_to_date" id="are_up_to_date-no" value="no" <?php echo $checked_no; ?>>
                        <label for="are_up_to_date-no">No</label>
                    </div>
                    <span id="error_are_up_to_date" class="error invalid-feedback"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">2) Are they being forwarded to Concerned offices in time<span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group row">
                    <?php
                        $i=1;
                        $being_forwarded = isset($section_form_details[0]['being_forwarded'])?$section_form_details[0]['being_forwarded']:"";

                        if($being_forwarded == 'yes'){
                            $checked_yes = 'checked';
                            $checked_no = '';
                        } else {

                            $checked_yes = '';
                            $checked_no = 'checked';
                        }
                        $i++;
                    ?>
                    <div class=" d-inline">
                        <input type="radio" name="being_forwarded" checked="" id="being_forwarded-yes" value="yes"<?php echo $checked_yes; ?>>
                        <label for="being_forwarded-yes">Yes</label>
                    </div>
                    <div class=" d-inline">
                        <input type="radio" name="being_forwarded" id="being_forwarded-no" value="no" <?php echo $checked_no; ?>>
                        <label for="being_forwarded-no">No</label>
                    </div>
                    <span id="error_being_forwarded" class="error invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 10. Last lot analyzed -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">10. Last lot analyzed</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Lot No : <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-4">
                 <?php echo $this->Form->control('last_lot_no', array('type'=>'text', 'id'=>'last_lot_no', 'class'=>'form-control input-field','value'=>$section_form_details[0]['last_lot_no'], 'placeholder'=>'Last lot No.', 'label'=>false)); ?>
                <span id="error_last_lot_no" class="error invalid-feedback error_last_lot_no"></span>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Last lot date : <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-4">
                  <?php echo $this->Form->control('lat_lot_date', array('type'=>'text', 'id'=>'date', 'escape'=>false, 'placeholder'=>'Enter DD/MM/YYYY', 'value'=>$section_form_details[0]['lat_lot_date'],'maxlength'=>'10', 'minlength'=>'10', 'class'=>'cvOn cvReq cvDate form-control', 'disabled'=>false, 'label'=>false)); ?>
                <span id="error_date" class="error invalid-feedback"></span>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Commodity : <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-4">
                  <?php echo $this->Form->control('commodity', array('type'=>'text', 'id'=>'commodity', 'class'=>'form-control input-field', 'value'=>$section_form_details[0]['commodity'],'placeholder'=>'Enter Commodity', 'label'=>false)); ?>
                  <span id="error_commodity" class="error invalid-feedback error_commodity"></span>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Name of the Packers : </label>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $options = $section_form_details[3];
                    $options = ['' => 'Select Packers'] + $options;
                    $selectedValues = $section_form_details[0]['name_of_packers']; // Provide the values you want to select as an array
                    echo $this->Form->control('name_of_packers', [
                        'type' => 'select',
                        'options' => $options,
                        'id' => 'name_of_packers',
                        'class' => 'form-control input-field',
                        'label' => false,
                        'value' => $selectedValues // Set the selected values directly in the control options
                    ]);?>
                 <?php if (empty($section_form_details[2])) : ?>
                    <ol class="badge">
                      <a target="_blank" href="/writereaddata/DMI/manuals/applicant/Manual_mapping_lab_pp.pdf">Manual for Mapping of CA With Lab/Printing Press</a>
                    </ol>
                <?php endif; ?>
                <span id="error_name_of_packers" class="error invalid-feedback"></span>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Analytical results : <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-4">
                  <?php echo $this->Form->control('p_analytical_reg', array('type'=>'textarea', 'id'=>'p_analytical_reg','value'=>$section_form_details[0]['p_analytical_reg'], 'class'=>'form-control input-field', 'placeholder'=>'Type here..', 'label'=>false)); ?>
                <span id="error_p_analytical_reg" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
  <div class="form-horizontal">
        <div class="card-body border">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                            <label for="field3" class="col-sm col-form-label"><span><?php if ($current_level == 'level_2' && $application_mode == 'edit' ) { echo 'Analytical Results Doc'; } else { echo 'Analytical Results Doc'; } ?></span></label>

                        <span class="float-left"><?php if ($current_level == 'level_2' && $application_mode == 'edit' && empty($section_form_details[0]['analytical_result_docs'])) { echo 'Attach doc'; }else{ echo 'Attached doc'; } ?> :
                        <?php if (!empty($section_form_details[0]['analytical_result_docs'])) { ?>
                            <a id="analytical_result_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['analytical_result_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['analytical_result_docs'])), -1))[0],23);?></a>
                        <?php }else{ echo "No Document Provided" ;} ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="custom-file col-sm">
                            <input type="file" name="analytical_result_docs" class="form-control" id="analytical_result_docs" multiple='multiple'>
                            <span id="error_analytical_result_docs" class="error invalid-feedback"></span>
                            <span id="error_type_analytical_result_docs" class="error invalid-feedback"></span>
                            <span id="error_size_analytical_result_docs" class="error invalid-feedback"></span>
                            </div>
                    </div>
                    <p class="lab_form_note float-right mt-3"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
            </div>
        </div>
    </div>
</div>
<!-- 11. Suggestions given during last -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">11. Suggestions given during last</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Suggestions given during last <span class="cRed">*</span></label>
                     <button class="m-3" id="last-sugeesion-popup">Get all previous suggestions</button>
                    <!-- pop up for display last suggestions -->
                    <section class="popup">
                        <div class="popup__content">
                            <div class="close">
                            <span></span>
                            <span></span>
                            </div>
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">IO Users</th>
                            <th scope="col">suggestions</th>
                            <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total_suggestions = $section_form_details[4];
                                $sr = 1;
                                if (!empty($total_suggestions)) {
                                    foreach ($total_suggestions as $each_sugg) {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $sr; ?></th>
                                            <td class="text-center"><?php echo $each_sugg['io_user_name']; ?></td>
                                            <td class="text-center"><?php
                                                if (empty($each_sugg['enumerate_briefly_suggestions'])) {
                                                    echo "NA";
                                                } else {
                                                    echo $each_sugg['enumerate_briefly_suggestions'];
                                                }
                                                ?></td>
                                            <td class="text-center"><?php
                                                if (empty($each_sugg['approved_date'])) {
                                                    echo "NA";
                                                } else {
                                                    echo $each_sugg['approved_date'];
                                                }
                                                ?></td>
                                        </tr>
                                        <?php
                                        $sr++;
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No suggestions found.</td></tr>";
                                }
                                ?>

                        </tbody>
                        </table>
                        </div>
                    </section>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                <?php
                $i=1;
                $e_briefly_suggestions_radio = isset($section_form_details[0]['e_briefly_suggestions_radio'])?$section_form_details[0]['e_briefly_suggestions_radio']:"";

                    if($e_briefly_suggestions_radio == 'yes'){
                        $checked_yes = 'checked';
                        $checked_no = '';
                    } else {

                        $checked_yes = '';
                        $checked_no = 'checked';
                    }
                    $i++;
                ?>
                <div class="d-inline">
                    <input type="radio" name="e_briefly_suggestions_radio" checked="" id="e_briefly_suggestions_radio-yes" value="yes" <?php echo $checked_yes; ?>>
                    <label for="e_briefly_suggestions_radio-yes">Yes
                    </label>
                </div>
                <div class=" d-inline">
                    <input type="radio" name="e_briefly_suggestions_radio" id="e_briefly_suggestions_radio-no" value="no" <?php echo $checked_no; ?>>
                    <label for="e_briefly_suggestions_radio-no">No</label>
                </div>
                <div class="col-sm-9">
                <?php
                    echo $this->Form->control('enumerate_briefly_suggestions', array('type'=>'textarea', 'id'=>'enumerate_briefly_suggestions', 'escape'=>false,'value'=>isset($section_form_details[0]['enumerate_briefly_suggestions'])?$section_form_details[0]['enumerate_briefly_suggestions']:"", 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Enter discrepancies here...')); ?>
                <span id="error_enumerate_briefly_suggestions" class="error invalid-feedback"></span>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- 12. Shortcomings noticed in present Inspection -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">12. Shortcomings noticed in present Inspection</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Shortcomings noticed in present Inspection <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                <?php echo $this->Form->control('shortcomings_noticed', array('type'=>'textarea', 'id'=>'shortcomings_noticed', 'value'=>$section_form_details[0]['shortcomings_noticed'],'class'=>'form-control input-field', 'placeholder'=>'Type here..', 'label'=>false)); ?>
                <span id="error_shortcomings_noticed" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
<!-- 13. Suggestions -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">13. Suggestions</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm col-form-label">Suggestions <span class="cRed">*</span></label>
                </div>
            </div>
            <div class="col-md-6">
                 <?php echo $this->Form->control('suggestions', array('type'=>'textarea', 'id'=>'suggestions', 'value'=>$section_form_details[0]['suggestions'],'class'=>'form-control input-field', 'placeholder'=>'Type here..', 'label'=>false)); ?>
                <span id="error_suggestions" class="error invalid-feedback"></span>
            </div>
        </div>
    </div>
</div>
<!-- Signnature and Name of the Office Authorized person of the laboratory-->
<div class="card-header sub-card-header-firm"><h3 class="card-title">Signnature and Name of the Office Authorized person of the laboratory</h3></div>
<div class="form-horizontal">
    <div class="card-body border">
        <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm col-form-label">Name of the Office Authorized person<span class="cRed">*</span></label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-sm">
                        <?php echo $this->Form->control('authorized_persion_name', array('type'=>'text', 'id'=>'authorized_persion_name','value'=>isset($section_form_details[0]['authorized_persion_name'])?$section_form_details[0]['authorized_persion_name']:"", 'placeholder'=>'Enter name', 'class'=>'form-control', 'label'=>false)); ?>
                    <span id="error_authorized_persion_name" class="error invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-horizontal">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                            <label for="field3" class="col-sm col-form-label"><span><?php if ($current_level == 'level_2' && $application_mode == 'edit' ) { echo 'Signature'; } else { echo 'Signature'; } ?></span></label>

                        <span class="float-left"><?php if ($current_level == 'level_2' && $application_mode == 'edit' && empty($section_form_details[0]['authorized_signature_docs'])) { echo 'Attach doc'; }else{ echo 'Attached doc'; } ?> :
                        <?php if (!empty($section_form_details[0]['authorized_signature_docs'])) { ?>
                            <a id="authorized_signature_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['authorized_signature_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['authorized_signature_docs'])), -1))[0],23);?></a>
                        <?php }else{ echo "No Document Provided" ;} ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="custom-file col-sm">
                                <input type="file" name="authorized_signature_docs" class="form-control" id="authorized_signature_docs" multiple='multiple'>
                            <span id="error_authorized_signature_docs" class="error invalid-feedback"></span>
                            <span id="error_type_authorized_signature_docs" class="error invalid-feedback"></span>
                            <span id="error_size_authorized_signature_docs" class="error invalid-feedback"></span>
                            </div>
                    </div>
                    <p class="lab_form_note float-right mt-3"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
            </div>
        </div>
    </div>
</div>
<!-- Signnature and Name of the Inspecting Designation -->
<div class="card-header sub-card-header-firm"><h3 class="card-title">Signnature and Name of the Inspecting Officer</h3></div>
    <div class="form-horizontal">
        <div class="form-horizontal">
            <div class="card-body border">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm col-form-label">Name of the Inspecting Officer<span class="cRed"> * </span></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm">
                            <?php echo $this->Form->control('name_of_inspecting_officer', array('type'=>'text', 'id'=>'name_of_inspecting_officer','placeholder'=>'Enter Name of the Inspecting Officer', 'class'=>'form-control', 'label'=>false,'value'=>isset($section_form_details[0]['name_of_inspecting_officer'])?$section_form_details[0]['name_of_inspecting_officer']:"")); ?>
                                <span id="error_name_of_inspecting_officer" class="error invalid-feedback"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-horizontal">
        <div class="form-horizontal">
            <div class="card-body border">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm col-form-label">Designation<span class="cRed"> * </span></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm">
                            <?php echo $this->Form->control('designation_inspecting_officer', array('type'=>'text', 'id'=>'designation_inspecting_officer', 'class'=>'form-control','value '=>isset($section_form_details[0]['designation_inspecting_officer'])?$section_form_details[0]['designation_inspecting_officer']:"", 'placeholder'=>'Please Enter Designation', 'label'=>false)); ?>
                            <span id="error_designation_inspecting_officer" class="error invalid-feedback"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-horizontal">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="field3" class="col-sm col-form-label"><span><?php if ($current_level == 'level_2' && $application_mode == 'edit' ) { echo 'Signature'; } else { echo 'Signature'; } ?></span><span class="cRed"> *</span></label>

                                <span class="float-left"><?php if ($current_level == 'level_2' && $application_mode == 'edit' && empty($section_form_details[0]['signnature_of_inspecting_officer_docs'])) { echo 'Attach docs'; }else{ echo 'Attached docs'; } ?> :
                                <?php if (!empty($section_form_details[0]['signnature_of_inspecting_officer_docs'])) { ?>
                                    <a id="signnature_of_inspecting_officer_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['signnature_of_inspecting_officer_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['signnature_of_inspecting_officer_docs'])), -1))[0],23);?></a>
                                <?php }else{ echo "No Document Provided" ;} ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="custom-file col-sm">
                                        <input type="file" name="signnature_of_inspecting_officer_docs" class="form-control" id="signnature_of_inspecting_officer_docs" multiple='multiple'>
                                    <span id="error_signnature_of_inspecting_officer_docs" class="error invalid-feedback"></span>
                                    <span id="error_type_signnature_of_inspecting_officer_docs" class="error invalid-feedback"></span>
                                    <span id="error_size_signnature_of_inspecting_officer_docs" class="error invalid-feedback"></span>
                                    </div>
                            </div>
                            <p class="lab_form_note float-right mt-3"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<input type="hidden" id="application_mode" value="<?php echo $_SESSION['application_mode']; ?>">
<input type="hidden" id="application_type_id" value="<?php echo $_SESSION['application_type']; ?>">
<input type="hidden" id="firm_type" value="<?php echo $firm_type; ?>">
<!-- added for if session value is set -->
<?php if(!empty($_SESSION['rtiupdatemode'])) { ?>
    <input type="hidden" id="checkeditsession" value="<?php echo $_SESSION['rtiupdatemode']; ?>">
<?php } ?>
<!-- //firm type is use to validate form fields added by shankhpal on 25/05/023 -->
<?php echo $this->Html->script('routininspection/routin_inspection01');
      echo $this->Html->script('routininspection/want_to_edit_rti');
      echo $this->Html->script('routininspection/rti_file_uploads_validation');
      echo $this->Html->css('RoutineInspection/routine_inspection_style');
      echo $this->Html->script('routininspection/rti_other_validation');
?>

