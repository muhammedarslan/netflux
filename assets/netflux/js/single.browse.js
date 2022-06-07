var path = window.location.pathname;
var splitPath = path.split("/");

$.post(
  InternalAjaxHost + "web-service/single/actions",
  { itemID: splitPath[2] },
  (j) => {
    try {
      var Jsn = JSON.parse(j);

      $("#SingleBrowseAction1").hide();
      $("#SingleBrowseAction2").hide();

      if (Jsn.InList == true) {
        $("#SingleBrowseAction2").fadeIn();
      } else {
        $("#SingleBrowseAction1").fadeIn();
      }

      if (Jsn.InLiked == true) {
        $("#SingleBrowseAction3").addClass("listliked");
      }

      if (Jsn.InUnliked == true) {
        $("#SingleBrowseAction4").addClass("listliked");
      }
    } catch (error) {
      $("#SingleBrowseAction1").hide();
      $("#SingleBrowseAction2").hide();
      $("#SingleBrowseAction3").hide();
      $("#SingleBrowseAction4").hide();
    }
  }
).fail(() => {
  $("#SingleBrowseAction1").hide();
  $("#SingleBrowseAction2").hide();
  $("#SingleBrowseAction3").hide();
  $("#SingleBrowseAction4").hide();
});

if ($("#SimilarVideos").length > 0) {
  $.post(
    InternalAjaxHost + "web-service/similar/videos",
    {
      itemID: splitPath[2],
    },
    (htmlData) => {
      $("#SimilarVideos").html(htmlData);
    }
  );
}

var SeasonChange = () => {
  var Season = $("#SeasonSelector").val();
  $(".episode_item").hide();
  $('[data-season-id="' + Season + '"]').show();
};
