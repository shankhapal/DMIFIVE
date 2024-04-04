// updated file by shankhpal on 12/06/2023
$(document).ready(function () {
  $(function () {
    var dataTable = null;

    $("#ca_pdf_table").hide();
    $("#pp_pdf_table").hide();
    $("#lab_pdf_table").hide();

    $("input:radio[name=pdf]").change(function () {
      var value = $(this).attr("value");

      $("#ca_pdf_table").hide();
      $("#pp_pdf_table").hide();
      $("#lab_pdf_table").hide();

      if (value == "ca") {
        $("#ca_pdf_table").show();
        if (!dataTable) {
          dataTable = $("#ca_pdf_table").DataTable();
        } else {
          dataTable.draw();
        }
      } else if (value == "pp") {
        $("#pp_pdf_table").show();
        if (!dataTable) {
          dataTable = $("#pp_pdf_table").DataTable();
        } else {
          dataTable.draw();
        }
      } else if (value == "lab") {
        $("#lab_pdf_table").show();
        if (!dataTable) {
          dataTable = $("#lab_pdf_table").DataTable();
        } else {
          dataTable.draw();
        }
      }
    });
  });
});
