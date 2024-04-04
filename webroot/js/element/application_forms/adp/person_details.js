// LAB FIRM OTHER JS

var person_details_value = $('#chemist_details_value_id').val();
var final_submit_status = $('#final_submit_status_id').val();

//var export_unit_status = $('#export_unit_status_id').val();

//applied this script on 24-05-2022 by Amol
//to disable Accreditation field once scrutinized, applicant can not change
if(final_submit_status=='approved'){
    
    if($('#is_accreditated-yes').is(':checked')) {
        $("#is_accreditated-no").prop('disabled',true);
    }
    if($('#is_accreditated-no').is(':checked')) {
        $("#is_accreditated-yes").prop('disabled',true);
    }
    
}

$("#chemists_details_docs").change(function(){
    file_browse_onclick('chemists_details_docs');
    return false;
});


$("#edit_person_details").click(function(e){
    if(person_table_validation()==false){
        e.preventDefault();
    }
});

$("#add_person_details").click(function(e){

    if(person_table_validation()==false){
        e.preventDefault();
    }
});


$("#chemists_employed_docs").change(function(){
    file_browse_onclick('chemists_employed_docs');
    return false;
});

$("#premises_belongs_to_docs").change(function(){
    file_browse_onclick('premises_belongs_to_docs');
    return false;
});

$("#total_area_covered_docs").change(function(){
    file_browse_onclick('total_area_covered_docs');
    return false;
});

$("#accreditation_docs").change(function(){
    file_browse_onclick('accreditation_docs');
    return false;
});

$("#apeda_docs").change(function(){
    file_browse_onclick('apeda_docs');
    return false;
});

$("#is_laboretory_equipped_docs").change(function(){
    file_browse_onclick('is_laboretory_equipped_docs');
    return false;
});


$(document).ready(function () {
    $('#nabl_accreditated_upto').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: new Date()
    });

	$("#signaturepdfformate").on('click',function(e){
		e.preventDefault();
		
		$.ajax({
			type: 'GET',
			beforeSend: function(xhr) {
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			url: '../Applicationformspdfs/signature_pdf_formate_for_adp',
			success: function() {
				// Code to execute after successful AJAX call (optional)
			}
		});

	})
});


if($('#is_laboretory_equipped-yes').is(":checked")){

    $("#laboretory_equipped_attached").show();

}else if($('#is_laboretory_equipped-no').is(":checked")){

    $("#laboretory_equipped_attached").hide();
}


$('#is_laboretory_equipped-yes').click(function(){

    $("#laboretory_equipped_attached").show();

});

$('#is_laboretory_equipped-no').click(function(){

    $("#laboretory_equipped_attached").hide();

});

if($('#is_accreditated-yes').is(":checked")){

    $("#is_accreditated_attached").show();

}else if($('#is_accreditated-no').is(":checked")){

    $("#is_accreditated_attached").hide();
}


$('#is_accreditated-yes').click(function(){

    $("#is_accreditated_attached").show();
});

$('#is_accreditated-no').click(function(){

    $("#is_accreditated_attached").hide();
});


if($('#premises_belongs_to-yes').is(":checked")){

    $("#belongs_to").hide();

}else if($('#premises_belongs_to-no').is(":checked")){

        $("#belongs_to").show();
}

$('#premises_belongs_to-yes').click(function(){

    $("#belongs_to").hide();

});

