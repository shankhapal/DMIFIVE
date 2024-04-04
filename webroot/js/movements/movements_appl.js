//added new file by Laxmi Bhadade for movement of application on 20-07-2023
$(window).on('load', function () {
	$("#appl_type").find('option:selected').removeAttr("selected");
});
$("#get_movement").click(function(e){
    if(movement_appl_validation() == false){
       
        e.preventDefault(); 
    }else{ 
        $('#movement_application').submit();
       
    }
});


//application type field not empty validations function
function movement_appl_validation(){
  
    var appl_type = $('#appl_type').val();
    var appl_id = $('#appl_id').val();
    var chemist_id  = $('#chemist_id').val();
    var value_return = true;
    if(appl_type==''){

        $("#error_appl_type").show().text("Please Select Application type");
        $("#appl_type").addClass("is-invalid");
        $("#appl_type").click(function(){$("#error_appl_type").hide().text;$("#appl_type").removeClass("is-invalid");});
        value_return = 'false';
    }
    if(appl_id==''){	

        $("#error_appl_id").show().text("Please Select Application Id");
        $("#appl_id").addClass("is-invalid");
        $("#appl_id").click(function(){$("#error_appl_id").hide().text;$("#appl_id").removeClass("is-invalid");});
        value_return = 'false';
    }
    if(appl_type !='' && appl_type == 4){	
        if(chemist_id ==''){
            $("#error_chemist_id").show().text("Please Select Chemist Id");
            $("#chemist_id").addClass("is-invalid");
            $("#chemist_id").click(function(){$("#error_appl_id").hide().text;$("#appl_id").removeClass("is-invalid");});
            value_return = 'false';
        }
    }

    if(value_return == 'false'){ 
        var msg = "Please check some fields are missing or not proper.";
        renderToast('error', msg);
        return false;
    }else{
        
        
       
    }
}

 


//to fetch application id list for specific appl type .
$('#appl_id').select2();

    $('#appl_id').on('select2:open', function() {
      $('.select2-search__field').focus();
      $('.appli_type').hide();
      $('.chemist').hide();
    var appl_type = 1;
    
    if(appl_type != ''){
        $.ajax({
            type: "POST",
            url:"../movements/get_appl_id",
            data: {appl_type:appl_type},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            }, 
       
            success: function (data) {
              
                $("#appl_id").html(''); var  resArray = JSON.parse(data);//response is JSOn encoded to parse JSON
                $("#appl_id").append("<input type= 'text' placeholder='search.' />");
                $("#appl_id").append("<option value=''>--Search Application ID--</option>");//for first option with value blank
                //taking each customer id and firm_name from array and creating options tag with value and text.
               
                $.each(resArray, function(value, value) {
                    if(appl_type != 4){
                    $("#appl_id").append($("<option></option>")
                    .attr("value", this.customer_id).text(this.customer_id +  "  "  + this.firm_name));
                    }else{
                        $("#appl_id").append($("<option></option>")
                        .attr("value", this.chemist_id).text(this.chemist_id +  "  "  + this.chemist_fname + "  " + this.chemist_lname));
                    }
                });
                //below added for dropdown search box 
                // $(function () {
                //     $("#appl_id").select2();
                // });
            }
        });
    }
});
$('.chemist').hide();
$('.appli_type').hide();

$('#appl_id').change(function(){	
    $('.chemist').hide();
    $('.appli_type').hide();
    var appl_id = $('#appl_id').val();
    
    if(appl_id != ''){
        $.ajax({
            type: "POST",
            url:"../movements/get_appl_type",
            data: {appl_id:appl_id},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            }, 
       
            success: function (data) {
                $('.appli_type').show();
                $("#appl_type").html(''); 
                var resultArray = JSON.parse(data); 
                //if application is not submitted for application ID show the message added by Vikas on [26-09-2023]
                if (resultArray.message !== undefined) { 
                 
                    $('#error_noappl label').show();
                    $('#error_noappl label').text(resultArray.message); 
                } 
                else{
                    $('#error_noappl label').hide();
                }
                
                $('.appli_type').show();
                $("#appl_type").append("<option value=''>--Select--</option>");
                
                $.each(resultArray, function(value, value) { 
                    
                        $("#appl_type").append($("<option></option>")
                        .attr("value", this.id).text(this.application_type));
                });
                
               
            }
        });
    }       
});


// for chemist application id

$('#appl_type').change(function(){	
    $(".chemist").hide();
    var appl_type = $('#appl_type').val();
    var appl_id = $('#appl_id').val();
    if((appl_type != '' && appl_type == 4) && appl_id != ''){
        $.ajax({
            type: "POST",
            url:"../movements/get_chemist_appl_id",
            data: {appl_id:appl_id},
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            }, 
       
            success: function (data) {
                $(".chemist").show();
                $("#chemist_id").html(''); 
                var resultArray = JSON.parse(data); 
                $('.chemist').show();
                $("#chemist_id").append("<option value=''>--Select--</option>");
                
                $.each(resultArray, function(value, value) { 
                   
                    $("#chemist_id").append($("<option></option>")
                    .attr("value", this.chemist_id).text(this.chemist_id +  "  "  + this.chemist_fname + "  " + this.chemist_lname));
                
                });
                
               
            }
        });
    }       
});


$(document).ready(function (){
//     var tableThLength = $(".movmentTable thead tr th").length
//     if(tableThLength == 4){
// 		$(".movmentTable").DataTable({"aoColumns": [null,null,{ "sType": "date-uk" },null],"pageLength": 50});
//     }
// });

//     jQuery.extend( jQuery.fn.dataTableExt.oSort, {
//         "date-uk-pre": function ( a ) {
//             var ukDatea = a.split('/');
//             return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
//         },
        
//         "date-uk-asc": function ( a, b ) {
//             return ((a < b) ? -1 : ((a > b) ? 1 : 0));
//         },
        
//         "date-uk-desc": function ( a, b ) {
//             return ((a < b) ? 1 : ((a > b) ? -1 : 0));
//         }
//     } );
 

    $('#movement_history').DataTable({
        "ordering": false,
        searching: false,
    });
// $('#movement_history').DataTable({
//     'pageLength':5,
//     columnDefs: [ { type: 'date', 'targets': [2] } ],
// order: [[ 2, 'desc' ]]
    
//     "order": [[2, "desc"]],
//     'pageLength':5,
//     'columnDefs': [
//         {
//             'targets': 2,
            
//             'createdCell':  function (td, cellData, rowData, row, col) {   

//                 var celltime =  cellData.split(' ')[1];
//                 var celldate = cellData.split(' ')[0];
//                 var celldate1 = celldate.split('-'); 
               
//                 var newDate = celldate1[2]+"/"+celldate1[1]+"/"+celldate1[0];                  
//                 var datestamp = new Date(newDate);
//                 $(td).html((datestamp.getDate()) + '-' + (datestamp.getMonth()+1) + '-' + datestamp.getFullYear() + ' '+celltime);
//             }
//         }
//     ],
//     "initComplete": function(settings, json) {
//         $($.fn.dataTable.tables(true)).DataTable()
//             .columns.adjust();               
//     }
//  });
});



