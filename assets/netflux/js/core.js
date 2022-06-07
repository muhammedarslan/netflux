var loadJavascriptAndCssFiles = (urls, successCallback) => {
  try {
    $.when
      .apply(
        $,
        $.map(urls, function (url) {
          if (url.includes(".css")) {
            return $.get(url, function (css) {
              $("<style>" + css + "</style>").appendTo("head");
            });
          } else {
            return $.getScript(url);
          }
        })
      )
      .then(function () {
        if (typeof successCallback === "function") successCallback();
      })
      .fail(function () {
        if (typeof failureCallback === "function") successCallback();
      });
  } catch {}
};

var Go = (url) => {
  barba.go(url);
};

var InputFreezeLabel = () => {
  var formFields = $(".form-field");
  formFields.each(function () {
    var field = $(this);
    var input = field.find("input");
    var label = field.find("label");

    function checkInput() {
      var valueLength = input.val().length;

      if (valueLength > 0) {
        label.addClass("freeze");
      } else {
        label.removeClass("freeze");
      }
    }

    checkInput();
    input.change(function () {
      checkInput();
    });
  });
};

var RefreshSearch = () => {
  try {
    var searchIcon = document.getElementsByClassName("search-box__icon")[0];
    var searchBox = document.getElementsByClassName("search-box")[0];

    searchIcon.addEventListener("click", activateSearch);

    function activateSearch() {
      searchBox.classList.toggle("active");
    }

    $(".search-box__input").on("keyup", (e) => {
      var SearchData = $(".search-box__input").val();
      if (SearchData != "") {
        if (typeof timer !== "undefined") {
          clearTimeout(timer);
        }
        timer = setTimeout(() => {
          barba.go(InternalAjaxHost + "browse/search?q=" + SearchData);
        }, 500);
      } else {
        if (typeof timer !== "undefined") {
          clearTimeout(timer);
        }
        timer = setTimeout(() => {
          barba.go(InternalAjaxHost + "browse");
        }, 500);
      }
    });
  } catch (error) {}
};

async function getScripts(scripts, callback) {
  var progress = 0;
  for (let index = 0; index < scripts.length; index++) {
    await $.getScript(scripts[index], function () {
      if (++progress == scripts.length) callback();
    });
  }
}

var WatchVideo = (itemID) => {
  barba.go(
    InternalAjaxHost + "watch/87654" + itemID + "/" + $("#PageJwtToken").val()
  );
};

var PageLoaded = () => {
  try {
    var path = window.location.pathname;
    $("#language-select").removeClass("lang-select-light");
    $("#language-select").removeClass("lang-select");
    InputFreezeLabel();
    if (
      path.split("/")[2] == "signup" ||
      path.split("/")[1] == "account" ||
      path.split("/")[1] == "subscription"
    ) {
      $("body").addClass("bg-white");
      $("#language-select").addClass("lang-select-light");
    } else {
      $("#language-select").addClass("lang-select");
    }

    if (path.split("/")[1] == "watch") {
      $("body").addClass("watch_body");
    }

    if (path.split("/")[2] == "signup") {
      $.post(
        InternalAjaxHost + "web-service/signup/step/listener",
        {
          currentPage: path,
        },
        (j) => {
          try {
            var ajaxJson = JSON.parse(j);
            if (
              ajaxJson.currentStep == "2" &&
              ajaxJson.selectedPacket != null
            ) {
              $("input[name=select_plan]").removeAttr("checked");
              $(
                "input[name=select_plan][value=" + ajaxJson.selectedPacket + "]"
              ).prop("checked", true);
              $("td").removeClass("selected");
              $(
                '[data-sid="' +
                  $(
                    "input[name=select_plan][value=" +
                      ajaxJson.selectedPacket +
                      "]"
                  ).attr("id") +
                  '"]'
              ).addClass("selected");
            }
          } catch (error) {
            console.log("Something were wrong!");
          }
        }
      );
    }

    var linkpath = path;
    if (linkpath.includes("browse/movies/87654")) {
      linkpath = "/browse/movies";
    }
    if (linkpath.includes("browse/series/87654")) {
      linkpath = "/browse/series";
    }

    $("li").removeClass("active");
    $('[data-link="' + linkpath + '"]').addClass("active");
    setTimeout(() => {
      $("li").removeClass("active");
      $('[data-link="' + linkpath + '"]').addClass("active");
    }, 2000);
    $.getJSON("?__a=1", (js) => {
      getScripts(js.PageJs, () => {
        $(".side-nav").removeClass("open");
        $(".mobile-menu-background").removeClass("d-block");
        $(".PureBlack").hide();
        $(".MainContent").fadeIn();
      });
      loadJavascriptAndCssFiles(js.PageCss);
    });
  } catch {
    console.log("Loaded error.");
  }
};

$(document).ready(function () {
  barba.init({
    //cacheIgnore: true,
    schema: {
      prefix: "data-barba",
      namespace: "easy",
      wrapper: "wrapper",
    },
    transitions: [
      {
        name: "default-transition",
        leave() {
          $("body").removeClass("bg-white");
          $("body").removeClass("watch_body");
          $(".PureBlack").show();
          $(".MainContent").hide();

          $("#TopBrowseVideo").remove();
          $("#TopBrowseImg").show();

          if (typeof $("#StreamType").val() != "undefined") {
            if ($("#StreamType").val() == "m3u8") {
              video.dispose();
            } else {
              video.pause();
              video.removeAttribute("src"); // empty source
              video.load();
            }
          }

          try {
            clearTimeout(tmTopBrowse);
            scr = null;
          } catch (error) {}
        },
        enter() {},
        after() {
          var OldHeader = $("header").attr("data-current-header");
          var NewHeader = $("#DataHeader").val();
          PageLoaded();
          /*
          setTimeout(() => {
            $("html, body").animate(
              {
                scrollTop: 0,
              },
              500
            );
          }, 500);
          */
          if (NewHeader == "browse" && OldHeader == "browse") {
            // Nothing, Smile :)
          } else {
            $.get("", { load: "header" }, (data) => {
              $("header").remove();
              $("body").prepend(data);
              if (NewHeader == "browse") {
                RefreshSearch();
              }
            });
          }
        },
      },
    ],
  });
});

var SelectLang = () => {
  var newLang = $("#language-select").val();
  window.location = "?hl=" + newLang;
};

var AjaxFail = () => {
  console.log("Something were wrong.");
};

var LogLevel = () => {
  $.post(InternalAjaxHost + "web-service/log", { log: "log" }, (data) => {});
};

//Page Loaded.
PageLoaded();
LogLevel();
setInterval(() => {
  LogLevel();
}, 30000);
