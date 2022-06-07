var ListAdded1 = (e) => {
  var token = $(e).attr("data-token");
  topbar.show();
  $(e).attr("style", "opacity:0.5;pointer-events:none");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/list/add",
      { tkn: token },
      (data) => {
        $(e).removeAttr("style");
        $(e).hide();
        $(e).next("span").fadeIn();
        topbar.hide();
      }
    );
  }, 500);
};

var ListAdded2 = (e) => {
  var token = $(e).attr("data-token");
  topbar.show();
  $(e).attr("style", "opacity:0.5;pointer-events:none");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/list/remove",
      { tkn: token },
      (data) => {
        $(e).removeAttr("style");
        $(e).hide();
        $(e).prev("span").fadeIn();
        topbar.hide();
      }
    );
  }, 500);
};

var ListLiked1 = (e) => {
  var token = $(e).attr("data-token");
  topbar.show();
  $(e).attr("style", "opacity:0.5;pointer-events:none");
  setTimeout(() => {
    $.post(InternalAjaxHost + "web-service/like", { tkn: token }, (data) => {
      $(e).removeAttr("style");
      var likedSpan = $(e);
      var unlikedSpan = $(e).next("span");

      likedSpan.removeClass("listliked");
      unlikedSpan.removeClass("listliked");

      var Jsn = JSON.parse(data);

      if (Jsn.Liked == true) {
        likedSpan.addClass("listliked");
      }

      if (Jsn.Unliked == true) {
        unlikedSpan.addClass("listliked");
      }

      topbar.hide();
    });
  }, 500);
};

var ListLiked2 = (e) => {
  var token = $(e).attr("data-token");
  topbar.show();
  $(e).attr("style", "opacity:0.5;pointer-events:none");
  setTimeout(() => {
    $.post(InternalAjaxHost + "web-service/unlike", { tkn: token }, (data) => {
      $(e).removeAttr("style");
      var likedSpan = $(e).prev("span");
      var unlikedSpan = $(e);

      likedSpan.removeClass("listliked");
      unlikedSpan.removeClass("listliked");

      var Jsn = JSON.parse(data);

      if (Jsn.Liked == true) {
        likedSpan.addClass("listliked");
      }

      if (Jsn.Unliked == true) {
        unlikedSpan.addClass("listliked");
      }

      topbar.hide();
    });
  }, 500);
};

var MoreDetail = (itemID) => {
  barba.go(InternalAjaxHost + "browse/87654" + itemID);
};
