//file created on 05-01-2024 by Amol, to manage Lab. export with Nabl date while adding firm

$(document).ready(function () {

    //when is already granted radio button clicked with YES option
    $('#radioPrimary1').click(function(){

        if($('#certification_type').val()==3 && $('#radioSuccess1').is(":checked")){

            $("#nabl_dt_field").show();

        }else{
            $("#nabl_dt_field").hide();
            $("#nabl_dt_field").val(null);
        }
    });

    //when is export radio button clicked with Export option
    $('#radioSuccess1').click(function(){

        if($('#certification_type').val()==3){
            $("#nabl_dt_field").show();

        }else{
            $("#nabl_val_dt").val(null);
            $("#nabl_dt_field").hide();
        }

    });

    //when is export radio button clicked with Domestic option
    $('#radioSuccess2').click(function(){

        $("#nabl_val_dt").val(null);
        $("#nabl_dt_field").hide();

    });

    //when is already granted radio button clicked with NO option
    $('#radioPrimary2').click(function(){

        if($('#certification_type').val()==3 && $('#radioSuccess1').is(":checked")){
            $("#nabl_val_dt").val(null);
        }

    });

    $('#nabl_val_dt').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: new Date(),//can not select back date
        clearBtn: true
    });

    //when is already granted radio button already checked with YES option
    if($('#radioPrimary1').is(":checked")){

        if($('#certification_type').val()==3 && $('#radioSuccess1').is(":checked")){

            $("#nabl_dt_field").show();

        }

    }

});

