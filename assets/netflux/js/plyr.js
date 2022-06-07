$(function () {
  var controls = $("#PlayerControllers").html();

  // Setup the player
  var player = new Plyr("#player", {
    controls,
    autoplay: false,
    clickToPlay: true,
  });
  player.on("ready", function (event) {
    var instance = event.detail.plyr;

    var hslSource = null;
    var sources = instance.media.querySelectorAll("source"),
      i;
    for (i = 0; i < sources.length; ++i) {
      if (sources[i].src.indexOf(".m3u8") > -1) {
        hslSource = sources[i].src;
      }
    }

    if (hslSource !== null && Hls.isSupported()) {
      var hls = new Hls();
      hls.loadSource(hslSource);
      hls.attachMedia(instance.media);
      hls.on(Hls.Events.MANIFEST_PARSED, function () {});
    }

    $.post(
      InternalAjaxHost + "web-service/watch/time",
      { videoID: $("#VideoID").val() },
      (data) => {
        setTimeout(() => {
          var J = JSON.parse(data);
          setTimeout(() => {
            player.currentTime = J.currentTime;
            setTimeout(() => {
              try {
                player.play();
              } catch (error) {}
            }, 200);

            setTimeout(() => {
              if (!player.playing) {
                $(".loading-container").hide();
                $(".player-area").addClass("video-stopped");
              }
            }, 500);
          }, 1000);
        }, 500);
      }
    ).fail(() => {
      console.log("Something were wrong!");
    });
  });
  player.on("pause", function (event) {
    setTimeout(() => {
      if (player.playing == false) {
        $(".player-area").addClass("video-stopped");
      }
    }, 100);
  });
  player.on("play", function (event) {
    $(".player-area").removeClass("video-stopped");
    setTimeout(() => {
      $(".loading-container").fadeOut();
    }, 700);
  });
  var LastCheck = Math.floor(Date.now() / 1000);
  player.on("timeupdate", (event) => {
    if (Math.floor(Date.now() / 1000) - LastCheck > 10) {
      LastCheck = Math.floor(Date.now() / 1000);
      $.post(InternalAjaxHost + "web-service/watch/log", {
        videoID: $("#VideoID").val(),
        currentTime: player.currentTime,
      });
    }
  });
  /*
  $(".player-area").on("mouseover", function () {
    setTimeout(() => {
      $(".player-area").removeClass("video-stopped");
    }, 3000);
  });
  
  $(".player-area").on("mouseleave", function () {
    player.pause();
    $(".player-area").addClass("video-stopped");
  });
*/
  $(".js-name-area-video").text($(".js-info-div").attr("data-name"));
});
