$("#dataTable").on("change", ".commodity", function () {
  let commodity_id = $("#ta-commodity-").val();

  $.ajax({
    type: "POST",
    url: "../AjaxFunctions/get_commodity_wise_charge",
    data: { commodity_id: commodity_id },
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
    },
    success: function (response) {
      if (response === "No Charge") {
        alert("No Charges available for selected commodity");
        $("#replica_charges").val("");
        $("#ta-packet_size_unit-").html("");
        return false;
      } else {
        var response = response.match(/~([^']+)~/)[1]; //getting data bitween ~..~ from response
        response = JSON.parse(response);
        $("#replica_charges").val(response["charge"]);
        var unit_list = response["unit_list"];
        var unit_option = "<option value=''>--Select--</option>";
        $.each(unit_list, function (index, value) {
          unit_option += "<option value='" + index + "'>" + value + "</option>";
        });

        $("#ta-packet_size_unit-").html(unit_option);
      }
    },
  });

  $.ajax({
    type: "POST",
    url: "../replica/get_commodity_wise_grade",
    data: { commodity_id: commodity_id },
    beforeSend: function (xhr) {
      // Add this line
      xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
    },
    success: function (response) {
      var response = response.match(/~([^']+)~/)[1]; //getting data bitween ~..~ from response

      if (response == "No Grade") {
        $.alert("No Grade available for selected Commodity");
        var grade_option = "<option value=''>--Select--</option>";
        $("#grade").val("");
        $("#grade").html(grade_option);

        return false;
      } else {
        response = JSON.parse(response); //response is JSOn encoded to parse JSON

        var grade_list = response["Grade"];

        var grade_option = "<option value=''>--Select--</option>";

        $.each(grade_list, function (index, value) {
          grade_option +=
            "<option value='" + index + "'>" + value + "</option>";
        });

        $("#grade").html(grade_option);
      }
    },
  });

  $("#ta-packet_size-").val("");
  $("#ta-no_of_packets-").val("");
  $("#replica_charges").val("");
});

$("#ta-packet_size-, #ta-no_of_packets-, #ta-packet_size_unit-").on(
  "input",
  function () {
    calculateQty();
  }
);

function calculateQty() {
  var packSize = parseFloat($("#ta-packet_size-").val());
  var selectedOption = $("#ta-packet_size_unit- option:selected");
  var unit = selectedOption.text();

  var totalPackages = parseFloat($("#ta-no_of_packets-").val());

  if (isNaN(packSize) || isNaN(totalPackages)) {
    $("#total_qty_graded_quintal").val("Invalid input");
    return;
  }

  var unitConversions = {
    quintal: 1,
    kg: 0.01,
    gm: 0.00001,
    ml: 0.000001,
    ltr: 0.1,
    Nos: 0.01,
  };

  if (unitConversions[unit] === undefined) {
    $("#total_qty_graded_quintal").val("Invalid unit");
    return;
  }

  var convertedunit = unitConversions[unit];
  var totalQty = packSize * totalPackages * convertedunit;

  var decimalPlaces = 3; // Change this to 2 if you want 2 decimal places
  var formattedQty = totalQty.toFixed(decimalPlaces);

  $("#total_qty_graded_quintal").val(formattedQty);
}

$(document).ready(function () {
  // Assuming #dataTable is a static parent container
  $("#dataTable").on("keyup", ".total_no_packages", function () {
    // Your code here
    var packet_size = $("#ta-packet_size-").val();
    var sub_unit_id = $("#ta-packet_size_unit-").val();
    var no_of_packets = $("#ta-no_of_packets-").val();
    var label_charge = $("#replica_charges").val();
    var commodity_id = $("#ta-commodity-").val();

    $.ajax({
      type: "POST",
      url: "../replica/get_gross_quantity_and_total_charge",
      data: {
        packet_size: packet_size,
        sub_unit_id: sub_unit_id,
        no_of_packets: no_of_packets,
        label_charge: label_charge,
        commodity_id: commodity_id,
      },
      beforeSend: function (xhr) {
        // Add this line
        xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
      },
      success: function (response) {
        var response = response.match(/~([^']+)~/)[1]; //getting data bitween ~..~ from response
        response = JSON.parse(response); //response is JSOn encoded to parse JSON

        // $("#ta-total_quantity-" + id_No).val(response["gross_quantity"]);
        $("#replica_charges").val(response["total_charges"]);

        get_overall_total_and_min_bal();
      },
    });
  });
});

//to get and check overall total charges is not greater than balance amount
function get_overall_total_and_min_bal() {
  var overall_total = 0;
  $("#dataTable > .table_body  > tr").each(function () {
    var total_label_charges = $("#replica_charges").val();
    console.log(total_label_charges);
    overall_total = parseFloat(overall_total) + parseFloat(total_label_charges);

    i++;
  });

  $.ajax({
    type: "POST",
    url: "../replica/check_bal_amt",
    beforeSend: function (xhr) {
      // Add this line
      xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
    },
    success: function (response) {
      var response = response.match(/~([^']+)~/)[1]; //getting data bitween ~..~ from response
      response = JSON.parse(response); //response is JSOn encoded to parse JSON

      $("#overall_total_chrg").val(overall_total);
    },
  });
}
