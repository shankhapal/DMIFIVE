// // User want to edit data or update
// // DESCRIPTION : FOR CHECKING THE SESSION IF SESSION IS YES , RETURN THE YES OR NO AND enabled all form fields
// // @AUTHOR : SHANKHPAL SHENDE
// // DATE : 24-11-2022
// $(document).ready(function () {
//   $("#wanttoedit").click(function (event) {
//     event.preventDefault();
//     enable_disable();
//   });

//   function enable_disable() {
//     var checkeditsession = $("#checkeditsession").val();

//     if (checkeditsession == "yes") {
//       var formId = $("#routine_inspection").prop("id");

//       $("#form_outer_main :input[type='submit']").show();
//       $("#form_outer_main :input[type='radio']").prop("disabled", false);

//       $('#routine_inspection select[name="approved_chemist"]').prop(
//         "disabled",
//         false
//       );
//       $('#routine_inspection select[name="name_of_packers"]').prop(
//         "disabled",
//         false
//       );

//       $("#p_analytical_reg,#shortcomings_noticed, #suggestions").prop(
//         "disabled",
//         false
//       );

//       $("#form_outer_main :input[type='select']").prop("disabled", false);
//       $("#form_outer_main :input[type='submit']").css("display", "none");
//       $("#form_outer_main :input[type='button']").prop("disabled", false);
//       $(".form_outer_main .custom-file").css("display", "none");

//       $("#save_btn").show();
//       $("#reset_btn").show();
//       $(".glyphicon-edit").show();

//       $("#form_outer_main :input[type='text']").prop("disabled", false);

//       $("#form_outer_main :input[type='file']").prop("disabled", false);
//       $("#form_outer_main .file_limits").show();

//       $(".acols").show();
//     } else {
//       $.ajax({
//         type: "POST",
//         url: "../AjaxFunctions/check-IfSesion-IsExists",
//         async: false,
//         beforeSend: function (xhr) {
//           xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
//         },
//         success: function () {
//           location.reload();
//         },
//       });
//     }
//   }
// });

// window.onload = function () {
//   var checkeditsession = $("#checkeditsession").val();
//   if (checkeditsession == "yes") {
//     $("#wanttoedit").click();
//     $("#wanttoedit").hide();
//   }
// };
