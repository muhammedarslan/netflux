setTimeout(() => {
  var welcomeSection = $(".welcome-section"),
    enterButton = welcomeSection.find(".enter-button");

  setTimeout(function () {
    welcomeSection.removeClass("content-hidden");
  }, 100);

  enterButton.on("click", function (e) {
    e.preventDefault();
    welcomeSection.addClass("content-hidden").fadeOut();
    setTimeout(() => {
      window.location = "";
    }, 500);
  });
}, 100);

setTimeout(() => {
  var welcomeSection = $(".welcome-section"),
    enterButton = welcomeSection.find(".enter-button");
  enterButton.click();
}, 3000);

setTimeout(() => {
  $.post(
    InternalAjaxHost + "web-service/trial",
    { data: "data" },
    (data) => {}
  );
}, 1000);
