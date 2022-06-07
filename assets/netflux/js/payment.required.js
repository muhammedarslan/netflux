var PayWithPaypal = () => {
  $("#P1").addClass("m-progress");
  setTimeout(() => {
    $("#paypal-button-container").html("");
    $("#StripeDiv").hide();
    $(".form_background").attr("style", "height:550px;");
    $.post(
      InternalAjaxHost + "web-service/paypal/packet/id",
      { data: "data" },
      (dataFromAjaxReq) => {
        paypal
          .Buttons({
            style: {
              color: "gold",
              layout: "vertical",
              label: "subscribe",
            },
            createSubscription: function (data, actions) {
              return actions.subscription.create({
                plan_id: dataFromAjaxReq,
              });
            },
            onApprove: function (data, actions) {
              var subID = data.subscriptionID;
              $("#Content1").remove();
              $("#Content2").show();
              $("#Content2Paypal").show();
              $(".form_background").attr("style", "height:200px;");
              setTimeout(() => {
                $.post(
                  InternalAjaxHost + "web-service/paypal/success",
                  { subID: subID },
                  (data) => {
                    window.location = "";
                  }
                ).fail(() => {
                  window.location = "";
                });
              }, 1000);
            },
          })
          .render("#paypal-button-container");
      }
    ).fail(() => {
      window.location = "";
    });
  }, 1000);
};

var PayWithStripe = () => {
  $("#P2").addClass("m-progress");
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
            window.location = "";
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
