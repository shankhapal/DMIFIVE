$(document).ready(function () {
  $("#time").combodate({
    firstItem: "name", // Show 'hour' and 'minute' string at first item of dropdown
    minuteStep: 1,
  });
});

$("#present_time_of_inspection").multiselect({
  maxWidth: 200,
  placeholder: "Select Option",
});
