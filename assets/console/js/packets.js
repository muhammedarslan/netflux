var TranslatePacket = (e) => {
  $("#FirstTabLink").click();
  var genreID = $(e).attr("data-id");
  $.post(
    InternalAjaxHost + "web-service/translation/packet",
    { genre: genreID },
    (js) => {
      var j = JSON.parse(js);

      $('[name="translate_packet_id"]').val(j.packet_id);
      $('[name="translate_original_title"]').val(j.original.name);

      $.each(j.translation, (key, value) => {
        $('[data-translate-title="' + key + '"]').val(value.name);
      });
    }
  );
};

var TrialModalOpen = () => {
  $.post(InternalAjaxHost + "web-service/trial/modal", { get: "data" }, (j) => {
    try {
      var Jsn = JSON.parse(j);

      $('[name="trial_period"]').val(Jsn.trialTime);
      $('[name="trial_active"]').val(Jsn.isActive);
    } catch (error) {
      AjaxFail();
    }
  }).fail(() => {
    AjaxFail();
  });
};

var CurrencyChangeEvent = () => {
  var cCode = $("#currenyCode").val();
  if (cCode != "") {
    $.post(
      InternalAjaxHost + "web-service/currency/code",
      { code: cCode },
      (j) => {
        try {
          var Jsn = JSON.parse(j);
          $('[name="exchange_rate"]').val(Jsn.currency);
        } catch (error) {
          AjaxFail();
        }
      }
    ).fail(() => {
      AjaxFail();
    });
  }
};
