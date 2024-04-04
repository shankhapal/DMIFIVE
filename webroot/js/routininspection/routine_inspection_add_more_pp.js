$(document).ready(function () {
  var form_section_id = $("#form_section_id").val();

  add_function();
  edit_function();
  delete_function();
  save_function();

  function add_function() {
    $("#add_packer_details").click(function (e) {
      e.preventDefault();

      var packer_id = $("#packer_id").val();
      var indent = $("#indent").val();
      var supplied = $("#supplied").val();
      var balance = $("#balance").val();
      var tbl_name = $("#tbl_name option:selected").text();

      var form_data = $("#" + form_section_id).serializeArray();

      form_data.push(
        { name: "packer_id", value: packer_id },
        { name: "indent", value: indent },
        { name: "supplied", value: supplied },
        { name: "balance", value: balance },
        { name: "tbl_name", value: tbl_name }
      );

      if (packer_table_details_val() == true) {
        $.ajax({
          type: "POST",
          url: "../AjaxFunctions/add_package_details",
          data: form_data,
          beforeSend: function (xhr) {
            // Add this line
            xhr.setRequestHeader(
              "X-CSRF-Token",
              $('[name="_csrfToken"]').val()
            );
          },
          success: function (response) {
            $("#printed_packaging_table").html(response);
            // $("#printed_packaging_table :input[type='text']").val("");
            $("#printed_packaging_table")
              .closest("form")
              .find("input[type=text], select")
              .val("");
            // $("#indent").val(
            //   $("#yourNumberInputId").prop("defaultValue")
            // );
            // $("#printed_packaging_table :input[type='number']").trigger(
            //   "reset"
            // );
            // $("#printed_packaging_table ")[0].reset();
            add_function();
            edit_function();
            delete_function();
            save_function();
          },
        });
      }
    });
  }
  function edit_function() {
    $(".edit_pack_id ").click(function (e) {
      e.preventDefault();

      var packer_id = $(this).attr("id");
      var form_data = $("#" + form_section_id).serializeArray();
      form_data.push({ name: "edit_pack_id", value: packer_id });

      $.ajax({
        type: "POST",
        url: "../AjaxFunctions/edit_pack_id",
        data: form_data,
        beforeSend: function (xhr) {
          // Add this line
          xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
        },
        success: function (response) {
          $("#printed_packaging_table").html(response);

          add_function();
          edit_function();
          delete_function();
          save_function();
        },
      });
    });
  }
  function delete_function() {
    $(".delete_packer_id").click(function (e) {
      e.preventDefault();

      var pack_id = $(this).attr("id");
      var form_data = $("#" + form_section_id).serializeArray();

      form_data.push({ name: "delete_pack_id", value: pack_id });

      $.ajax({
        type: "POST",
        url: "../AjaxFunctions/delete_pack_id",
        data: form_data,
        beforeSend: function (xhr) {
          // Add this line
          xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
        },
        success: function (response) {
          $("#printed_packaging_table").html(response);

          add_function();
          edit_function();
          delete_function();
          save_function();
        },
      });
    });
  }
  // SAVE FUNCTION
  // Description : this is used to on the click of SAVE button
  // @AUTHOR : SHANKHPAL SHENDE
  // DATE : 28-12-2022 (M)

  function save_function() {
    $("#save_packer_details").click(function (e) {
      e.preventDefault();

      var edit_pack_id = "";
      var save_packer_id = $(this).attr("id");
      var packer_id = $("#packer_id").val();
      var indent = $("#indent").val();
      var supplied = $("#supplied").val();
      var balance = $("#balance").val();
      var tbl = $("#tbl_name").val();
      var form_data = $("#" + form_section_id).serializeArray();

      form_data.push(
        { name: "save_packer_id", value: save_packer_id },
        { name: "packer_id", value: packer_id },
        { name: "indent", value: indent },
        { name: "supplied", value: supplied },
        { name: "balance", value: balance },
        { name: "tbl", value: tbl },
        { name: "edit_pack_id", value: edit_pack_id }
      );

      if (packer_table_details_val() == true) {
        $.ajax({
          type: "POST",
          url: "../AjaxFunctions/edit_pack_id",
          data: form_data,
          beforeSend: function (xhr) {
            // Add this line
            xhr.setRequestHeader(
              "X-CSRF-Token",
              $('[name="_csrfToken"]').val()
            );
          },
          success: function (response) {
            $("#printed_packaging_table").html(response);
            $("#printed_packaging_table :input[type='text']").val("");

            add_function();
            edit_function();
            delete_function();
            save_function();
          },
        });
      }
    });
  }
});

