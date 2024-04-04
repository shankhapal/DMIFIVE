$(function(){

	$(document).on('keyup keypress blur change','.cvcalyear',function(){
   
     
		//tis logic is used for calculate year difference beeteween two dates added by shankhpal shende on 27/09/2022
		var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
		$("#ta-total-"+id_No).val();
		id_No = id_No[2];
		var date1 = $("#ta-from_dt-"+id_No).val();
		var date2 = $("#ta-to_dt-"+id_No).val(); 
		var yearsDiff =  new Date(date2).getFullYear() - new Date(date1).getFullYear();
    //calculated experience difference with basis  of month and year by laxmi 10-07-2023
     const split = date1.split('-');
     const split1 = date2.split('-');

    var monthsDiff; 
    const start = new Date(split[0]+','+split[1]+','+split[2]);
    const end = new Date(split1[0]+','+split1[1]+','+split[2]);
    let month= end.getMonth() - start.getMonth() + 
    (12 * (end.getFullYear() - start.getFullYear()));
   

    
    if(month == 12){
        yearsDiff = 1;
        monthsDiff = 0;
      }else if(month > 12){
        var i = 10;
        var calMonth = 12;
        var calculated_years;
        var calculatedMonth;
        for (var k = 1; k<=i; k++){
          calculated_years = month / calMonth;
          calculatedMonth =  month % calMonth;
        }
         yearsDiff = Math.trunc(calculated_years);
         monthsDiff = calculatedMonth;
      }else{
			yearsDiff = 0;
			monthsDiff = month;
		}
		yearsDiff = Math.max(0, yearsDiff);
    monthsDiff = Math.max(0, monthsDiff);
		if( (isNaN(yearsDiff)) || yearsDiff === 0 || yearsDiff === undefined){
            yearsDiff =  0;
       }
     if((isNaN(monthsDiff)) || monthsDiff === 0 || monthsDiff === undefined ){
              monthsDiff =  0;
     }

		
		$("#ta-total-"+id_No).val(yearsDiff +"."+ monthsDiff);

	})  
  
	$('#value1, #value2').keyup(function(){

		var value1 = parseFloat($('#value1').val()) || 0;
		var value2 = parseFloat($('#value2').val()) || 0;
		$('#sum').val(value1 + value2);
	});
});

      $(document).ready(function(){
      $('form').keypress(function (event) {
          if (event.keyCode === 10 || event.keyCode === 13) {
              event.preventDefault();
          }
      });
    });
    
    
    /* custom validations functions */
    
  $(document).ready(function(){
  
    $(document).on('keyup keypress blur change','.cvOn',function(){
        
      $(this).removeClass('is-invalid'); 
      $(this).parent().next('.err_cv').text('');
      
      var formId = $(this).closest('form').attr('id');
      var input = $(this).val();
      var inputId = $(this).attr('id');
  
      var inputMin = $(this).attr('min');
      var inputMaxlength = $(this).attr('maxlength');
      var inputFloat = $(this).attr('cvfloat');
  
      var inputMinMax = "";
  
      if (typeof inputMin !== 'undefined' && inputMin !== false) {
        inputMinMax = inputMin;
      }
  
      if (typeof inputMaxlength !== 'undefined' && inputMaxlength !== false) {
        inputMinMax = inputMaxlength;
      }
  
      var inputFloatVal = "";
  
      if (typeof inputFloat !== 'undefined' && inputFloat !== false) {
        inputFloatVal = inputFloat;
      }
  
  
  
      var classArr = $(this).attr('class');
      var classArr = classArr.split(" ");
  
      var funcs = {
        'cvReq': cvReq,
        'cvNum': cvNum,
        'cvMin': cvMin,
        'cvMaxLen': cvMaxLen,
        'cvEmail': cvEmail,
        'cvFloat': cvFloat,
        'cvDate': cvDate,
        'cvAlpha': cvAlpha,
        'cvAlphaNum': cvAlphaNum,
        /*...*/
      };
  
      var cvClassArr = ['cvReq','cvNum','cvMin','cvMaxLen','cvEmail','cvFloat','cvDate','cvAlpha','cvAlphaNum'];
  
      $.each(classArr, function(item, index){
        if(jQuery.inArray(index, cvClassArr) !== -1){
          var errorText = getErrorText(index, inputMinMax, inputFloatVal);
          var funcStatus = funcs[index](formId, input, inputId);
          if(funcStatus == '1'){
            $('#'+inputId).parent().parent().find('.err_cv:first').text(errorText).css('color','red');
            disSubmitBtn(formId);
            return false;
          } else {
            $('#'+inputId).parent().parent().find('.err_cv:first').text('');
            enableSubmitBtn(formId);
          }
        }
      });
  
  
    });
  
    function getErrorText(className, inputMinMax, inputFloatVal){
      var txt;
      switch(className){
        case 'cvReq':
          txt = "Field is required";
          break;
        case 'cvNum':
          txt = "Must be numeric";
          break;
        case 'cvMin':
          txt = "Minimum value should be " + inputMinMax;
          break;
        case 'cvMaxLen':
          txt = "Maximum value should be " + inputMinMax;
          break;
        case 'cvEmail':
          txt = "Invalid email address";
          break;
        case 'cvFloat':
          txt = "Please enter a value less than or equal to " + inputFloatVal;
          break;
        case 'cvDate':
          txt = "Please enter date in dd/mm/yyyy format";
          break;
          case 'cvDateTrai':
            txt = "Please enter date in dd-mm-yyyy format";
            break;
        case 'cvAlpha':
          txt = "Please enter alphabet characters only";
          break;
        case 'cvAlphaNum':
          txt = "Please enter alphabet and numbers characters only";
          break;		
        default:
          txt = "Invalid";
  
      }
  
      return txt;
  
    }
  
  
    function cvReq(formId, input){
  
      var result = '0';
      if(input == ''){
        result = '1';
      }
  
      return result;
  
    }
  
    function cvNum(formId, input, inputId){
  
      var result = '0';
      if($.isNumeric(input)){
        result = '0';
      } else {
        $('#'+formId+' #'+inputId).val($('#'+inputId).val().replace(/[^0-9]/g, ''));
        result = '1';
      }
  
      return result;
  
    }
  
    function cvMin(formId, input, event){
  
      var min = $('#'+formId+' [name="'+event+'"]').attr('min');
  
      var result = '0';
      if(input < min){
        result = '1';
      }
  
      return result;
  
    }
  
    function cvMaxLen(formId, input, event){
  
      var maxLen = $('#'+formId+' [name="'+event+'"]').attr('maxlength');
  
      var result = '0';
      if(input.length > maxLen){
        result = '1';
      }
  
      return result;
  
    }
  
    function cvEmail(formId, input){
  
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  
      var result = '0';
      if(!regex.test(input)){
        result = '1';
      }
  
      return result;
  
    }
    
    function cvFloat(formId, input, inputId){
  
      var result = '0';
  
      var floatVal = $('#'+formId+' #'+inputId).attr('cvfloat');
  
      var floatValArr = floatVal.split('.');
      var valDec = floatValArr[1].length;
      var valNum = floatValArr[0];
  
      var inputArr = input.split('.');
      if(inputArr.length == 2){
        if(inputArr[1].length > valDec){
          $('#'+formId+' #'+inputId).val(parseFloat(input).toFixed(valDec));
        }
      }
  
      if(parseInt(inputArr[0]) > parseInt(valNum)){
        result = '1';
      }
  
      return result;
  
    }
    
    
    
     function cvAlpha(formId, input, inputId){    
      
      var regex = new RegExp("^[a-zA-Z ]+$");    
      if (regex.test(input)) {
          var result = '0';
      }else{
           result = '1';
      }
      
      return result;
  
    }
    
    
    function cvAlphaNum(formId, input, inputId){    
      
      var regex = new RegExp("^[a-zA-Z0-9 ]+$");    
      if (regex.test(input)) {
          var result = '0';
      }else{
           result = '1';
      }
      
      return result;
  
    }
    
    
    function cvDate(formId, input, inputId){
        
      var input = $.trim(input);
      var date = input.split("/");
      
      var result = '0';
      
      if(date.length == 3){
          
          var day = date[0];
          var month = date[1];  
          var regex = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{5})$/;	
          //var regex = /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
          if (regex.test(input) || input.length == 0) {
              result = '1';
  
          }
          if (day > 31) {
              result = '1';
          }
          else
              if (month > 12) {
                  result = '1';
              }
          
      }else{
          
          result = '1';
      }
      
      
      return result;
  
    }
    
    
  
    function checkAllError(){
  
      var errCount = '0';
      var element = document.getElementsByClassName('err_cv');
      for(var i=0;i<element.length;i++){
        if(element[i].innerHTML){
          errCount++;
        }
      }
  
      return errCount;
  
    }
  
    function disSubmitBtn(formId){
      //console.log(formId);
      //$('#'+formId+' :submit').prop('disabled','true');
      $(':input[type="submit"]').prop('disabled', true);
  
    }
  
    function enableSubmitBtn(formId){
  
      var errCount = checkAllError();
  
      if(errCount == 0){
        //$('#'+formId+' :submit').removeAttr('disabled');
        $(':input[type="submit"]').prop('disabled', false);
      }
  
    }
  
  });
  
  
  
  
  $(document).ready(function(){
// added below  function by laxmi to view profile and sign after upload without save on [06-07-2023]
    $('.file_profile').change(function(){
     var upload_view_src = $('.chemist_doc').attr('src');
     $('.uploadpreview').remove();
     var ext = this.value.match(/\.(.+)$/)[1];
     switch (ext) {
         case 'jpg':
         case 'jpeg':
          $('.file_profile').attr('disabled', false);
          break;
         default:
             $.alert('This is not an allowed file type.');
             this.value = '';
      }
      // var path = URL.createObjectURL(event.target.files[0]);
      // $("<img src width=auto height=80px class= uploadpreview>").insertAfter( $("#profile_img") ).fadeIn("fast").attr('src',path);
    });
    $('.file_sign').change(function(){
      $('.signpreview').remove();
      var ext = this.value.match(/\.(.+)$/)[1];
     switch (ext) {
         case 'jpg':
         case 'jpeg':
          $('.file_sign').attr('disabled', false);
          break;
         default:
             $.alert('This is not an allowed file type.');
             this.value = '';
      }
      // var path = URL.createObjectURL(event.target.files[0]);
      // $("<img src width=auto height=80px class=signpreview>").insertAfter( $("#sign_img") ).fadeIn("fast").attr('src',path);
    });

    //End by Laxmi
  
      $('.form_name').on('submit', function(){
          
          var returnFormStatus = true;
          var formSelStatus = 'valid';
          var returnEmptyStatus = formEmptyStatus('.form_name');
          returnFormStatus = (returnEmptyStatus == 'invalid') ? false : returnFormStatus; 
          
          //select field validations
          var selRw = $('.form_name').find('select').not(':hidden,.cvNotReq').not('select[disabled]').length;

           
          for(var i=0;i<selRw;i++){
              var selField = $('.form_name').find('select').not(':hidden,.cvNotReq').not('select[disabled]').eq(i).val();
              if(selField == ''){
                  showElAlrt('.form_name', 'select', i);
                  formSelStatus = 'invalid';
              
              }
          }

          // added for file upload validation by laxmi on 06-07-2023
          var selfile = $('.form-for-file-too').find('.filetype').length;
          
          for(var i=0;i<selRw;i++){
            var selfile = $('.form-for-file-too').find(".selectedFile").eq(i).val();
            var selfileselect = $('.form-for-file-too').find('.filetype').eq(i).val();
            if( selfileselect == ''){ 
              if(selfile == ''){  
                showElAlrt('.form-for-file-too', '.filetype', i);
                formSelStatus = 'invalid';
              }
                
            }
          }
             //end Laxmi
  
          if(formSelStatus == 'invalid'){
              //showAlrt('Select value from dropdown!');
              returnFormStatus = false;
          }
          
          return returnFormStatus;
      });
  
  });
  
  
  /* common empty field validations */
  function formEmptyStatus(formId){
      
      var formStatus = 'valid';
      $(formId).find('input').not(':hidden,:button').not('input[disabled]').removeClass('is-invalid');
      var inRw = $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').length;
      
      for(var i=0;i<inRw;i++){
          
          var inField = $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(i).val();
          
          var inType = $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(i).attr('type');
          if(inType == 'file'){
            var prevField = $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(i).parent().next('.hidden_doc').val();
            
            if(prevField == '' & inField == ''){

              showFieldAlrt(formId, i);
              $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(i).parent().next('.hidden_doc').next('.err_cv').text('Required Field');
              formStatus = 'invalid';
            }
            
          } else if(inType == 'radio'){
              
              var inRadioOption = $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(i).attr('name');
              var radioval = $('input[name="'+inRadioOption+'"]:checked').length;
              
              if(radioval == 0){				
                  $('input[name="'+inRadioOption+'"]').parent().find('.err_cv').text('Required Field');
                  formStatus = 'invalid';
              }
              
          }else if(inField == ''){
              
              showFieldAlrt(formId, i);
              formStatus = 'invalid';
              //this condition added on 06-02-2022 by Amol
              //to bypass these 3 sections on chemist flow, as this is not allowing to save btn to submit 
              //On 08-06-2022 : The two new form id are addded to temporary by pass the profile and education sections
              if($(formId).attr('id')=='experience' || $(formId).attr('id')=='training' || $(formId).attr('id')=='other_details' || $(formId).attr('id')=='profile' || $(formId).attr('id')=='education' ){
                  formStatus = 'valid';
              }
          }
      }
  
      if(formStatus == 'invalid'){
          //showAlrt('Invalid data !');
      }
  
      return formStatus;
  
  }
  
  
  function showFieldAlrt(formId, eqId){
  
      $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(eqId).addClass('is-invalid');
      $(formId).find('input').not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(eqId).parent().next('.err_cv').text('Required Field');
  
  }
  
  /* validation related alert messages */
  function showAlrt(msg){
      
      remAlrt();
      var alrtCon = '<div class="alrt-danger toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">';
      alrtCon += '<div class="alrt-body">';
      alrtCon += '<i class="fa fa-exclamation-triangle"></i>';
      alrtCon += '<span> '+msg+'</span>';
      alrtCon += '</div>';
      alrtCon += '</div>';
      $('.alrt-div').append(alrtCon);
      $('.alrt-danger').show();
  
  }
  
  function remAlrt(){
      $('.alrt-div .hide').remove();
  }
  
  function showElAlrt(formId, elNm, eqId){
  
      $(formId).find(elNm).not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(eqId).addClass('is-invalid');
      $(formId).find(elNm).not(':hidden,:button,.cvNotReq').not('input[disabled]').eq(eqId).parent().next('.err_cv').text('Required Field');
  
  }