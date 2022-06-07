const loadJavascriptAndCssFiles = (urls, successCallback) => {
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
};

const Go = (url) => {
  barba.go(url);
};

async function getScripts(scripts, callback) {
  var progress = 0;
  for (let index = 0; index < scripts.length; index++) {
    await $.getScript(scripts[index], function () {
      if (++progress == scripts.length) callback();
    });
  }
}

const PageLoaded = () => {
  setTimeout(() => {
    const path = window.location.pathname;
    if (path.split("/")[2] == "signup" || path.split("/")[1] == "account") {
      $("body").addClass("bg-white");
    }
    $(".sidebar a").removeClass("active");
    $('[href="' + window.location.pathname + '"]').addClass("active");
    $.getJSON("?__a=1", (js) => {
      getScripts(js.PageJs, () => {
        $(".PureBlack").hide();
        $(".MainContent").fadeIn();
      });
      loadJavascriptAndCssFiles(js.PageCss);
    });
    LoadAdminPage();
  }, 1000);
  setTimeout(() => {
    LoadAdminPage();
  }, 2000);
};

$(document).ready(function () {
  barba.init({
    cacheIgnore: true,
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
          $(".MainContent").fadeOut();
          $(".sidebar a").removeClass("active");
          $('[href="' + window.location.pathname + '"]').addClass("active");
          setTimeout(() => {
            $(".PureBlack").fadeIn();
          }, 500);
        },
        enter() {
          PageLoaded();
        },
      },
    ],
  });
});

const SelectLang = () => {
  const newLang = $("#language-select").val();
  window.location = "?hl=" + newLang;
};

const AjaxFail = () => {
  console.log("Something were wrong.");
};

const LogLevel = () => {
  $.post(InternalAjaxHost + "web-service/log", { log: "log" }, (data) => {});
};

//Page Loaded.
PageLoaded();
LoadAdminPage();
LogLevel();
setInterval(() => {
  LogLevel();
}, 30000);