function packer_table_details_val() {
  var packer_id = $("#packer_id").val();
  var indent = $("#indent").val();
  var supplied = $("#supplied").val();
  var balance = $("#balance").val();
  var tbl_name = $("#tbl_name").val();

  var value_return = "true";

  if (packer_id == "") {
    $("#error_packer_id").show().text("Please select packer id");
    setTimeout(function () {
      $("#error_packer_id").fadeOut();
    }, 8000);
    $("#packer_id").addClass("is-invalid");
    $("#packer_id").click(function () {
      $("#error_packer_id").hide().text;
      $("#packer_id").removeClass("is-invalid");
    });
    value_return = "false";
  }

  if (indent == "") {
    $("#error_indent").show().text("Indent must be filled out");
    setTimeout(function () {
      $("#error_indent").fadeOut();
    }, 8000);
    $("#indent").addClass("is-invalid");
    $("#indent").click(function () {
      $("#error_indent").hide().text;
      $("#indent").removeClass("is-invalid");
    });
    value_return = "false";
  }
  if (supplied == "") {
    $("#error_supplied").show().text("Supplied must be filled out");
    setTimeout(function () {
      $("#error_supplied").fadeOut();
    }, 8000);
    $("#supplied").addClass("is-invalid");
    $("#supplied").click(function () {
      $("#error_supplied").hide().text;
      $("#supplied").removeClass("is-invalid");
    });
    value_return = "false";
  }
  if (balance == "") {
    $("#error_balance").show().text("Balance must be filled out");
    setTimeout(function () {
      $("#error_balance").fadeOut();
    }, 8000);
    $("#balance").addClass("is-invalid");
    $("#balance").click(function () {
      $("#error_balance").hide().text;
      $("#balance").removeClass("is-invalid");
    });
    value_return = "false";
  }
  if (tbl_name == "") {
    $("#error_tbl_name").show().text("tbl name must be filled out");
    setTimeout(function () {
      $("#error_tbl_name").fadeOut();
    }, 8000);
    $("#tbl_name").addClass("is-invalid");
    $("#tbl_name").click(function () {
      $("#error_tbl_name").hide().text;
      $("#tbl_name").removeClass("is-invalid");
    });
    value_return = "false";
  }
  if (value_return == "false") {
    // alert("Please check some fields are missing or not proper.");
    var msg = "Please check some fields are missing or not proper.";
    $.alert(msg);
    return false;
  } else {
    return true;
  }
}

//to get packer id wise tbl
$("#printed_packaging_table").on("change", ".packer_id", function () {
  var id_No = this.id.split("-"); //to get dynamic id of element for each row, and split to get no.
  id_No = id_No[2];

  var packer_id = $("#packer_id").val();

  $.ajax({
    type: "POST",
    url: "../AjaxFunctions/get_packer_id_wise_tbl",
    data: { packer_id: packer_id },
    beforeSend: function (xhr) {
      // Add this line
      xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
    },
    success: function (response) {
      var response = response.match(/~([^']+)~/)[1]; //getting data bitween ~..~ from response
      if (response == "No data") {
        alert("No tbl available for selected packer_id");
        $("#ta-label_charge-" + id_No).val("");
        $("#ta-packet_size_unit-" + id_No).html("");
        $("#ta-total_quantity-" + id_No).val("");
        return false;
      } else {
        response = JSON.parse(response); //response is JSOn encoded to parse JSON

        $("#tbl_name").val(response["tbl_name"]);

        var tbl_list = response["tbl_name"];

        var tbl_option = "<option value=''>--Select--</option>";
        $.each(tbl_list, function (index, value) {
          tbl_option += "<option value='" + index + "'>" + value + "</option>";
        });

        $("#tbl_name").html(tbl_option);
      }
    },
  });
});

// Added for Available stock of printed packaging material with Agmark replica (packer wise)
// Author: shankhpal shende
// Date 23/05/2023
$(document).ready(function () {
  $("#indent, #supplied").keyup(function () {
    // Get the value of the "indent" input field or set it to 0 if empty
    const indent_value = $("#indent").val() || 0;

    // Get the value of the "supplied" input field or set it to 0 if empty
    const supplied_value = $("#supplied").val() || 0;

    // Calculate the sum of the "indent" and "supplied" values
    const balance_value = parseFloat(indent_value) + parseFloat(supplied_value);

    // Set the calculated sum as the value of the "balance" input field
    $("#balance").val(balance_value);
  });

  $(".indent").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      $("#error_indent").html("Number Only").stop().show().fadeOut("slow");
      return false;
    }
  });

  $(".supplied").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      $("#error_supplied").html("Number Only").stop().show().fadeOut("slow");
      return false;
    }
  });
});
