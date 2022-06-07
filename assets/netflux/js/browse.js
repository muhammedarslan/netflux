var browse = () => {
  var swiper = new Swiper(".swiper-container", {
    calculateHeight: true,
    touchRatio: 0,
    speed: 800,
    slidesPerView: 6,
    spaceBetween: 4,
    slidesPerGroup: 6,
    loop: false,
    loopFillGroupWithBlank: false,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  $(".swiper-container").each(function (index) {
    var ent = "entity-" + index;
    var entSelector = "." + ent + " ";
    $(this).addClass(ent);

    $(entSelector + ".swiper-slide").hover(
      function () {
        $(entSelector + ".swiper-slide").addClass("moving-left");
        $(entSelector + ".swiper-slide:hover~.swiper-slide").addClass(
          "moving-right"
        );
        $(
          entSelector +
            ".swiper-slide:first-child:hover, .swiper-slide:nth-child(7n):hover"
        ).addClass("fc-move");
        $(
          entSelector +
            ".swiper-slide:first-child:hover~.swiper-slide, .swiper-slide:nth-child(7n):hover~.swiper-slide"
        ).addClass("fc-s-move");
      },
      function () {
        $(entSelector + ".swiper-slide").removeClass(
          "moving-left moving-right fc-move fc-s-move"
        );
      }
    );

    $(entSelector + ".swiper-slide:nth-child(6n)").hover(
      function () {
        $(entSelector + ".swiper-slide").addClass("move-left");
      },
      function () {
        $(entSelector + ".swiper-slide").removeClass("move-left");
      }
    );

    $(".swiper-container" + "." + ent).hover(function () {
      var entityCount = $(entSelector + ".swiper-slide").length;
      if (entityCount > 6) {
        $(entSelector + ".swiper-button-prev").toggleClass("d-none");
        $(entSelector + ".swiper-button-next").toggleClass("d-none");
      }
    });
  });
};
setTimeout(() => {
  browse();
}, 100);
