$(document).ready(function () {
    
    //For Surrender Publishing Block
    if($('#is_surrender_published-yes').is(":checked")){
		$("#is_surrender_published_docs_block").show();
	}else if($('#is_surrender_published-no').is(":checked")){
		$("#is_surrender_published_docs_block").hide();
	}

    $('#is_surrender_published-yes').click(function(){
        $("#is_surrender_published_docs_block").show();
	});

    $('#is_surrender_published-no').click(function(){
        $("#is_surrender_published_docs_block").hide();
	});


    //For CA Book Block
	// This code is commented out because it is not necessary by UAT Suggestion - Akash [12-05-2023]
    /*----------------------------------------------------
		if($('#is_cabook_submitted-yes').is(":checked")){
			$("#is_cabook_submitted_docs_block").show();
		}else if($('#is_cabook_submitted-no').is(":checked")){
			$("#is_cabook_submitted_docs_block").hide();
		}

		$('#is_cabook_submitted-yes').click(function(){
			$("#is_cabook_submitted_docs_block").show();
		});

		$('#is_cabook_submitted-no').click(function(){
			$("#is_cabook_submitted_docs_block").hide();
		});
	-----------------------------------------------------*/


    //For Replica Submission
    if($('#is_ca_have_replica-yes').is(":checked")){
		$("#is_ca_have_replica_block").show();
	}else if($('#is_ca_have_replica-no').is(":checked")){ 
		$("#is_ca_have_replica_block").hide();
	}

    $('#is_ca_have_replica-yes').click(function(){
		$("#is_ca_have_replica_block").show();
	});

    $('#is_ca_have_replica-no').click(function(){
		$("#is_ca_have_replica_block").hide();
	});

    if($('#is_replica_submitted-yes').is(":checked")){
		$("#is_replica_submitted_docs_block").show();
	}else if($('#is_replica_submitted-no').is(":checked")){ 
		$("#is_replica_submitted_docs_block").hide();
	}

    $('#is_replica_submitted-yes').click(function(){
		$("#is_replica_submitted_docs_block").show();
	});

    $('#is_replica_submitted-no').click(function(){
		$("#is_replica_submitted_docs_block").hide();
	});




});