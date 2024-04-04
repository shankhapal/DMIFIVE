<!-- Added new fields to upload for change in premises on 17-05-2023 by Amol -->
    <!-- for FSSAI cert. -->
    <!-- added $firm_type==1 cond. on 15-07-2023 as said fssai upload will be only for CA -->
    <?php if ($firm_type==1) { ?>
        <div class="d-inline-block">
            <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> FSSAI Related Document</p>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
                        <?php if(!empty($section_form_details[0]['premises_fssai_doc'])){?>
                            <a id="premises_fssai_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['premises_fssai_doc']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['premises_fssai_doc'])), -1))[0],23);?></a>
                        <?php } ?>
                    
                    <div class="custom-file col-sm-9">
                        <input type="file" name="premises_fssai_doc" class="form-control" id="premises_fssai_doc", multiple='multiple'>
                        <span id="error_premises_fssai_doc" class="error invalid-feedback"></span>
                        <span id="error_type_premises_fssai_doc" class="error invalid-feedback"></span>
                        <span id="error_size_premises_fssai_doc" class="error invalid-feedback"></span>
                    </div>
                </div>
            <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
        </div>
        <div class="clearfix"></div>
    <?php } ?>

    <!-- for GST cert. -->
    <div class="d-inline-block">
        <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> GST Related Document</p>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
                    <?php if(!empty($section_form_details[0]['premises_gst_doc'])){?>
                        <a id="premises_gst_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['premises_gst_doc']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['premises_gst_doc'])), -1))[0],23);?></a>
                    <?php } ?>
                
                <div class="custom-file col-sm-9">
                    <input type="file" name="premises_gst_doc" class="form-control" id="premises_gst_doc", multiple='multiple'>
                    <span id="error_premises_gst_doc" class="error invalid-feedback"></span>
                    <span id="error_type_premises_gst_doc" class="error invalid-feedback"></span>
                    <span id="error_size_premises_gst_doc" class="error invalid-feedback"></span>
                </div>
            </div>
        <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
    </div>
    <div class="clearfix"></div>

    <!-- for Ownership cert. -->
    <div class="d-inline-block">
        <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Ownership related Document</p>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
                    <?php if(!empty($section_form_details[0]['premises_ownership_doc'])){?>
                        <a id="premises_ownership_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['premises_ownership_doc']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['premises_ownership_doc'])), -1))[0],23);?></a>
                    <?php } ?>
                
                <div class="custom-file col-sm-9">
                    <input type="file" name="premises_ownership_doc" class="form-control" id="premises_ownership_doc", multiple='multiple'>
                    <span id="error_premises_ownership_doc" class="error invalid-feedback"></span>
                    <span id="error_type_premises_ownership_doc" class="error invalid-feedback"></span>
                    <span id="error_size_premises_ownership_doc" class="error invalid-feedback"></span>
                </div>
            </div>
        <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
    </div>
    <div class="clearfix"></div>

    <!-- for map cert. -->
    <div class="d-inline-block">
        <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Premises Map Document</p>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
                    <?php if(!empty($section_form_details[0]['premises_map_doc'])){?>
                        <a id="premises_map_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['premises_map_doc']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['premises_map_doc'])), -1))[0],23);?></a>
                    <?php } ?>
                
                <div class="custom-file col-sm-9">
                    <input type="file" name="premises_map_doc" class="form-control" id="premises_map_doc", multiple='multiple'>
                    <span id="error_premises_map_doc" class="error invalid-feedback"></span>
                    <span id="error_type_premises_map_doc" class="error invalid-feedback"></span>
                    <span id="error_size_premises_map_doc" class="error invalid-feedback"></span>
                </div>
            </div>
        <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
    </div>
    <div class="clearfix"></div>

    <!-- for Machineries Details. -->
    <div class="d-inline-block">
        <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Machineries Related Document</p>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
                    <?php if(!empty($section_form_details[0]['premises_machineries_doc'])){?>
                        <a id="premises_machineries_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['premises_machineries_doc']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$section_form_details[0]['premises_machineries_doc'])), -1))[0],23);?></a>
                    <?php } ?>
                
                <div class="custom-file col-sm-9">
                    <input type="file" name="premises_machineries_doc" class="form-control" id="premises_machineries_doc", multiple='multiple'>
                    <span id="error_premises_machineries_doc" class="error invalid-feedback"></span>
                    <span id="error_type_premises_machineries_doc" class="error invalid-feedback"></span>
                    <span id="error_size_premises_machineries_doc" class="error invalid-feedback"></span>
                </div>
            </div>
        <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
    </div><div class="clearfix"></div>