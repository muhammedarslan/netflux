$.urlParam = function (t) {
  var e = new RegExp("[?&]" + t + "=([^&#]*)").exec(window.location.search);
  return null !== e ? e[1] || 0 : InternalAjaxHost + "admin";
};

$("#LoginForm").on("submit", () => {
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
        InternalAjaxHost + "web-service/login/admin",
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
    }, 1000);
  }
});
