$(document).ready(function () {
    const form_status = $("#status").val();
    var application_mode = $("#application_mode").val();
    if (application_mode == "view") {
      $("#section_form_id :input").prop("disabled", false);
      $(document).ready(function () {
        $(".glyphicon-edit").css("display", "none");
        $(".glyphicon-remove-sign").css("display", "none");
      });
    }

    $("#date_of_packing").on("change", function () {
      var date_of_sampling = $("#date_of_sampling").val();
      var date_of_packing = $(this).val();

      if (
        date_of_sampling !== "" &&
        date_of_packing !== "" &&
        date_of_packing < date_of_sampling
      ) {
        $("#error-date_of_packing")
          .show()
          .css({
            color: "red",
            "font-size": "14px",
            "font-weight": "bold",
          })
          .text("Error: Date of packing cannot be before the date of sampling.");

        $("#date_of_packing").addClass("is-invalid");

        // Use the click event handler to hide the error and remove the 'is-invalid' class
        $("#date_of_packing").on("click", function () {
          $("#error-date_of_packing").hide().text("");
          $("#date_of_packing").removeClass("is-invalid");
        });

        // Reset the value of date_of_packing to an empty string
        $("#date_of_packing").val("");
      }

      if (date_of_sampling === "") {
        $("#error-date_of_sampling")
          .show()
          .css({
            color: "red",
            "font-size": "14px", // You can adjust the font size as needed
            "font-weight": "bold", // You can adjust the font weight as needed
          })
          .text("Error: Please select the first Date of Sampling.");

        $("#date_of_sampling").addClass("is-invalid");

        // Use the click event handler to hide the error and remove the 'is-invalid' class
        $("#date_of_sampling").on("click", function () {
          $("#error-date_of_sampling").hide().text("");
          $("#date_of_sampling").removeClass("is-invalid");
        });
        $("#date_of_packing").val("");
      } else {
        // If date_of_sampling is not empty, hide the error message and remove the 'is-invalid' class
        $("#error-date_of_sampling").hide().text("");
        $("#date_of_sampling").removeClass("is-invalid");
      }
    });

    function extractMonthAndYear(period) {
      // Split the input string into two parts using the separator "-"
      var parts = period.split(" - ");

      // Extracting start and end months and years
      var startParts = parts[0].split("-");
      var startMonth = startParts[0];
      var startYear = startParts[1];

      var endParts = parts[1].split("-");
      var endMonth = endParts[0];
      var endYear = endParts[1];

      return {
        startMonth: startMonth,
        startYear: startYear,
        endMonth: endMonth,
        endYear: endYear,
      };
    }

    var periodString = $("#selected_period").val();

    var result = extractMonthAndYear(periodString);

    // Format the start date
    var startMonthIndex = new Date(
      Date.parse(result.startMonth + " 1, 2000")
    ).getMonth();
    var formattedStartDate =
      (startMonthIndex + 1).toString().padStart(2, "0") +
      "/01/" +
      result.startYear;

    // Set the formatted start date as the start date in datepicker
    $("#date_of_sampling, #date_of_packing").datepicker(
      "setStartDate",
      formattedStartDate
    );

    // Set the end date to the last day of September
    var formattedEndDate =
      "09/" + new Date(result.endYear, 9, 0).getDate() + "/" + result.endYear;

    // Set the formatted end date as the end date in datepicker
    $("#date_of_sampling, #date_of_packing").datepicker(
      "setEndDate",
      formattedEndDate
    );

    if (form_status === "Granted") {
      $("#comment_reply_box").hide();
    } else {
      $("#date_of_sampling").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
      });
      $("#date_of_packing").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
      });

      $("#report_date").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
      });

      $current_level = $("#current_level").val();
      if ($current_level === "level_3") {
        $("#form_inner_main .glyphicon-edit").css("display", "none");
        $("#form_inner_main .glyphicon-remove-sign").css("display", "none");
        $("#form_inner_main #add_new_row").css("display", "none");
      }

      // if lab is not NABL Accredited then dissabled the field
      const labNablAccreditedInput = document.getElementById(
        "lab_nabl_accredited"
      ).value;

      const reportNoInput = document.getElementById("report_no");
      const reportDateInput = document.getElementById("report_date");
      const remarksInput = document.getElementById("remarks");
      const laboratorynameInput = document.getElementById("laboratory_name");
      const rpl_reportno = document.getElementsByClassName("rpl_reportno");
      if (labNablAccreditedInput === "" || labNablAccreditedInput === null) {
        reportNoInput.style.display = "none";
        reportDateInput.style.display = "none";
        remarksInput.style.display = "none";
        laboratorynameInput.style.display = "none";
        rpl_reportno.style.display = "none";
      } else {
        reportNoInput.style.display = "block"; // Or "initial" depending on your CSS
        reportDateInput.style.display = "block"; // Or "initial" depending on your CSS
        remarksInput.style.display = "block"; // Or "initial" depending on your CSS
        laboratorynameInput.style.display = "block";
      }
    }

    $("#downloadButton").click(function (e) {
      e.preventDefault();
      alert();
    });
  });
