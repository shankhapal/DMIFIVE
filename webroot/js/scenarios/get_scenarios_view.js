// let radioInputs = document.querySelectorAll("input");

// alert(radioInputs);

$(function () {
  let radBtnDefault = document.getElementById("scenario-pp");
  if ((radBtnDefault.checked = true)) {
    $(".printing_press").show();
  }
});

$(document).ready(function () {
  let checkrole = $("#checkrole").val();

  if (checkrole == "SO") {
    $("label[for='scenario-lab_d']").css("display", "none");
    $("label[for='scenario-lab_e']").css("display", "none");
  }
  $('input[type="radio"]').click(function () {
    if ($(this).attr("value") == "pp") {
      $(".printing_press").show();
      $(".ca_non_bevo").hide();
      $(".ca_bevo").hide();
      $(".lab_d").hide();
      $(".lab_e").hide();
    }
    if ($(this).attr("value") == "ca_bevo") {
      $(".ca_bevo").show();
      $(".printing_press").hide();
      $(".ca_non_bevo").hide();
      $(".lab_d").hide();
      $(".lab_e").hide();
    }
    if ($(this).attr("value") == "ca_non_bevo") {
      $(".ca_non_bevo").show();
      $(".ca_bevo").hide();
      $(".printing_press").hide();
      $(".lab_d").hide();
      $(".lab_e").hide();
    }
    if ($(this).attr("value") == "lab_d") {
      $(".lab_d").show();
      $(".printing_press").hide();
      $(".ca_bevo").hide();
      $(".ca_non_bevo").hide();
      $(".lab_e").hide();
    }
    if ($(this).attr("value") == "lab_e") {
      $(".lab_e").show();
      $(".lab_d").hide();
      $(".printing_press").hide();
      $(".ca_bevo").hide();
      $(".ca_non_bevo").hide();
    }
  });
});
