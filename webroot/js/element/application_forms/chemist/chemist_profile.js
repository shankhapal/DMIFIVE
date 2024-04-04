

  $(document).ready(function() {
    // onchange of profile and sign save in db and preview image added by laxmi on 06-09-2023
    $('#profile_photo_prev').hide();
    $('input[type="file"][name="profile_photo"]').change(function(){
      var photo = $(this).val();
      var file = this.files[0];
      var formData = new FormData();
      formData.append('file', file);
        $.ajax({
          method: 'POST',
          url : '../chemist/chemist_photo_preview/',
          data : formData,
          mimeType: "multipart/form-data",
          processData: false,
          contentType: false,
          cache: false,
          beforeSend: function (xhr){
              xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
          }, 
          success: function(data){
            $('#profile_photo_prev').show();
            var profileImg = $('.profileImg').attr('src');
            if(profileImg == '' || profileImg == undefined){
              $('.profileImg').hide();
            }
            
            let object = JSON.parse(data);
            $('#profile_photo_prev').attr('src', object.profile_photo);
            $('#profile_photo_prev').attr('class', object.chemist_id);
           

          },
        });
      });

        // onchange of profile and sign save in db and preview image added by laxmi on 06-09-2023
    $('#profile_sign_prev').hide();
    $('input[type="file"][name="signature_photo"]').change(function(){
      var photo = $(this).val();
      var file = this.files[0];
      var formData = new FormData();
      formData.append('file', file);
        $.ajax({
          method: 'POST',
          url : '../chemist/chemist_sign_preview/',
          data : formData,
          mimeType: "multipart/form-data",
          processData: false,
          contentType: false,
          cache: false,
          beforeSend: function (xhr){
              xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
          }, 
          success: function(data){
            $('#profile_sign_prev').show();
            var sign = $('.profilesign').attr('src');
            if(sign == '' || sign == undefined){
              $('.profilesign').hide();
            }
            
            let object = JSON.parse(data);
            $('#profile_sign_prev').attr('src', object.sign);
            $('#profile_sign_prev').attr('class', object.chemist_id);
           

          },
        });
      }); 
   

    
  });