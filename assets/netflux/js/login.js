$.urlParam = function (t) {
  var e = new RegExp("[?&]" + t + "=([^&#]*)").exec(window.location.search);
  return null !== e ? e[1] || 0 : InternalAjaxHost + "browse";
};

bootstrapValidate("#form_email", "email:error");
bootstrapValidate("#form_password", "min:5:error");

setTimeout(() => {
  $("#form_email").removeAttr("disabled");
  $("#form_password").removeAttr("disabled");
  document.getElementById("form_email").focus();
}, 500);

function isValidEmailAddressLogin(emailAddress) {
  var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
  return pattern.test(emailAddress);
}

var LoginFormSubmit = () => {
  if (!$("#form_button").hasClass("m-progress")) {
    if (
      $("#form_password").val() != "" &&
      $("#form_email").val() != "" &&
      $("#form_password").val().length > 4 &&
      isValidEmailAddressLogin($("#form_email").val())
    ) {
      $("#AjaxContent").html("");
      var Email = $("#form_email").val();
      var Password = $("#form_password").val();
      $("#form_password").blur();

      if (Email != "" && Password != "") {
        $("#form_email").attr("style", "pointer-events:none;opacity:0.5;");
        $("#form_password").attr("style", "pointer-events:none;opacity:0.5;");
        $("#form_button").attr("style", "pointer-events:none;opacity:0.5;");
        $("#form_button").addClass("m-progress");
        setTimeout(() => {
          $.post(
            InternalAjaxHost + "web-service/login",
            $("#LoginForm").serialize(),
            (data) => {
              try {
                var JsonLogin = JSON.parse(data);

                if (JsonLogin.status == "success") {
                  $("#AjaxContent").hide();
                  $("#AjaxContent").html(
                    '<div class="alert alert-' +
                      JsonLogin.label +
                      '" role="alert">' +
                      JsonLogin.message +
                      "</div>"
                  );
                  //$(".form_background").attr("style", "height:580px;");
                  //$("#AjaxContent").fadeIn();
                  // $("#form_email").removeAttr("style");
                  // $("#form_password").removeAttr("style");
                  //$("#form_button").removeAttr("style");
                  //$("#form_button").removeClass("m-progress");
                  //$("#form_email").attr("disabled", "disabled");
                  //$("#form_button").attr("disabled", "disabled");
                  setTimeout(() => {
                    barba.go($.urlParam("nextpage"));
                  }, 1000);
                } else {
                  $("#AjaxContent").hide();
                  $("#AjaxContent").html(
                    '<div class="alert alert-' +
                      JsonLogin.label +
                      '" role="alert">' +
                      JsonLogin.message +
                      "</div>"
                  );
                  $(".form_background").attr("style", "height:580px;");
                  $("#AjaxContent").fadeIn();
                  $("#form_password").val("");
                  $("#form_email").removeAttr("style");
                  $("#form_password").removeAttr("style");
                  $("#form_button").removeAttr("style");
                  $("#form_button").removeClass("m-progress");
                }
              } catch (error) {
                window.location = "";
              }
            }
          ).fail(() => {
            window.location = "";
          });
        }, 700);
      }
    }
  }
};

$("#LoginForm").on("submit", () => {
  $("#form_button").click();
});

var LoginWith = (data) => {
  $("#form_email").attr("style", "pointer-events:none;opacity:0.5;");
  $("#form_password").attr("style", "pointer-events:none;opacity:0.5;");
  $("#form_button").attr("style", "pointer-events:none;opacity:0.5;");
  $("#form_button").addClass("m-progress");

  var url = InternalAjaxHost + "social-login/with/" + data;

  var w = 600;
  var h = 600;
  var y = window.top.outerHeight / 2 + window.top.screenY - h / 2;
  var x = window.top.outerWidth / 2 + window.top.screenX - w / 2 - 50;

  var newwin = window.open(
    url,
    "Login with facebook",
    "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=" +
      w +
      ", height=" +
      h +
      ", top=" +
      y +
      ", left=" +
      x
  );
  if (window.focus) {
    newwin.focus();
  }

  var timer = setInterval(function () {
    if (newwin.closed) {
      clearInterval(timer);
      $.post(
        InternalAjaxHost + "web-service/check/session",
        { source: data },
        (data) => {
          try {
            var JsonData = JSON.parse(data);

            if (JsonData.isLogged == true) {
              $("#AjaxContent").hide();
              $("#AjaxContent").html(
                '<div class="alert alert-' +
                  JsonData.label +
                  '" role="alert">' +
                  JsonData.message +
                  "</div>"
              );
              //$(".form_background").attr("style", "height:580px;");
              //$("#AjaxContent").fadeIn();
              // $("#form_email").removeAttr("style");
              // $("#form_password").removeAttr("style");
              //$("#form_button").removeAttr("style");
              //$("#form_button").removeClass("m-progress");
              //$("#form_email").attr("disabled", "disabled");
              //$("#form_button").attr("disabled", "disabled");
              setTimeout(() => {
                barba.go($.urlParam("nextpage"));
              }, 1000);
            } else {
              $("#AjaxContent").hide();
              $("#AjaxContent").html(
                '<div class="alert alert-' +
                  JsonData.label +
                  '" role="alert">' +
                  JsonData.message +
                  "</div>"
              );
              $(".form_background").attr("style", "height:580px;");
              $("input").attr("style", "pointer-events:none;opacity:0.5;");
              $("a").attr("style", "pointer-events:none;opacity:0.5;");
              $("#AjaxContent").fadeIn();
              $("#form_email").removeAttr("style");
              $("#form_password").removeAttr("style");
              $("#form_button").removeAttr("style");
              $("#form_button").removeClass("m-progress");
              window.location = InternalAjaxHost + "go?nextpage=/signup";
            }
          } catch (error) {
            $("#form_email").removeAttr("style");
            $("#form_password").removeAttr("style");
            $("#form_button").removeAttr("style");
            $("#form_button").removeClass("m-progress");
            console.log(error);
            //window.location = "";
          }
        }
      ).fail(() => {
        $("#form_email").removeAttr("style");
        $("#form_password").removeAttr("style");
        $("#form_button").removeAttr("style");
        $("#form_button").removeClass("m-progress");
        //window.location = "";
      });
    }
  }, 1000);
};
