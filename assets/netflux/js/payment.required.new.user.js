var PayWithPaypal = () => {
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/paypal/packet/id",
      { data: "data" },
      (dataFromAjaxReq) => {
        $("#PaymentLoadingText").hide();
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
              $("#paypal-button-container").hide();
              $("#PaypalSuccessText").fadeIn();
              setTimeout(() => {
                $.post(
                  InternalAjaxHost + "web-service/paypal/success",
                  { subID: subID },
                  (data) => {
                    window.location = InternalAjaxHost + "browse";
                  }
                ).fail(() => {
                  window.location = InternalAjaxHost + "browse";
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
  $("#P1").hide();
  $("#P2").hide();
  $(".PaymentLoading").fadeIn();

  if (inp == "paypal") {
    PayWithPaypal();
  } else if (inp == "stripe") {
    PayWithStripe();
  }
};

$.post(
  InternalAjaxHost + "web-service/paypal/client/id",
  { load: "client" },
  (id) => {
    $("body").append(
      '<script src="https://www.paypal.com/sdk/js?client-id=' +
        id +
        '&vault=true" data-sdk-integration-source="button-factory"></script><script src="https://js.stripe.com/v3/"></script>'
    );
  }
);
