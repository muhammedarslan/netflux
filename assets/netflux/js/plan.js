var Pmodel = (leftPrice) => {
  var modal = new tingle.modal({
    footer: false,
    stickyFooter: false,
    closeMethods: ["button"],
    beforeClose: function () {
      return true;
      return false;
    },
  });

  modal.setContent(document.querySelector(".tingle-bil").innerHTML);
  modal.open();
  setTimeout(() => {
    $(".lprice").text(leftPrice);
  }, 500);
};

var PayWithPaypal = () => {
  $(".P1").addClass("m-progress");
  setTimeout(() => {
    $(".paypal-button-container").html("");
    $(".StripeDiv").hide();
    PaypalModel2();
    $.post(
      InternalAjaxHost + "web-service/paypal/packet/id",
      { data: "data" },
      (dataFromAjaxReq) => {
        setTimeout(() => {
          paypal
            .Buttons({
              style: {
                color: "gold",
                layout: "horizontal",
                label: "subscribe",
              },
              createSubscription: function (data, actions) {
                return actions.subscription.create({
                  plan_id: dataFromAjaxReq,
                });
              },
              onApprove: function (data, actions) {
                var subID = data.subscriptionID;
                $("body").attr("style", "opacity:0.5;pointer-events:none");
                setTimeout(() => {
                  $.post(
                    InternalAjaxHost + "web-service/paypal/success",
                    { subID: subID },
                    (data) => {
                      window.location = InternalAjaxHost + "account";
                    }
                  ).fail(() => {
                    window.location = "";
                  });
                }, 1);
              },
            })
            .render("#PaypalModals");
        }, 500);
      }
    ).fail(() => {
      window.location = "";
    });
  }, 1000);
};

var PaypalModel2 = () => {
  var modal2 = new tingle.modal({
    footer: false,
    stickyFooter: false,
    closeMethods: [],
    beforeClose: function () {
      return true;
      return false;
    },
  });

  modal2.setContent(
    '<h2 style="text-align:center;" >' +
      $("#PTexts").val() +
      '</h2><hr><div style="text-align:center"; id="PaypalModals"></div>'
  );
  modal2.open();
};

var PayWithStripe = () => {
  $(".P2").addClass("m-progress");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/pay/stripe",
      { data: "data" },
      (data) => {
        var JsonData = JSON.parse(data);

        var stripe = Stripe(JsonData.StripeToken);

        stripe
          .redirectToCheckout({
            sessionId: JsonData.PayToken,
          })
          .then(function (result) {
            modal.close();
          });
      }
    ).fail(() => {
      window.location = "";
    });
  }, 500);
};

var PayWith = (inp) => {
  if (inp == "paypal") {
    PayWithPaypal();
  } else if (inp == "stripe") {
    PayWithStripe();
  }
};

$("#ButtonStep2").on("click", () => {
  $("#ButtonStep2").addClass("m-progress");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/change/plan",
      { packet: $("input[name=select_plan]:checked").val() },
      (data) => {
        $("table").remove();
        $("#ChangeBtn").remove();
        $(".plan-notice").remove();
        $(".sticky-buttons").remove();
        $("#LastArea").fadeIn();
        $("html, body").animate(
          {
            scrollTop: 0,
          },
          500
        );
        if (data == "SubRequired") {
          setTimeout(() => {
            Pmodel(data);
          }, 500);
        }
      }
    ).fail(() => {
      window.location = InternalAjaxHost;
    });
  }, 500);
});

$("input[name=select_plan]").on("change", () => {
  $("#ChangeBtn").hide();
  $("td").removeClass("selected");
  $(
    '[data-sid="' + $("input[name=select_plan]:checked").attr("id") + '"]'
  ).addClass("selected");

  if ($("#CurrentPlan").val() != $("input[name=select_plan]:checked").val()) {
    $("#ChangeBtn").show();
  }
});

setTimeout(() => {
  var PypalUrl = $("#PaypalUrl").val();
  $("main").append(
    '<script src="' +
      PypalUrl +
      '" data-sdk-integration-source="button-factory"></script>'
  );
}, 1000);
