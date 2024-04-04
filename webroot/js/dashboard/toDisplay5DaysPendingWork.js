$(document).ready(function () {
  $.ajax({
    type: "POST",
    async: true,
    url: "../AjaxFunctions/toDisplay5DaysPendingWork",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-CSRF-Token", $('[name="_csrfToken"]').val());
    },
    success: function (response) {
      var tableRows = "";

      // Parse the JSON response
      var data = JSON.parse(response);

      // Iterate through each property in the data object
      for (var prop in data) {
        if (data.hasOwnProperty(prop)) {
          var innerObj = data[prop];

          // Iterate through each inner array
          for (var key in innerObj) {
            if (innerObj.hasOwnProperty(key)) {
              var arrayValues = innerObj[key];

              // Iterate through each array value
              for (var i = 0; i < arrayValues.length; i++) {
                var row = arrayValues[i];
                tableRows +=
                  "<tr>" +
                  "<td>" +
                  row.appl_type +
                  "</td>" +
                  "<td>" +
                  row.appl_id +
                  "</td>" +
                  "<td>" +
                  row.process +
                  "</td>" +
                  "</tr>";
              }
            }
          }
        }
      }

      // Insert the table rows into the table body
      $("#myPendingWorkModelBody").html(tableRows);

      // Initialize DataTables on your table
      $(".table").DataTable();

      // Display the modal
      $("#myPendingWorkModel").modal("show");
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log("Error: " + errorThrown);
      // Handle any errors that occur during the Ajax request
    },
  });
});
