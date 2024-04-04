 
		// Change on 3/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
		$('.search_field').val('');

		$('#application_type').multiselect({
			placeholder: 'Select Application Type',
            includeSelectAllOption: true,
			nonSelectedText :'Select Application Type',
			buttonWidth: '100%',
            maxHeight: 400,
		});

		$('#office').multiselect({
			placeholder: 'Select Office',
			includeSelectAllOption: true,
			buttonWidth: '100%',
            maxHeight: 200,
		});

 

		$(document).ready(function () {
  			// This is used add the filter by date on 28-11-2023 By Vikas
			 $.fn.dataTable.moment('DD/MM/YYYY'); // Tell DataTables to use the specified date format

			  $(document).ready(function() {
			    $('#approved_applications_report_table').DataTable({
			      "order": [],
			      "columnDefs": [{
			        "targets": 9,  // Index of the date column (assuming it's the 9th column, as indexing starts from 0)
			        "type": "datetime-moment"  // Use the datetime-moment sorting type
			      }]
			    });
			  });


			$('#search_btn').click(function(){

				var from = $("#fromdate").val().split("/");
				var fromdate = new Date(from[2], from[1] - 1, from[0]);

				var from = $("#todate").val().split("/");
				var todate = new Date(from[2], from[1] - 1, from[0]);

				
			 
				if(todate < fromdate){

					alert('Invalid Date Range Selection');
					return false;
				}
				
				var fromdate = $("#fromdate").val();
				var todate = $("#todate").val();
				if((todate =='' && fromdate !='')) 
                {
                    alert('Please Select Both Date Range');
                    return false;
                }

				if((fromdate =='' && todate !='')) 
                {
                    alert('Please Select Both Date Range');
                    return false;
                } 	
			});

			$('html, body').animate({
        		scrollTop: $('#page-load').offset().top
    		}, 'slow');


			// Initialize the datepicker for the 'todate' input By Vikas on [28-11-2023]
		   $('#fromdate').datepicker({
		     endDate: 'd', // This sets the end date to Today
		     orientation: "left top",
		     autoclose: true,
		     format: "dd/mm/yyyy", // Change the format as needed
		 	}); 

		   $('#todate').datepicker({
		     endDate: 'd', // This sets the end date to Today
		     orientation: "left top",
		     autoclose: true,
		     format: "dd/mm/yyyy", // Change the format as needed
			 });

 
		});
