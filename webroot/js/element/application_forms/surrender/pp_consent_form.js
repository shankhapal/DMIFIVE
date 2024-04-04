$(document).ready(function () {
    
    //Submission of balance printed material.
    if($('#is_balance_printing_submitted-yes').is(":checked")){
		$("#is_balance_printing_submitted_docs_block").show();
	}else if($('#is_balance_printing_submitted-no').is(":checked")){
		$("#is_balance_printing_submitted_docs_block").hide();
	}

    $('#is_balance_printing_submitted-yes').click(function(){
        $("#is_balance_printing_submitted_docs_block").show();
	});

    $('#is_balance_printing_submitted-no').click(function(){
        $("#is_balance_printing_submitted_docs_block").hide();
	});


    //Declaration of not to print under Agmark.
    if($('#printing_declaration-yes').is(":checked")){
		$("#printing_declaration_docs_block").show();
	}else if($('#printing_declaration-no').is(":checked")){
		$("#printing_declaration_docs_block").hide();
	}

    $('#printing_declaration-yes').click(function(){
		$("#printing_declaration_docs_block").show();
	});

    $('#printing_declaration-no').click(function(){
		$("#printing_declaration_docs_block").hide();
	});


    //Is Associated packers conveyed
    if($('#is_packers_conveyed-yes').is(":checked")){
		$("#is_packers_conveyed_docs_block").show();
	}else if($('#is_packers_conveyed-no').is(":checked")){
		$("#is_packers_conveyed_docs_block").hide();
	}

    $('#is_packers_conveyed-yes').click(function(){
		$("#is_packers_conveyed_docs_block").show();
	});

    $('#is_packers_conveyed-no').click(function(){
		$("#is_packers_conveyed_docs_block").hide();
	});


});