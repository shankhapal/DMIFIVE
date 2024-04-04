// file created by laxmi on 23-12-22
$(document).ready(function(){
  $('.ro_to_ral').DataTable();
  //datepicker added by laxmi on 28-12-2022
  // The Calender
  


  $('#sheduleFrom').datepicker({
    setDate: new Date(),
    autoclose: true,
    startDate:'+0d',
    format: 'dd/mm/yyyy',
  })
  .on('changeDate', function (selected) { 
    $('#sheduleTo').val('');
    startDate = new Date(selected.date.valueOf());
    //startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
    startDate.setDate(startDate.getDate() + 1);    
    $('#sheduleTo').datepicker('setStartDate', startDate);
});


  $('#sheduleTo').datepicker({
    
    autoclose: true,
    setDate: new Date(),
    startDate:'+0d',
    format: 'dd/mm/yyyy',
 
    
  })
  .on('changeDate', function (selected) { 
    FromEndDate = new Date(selected.date.valueOf());
    FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
   
    $('#sheduleFrom').datepicker('setEndDate', FromEndDate);
});

	$('#btnSubmit').on('click', function() {
    var roOffice = $('#roOffice').val();
     var scheduleDateFrom = $('#sheduleFrom').val();
      var scheduleDateTo= $('#sheduleTo').val();
     
    
    if(roOffice == ""  || roOffice == null){
     
     $('.err_cv_ro_office').html("Please select RAL Office");
      return false;
     
     }
      
		
    if(scheduleDateFrom == ""){
     $('.err_cv_shedule_from').html("Please select  From date");
     return false;
    }
    
    if(scheduleDateTo == ""){
     $('.err_cv_shedule_to').html("Please select To date");
     return false;
    }

    return true;
});
});

jQuery(document).ready(function($) {
  $(function() {
  $("#trainingCompleted").on("click",function() {
    if($('#trainingCompleted').is(':checked')){

      $('#submitbtn').show();
    }else{
      $('#submitbtn').hide();
    }
    
  });
});

});


//for confirm dates open quickly reschedule form and set values
$(document).ready(function(){
  
  var ConfirmClickYes = window.localStorage.getItem('confirmClick');
  if(ConfirmClickYes == 'yes'){
    window.localStorage.removeItem('confirmClick');
   $('.submitReschedule')[0].click();
  }

  // to hide error msg added below by laxmi on 01-09-2023
  $('#roOffice').click(function(){
   $('.err_cv_ro_office').hide();
  });
  $('#sheduleFrom').click(function(){
    $('.err_cv_shedule_from').hide();
    $('#sheduleTo').val('');
   });
   $('#sheduleTo').click(function(){
    $('.err_cv_shedule_to ').hide();
   });


   //onclick submit btn of training schedule at ro added by laxmi on 04-09-2023
   
$('#submit').on('click', function() {
     var scheduleDateFrom = $('#sheduleFrom').val();
     var scheduleDateTo= $('#sheduleTo').val();
     var valueReturn = true;
      if(scheduleDateFrom == ""){
        $('.err_cv_shedule_from').html("Please select  From date");
        valueReturn = false;
      }
    
    if(scheduleDateTo == ""){
     $('.err_cv_shedule_to').html("Please select To date");
     valueReturn = false;
    }
    if(valueReturn == false){
      return false;
    }else{
     return true;
    }
});

});