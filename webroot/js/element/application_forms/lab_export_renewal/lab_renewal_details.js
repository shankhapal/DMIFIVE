
//for file uploading validation
$("#nabl_cert_docs").change(function(){
	file_browse_onclick_5mb('nabl_cert_docs');
	return false;
});

$("#apeda_cert_docs").change(function(){
	file_browse_onclick_5mb('apeda_cert_docs');
	return false;
});

$("#grading_details_docs").change(function(){
	file_browse_onclick_5mb('grading_details_docs');
	return false;
});


function lab_renewal_export_validations() {

    var accreditation_no = $("#accreditation_no").val();
    var accreditation_scope = $("#accreditation_scope").val();
    var nabl_accreditated_upto = $("#nabl_accreditated_upto").val();
    var nabl_cert_docs = $("#nabl_cert_docs").val();
    var apeda_cert_docs = $("#apeda_cert_docs").val();
    var grading_details_docs = $("#grading_details_docs").val();
    var nabl_cert_docs_value = $("#nabl_cert_docs_value").text();
    var apeda_cert_docs_value = $("#apeda_cert_docs_value").text();
    var grading_details_docs_value = $("#grading_details_docs_value").text();
    
    if(accreditation_no==""){
    
        $("#error_accreditation_no").show().text("Please enter the accreditation number!");
        $("#accreditation_no").addClass("is-invalid");
        $("#accreditation_no").click(function(){$("#error_accreditation_no").hide().text;$("#accreditation_no").removeClass("is-invalid");});
        value_return = 'false';
    }
    
    if(accreditation_scope==""){
    
        $("#error_accreditation_scope").show().text("Please enter the accreditation scope!");
        $("#accreditation_scope").addClass("is-invalid");
        $("#accreditation_scope").click(function(){$("#error_accreditation_scope").hide().text;$("#accreditation_scope").removeClass("is-invalid");});
        value_return = 'false';
    }

    if(nabl_accreditated_upto==""){
    
        $("#error_nabl_accreditated_upto").show().text("Please enter the new dates NABL accreditated upto!");
        $("#nabl_accreditated_upto").addClass("is-invalid");
        $("#nabl_accreditated_upto").click(function(){$("#error_nabl_accreditated_upto").hide().text;$("#nabl_accreditated_upto").removeClass("is-invalid");});
        value_return = 'false';
    }

    if(nabl_cert_docs_value==""){
        if(check_file_upload_validation(nabl_cert_docs).result == false){

            $("#error_nabl_cert_docs").show().text(check_file_upload_validation(nabl_cert_docs).error_message);
            $("#nabl_cert_docs").addClass("is-invalid");
            $("#nabl_cert_docs").click(function(){$("#error_nabl_cert_docs").hide().text; $("#nabl_cert_docs").removeClass("is-invalid");});

            value_return = 'false';
        }
    }
    if(apeda_cert_docs_value==""){
        if(check_file_upload_validation(nabl_cert_docs).result == false){

            $("#error_apeda_cert_docs").show().text(check_file_upload_validation(apeda_cert_docs).error_message);
            $("#apeda_cert_docs").addClass("is-invalid");
            $("#apeda_cert_docs").click(function(){$("#error_apeda_cert_docs").hide().text; $("#apeda_cert_docs").removeClass("is-invalid");});

            value_return = 'false';
        }
    }
    if(grading_details_docs_value==""){
        if(check_file_upload_validation(grading_details_docs).result == false){

            $("#error_grading_details_docs").show().text(check_file_upload_validation(grading_details_docs).error_message);
            $("#grading_details_docs").addClass("is-invalid");
            $("#grading_details_docs").click(function(){$("#error_grading_details_docs").hide().text; $("#grading_details_docs").removeClass("is-invalid");});

            value_return = 'false';
        }
    }
    
    if(value_return == 'false'){
        var msg = "Please check some fields are missing or not proper.";
        renderToast('error', msg);
        return false;
    }else{
        exit();
    }
}


$(document).ready(function () {
    $('#nabl_accreditated_upto').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: new Date()
    });
});

$(document).ready(function () {
    bsCustomFileInput.init();
  });

function check_file_upload_validation(field_value){
    var error_message = 'Please upload the required file';

    if(field_value == "")
    {
        return {result: false, error_message: error_message};
    }

    return true;
}

//File validation common function (with 5 mb size)
//This function is called on file upload browse button to validate selected file
function file_browse_onclick_5mb(field_id){

    var selected_file = $('#'.concat(field_id)).val();
    var ext_type_array = ["jpg" , "pdf",];
    var get_file_size = $('#'.concat(field_id))[0].files[0].size;
    var get_file_ext = selected_file.split(".");
    var value_return = 'true';
    get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

    if(get_file_size > 5242880){ //for lab exp ren. file size limit is 5mb

        $("#error_size_".concat(field_id)).show().text("Please select file below 5mb");
        $("#error_size_".concat(field_id)).addClass("is-invalid");
        $("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text; $("#").removeClass("is-invalid");});
        $('#'.concat(field_id)).val('')
        value_return = 'false';

    }


    if (ext_type_array.lastIndexOf(get_file_ext) == -1){

        $("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
        $("#error_type_".concat(field_id)).addClass("is-invalid");
        $("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text; $("#").removeClass("is-invalid");});
        $('#'.concat(field_id)).val('');

        value_return = 'false';
    }

    if(value_return == 'false'){
        return false;
    }else{
        exit();
    }

}