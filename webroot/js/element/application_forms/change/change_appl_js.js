//$(document).ready(function() {

    //to disable all last details fields
    $(".last_details_change :input").prop("disabled",true);
	
	//to get selected the commodities or packing types from multiselect drop down in post
	$("#selected_commodity option[value!='']").prop('selected',true);
	$("#selected_packing_types option[value!='']").prop('selected',true); 

    function change_appl_validation(){

        var firm_name=$("#firm_name").val();
        var mobile_no=$("#mobile_no").val();
        var email_id=$("#email_id").val();
        var phone_no=$("#phone_no").val();
        var street_address=$("#street_address").val();
        var state=$("#state").val();
        var district=$("#district").val();
        var postal_code=$("#postal_code").val();
        var lab_name=$("#lab_name").val();
        var lab_type=$("#lab_type").val();
        var chemist_details_docs=$("#chemist_details_docs").val();
        var lab_equipped_docs=$("#lab_equipped_docs").val();
        var lab_consent_docs=$("#lab_consent_docs").val();
        var category=$("#category").val();
        var commodity=$("#commodity").val();
        var selected_commodity=$("#selected_commodity").val();
        var packing_types=$("#packing_types").val();
        var selected_packing_types=$("#selected_packing_types").val();
		var business_type=$("#business_type").val();

		//new fields added on 17-05-2023 by Amol
		var commodity_fssai_no=$("#commodity_fssai_no").val();
		var commodity_fssai_doc=$("#commodity_fssai_doc").val();
		var premises_fssai_doc=$("#premises_fssai_doc").val();
		var premises_gst_doc=$("#premises_gst_doc").val();
		var premises_ownership_doc=$("#premises_ownership_doc").val();
		var premises_map_doc=$("#premises_map_doc").val();
		var premises_machineries_doc=$("#premises_machineries_doc").val();
		var tbl_proforma_a2_doc =$("#tbl_proforma_a2_doc").val();
		
		var selectedValues = $("#selectedValues").val();
		selectedValues = selectedValues.split(",");
		
		var firm_type = $("#firm_type").val();

        var value_return = 'true';

        if(selectedValues.includes("1")==true && firm_name==""){				
            $("#error_firm_name").show().text("Please enter firm name");
            $("#firm_name").addClass("is-invalid");
            $("#firm_name").click(function(){$("#error_firm_name").hide().text; $("#firm_name").removeClass("is-invalid");});
            value_return = 'false';
        }
		
		if(selectedValues.includes("2")==true){
			if(mobile_no==""){				
				$("#error_mobile_no").show().text("Please enter mobile number");
				$("#mobile_no").addClass("is-invalid");
				$("#mobile_no").click(function(){$("#error_mobile_no").hide().text; $("#mobile_no").removeClass("is-invalid");});
				value_return = 'false';
			}
			if(email_id==""){				
				$("#error_email_id").show().text("Please enter email id");
				$("#email_id").addClass("is-invalid");
				$("#email_id").click(function(){$("#error_email_id").hide().text; $("#email_id").removeClass("is-invalid");});
				value_return = 'false';
			}
			/*if(phone_no==""){				
				$("#error_phone_no").show().text("Please enter phone number");
				$("#phone_no").addClass("is-invalid");
				$("#phone_no").click(function(){$("#error_phone_no").hide().text; $("#phone_no").removeClass("is-invalid");});
				value_return = 'false';
			}*/
		}

		if(selectedValues.includes("3")==true){
			//new validation script added for upload fields required for TBL change, 18-05-2023
			if($("#tbl_proforma_a2_doc_value").text() == ''){	
				if(check_file_upload_validation(tbl_proforma_a2_doc).result == false){			
					$("#error_tbl_proforma_a2_doc").show().text("Please select related file");
					$("#tbl_proforma_a2_doc").addClass("is-invalid");
					$("#tbl_proforma_a2_doc").click(function(){$("#error_tbl_proforma_a2_doc").hide().text; $("#tbl_proforma_a2_doc").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}
		
		if(selectedValues.includes("5")==true){
			if(street_address==""){				
				$("#error_street_address").show().text("Please enter Street Address");
				$("#street_address").addClass("is-invalid");
				$("#street_address").click(function(){$("#error_street_address").hide().text; $("#street_address").removeClass("is-invalid");});
				value_return = 'false';
			}
			if(state==""){				
				$("#error_state").show().text("Please select state");
				$("#state").addClass("is-invalid");
				$("#state").click(function(){$("#error_state").hide().text; $("#state").removeClass("is-invalid");});
				value_return = 'false';
			}
			if(district==""){				
				$("#error_district").show().text("Please district state");
				$("#district").addClass("is-invalid");
				$("#district").click(function(){$("#error_district").hide().text; $("#district").removeClass("is-invalid");});
				value_return = 'false';
			}
			if(postal_code==""){				
				$("#error_postal_code").show().text("Please enter pin code");
				$("#postal_code").addClass("is-invalid");
				$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
				value_return = 'false';
			}

			//new validation script added for upload fields required for premises change, 17-05-2023
			//added $firm_type==1 cond. on 15-07-2023 as said fssai upload will be only for CA
			if (firm_type == 1) {
				if($("#premises_fssai_doc_value").text() == ''){	
					if(check_file_upload_validation(premises_fssai_doc).result == false){			
						$("#error_premises_fssai_doc").show().text("Please select related file");
						$("#premises_fssai_doc").addClass("is-invalid");
						$("#premises_fssai_doc").click(function(){$("#error_premises_fssai_doc").hide().text; $("#premises_fssai_doc").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}

			if($("#premises_gst_doc_value").text() == ''){	
				if(check_file_upload_validation(premises_gst_doc).result == false){		
					$("#error_premises_gst_doc").show().text("Please select related file");
					$("#premises_gst_doc").addClass("is-invalid");
					$("#premises_gst_doc").click(function(){$("#error_premises_gst_doc").hide().text; $("#premises_gst_doc").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			if($("#premises_ownership_doc_value").text() == ''){
				if(check_file_upload_validation(premises_ownership_doc).result == false){			
					$("#error_premises_ownership_doc").show().text("Please select related file");
					$("#premises_ownership_doc").addClass("is-invalid");
					$("#premises_ownership_doc").click(function(){$("#error_premises_ownership_doc").hide().text; $("#premises_ownership_doc").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			if($("#premises_map_doc_value").text() == ''){	
				if(check_file_upload_validation(premises_map_doc).result == false){			
					$("#error_premises_map_doc").show().text("Please select related file");
					$("#premises_map_doc").addClass("is-invalid");
					$("#premises_map_doc").click(function(){$("#error_premises_map_doc").hide().text; $("#premises_map_doc").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			if($("#premises_machineries_doc_value").text() == ''){	
				if(check_file_upload_validation(premises_machineries_doc).result == false){			
					$("#error_premises_machineries_doc").show().text("Please select related file");
					$("#premises_machineries_doc").addClass("is-invalid");
					$("#premises_machineries_doc").click(function(){$("#error_premises_machineries_doc").hide().text; $("#premises_machineries_doc").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

		}
		
		if(selectedValues.includes("6")==true){
			if(lab_name==""){				
				$("#error_lab_name").show().text("Please enter Laboratory name");
				$("#lab_name").addClass("is-invalid");
				$("#lab_name").click(function(){$("#error_lab_name").hide().text; $("#lab_name").removeClass("is-invalid");});
				value_return = 'false';
			}
			if(lab_type==""){				
				$("#error_lab_type").show().text("Please enter Laboratory type");
				$("#lab_type").addClass("is-invalid");
				$("#lab_type").click(function(){$("#error_lab_type").hide().text; $("#lab_type").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if($('#lab_type').val() == 1){
				if($("#chemist_detail_docs_value").text() == ''){
					if(check_file_upload_validation(chemist_details_docs).result == false){			
						$("#error_chemist_details_docs").show().text("Please select related file");
						$("#chemist_details_docs").addClass("is-invalid");
						$("#chemist_details_docs").click(function(){$("#error_chemist_details_docs").hide().text; $("#chemist_details_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}

				if($("#lab_equipped_docs_value").text() == ''){
					if(check_file_upload_validation(lab_equipped_docs).result == false){			
						$("#error_lab_equipped_docs").show().text("Please select related file");
						$("#lab_equipped_docs").addClass("is-invalid");
						$("#lab_equipped_docs").click(function(){$("#error_lab_equipped_docs").hide().text; $("#lab_equipped_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}

			}else{
				if($("#consent_letter_docs_value").text() == ''){
					if(check_file_upload_validation(lab_consent_docs).result == false){			
						$("#error_lab_consent_docs").show().text("Please select related file");
						$("#lab_consent_docs").addClass("is-invalid");
						$("#lab_consent_docs").click(function(){$("#error_lab_consent_docs").hide().text; $("#lab_consent_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}

			}
			
			
			
		}
		
		
		if(selectedValues.includes("7")==true){
			
			if (firm_type == 2) {
				if(selected_packing_types==""){				
					$("#error_selected_packing_types").show().text("Please add packing type");
					$("#selected_packing_types").addClass("is-invalid");
					$("#selected_packing_types").click(function(){$("#error_selected_packing_types").hide().text; $("#selected_packing_types").removeClass("is-invalid");});
					value_return = 'false';
				}
			}else{
				if(category==""){				
					$("#error_category").show().text("Please select Category");
					$("#category").addClass("is-invalid");
					$("#category").click(function(){$("#error_category").hide().text; $("#category").removeClass("is-invalid");});
					value_return = 'false';
				}

				if(selected_commodity==""){	
					$("#error_selected_commodity").show().text("Please add commodities");
					$("#selected_commodity").addClass("is-invalid");
					$("#selected_commodity").click(function(){$("#error_selected_commodity").hide().text; $("#selected_commodity").removeClass("is-invalid");});
					value_return = 'false';
				}

				if(commodity_fssai_no==""){	
					$("#error_commodity_fssai_no").show().text("Please FSSAI No.");
					$("#commodity_fssai_no").addClass("is-invalid");
					$("#commodity_fssai_no").click(function(){$("#error_commodity_fssai_no").hide().text; $("#commodity_fssai_no").removeClass("is-invalid");});
					value_return = 'false';
				}

				//validation for FSSAI added on 17-05-2023 by Amol
				if($("#commodity_fssai_doc_value").text() == ''){

					if(check_file_upload_validation(commodity_fssai_doc).result == false){	
						
						$("#error_commodity_fssai_doc").show().text(check_file_upload_validation(commodity_fssai_doc).error_message);
						$("#commodity_fssai_doc").addClass("is-invalid");
						$("#commodity_fssai_doc").click(function(){$("#error_commodity_fssai_doc").hide().text; $("#commodity_fssai_doc").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
			
		}
		
		if(selectedValues.includes("9")==true && business_type==""){				
            $("#error_business_type").show().text("Please enter firm name");
            $("#business_type").addClass("is-invalid");
            $("#business_type").click(function(){$("#error_business_type").hide().text; $("#business_type").removeClass("is-invalid");});
            value_return = 'false';
        }

        if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}
		else{
			exit();
			
		}

    }

    $("#chemist_details_docs").change(function(){
        file_browse_onclick('chemist_details_docs');
    });

    $("#lab_equipped_docs").change(function(){
        file_browse_onclick('lab_equipped_docs');
    });

    $("#lab_consent_docs").change(function(){
        file_browse_onclick('lab_consent_docs');
    });
	//added on 03-05-2023 by Amol
	$("#rel_doc").change(function(){
        file_browse_onclick('rel_doc');
    });

	//added on 17-05-2023 for new upload fields by Amol
	$("#commodity_fssai_doc").change(function(){
        file_browse_onclick('commodity_fssai_doc');
    });
	$("#tbl_proforma_a2_doc").change(function(){
        file_browse_onclick('tbl_proforma_a2_doc');
    });
	$("#premises_fssai_doc").change(function(){
        file_browse_onclick('premises_fssai_doc');
    });
	$("#premises_gst_doc").change(function(){
        file_browse_onclick('premises_gst_doc');
    });
	$("#premises_ownership_doc").change(function(){
        file_browse_onclick('premises_ownership_doc');
    });
	$("#premises_map_doc").change(function(){
        file_browse_onclick('premises_map_doc');
    });
	$("#premises_machineries_doc").change(function(){
        file_browse_onclick('premises_machineries_doc');
    });


    function file_browse_onclick(field_id){
	
		var selected_file = $('#'.concat(field_id)).val();
		var ext_type_array = ["jpg" , "pdf"];
		
		var get_file_size = $('#'.concat(field_id))[0].files[0].size;
		var get_file_ext = selected_file.split(".");
		
		var value_return = 'true';
		
		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();
		
		if(get_file_size > 2097152){

			$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
			$("#error_size_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text; $("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('')
			value_return = 'false';
		}
		
		
		if (ext_type_array.lastIndexOf(get_file_ext) == -1){
		
			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}
		
		if(value_return == 'false')
		{
			return false;
		}
		else{
			exit();			
		}
		
	}
	
	
	//for lab details section
	$("#hide_consent_letter").hide();
	$("#show_chemist_details").show();

	$('#lab_type').change(function(){

		if($('#lab_type').val() == 1)
		{
			$("#hide_consent_letter").hide();
			$("#show_chemist_details").show();

		}
		else{
			$("#hide_consent_letter").show();
			$("#show_chemist_details").hide();
		}

	});


	if($('#lab_type').val() != ""){

		if($('#lab_type').val() != 1)
		{
			$("#hide_consent_letter").show();
			$("#show_chemist_details").hide();

		}else{

			$("#hide_consent_letter").hide();
			$("#show_chemist_details").show();

		}

	}


//});