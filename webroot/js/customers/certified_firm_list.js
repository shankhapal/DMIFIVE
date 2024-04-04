$(document).ready(function(){
$(".box").change(function () {
   $('.box').not(this).prop('checked', false);

   $('.cont').hide();
});
});



//for list display
$(function() {
$("#search_btn").click(function() {
var name = $('input[type="radio"]:checked').attr('name');
 var value =$('input[type="radio"]:checked').val();

var save = $('#search_btn').val(); console.log(save);
if(name == null  || name == "undefined"){
     $("#error_msg").show();
    $("#error_msg").text("Please select any option");
    $('input[type="radio"]').click(function(){
         $("#error_msg").hide();

    });
     $('#search_text').click(function(){
         $("#error_msg").hide();
          
    });
  return false;  
 
} else {

 //for redirect to controller
  
$.ajax({
  type: "POST",
  url: "../customers/certified_firm_list", 
  data:{type:value, save:save},
 cache: false,
 beforeSend: function (xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },

 success: function (result) {
 $('#table-id').DataTable().destroy();
  $('.cont').show();
  $('customer_data').show();
  $('#customer_data').html(result);
  $('#table-id').DataTable(); 
  $(".form_spinner").hide();
    return result;

},
error: function () {
    alert('Something went wrong');
}
});

return false;
}
});
});

