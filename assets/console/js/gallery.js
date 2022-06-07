$(".filter-button").click(function () {
  var value = $(this).attr("data-filter");

  if (value == "all") {
    $(".filter").show("1000");
  } else {
    $(".filter")
      .not("." + value)
      .hide("3000");
    $(".filter")
      .filter("." + value)
      .show("3000");
  }

  if ($(".filter-button").removeClass("active")) {
    $(this).removeClass("active");
  }
  $(this).addClass("active");
});

$(".fancybox").fancybox({
  openEffect: "none",
  closeEffect: "none",
});

var GroupSelectGallery = () => {
  if ($("#avatar_s_g").val() == "__new__") {
    $("#avatarField1").hide();
    $("#avatarField2").fadeIn();
  }
};

var GroupSelectGallery2 = () => {
  $("#avatarField2").hide();
  $("#avatarField1").fadeIn();
};

setTimeout(() => {
  lazyload();
}, 500);
