$(document).ready(function () {
  $("#analytical_result_docs").change(function () {
    file_browse_onclick("analytical_result_docs");
  });

  $("#shortcomings_noticed_docs").change(function () {
    file_browse_onclick("shortcomings_noticed_docs");
  });

  $("#signnature_of_packer_docs").change(function () {
    file_browse_onclick("signnature_of_packer_docs");
  });
  $("#signnature_of_inspecting_officer_docs").change(function () {
    file_browse_onclick("signnature_of_inspecting_officer_docs");
  });

  $("#signnature_io_docs").change(function () {
    file_browse_onclick("signnature_io_docs");
  });
  $("#authorized_signature_docs").change(function () {
    file_browse_onclick("authorized_signature_docs");
  });
});

function file_browse_onclick(field_id) {
  var selected_file = $("#".concat(field_id)).val();
  var ext_type_array = ["jpg", "pdf"];

  var get_file_size = $("#".concat(field_id))[0].files[0].size;
  var get_file_ext = selected_file.split(".");

  var value_return = "true";

  get_file_ext = get_file_ext[get_file_ext.length - 1].toLowerCase();

  if (get_file_size > 2097152) {
    $("#error_size_".concat(field_id))
      .show()
      .text("Please select file below 2mb");
    $("#error_size_".concat(field_id)).addClass("is-invalid");
    $("#".concat(field_id)).click(function () {
      $("#error_size_".concat(field_id)).hide().text;
      $("#".concat(field_id)).removeClass("is-invalid");
    });
    $("#".concat(field_id)).val("");
    value_return = "false";
  }

  if (ext_type_array.lastIndexOf(get_file_ext) == -1) {
    $("#error_type_".concat(field_id))
      .show()
      .text("Please select file of jpg, pdf type only");
    $("#error_type_".concat(field_id)).addClass("is-invalid");
    $("#".concat(field_id)).click(function () {
      $("#error_type_".concat(field_id)).hide().text;
      $("#".concat(field_id)).removeClass("is-invalid");
    });
    $("#".concat(field_id)).val("");
    value_return = "false";
  }

  if (value_return == "false") {
    return false;
  } else {
    exit();
  }
}
