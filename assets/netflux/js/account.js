var CancelSub = (type) => {
  var a = confirm(type + " " + $("#CancelText").val());

  if (a) {
    $("section").attr("style", "opacity:0.3;pointer-events:none;");
    setTimeout(() => {
      $.post(
        InternalAjaxHost + "web-service/cancel/subscription",
        { sub: type },
        (data) => {
          try {
            var j = JSON.parse(data);
            $(".Btn_" + j.subID).hide();
            $("#Span_" + j.subID).text($("#CanceledText").val());
            $("#Span_" + j.subID).attr("style", "color:red;font-weight:600;");
            $("section").removeAttr("style");
            $(".account-section-item-plan").text(j.newPacketName);
            $(".cancel_sub_element").fadeOut();
          } catch (error) {
            window.location = "";
          }
        }
      ).fail(() => {
        window.location = "";
      });
    }, 1000);
  }
};

async function ChangePassword() {
  var { value: formValues } = await Swal.fire({
    title: $("#ChangePass").val(),
    confirmButtonText: $("#ChangePass").val().replace(/i/g, "İ").toUpperCase(),
    cancelButtonText: $("#ChangePass4").val(),
    showCancelButton: true,
    html:
      '<input placeholder="' +
      $("#ChangePass2").val() +
      '" id="swal-input1" class="swal2-input" type="password" >' +
      '<input placeholder="' +
      $("#ChangePass3").val() +
      '" id="swal-input2" class="swal2-input" type="password" >',
    focusConfirm: false,
    preConfirm: () => {
      return [
        document.getElementById("swal-input1").value,
        document.getElementById("swal-input2").value,
      ];
    },
  });

  if (formValues) {
    var P1S = formValues[0];
    var P2S = formValues[1];

    if (P1S != "") {
      $.post(
        InternalAjaxHost + "web-service/password/change",
        { p1: P1S, p2: P2S },
        (data) => {
          var JsonResponse = JSON.parse(data);

          Swal.fire({
            icon: JsonResponse.label,
            text: JsonResponse.text,
            confirmButtonText: JsonResponse.Close,
          });
        }
      );
    }
  }
}

var CancelAccount = () => {
  var a = confirm($("#CancelAccountText").val());

  if (a) {
    $("section").attr("style", "opacity:0.3;pointer-events:none;");
    setTimeout(() => {
      $.post(
        InternalAjaxHost + "web-service/cancel/account",
        { sub: "sub" },
        (data) => {
          window.location = "/login";
        }
      ).fail(() => {
        window.location = "/login";
      });
    }, 1000);
  }
};

var BillingModal = (typec) => {
  $.post(
    InternalAjaxHost + "web-service/billing/detail",
    { type: typec },
    (data) => {
      try {
        var JsonData = JSON.parse(data);

        var modal = new tingle.modal({
          footer: false,
          stickyFooter: false,
          closeMethods: ["overlay", "button", "escape"],
          closeLabel: JsonData.closeLabel,
          beforeClose: function () {
            return true;
            return false;
          },
        });

        modal.setContent(JsonData.HtmlContent);
        modal.open();
        setTimeout(() => {
          CreateTable(JsonData.TableID);
        }, 500);
      } catch (error) {
        window.location = "";
      }
    }
  ).fail(() => {
    window.location = "";
  });
};

var AccountContainer = () => {
  $.post(
    InternalAjaxHost + "web-service/load/account/container",
    { get: "container" },
    (data) => {
      $("#AccountContainerSection").html(data);
    }
  ).fail(() => {
    window.location = "/";
  });
};

var DestroySessions = () => {
  $(".ds_sns").attr("style", "opacity:0.8;pointer-events:none;");
  topbar.show();
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/destroy/sessions",
      { destroy: "all" },
      (j) => {
        try {
          var Jsn = JSON.parse(j);
          $("#DestroySessionsT1").hide();
          $("#DestroySessionsT2").fadeIn();
          topbar.hide();
        } catch (error) {
          AjaxFail();
        }
      }
    ).fail(() => {
      AjaxFail();
    });
  }, 500);
};

setTimeout(() => {
  AccountContainer();
}, 1);
