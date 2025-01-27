$(document).ready(function(){
	
	var form_section_id = $('#form_section_id').val();
	var cname = $('#cname').val();

	add_function();
	edit_function();
	delete_function();
	save_function();

	function add_function(){

		$('#add_tank_details').click(function(e) { 
			e.preventDefault();
			
			
			var tank_no = $('#tank_no').val();
			var tank_shape = $('#tank_shape').val();
			var tank_size = $('#tank_size').val();
			var tank_capacity = $('#tank_capacity').val();
			
			var form_data = $("#"+form_section_id).serializeArray();
				form_data.push(	{name: "tank_no",value: tank_no},
								{name: "tank_shape",value: tank_shape},
								{name: "tank_size",value: tank_size},
								{name: "tank_capacity",value: tank_capacity},
								{name:'cname',value:cname});
			
			if(validate_tanks_details() == true){
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/addTankDetails",
					data: form_data,    
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function(response){
						
						$("#tank_table").html(response);
						$("#tank_table :input[type='text']").val('');
						add_function();
						edit_function();
						delete_function();
						save_function();	
					}                             
				}); 
			}
		});

	}



	function edit_function(){


		$('.edit_tank_id').click(function(e) { 
			e.preventDefault();
			
			var tank_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
				form_data.push({name: "edit_tank_id",value: tank_id},
							{name:'cname',value:cname});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/editTankId",
				data: form_data, 
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#tank_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();	
					
				}                             
			}); 

		});

	}


	function delete_function(){

		$('.delete_tank_id').click(function(e) { 
			e.preventDefault();
			
			var tank_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
				form_data.push({name: "delete_tank_id",value: tank_id},
							   {name:'cname',value:cname});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/deleteTankId",
				data: form_data,  
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#tank_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();	
				
				}                             
			}); 
	
		});

	}


	function save_function(){

		$('#save_tank_details').click(function(e) { 
			e.preventDefault();
			
			var edit_tank_id = '';
			var save_tank_id = $(this).attr('id');
			var tank_no = $('#tank_no').val();
			var tank_shape = $('#tank_shape').val();
			var tank_size = $('#tank_size').val();
			var tank_capacity = $('#tank_capacity').val();
			
			var form_data = $("#"+form_section_id).serializeArray();
				form_data.push({name: "save_tank_id",value: save_tank_id},
								{name: "tank_no",value: tank_no},
								{name: "tank_shape",value: tank_shape},
								{name: "tank_size",value: tank_size},
								{name: "tank_capacity",value: tank_capacity},
								{name: "edit_tank_id",value: edit_tank_id},
							   {name:'cname',value:cname});
			
			if(validate_tanks_details() == true){
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/editTankId",
					data: form_data,        
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									}, 
					success: function(response){
						
						$("#tank_table").html(response);
						$("#tank_table :input[type='text']").val('');
						add_function();
						edit_function();
						delete_function();
						save_function();	
						
					}                             
				}); 
			}
		});
		
	}

});
