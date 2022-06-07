var ManageActors = (e) => {
  var editData = $(e).attr("data-id");
  $.post(
    InternalAjaxHost + "web-service/get/actors",
    { id: editData },
    (data) => {
      var JsnO = JSON.parse(data);

      $("#ActorsInput").val(JsnO.VideoName);
      $("#ActorsID").val(JsnO.VideoID);
      $("#ActorsSelect2").html(JsnO.HtmlSelect);
      $("#ActorsSelect2").select2({
        width: "100%",
        allowClear: true,
      });
    }
  );
};

var ManageDirectors = (e) => {
  var editData = $(e).attr("data-id");
  $.post(
    InternalAjaxHost + "web-service/get/directors",
    { id: editData },
    (data) => {
      var JsnO = JSON.parse(data);

      $("#DirectorsInput").val(JsnO.VideoName);
      $("#DirectorsID").val(JsnO.VideoID);
      $("#DirectorsSelect2").html(JsnO.HtmlSelect);
      $("#DirectorsSelect2").select2({
        width: "100%",
        allowClear: true,
      });
    }
  );
};

var CreateStream = (e) => {
  var videoID = $(e).attr("data-id");
  $.post(
    InternalAjaxHost + "web-service/get/stream",
    { id: videoID },
    (data) => {
      var JsnO = JSON.parse(data);
      $("#VideoLink").val(JsnO.VideoSource);
      $("#StreamLink").val(JsnO.StreamSource);
      $("#VideoIDStream").val(JsnO.VideoID);
    }
  );
};

var TranslateVideo = (e) => {
  $("#FirstTabLink").click();
  var videoID = $(e).attr("data-id");
  $.post(
    InternalAjaxHost + "web-service/translations/video",
    { video: videoID },
    (js) => {
      var j = JSON.parse(js);

      $('[name="translate_video_id"]').val(j.video_id);
      $('[name="translate_original_title"]').val(j.original.name);
      $('[name="translate_original_description"]').val(j.original.description);

      $.each(j.translation, (key, value) => {
        $('[data-translate-title="' + key + '"]').val(value.name);
        $('[data-translate-text="' + key + '"]').val(value.description);
      });
    }
  );
};
