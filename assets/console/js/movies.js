setTimeout(() => {
  var sp = $("#PlaceHolderSlt").val();
  $("#NewMovieCategories").select2({
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
      $('[name="edit_video_categories[]"]').val(cat);

      $("#EditMovieCategories").select2({
        width: "100%",
        placeholder: {
          id: "-1",
          text: "--- " + $("#PlaceHolderSlt").val() + " ---",
          selected: "selected",
        },
        allowClear: true,
      });
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

                $("#EditMovieCategories").select2({
                  width: "100%",
                  placeholder: {
                    id: "-1",
                    text: "--- " + $("#PlaceHolderSlt").val() + " ---",
                    selected: "selected",
                  },
                  allowClear: true,
                });
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
