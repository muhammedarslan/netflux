var TranslateGenre = (e) => {
  $("#FirstTabLink").click();
  var genreID = $(e).attr("data-id");
  $.post(
    InternalAjaxHost + "web-service/translations/genre",
    { genre: genreID },
    (js) => {
      var j = JSON.parse(js);

      $('[name="translate_genre_id"]').val(j.genre_id);
      $('[name="translate_original_title"]').val(j.original.name);

      $.each(j.translation, (key, value) => {
        $('[data-translate-title="' + key + '"]').val(value.name);
      });
    }
  );
};
