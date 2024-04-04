	$(document).ready(function () {
	bsCustomFileInput.init();
	});



	function ca_consent_form_validations(){

		var reason=$("#reason").val();
		var required_document=$("#required_document").val();
		var is_surrender_published = $('input[name="is_surrender_published"]:checked').val();
		var is_surrender_published_docs = $("#is_surrender_published_docs").val();

		//var is_cabook_submitted = $('input[name="is_cabook_submitted"]:checked').val(); // This code is commented out because it is not necessary by UAT Suggestion - Akash [12-05-2023]
		//var is_cabook_submitted_docs = $("#is_cabook_submitted_docs").val(); // This code is commented out because it is not necessary by UAT Suggestion - Akash [12-05-2023]

		var is_ca_have_replica = $('input[name="is_ca_have_replica"]:checked').val();
		var is_replica_submitted = $('input[name="is_replica_submitted"]:checked').val();
		var is_replica_submitted_docs = $("#is_replica_submitted_docs").val();    
		var value_return = 'true';

		//Reason and Reason Document
		if(reason==""){

			$("#error_reason").show().text("Please Enter Valid Reason!!");
			$("#reason").addClass("is-invalid");
			$("#reason").click(function(){$("#error_reason").hide().text;$("#reason").removeClass("is-invalid");});
			value_return = 'false';
		}

		if($('#required_document_value').text() == ""){
			
			if(check_file_upload_validation(required_document).result == false){
				
				$("#error_required_document").show().text(check_file_upload_validation(required_document).error_message);
				$("#required_document").addClass("is-invalid");
				$("#required_document").click(function(){$("#error_required_document").hide().text;$("#required_document").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		//Publish Surreder
		if (is_surrender_published == "yes") {

			if($('#is_surrender_published_docs_value').text() == ""){
			
				if(check_file_upload_validation(is_surrender_published_docs).result == false){
					
					$("#error_is_surrender_published_docs").show().text(check_file_upload_validation(is_surrender_published_docs).error_message);
					$("#is_surrender_published_docs").addClass("is-invalid");
					$("#is_surrender_published_docs").click(function(){$("#error_is_surrender_published_docs").hide().text;$("#is_surrender_published_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			} 
		}


		//CA Book
		//This code is commented out because it is not necessary by UAT Suggestion - Akash [12-05-2023]
		/*
			if (is_cabook_submitted == "yes") {
			
				if($('#is_cabook_submitted_docs_value').text() == ""){
				
					if(check_file_upload_validation(is_cabook_submitted_docs).result == false){
						
						$("#error_is_cabook_submitted_docs").show().text(check_file_upload_validation(is_cabook_submitted_docs).error_message);
						$("#is_cabook_submitted_docs").addClass("is-invalid");
						$("#is_cabook_submitted_docs").click(function(){$("#error_is_cabook_submitted_docs").hide().text;$("#is_cabook_submitted_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
		*/


		//Replica
		if (is_ca_have_replica == "yes") {

			if (is_replica_submitted !="" && is_replica_submitted=="yes") {

				if($('#is_replica_submitted_docs_value').text() == ""){
			
					if(check_file_upload_validation(is_replica_submitted_docs).result == false){
						
						$("#error_is_replica_submitted_docs").show().text(check_file_upload_validation(is_replica_submitted_docs).error_message);
						$("#is_replica_submitted_docs").addClass("is-invalid");
						$("#is_replica_submitted_docs").click(function(){$("#error_is_replica_submitted_docs").hide().text;$("#is_replica_submitted_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
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



	function pp_consent_form_validations(){

		var reason=$("#reason").val();
		var required_document=$("#required_document").val();
		var is_balance_printing_submitted = $('input[name="is_balance_printing_submitted"]:checked').val();
		var is_balance_printing_submitted_docs = $("#is_balance_printing_submitted_docs").val();
		var printing_declaration = $('input[name="printing_declaration"]:checked').val();
		var printing_declaration_docs = $("#printing_declaration_docs").val();
		var is_packers_conveyed = $('input[name="is_packers_conveyed"]:checked').val();
		var is_packers_conveyed_docs = $("#is_packers_conveyed_docs").val();    
		var value_return = 'true';

		//Reason and Reason Document
		if(reason==""){

			$("#error_reason").show().text("Please Enter Valid Reason!!");
			$("#reason").addClass("is-invalid");
			$("#reason").click(function(){$("#error_reason").hide().text;$("#reason").removeClass("is-invalid");});
			value_return = 'false';
		}

		if($('#required_document_value').text() == ""){
			
			if(check_file_upload_validation(required_document).result == false){
				
				$("#error_required_document").show().text(check_file_upload_validation(required_document).error_message);
				$("#required_document").addClass("is-invalid");
				$("#required_document").click(function(){$("#error_required_document").hide().text;$("#required_document").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		//Balance Print
		if (is_balance_printing_submitted == "yes") {

			if($('#is_balance_printing_submitted_docs_value').text() == ""){
			
				if(check_file_upload_validation(is_balance_printing_submitted_docs).result == false){
					
					$("#error_is_balance_printing_submitted_docs").show().text(check_file_upload_validation(is_balance_printing_submitted_docs).error_message);
					$("#is_balance_printing_submitted_docs").addClass("is-invalid");
					$("#is_balance_printing_submitted_docs").click(function(){$("#error_is_balance_printing_submitted_docs").hide().text;
					$("#is_balance_printing_submitted_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			} 
		}


		//Printing Declaration
		if (printing_declaration == "yes") {
			
			if($('#printing_declaration_docs_value').text() == ""){
			
				if(check_file_upload_validation(printing_declaration_docs).result == false){
					
					$("#error_printing_declaration_docs").show().text(check_file_upload_validation(printing_declaration_docs).error_message);
					$("#printing_declaration_docs").addClass("is-invalid");
					$("#printing_declaration_docs").click(function(){$("#error_printing_declaration_docs").hide().text;
					$("#printing_declaration_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}

		//Packers Conveyed
		if (is_packers_conveyed == "yes") {
			
			if($('#is_packers_conveyed_docs_value').text() == ""){
			
				if(check_file_upload_validation(is_packers_conveyed_docs).result == false){
					
					$("#error_is_packers_conveyed_docs").show().text(check_file_upload_validation(is_packers_conveyed_docs).error_message);
					$("#is_packers_conveyed_docs").addClass("is-invalid");
					$("#is_packers_conveyed_docs").click(function(){$("#error_is_packers_conveyed_docs").hide().text;$("#is_packers_conveyed_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
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



	function lab_consent_form_validations(){

		var reason=$("#reason").val();
		var required_document=$("#required_document").val();
		var noc_for_lab = $('input[name="noc_for_lab"]:checked').val();
		var noc_for_lab_docs = $("#noc_for_lab_docs").val();
		var is_lab_packers_conveyed = $('input[name="is_lab_packers_conveyed"]:checked').val();
		var is_lab_packers_conveyed_docs = $("#is_lab_packers_conveyed_docs").val();    
		var value_return = 'true';

		//Reason and Reason Document
		if(reason==""){

			$("#error_reason").show().text("Please Enter Valid Reason!!");
			$("#reason").addClass("is-invalid");
			$("#reason").click(function(){$("#error_reason").hide().text;$("#reason").removeClass("is-invalid");});
			value_return = 'false';
		}

		if($('#required_document_value').text() == ""){
			
			if(check_file_upload_validation(required_document).result == false){
				
				$("#error_required_document").show().text(check_file_upload_validation(required_document).error_message);
				$("#required_document").addClass("is-invalid");
				$("#required_document").click(function(){$("#error_required_document").hide().text;$("#required_document").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		//NOC 
		if (noc_for_lab == "yes") {

			if($('#noc_for_lab_docs_value').text() == ""){
			
				if(check_file_upload_validation(noc_for_lab_docs).result == false){
					
					$("#error_noc_for_lab_docs").show().text(check_file_upload_validation(noc_for_lab_docs).error_message);
					$("#noc_for_lab_docs").addClass("is-invalid");
					$("#noc_for_lab_docs").click(function(){$("#error_noc_for_lab_docs").hide().text;
					$("#noc_for_lab_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			} 
		}

		//Packers Conveyed
		if (is_lab_packers_conveyed == "yes") {
			
			if($('#is_lab_packers_conveyed_docs_value').text() == ""){
			
				if(check_file_upload_validation(is_lab_packers_conveyed_docs).result == false){
					
					$("#error_is_lab_packers_conveyed_docs").show().text(check_file_upload_validation(is_lab_packers_conveyed_docs).error_message);
					$("#is_lab_packers_conveyed_docs").addClass("is-invalid");
					$("#is_lab_packers_conveyed_docs").click(function(){$("#error_is_lab_packers_conveyed_docs").hide().text;$("#is_lab_packers_conveyed_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
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