$('#premises_belongs_to-no').click(function(){

    $("#belongs_to").show();

});




	$(document).ready(function () {
		bsCustomFileInput.init();

		$('#profile_pic').on('change', function () {
			validateFile('#profile_pic', 'error_profile_pic', 200, 200);
		});

		$('#signature_docs').on('change', function () {
			validateFile('#signature_docs', 'error_signature_docs', 2481, 3507);
		});
	});

	var MAX_SIZE = 1024 * 200; // 200 KB
	var VALID_TYPES = ['image/jpeg'];


	function validateFile(inputSelector, errorContainer, maxWidth, maxHeight) {
		var input = $(inputSelector)[0];
		var file = input.files[0];
	  
		// Check if a file is selected
		if (file) {
		  // Check file size
		  if (file.size > MAX_SIZE) {
			showError(errorContainer, "File size exceeds the maximum (200KB). Please select a smaller file.", inputSelector);
			return;
		  }
	  
		  // Check file type
		  var fileType = file.type.toLowerCase();
		  if (!VALID_TYPES.includes(fileType)) {
			showError(errorContainer, 'Invalid file type. Please select a valid JPEG image.', inputSelector);
			return;
		  }
	  
		  // Use FileReader to get image dimensions
		  var reader = new FileReader();
		  reader.onload = function (e) {
			var img = new Image();
			img.src = e.target.result;
			img.onload = function () {
			  // Check image dimensions
			  if (this.width > maxWidth || this.height > maxHeight) {
				showError(errorContainer, `Image dimensions exceed the maximum allowed dimensions (${maxWidth}x${maxHeight} pixels). Please select a smaller image.`, inputSelector);
				return;
			  }
	  
			  // If all checks pass, you can proceed with uploading the image or perform other actions
			  console.log('File is valid:', file.name);
			};
		  };
		  reader.readAsDataURL(file);
		}
	  }

	  function showError(errorContainer, errorMessage, inputSelector) {
		$(`#${errorContainer}`).show().text(errorMessage);
		$(inputSelector).addClass("is-invalid");
		$(inputSelector).click(function () {
		  $(`#${errorContainer}`).hide().text;
		  $(inputSelector).removeClass("is-invalid");
		});
		setTimeout(function () {
		  $(`#${errorContainer}`).fadeOut();
		}, 5000);
	  }





	// DESIGNATED PERSON TABLE VALIDATION
	// DESCRIPTION : ----
	// @AUTHOR : SHANKHPAL SHENDE
	// DATE : 09/11/2022

	function person_table_validation(){
  
		var person_name = $("#person_name").val();
		var person_qualification = $("#person_qualification").val();
		var person_qualifi_details_doc = $("#person_qualifi_details_doc").val();
		var person_experience = $("#person_experience").val();
		var person_exp_details_doc = $("#person_exp_details_doc").val();
        
		var profile_pic = $("#profile_pic").val();
		var signature_docs = $("#signature_docs").val();
		var designation = $("#designation").val();
     
		var value_return = 'true';

        if((person_name) == ""){

			$("#error_person_name").show().text("Please Enter Persion name");
			$("#person_name").addClass("is-invalid");
			$("#person_name").click(function(){$("#error_person_name").hide().text; $("#person_name").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_person_name").fadeOut();},5000);
			value_return = 'false';
		}

		
		if((designation) == ""){

			$("#error_designation").show().text("Please Enter Designation");
			$("#designation").addClass("is-invalid");
			$("#designation").click(function(){$("#error_designation").hide().text; $("#designation").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_designation").fadeOut();},5000);
			value_return = 'false';
		}

		if((person_qualification) == ""){

			$("#error_qualification").show().text("Please Enter Qualification");
			$("#person_qualification").addClass("is-invalid");
			$("#person_qualification").click(function(){$("#error_qualification").hide().text; $("#person_qualification").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_qualification").fadeOut();},5000);
			value_return = 'false';
		}

		
		if((person_experience) == ""){

			$("#error_experience").show().text("Please Enter Experience");
			$("#person_experience").addClass("is-invalid");
			$("#person_experience").click(function(){$("#error_experience").hide().text; $("#person_experience").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_experience").fadeOut();},5000);
			value_return = 'false';
		}
		
		
		if($('#person_qualifi_details_doc').text() == "" || $('#person_exp_details_doc').text() == "" || $('#profile_pic').text() == "" || $('#signature_docs').text() == ""){
           
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(person_qualifi_details_doc) == ""){

				$("#error_person_qualifi_details_doc").show().text(check_file_upload_validation(person_qualifi_details_doc).error_message);
				$("#person_qualifi_details_doc").addClass("is-invalid");
				$("#person_qualifi_details_doc").click(function(){$("#error_person_qualifi_details_doc").hide().text; $("#person_qualifi_details_doc").removeClass("is-invalid");});
				setTimeout(function(){ $("#error_person_qualifi_details_doc").fadeOut();},5000);
				value_return = 'false';
			}
			if(check_file_upload_validation(person_exp_details_doc) == ""){
            
				$("#error_person_exp_details_doc").show().text(check_file_upload_validation(person_exp_details_doc).error_message);
				$("#person_exp_details_doc").addClass("is-invalid");
				$("#person_exp_details_doc").click(function(){$("#error_person_exp_details_doc").hide().text; $("#person_exp_details_doc").removeClass("is-invalid");});
				setTimeout(function(){ $("#error_person_exp_details_doc").fadeOut();},5000);
				value_return = 'false';
			}
			if((signature_docs) == ""){
				$("#error_signature_docs").show().text("Please select a signature image in JPG format.");
				$("#signature_docs").addClass("is-invalid");
				$("#signature_docs").click(function(){$("#error_signature_docs").hide().text; $("#signature_docs").removeClass("is-invalid");});
				setTimeout(function(){ $("#error_signature_docs").fadeOut();},5000);
				value_return = 'false';
			}
			if (profile_pic == "") {
                $("#error_profile_pic").show().text("Please select a profile picture");
                $("#profile_pic").addClass("is-invalid");
                $("#profile_pic").click(function () {
                    $("#error_profile_pic").hide().text;
                    $("#profile_pic").removeClass("is-invalid");
                });
                setTimeout(function () {
                    $("#error_profile_pic").fadeOut();
                }, 5000);
                value_return = 'false';
            } else {
                // Check if the selected file has a .jpg or .jpeg extension
                var allowedExtensions = /(\.jpg|\.jpeg)$/i;
                if (!allowedExtensions.test(profile_pic)) {
                    $("#error_profile_pic").show().text("Please select a JPG or JPEG image");
                    $("#profile_pic").addClass("is-invalid");
                    $("#profile_pic").click(function () {
                        $("#error_profile_pic").hide().text;
                        $("#profile_pic").removeClass("is-invalid");
                    });
                    setTimeout(function () {
                        $("#error_profile_pic").fadeOut();
                    }, 5000);
                    value_return = 'false';
                }
            }
			


		}


		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}


	}


    function check_file_upload_validation(field_value)
	{
		var error_message = 'Please upload the required file';

		if(field_value == "")
		{
			return {result: false, error_message: error_message};
		}

		return true;
	}