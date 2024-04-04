
	function field_indication(){
	
		$("#userid_indication").show().text("user id ex: 210/2016 or 210/1/NGP/001 ");
		$("#userid_indication").css({"color":"blue","font-size":"13px","text-align":"right","font-weight":"500"});
		setTimeout(function(){ $("#userid_indication").fadeOut();},15000);
	}

	function get_new_captcha(){
		$.ajax({
			type: "POST",
			async:true,
			url:"refresh_captcha_code",
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {
				$("#captcha_img").html(data);
			}
		});
	}


    //login customer validations call
    $('#login_customer_validation_call').click(function (e) { 
        e.preventDefault();
        login_customer_validations();
    });