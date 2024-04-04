$(document).ready(function () {

    $(function () {
      // Generic logic
      var toggleDropdown = function (owner) {
        // hasOwner is a boolean store
        var hasOwner = typeof owner !== typeof undefined && owner;

      // Use boolean to decide whether to disable/enable co-owner field
      $("#co-owner").prop("disabled", !hasOwner).val("");
    };

    // Trigger logic when #owner is updated
    $("#owner").on("change", function () {
      toggleDropdown($(this).val());
    });

    // Trigger logic on DOM ready
    toggleDropdown();
  });
});
