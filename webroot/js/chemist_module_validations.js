	$(document).ready(function(){
		//added on 12-08-2017 by Amol to avoid copy paste on confirm email field
		$('#confirm_email').bind("cut copy paste",function(e) {
			e.preventDefault();
		});

		chemist_registration_validations();
	});

	$("#profile_photo").change(function(){
		
		file_browse_onclick('profile_photo');
		return false;
	});



	//File validation common function
	//This function is called on file upload browse button to validate selected file
	function file_browse_onclick(field_id){

		var selected_file = $('#'.concat(field_id)).val();
		var ext_type_array = ["jpg" , "pdf"];
		var get_file_size = $('#'.concat(field_id))[0].files[0].size;
		var get_file_ext = selected_file.split(".");
		var validExt = get_file_ext.length-1;
		var value_return = 'true';

		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

		if(get_file_size > 2097152){

			$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
			$("#error_size_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(ext_type_array.lastIndexOf(get_file_ext) == -1){

			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if (validExt != 1){

			$("#error_type_".concat(field_id)).show().text("Invalid file uploaded");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}

	}
	
	
	//This below startDate adn endDate is changed to - 18y-66y - Akash[13-04-2023]
	$(document).ready(function () {
		$('#dob').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			 startDate: '-66y',
			 endDate: '-18y'
		});
	});

	function chemist_registration_validations(){

		$("#add_chemist").click(function(){

			var fname = $("#chemist_fname").val();
			var lname = $("#chemist_lname").val();
			var email = $("#email").val();
			var mobile = $("#mobile").val();
			var dob = $("#dob").val();
            //variable set by laxmi for chemist_training completed or not radio 
            var is_training_completed = $("#approved").val();
			var value_return = 'true';
            //category and commodity added by laxmi on 10-07-2023
			var selectedCom = $("#selected_commodity").val();
			var category = $("commodity_category").val();
			var commodity = $("commodity").val();


			if(check_alpha_character_validation(fname).result == false){

				$("#error_f_name").show().text(check_alpha_character_validation(fname).error_message);
				$("#chemist_fname").addClass("is-invalid");
				$("#chemist_fname").click(function(){$("#error_f_name").hide().text;$("#chemist_fname").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(check_alpha_character_validation(lname).result == false){

				$("#error_lname").show().text(check_alpha_character_validation(lname).error_message);
				$("#chemist_lname").addClass("is-invalid");
				$("#chemist_lname").click(function(){$("#error_lname").hide().text;$("#chemist_lname").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(dob==""){

				$("#error_dob").show().text("Please Enter Date of Birth");
				$("#dob").addClass("is-invalid");
				$("#dob").click(function(){$("#error_dob").hide().text;$("#dob").removeClass("is-invalid");});
				value_return = 'false';
            }else{
				//added else to do not enter dob below 18 years by laxmi on 28-07-2023
				var userDateinput = dob;
							// convert user input value into date object
				var birthDate = new Date(userDateinput);
				// get difference from current date;
				var difference=Date.now() - birthDate.getTime(); 
					
				var  ageDate = new Date(difference); 
				var calculatedAge=   Math.abs(ageDate.getUTCFullYear() - 1970);
				
				if(calculatedAge < 18){
				$("#error_dob").show().text("Please Do Not Enter Date of Birth less than 18 Years");
				$("#dob").addClass("is-invalid");
				$("#dob").click(function(){$("#error_dob").hide().text;$("#dob").removeClass("is-invalid");});
				
				value_return = 'false'; 
					 
				}else{
					value_return = 'true';
				}
				
			}


			if(email==""){

				$("#error_email").show().text("Please enter email address.");
				$("#email").addClass("is-invalid");
				$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
				value_return = 'false';
			}else{

				if(!email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){

					$("#error_email").show().text("Entered email id is not valid.");
					$("#email").addClass("is-invalid");
					$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
					value_return = 'false';

				}

			}

			if(mobile==""){

				$("#error_mobile").show().text("Please Enter mobile No.");
				$("#mobile").addClass("is-invalid");
				$("#mobile").click(function(){$("#error_mobile").hide().text;$("#mobile").removeClass("is-invalid");});
				value_return = 'false';
			}else{

				if(!(mobile.match(/^(?=.*[0-9])[0-9]{10}$/g)))//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol
				{
					$("#error_mobile").show().text("Mobile no. is not valid, only 10 digits no. allowed");
					$("#mobile").addClass("is-invalid");
					$("#mobile").click(function(){$("#error_mobile").hide().text;$("#mobile").removeClass("is-invalid");});
					value_return = 'false';
				}

				//In this array added '6' as suggested by Tarun Sir on 26-04-2023 - Akash [27-04-2023]
				var validfirstno = ['6','7','8','9'];
				//get first character of mobile no.
				var f_m_no = mobile.charAt(0);
				if($.inArray(f_m_no,validfirstno) != -1){
					//valid
				}else{

					$("#error_mobile").show().text("Invalid mobile number");
					$("#mobile").addClass("is-invalid");
					$("#mobile").click(function(){$("#error_mobile").hide().text;$("#mobile").removeClass("is-invalid");});
					value_return='false';
				}

				/*}else{

					if(!(mobile.match(/^(?=.*[0-9])[0-9]{10}$/g) || mobile.match(/^[X-X]{6}[0-9]{4}$/i)))//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol
					{
						$("#error_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
						$("#mobile").addClass("is-invalid");
						$("#mobile").click(function(){$("#error_mobile_no").hide().text;$("#mobile").removeClass("is-invalid");});
						value_return = 'false';
					}

				}*/

}

			//to select chemist alredy approved for training or not radio button added by laxmi B on 16-12-2022
			if(is_training_completed == ""){

				$("#error_is_training_completed").show().text("Please Select any option");
				$("#approved").addClass("is-invalid");
				$("#approved").click(function(){$("#error_is_training_completed").hide().text;$("#approved").removeClass("is-invalid");});
				value_return = 'false';
			}
            //commodity and category not empty validation added by laxmi on 10-07-2023
			if(selectedCom == '' || selectedCom == undefined){
			  if(category == '' || category == undefined){
				$("#error_commodity_category").show().text("Please select commodity category");
				$("#commodity_category").addClass("is-invalid");
				$("#commodity_category").click(function(){$("#error_commodity_category").hide().text;$("#commodity_category").removeClass("is-invalid");});
				value_return = 'false';
			  }

			   if(commodity == '' || commodity == undefined){
				 $("#error_commodity").show().text("Please select commodity category");
				 $("#commodity").addClass("is-invalid");
				 $("#commodity").click(function(){$("#error_commodity").hide().text;$("#commodity").removeClass("is-invalid");});
				 value_return = 'false';
			    }
			}

			if(value_return == 'false'){
				var msg = "Please check some fields are missing or not proper.";
				renderToast('error', msg);
				return false;
			
			}else{

				$("#reg_customer_form").submit();
			}
		});

	}


	$( document ).ready(function() {

		$("#state").change(function(){

			var stateid = $(this).val();

			$.ajax({

				type:'POST',
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				url:'../AjaxFunctions/show_district_dropdown',
				data:{ state:stateid },
				success:function(response){

					$('#district').empty();
					$('#district').html(response);
				}


			});


		});


		var application_dashboard = $("#application_dashboard").val();
		var final_submit_status = $("#final_submit_status").val();
		var form_status = $("#form_status").val();
		var final_submt_btn = $("#final_submt_btn").val();

		if(application_dashboard == 'ro'){

			$('.table_form').ready(function() {

				$("#form_outer_main :input").prop("disabled", true);
				$("#form_outer_main :input[type='radio']").prop("disabled", true);
				$("#form_outer_main :input[type='select']").prop("disabled", true);
				$("#form_outer_main :input[type='submit']").css('display','none');
				$("#form_outer_main :input[type='reset']").css('display','none');
				$("#form_outer_main :input[type='button']").prop("disabled", true);
				$(".remove_btn,#add_more").remove();
			});

			if(final_submit_status == 'referred_back' || final_submit_status == 'approved' || form_status == 'approved'){
				$(".remark_action,#add_more,#ro_referred_back,#final_submit_referred_back,#approved,#reffered_back_comment_bx").remove();
			}

		}else if(application_dashboard == 'chemist'){

			if(final_submit_status == 'pending' || final_submit_status == 'replied' || final_submit_status == 'approved' || form_status == 'approved'){

				$('.table_form').ready(function() {

					$("#form_outer_main :input").prop("disabled", true);
					$("#form_outer_main :input[type='radio']").prop("disabled", true);
					$("#form_outer_main :input[type='select']").prop("disabled", true);
					$("#form_outer_main :input[type='submit']").css('display','none');
					$("#form_outer_main :input[type='reset']").css('display','none');
					$("#form_outer_main :input[type='button']").prop("disabled", true);
					$(".remove_btn,#add_more,#save_btn,#final_submit,#reffered_back_comment_bx").remove();
				});

			}
			// else if(final_submit_status == 'referred_back' && form_status != 'referred_back'){

			// 	$('.table_form').ready(function() {

			// 		$("#form_outer_main :input").prop("disabled", true);
			// 		$("#form_outer_main :input[type='radio']").prop("disabled", true);
			// 		$("#form_outer_main :input[type='select']").prop("disabled", true);
			// 		$("#form_outer_main :input[type='submit']").css('display','none');
			// 		$("#form_outer_main :input[type='reset']").css('display','none');
			// 		$("#form_outer_main :input[type='button']").prop("disabled", true);
			// 		$(".remove_btn,#add_more,#save_btn,#final_submit,#reffered_back_comment_bx").hide();

			// 		if(final_submt_btn == 'yes'){
			// 			$('#final_submit').show();
			// 		}
			// 	});


			// }

			// if(form_status == '' || form_status == 'saved'){
			// 	$(".comment_bx_container").remove();
			// }



		}



		$('#referredbackcommenttable').on('click','tbody tr .che-referred-back-comment-bx', function(){

			if(application_dashboard == 'ro'){
				var comment = $(this).closest("tr").find('td:eq(1)').text();
			}if(application_dashboard == 'chemist'){
				var comment = $(this).closest("tr").find('td:eq(3)').text();
					//$("#form_outer_main :input").prop("disabled", false);
					//$("#form_outer_main :input[type='radio']").prop("disabled", false);
					//$("#form_outer_main :input[type='select']").prop("disabled", false);
					//$(".remove_btn,#add_more,#save_btn,#reffered_back_comment_bx").show();
					$('#save_btn').val('Save');
			}
			var reffered_back_id = $(this).attr('id');
			$('#reffered_back_comment_bx').val(comment);
			$(this).closest("tr").remove();
			$('#reffered_back_id').val(reffered_back_id);


		});


		//FOR CHECKING THE MOBILE NUMBER ALREADY EXITS AJAX AND VALIDATION IS ADDED BY AKASH ON 16-12-2021
		$('#mobile').focusout(function(){

			var mobile = $("#mobile").val();

			$.ajax({
				type : 'POST',
				url : '../AjaxFunctions/check_mobile_number_exist_in_chemist_table',
				async : true,
				data : {mobile:mobile},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success : function(response){

					if($.trim(response)=='yes'){

						$.alert({
							title: "Alert!",
							content: 'Enetered Mobile Number is already exist !!',
							type: 'red',
							typeAnimated: true,
							buttons: {
								Ok: {
									text: 'Ok',
									btnClass: 'btn-red',
									action: function(){
										$("#mobile").val('');
									}
								},
							}
						});
					}
				}
			});
		});

		//FOR CHECKING THE EMAIL ALREADY EXITS AJAX AND VALIDATION IS ADDED BY AKASH ON 16-12-2021
		$('#email').focusout(function(){

			var email = $("#email").val();

			$.ajax({
				type : 'POST',
				url : '../AjaxFunctions/checkEmailNumberExistInChemistTable',
				async : true,
				data : {email:email},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success : function(response){

					if($.trim(response)=='yes'){

						$.alert({
							title: "Alert!",
							content: 'Enetered email  is already exist !!',
							type: 'red',
							typeAnimated: true,
							buttons: {
								Ok: {
									text: 'Ok',
									btnClass: 'btn-red',
									action: function(){
										$("#email").val('');
									}
								},
							}
						});
					}
				}
			});
		});






	});

	// function for Alpha character, whitespace character and blank value validation by pravin 10-07-2017
	function check_alpha_character_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 50 character alphabets value allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^[A-z ]{1,50}$/) == null)
		{

			return {result: false, error_message: error_message1};

		}else{

			//if(field_length == update_field_value){

			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{

				return true;

			}else{

				return {result: false, error_message: error_message1};
			}
		}

	}

	function renderToast(theme, msgTxt) {

		$('#toast-msg-'+theme).html(msgTxt);
		$('#toast-msg-box-'+theme).fadeIn('slow');
		$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');

	}



	$(document).ready(function(){

		$('#education').on('change', '.cvFile', function(){

			var selected_file = $(this).val();
			var field_id = $(this).attr('id');
			var ext_type_array = ["pdf"];
			var get_file_size = $('#'.concat(field_id))[0].files[0].size;
			var get_file_ext = selected_file.split(".");
			var validExt = get_file_ext.length-1;
			var value_return = 'true';
		
			get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();
		
			if(get_file_size > 5097152){
		
			  $("#".concat(field_id)).parent().append('<span class="err">Please select file below 5mb </span>').css('color','red');
			  $("#".concat(field_id)).addClass("is-invalid");
			  $("#".concat(field_id)).click(function(){ $("#".concat(field_id)).parent().find('.err').remove(); $("#".concat(field_id)).removeClass("is-invalid");});
			  $('#'.concat(field_id)).val('');
			  value_return = 'false';
			}
		
			if(ext_type_array.lastIndexOf(get_file_ext) == -1){
		
			  $("#".concat(field_id)).parent().append('<span class="err">Please select file type pdf only</span>').css('color','red');
			  $("#".concat(field_id)).addClass("is-invalid");
			  $("#".concat(field_id)).click(function(){ $("#".concat(field_id)).parent().find('.err').remove();  $("#".concat(field_id)).removeClass("is-invalid");});
			  $('#'.concat(field_id)).val('');
			  value_return = 'false';
			}
		
			if (validExt != 1){
		
			  $("#".concat(field_id)).parent().append('<span class="err">Invalid file uploaded</span>').css('color','red');
			  
			  $("#".concat(field_id)).addClass("is-invalid");
			  $("#".concat(field_id)).click(function(){ $("#".concat(field_id)).parent().find('.err').remove(); $("#".concat(field_id)).removeClass("is-invalid");});
			  $('#'.concat(field_id)).val('');
			  value_return = 'false';
			}
		
			if(value_return == 'false'){
			  return false;
			}else{
			  exit();
			}
		});
	});