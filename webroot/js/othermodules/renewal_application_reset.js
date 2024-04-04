
$('#reset_table').DataTable();

$('#customer_id').on('change', function() {
    var selectedValue = $(this).val();
    if (selectedValue !== '') {
        showFirmDetails();
    }
});

$('#concent').hide();


	//Description : To Attach the Packer Id and the Sample Code Together on the click of the Allocate button.
	//Author : Akash Thakre
	//Date : 25-05-2023
	
	function showFirmDetails() {
		
		var customer_id = $('#customer_id').val(); 
		if (customer_id !== '') {

			$.ajax({
				url: '../othermodules/getFirmDetailsForRenewalReset',
				type: 'POST',
				data: {customer_id: customer_id},
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response) {

					var responseObject = JSON.parse(response.replace(/~/g, ''));
					console.log(responseObject);
					// Assuming you have a div element with the id "firm_details"
					var responseDiv = document.getElementById("firm_details");
            
					var htmlContent = 
					"<div class='card card-primary'>" +
						"<div class='card-header'><h3 class='card-title'>Firm Details</h3></div>" +
						"<div class='card-body'>" +
							"<dl class='row'>" +
								"<dt class='col-sm-4'>Applicant ID: </dt>" +
								"<dd class='col-sm-8'>" + responseObject.customer_id + "</dd>" +
								"<dt class='col-sm-4'>Firm Name: </dt>" +
								"<dd class='col-sm-8'>" + responseObject.firm_name + "</dd>" +
								"<dt class='col-sm-4'>Address: </dt>" +
								"<dd class='col-sm-8'> " + responseObject.street_address + ", " + responseObject.district_name + ", " + responseObject.state_name + ", " + responseObject.postal_code + "</dd>" +
								"<dt class='col-sm-4'>Export: </dt>" +
								"<dd class='col-sm-8'>" + responseObject.export_unit + "</dd>" +
								"<dt class='col-sm-4'>Form Type: </dt>" +
								"<dd class='col-sm-8'>" + responseObject.form_type + "</dd>" +
								"<dt class='col-sm-4'>Last Validity Date: </dt>" +
								"<dd class='col-sm-8'>" + responseObject.certificate_validity_date + "</dd>" +
								"<dt class='col-sm-4'>This action will take back the application to DDO: </dt>" +
								"<dd class='col-sm-8'>" + responseObject.pao_details + "</dd>" +
							"</dl>"
						"</div>"+
					"<div class='card-footer'>";
					htmlContent += "</div>";


					// Setting the HTML content of the div
					responseDiv.innerHTML = htmlContent;

                    $('#concent').show();

				}
			});

		} else {
			$('#firm_details').hide();
            $('#concent').hide();
		}

	}


	$(document).ready(function() {
		$('#submit').click(function (e) { 
			
			// Check if the checkbox is checked
			var isChecked = $('#renewal_consent').prop('checked');
			if (isChecked) {
				$('#reset_renewal').submit();
			} else {
				$.alert("Please Check the consent Check box before submit !!");
				return false;
			}
		});
	});
