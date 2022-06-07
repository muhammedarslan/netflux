$("#Button1").on("click", () => {
  $("#Button1").attr("style", "pointer-events:none;opacity:0.5;");
  $("#Button1").addClass("m-progress");
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/destroy/all",
      { destroy: "all" },
      (data) => {
        window.location = InternalAjaxHost + "browse";
      }
    );
  }, 1000);
});
