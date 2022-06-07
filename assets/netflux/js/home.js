$.post(
  InternalAjaxHost + "web-service/clear/register",
  { clear: "data" },
  (data) => {}
);

bootstrapValidate("#email1", "email:error");
bootstrapValidate("#email2", "email:error");

$("#email1").keyup(function (e) {
  if (e.keyCode === 13) {
    $("#Button1").click();
  }
});

var SignUp = (inp) => {
  $("#email1").blur();
  $("#email2").blur();
  if (inp == "email1") {
    if ($("input")[1].reportValidity()) {
      $("#Button1").addClass("m-progress");
      $("#Div1").attr("style", "pointer-events:none;opacity:0.8");
      $('.input-group svg').hide();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/home/email",
          { email: $("#email1").val() },
          (data) => {
            if (data == "fail") {
              window.location = "";
            } else {
              barba.go(data);
            }
          }
        ).fail(() => {
          window.location = "";
        });
      }, 1000);
    }
  } else if (inp == "email2") {
    if ($("input")[2].reportValidity()) {
      $("#Button2").addClass("m-progress");
      $("#Div2").attr("style", "pointer-events:none;opacity:0.8");
      $('.input-group svg').hide();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/home/email",
          { email: $("#email2").val() },
          (data) => {
            if (data == "fail") {
              window.location = "";
            } else {
              barba.go(data);
            }
          }
        ).fail(() => {
          window.location = "";
        });
      }, 1000);
    }
  }
};
