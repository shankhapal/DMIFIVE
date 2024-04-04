$(document).ready(function () {
  $('input[type="radio"]').click(function () {
    var inputValue = $(this).attr("value");

    var targetBox = $("." + inputValue);
    $(".box").not(targetBox).hide();

    $(targetBox).show();

    if ($("#pp-pp").is(":checked")) {
      $("#lab").prop("disabled", true);
      $("#pp").prop("disabled", false);
      $("#won_lab").hide();
    }
    if ($("#pp-lab").is(":checked")) {
      $("#pp").prop("disabled", true);
      $("#lab").prop("disabled", false);
      $("#wonlab").prop("disabled", true);
      $("#won_lab").hide();
    }
    if ($("#pp-wonlab").is(":checked")) {
      //$(".lab").hide();
      $("#won_lab").show();
    }
  });

  $(".delete_pp_id,.delete_lab_id,.delete_own_lab_id ").click(function (event) {
    event.preventDefault();
    var record_id = $(this).attr("id");

    // ajax request
    $.ajax({
      url: "../AjaxFunctions/get_alloted_replica_list",
      type: "POST",
      data: { record_id: record_id },
      beforeSend: function (xhr) {
        // Add this line
        xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
      },
      success: function (response) {
        // Add response in Modal body
        $(".modal-body").html(response);
        // Display Modal
        $("#replicaModal").modal("show");
      },
    });
  });

  $("#delete_pplab").on("click", function () {
    var remark = $("#remark").val();
    var record_id = $(".myTable").attr("id");

    if (remark == "") {
      $("#remark_err").html("Remark can not be empty");
      $("#message").html(
        `<div class="alert alert-warning">Please fill all required field</div>`
      );
      $("#remark").on("keyup", function () {
        $("#remark_err").hide(); // Hide the error message on keyup event in the input box
      });
    } else {
      //Ajax Request
      $.ajax({
        url: "../AjaxFunctions/attached_pp_lab_delete",
        type: "POST",
        data: { record_id: record_id, remark: remark },
        beforeSend: function (xhr) {
          // Add this line
          xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
        },
        success: function (response) {
          if (response == "yes") {
            // Remove row from HTML Table
            $(".myTable").closest("tr").css("background", "tomato");
            $(".myTable")
              .closest("tr")
              .fadeOut(800, function () {
                $(this).remove();
              });
            $("#replicaModal").modal("hide"); // Hide the modal
            location.reload(true);
          } else {
            alert("Invalid ID.");
          }
        },
      });
    }
  });
});

const checkremark = () => {
  var remark = $("#remark").val();
  if (remark == "") {
    $("#remark_err").html("Remark can not be empty");

    return false;
  }
};
