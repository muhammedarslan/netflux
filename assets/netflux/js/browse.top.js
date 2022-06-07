(function (a) {
  a.expr[":"].onScreen = function (b) {
    var c = a(window),
      d = c.scrollTop(),
      e = c.height(),
      f = d + e,
      g = a(b),
      h = g.offset().top,
      i = g.height(),
      j = h + i;
    return (
      (h >= d && h < f) || (j > d && j <= f) || (i > e && h <= d && j >= f)
    );
  };
})(jQuery);

/*
window.addEventListener("load", function () {
  setTimeout(() => {
    $(document).scroll(function () {
      if ($("#TopBrowseVideo").is(":onScreen")) {
        $("#TopBrowseImg").hide();
        $("#TopBrowseVideo").show();
      } else {
        $("#TopBrowseVideo").hide();
        $("#TopBrowseImg").show();
      }
    });
  }, 5000);

  var tmTopBrowse = setTimeout(() => {
    $("#TopBrowseImg").hide();
    $("#TopBrowseVideo").fadeIn(2000);
    document.getElementById("TopBrowseVideo").play();
  }, 3000);
});
*/
