$("#ButtonStep1").on("click", () => {
  $("#ButtonStep1").addClass("m-progress");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/register/step1",
      { data: "data" },
      (data) => {
        barba.go(InternalAjaxHost + AppLang + "/signup" + "/packets");
      }
    ).fail(() => {
      window.location = InternalAjaxHost;
    });
  }, 500);
});

$("#ButtonStep2").on("click", () => {
  $("#ButtonStep2").addClass("m-progress");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/register/step2",
      { packet: $("input[name=select_plan]:checked").val() },
      (data) => {
        var j = JSON.parse(data);

        if (j.status == "goBrowse") {
          window.location = "/";
        } else {
          barba.go(InternalAjaxHost + AppLang + "/signup" + "/setup");
          setTimeout(() => {
            $("html, body").animate(
              {
                scrollTop: 0,
              },
              500
            );
          }, 500);
        }
      }
    ).fail(() => {
      window.location = InternalAjaxHost;
    });
  }, 500);
});

$("#ButtonStep3").on("click", () => {
  $("#ButtonStep3").addClass("m-progress");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/register/step3",
      { data: "data" },
      (data) => {
        var JsnR = JSON.parse(data);
        barba.go(
          InternalAjaxHost + AppLang + "/signup" + "/account/" + JsnR.unix
        );
      }
    ).fail(() => {
      window.location = InternalAjaxHost;
    });
  }, 500);
});

function isValidEmailAddress(emailAddress) {
  var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
  return pattern.test(emailAddress);
}

var Button4Click = () => {
  if (!$("#ButtonStep4").hasClass("m-progress")) {
    if (
      $("#password").val() != "" &&
      $("#email").val() != "" &&
      $("#password").val().length > 4 &&
      isValidEmailAddress($("#email").val())
    ) {
      $("#ButtonStep4").addClass("m-progress");
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/register/step4",
          { email: $("#email").val(), password: $("#password").val() },
          (data) => {
            try {
              var JsnDs = JSON.parse(data);

              if (JsnDs.paymentRequired == true) {
                barba.go(InternalAjaxHost + "subscription/info/" + JsnDs.unix);
              } else {
                $(".signup-form").remove();
                $("#LastArea").fadeIn("slow");
                setTimeout(() => {
                  window.location = "/";
                }, 1500);
              }
            } catch (error) {
              //window.location = InternalAjaxHost;
            }
          }
        ).fail(() => {
          //window.location = InternalAjaxHost;
        });
      }, 500);
    }
  }
};

$("input[name=select_plan]").on("change", () => {
  $("td").removeClass("selected");
  $(
    '[data-sid="' + $("input[name=select_plan]:checked").attr("id") + '"]'
  ).addClass("selected");
});

$("#email").click();

try {
  bootstrapValidate("#email", "email:error");
  bootstrapValidate("#password", "min:5:error");
} catch (error) {}
