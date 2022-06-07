setTimeout(() => {
  var sp = $("#PlaceHolderSlt").val();
  $("#NewSeriesCategories").select2({
    width: "100%",
    placeholder: {
      id: "-1",
      text: "--- " + sp + " ---",
      selected: "selected",
    },
    allowClear: true,
  });
}, 1000);

var EditMovie = (e) => {
  var editData = $(e).attr("data-edit");
  $("#EditImages").html("");
  $.post(
    InternalAjaxHost + "web-service/edit/data",
    { data: editData },
    (data) => {
      var Jsn = JSON.parse(data);
      var cat = JSON.parse(Jsn.video_categories);
      var img = JSON.parse(Jsn.video_images);

      $.each(img, (key, item) => {
        $("#EditImages").append(
          '<a  onclick="EditImg(' +
            Jsn.id +
            ",'" +
            item +
            '\');" style="color:blue;text-decoration:none;margin-right:10px;" href="javascript:;">Image' +
            key +
            ".png</a> "
        );
      });
    }
  );
};

var EditSeries = (e) => {
  var editData = $(e).attr("data-edit");
  $("#NewSeasonSelect22").html("");
  $("#NewSelect22").html("");
  $.post(
    InternalAjaxHost + "web-service/series/data",
    { data: editData },
    (data) => {
      var Jsn = JSON.parse(data);
      $("#NewSeasonSelect22").html(Jsn.SeriesHtml);
      $("#NewSelect22").html(Jsn.SeasonHtml);
    }
  );
};

var EditImg = (id, item) => {
  Swal.fire({
    title: SomeTextLangs.EditImg1,
    text: SomeTextLangs.EditImg2,
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#3085d6",
    confirmButtonText: SomeTextLangs.EditImg3,
    cancelButtonText: SomeTextLangs.EditImg4,
  }).then((result) => {
    if (result.value) {
      topbar.show();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/delete/image",
          { video_id: id, image: item },
          (data) => {
            var editData = "series_and_movies-" + data;
            $("#EditImages").html("");
            $.post(
              InternalAjaxHost + "web-service/edit/data",
              { data: editData },
              (data) => {
                var Jsn = JSON.parse(data);
                var cat = JSON.parse(Jsn.video_categories);
                var img = JSON.parse(Jsn.video_images);

                $.each(img, (key, item) => {
                  $("#EditImages").append(
                    '<a  onclick="EditImg(' +
                      Jsn.id +
                      ",'" +
                      item +
                      '\');" style="color:blue;text-decoration:none;margin-right:10px;" href="javascript:;">Image' +
                      key +
                      ".png</a> "
                  );
                });
                $('[name="edit_video_categories[]"]').val(cat);
                topbar.hide();
              }
            );
          }
        ).fail(() => {
          window.location = "";
        });
      }, 500);
    } else {
      Swal.close();
      setTimeout(() => {
        window.open(item, "_blank");
      }, 500);
    }
  });
};

var RefreshSeries = () => {
  $.post(
    InternalAjaxHost + "web-service/refresh/series",
    { data: "data" },
    (data) => {
      $("#NewSeasonSelect").html(data);
      $("#NewSeasonSelect2").html(data);
    }
  );
};

var SelectSeason = (e) => {
  var v = $("#NewSeasonSelect2").val();
  $.post(InternalAjaxHost + "web-service/refresh/season", { id: v }, (data) => {
    $("#NewSelect2").html(data);
  });
};

var SelectSeason2 = (e) => {
  var v = $("#NewSeasonSelect22").val();
  $.post(InternalAjaxHost + "web-service/refresh/season", { id: v }, (data) => {
    $("#NewSelect22").html(data);
  });
};
