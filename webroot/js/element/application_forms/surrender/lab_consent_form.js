$(document).ready(function () {
    
    //Submission of balance printed material.
    if($('#noc_for_lab-yes').is(":checked")){
		$("#noc_for_lab_docs_block").show();
	}else if($('#noc_for_lab-no').is(":checked")){
		$("#noc_for_lab_docs_block").hide();
	}

    $('#noc_for_lab-yes').click(function(){
        $("#noc_for_lab_docs_block").show();
	});

    $('#noc_for_lab-no').click(function(){
        $("#noc_for_lab_docs_block").hide();
	});

    //Is Associated packers conveyed
    if($('#is_lab_packers_conveyed-yes').is(":checked")){
		$("#is_lab_packers_conveyed_docs_block").show();
	}else if($('#is_lab_packers_conveyed-no').is(":checked")){
		$("#is_lab_packers_conveyed_docs_block").hide();
	}

    $('#is_lab_packers_conveyed-yes').click(function(){
		$("#is_lab_packers_conveyed_docs_block").show();
	});

    $('#is_lab_packers_conveyed-no').click(function(){
		$("#is_lab_packers_conveyed_docs_block").hide();
	});


});