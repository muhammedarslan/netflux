var videoContainer = document.querySelector(".video-container");

var video = videojs("VideoStream");

var controlsContainer = document.querySelector(
  ".video-container .controls-container"
);

var CheckButtons = () => {
  try {
    if (video.muted()) {
      fullVolumeButton.style.display = "none";
      mutedButton.style.display = "";
    } else {
      fullVolumeButton.style.display = "";
      mutedButton.style.display = "none";
    }

    if (video.paused()) {
      playButton.style.display = "";
      pauseButton.style.display = "none";
    } else {
      playButton.style.display = "none";
      pauseButton.style.display = "";
    }
  } catch (error) {}
};

var playPauseButton = document.querySelector(
  ".video-container .controls button.play-pause"
);
var rewindButton = document.querySelector(
  ".video-container .controls button.rewind"
);
var fastForwardButton = document.querySelector(
  ".video-container .controls button.fast-forward"
);
var volumeButton = document.querySelector(
  ".video-container .controls button.volume"
);
var fullScreenButton = document.querySelector(
  ".video-container .controls button.full-screen"
);
var playButton = playPauseButton.querySelector(".playing");
var pauseButton = playPauseButton.querySelector(".paused");
var fullVolumeButton = volumeButton.querySelector(".full-volume");
var mutedButton = volumeButton.querySelector(".muted");
var maximizeButton = fullScreenButton.querySelector(".maximize");
var minimizeButton = fullScreenButton.querySelector(".minimize");

var progressBar = document.querySelector(
  ".video-container .progress-controls .progress-bar-custom"
);
var watchedBar = document.querySelector(
  ".video-container .progress-controls .progress-bar-custom .watched-bar"
);
var timeLeft = document.querySelector(
  ".video-container .progress-controls .time-remaining"
);

var controlsTimeout;
controlsContainer.style.opacity = "0";
watchedBar.style.width = "0px";
pauseButton.style.display = "none";
minimizeButton.style.display = "none";

var displayControls = () => {
  $(".go_back_container").fadeIn();
  controlsContainer.style.opacity = "1";
  document.body.style.cursor = "initial";
  if (controlsTimeout) {
    clearTimeout(controlsTimeout);
  }
  controlsTimeout = setTimeout(() => {
    controlsContainer.style.opacity = "0";
    document.body.style.cursor = "none";
    $(".go_back_container").fadeOut();
  }, 5000);
};

var playPause = () => {
  try {
    if (video.paused()) {
      video.play();
      playButton.style.display = "none";
      pauseButton.style.display = "";
    } else {
      video.pause();
      displayControls();
      playButton.style.display = "";
      pauseButton.style.display = "none";
    }
    setTimeout(() => {
      CheckButtons();
    }, 1000);
  } catch (error) {}
};

var toggleMute = () => {
  if (video.muted()) {
    video.muted(false);
    fullVolumeButton.style.display = "";
    mutedButton.style.display = "none";
  } else {
    video.muted(true);
    fullVolumeButton.style.display = "none";
    mutedButton.style.display = "";
  }

  setTimeout(() => {
    CheckButtons();
    video.play();
  }, 1000);
};

var toggleFullScreen = () => {
  if (!document.fullscreenElement) {
    videoContainer.requestFullscreen();
  } else {
    document.exitFullscreen();
  }
  setTimeout(() => {
    CheckButtons();
  }, 1000);
};

document.addEventListener("fullscreenchange", () => {
  if (!document.fullscreenElement) {
    maximizeButton.style.display = "";
    minimizeButton.style.display = "none";
  } else {
    maximizeButton.style.display = "none";
    minimizeButton.style.display = "";
  }
});

document.addEventListener("keyup", (event) => {
  if (event.code === "Space") {
    playPause();
  }

  if (event.code === "KeyM") {
    toggleMute();
  }

  if (event.code === "KeyF") {
    toggleFullScreen();
  }

  displayControls();
  setTimeout(() => {
    CheckButtons();
  }, 1000);
});

document.addEventListener("mousemove", () => {
  displayControls();
});

var LastCheck = Math.floor(Date.now() / 1000);

video.on("timeupdate", function () {
  watchedBar.style.width = (video.currentTime() / video.duration()) * 100 + "%";
  var totalSecondsRemaining = video.duration() - video.currentTime();
  var time = new Date(null);
  time.setSeconds(totalSecondsRemaining);
  var hours = null;

  if (totalSecondsRemaining >= 3600) {
    hours = time.getHours().toString().padStart("2", "0");
  }

  var minutes = time.getMinutes().toString().padStart("2", "0");
  var seconds = time.getSeconds().toString().padStart("2", "0");

  timeLeft.textContent = `${hours ? hours : "00"}:${minutes}:${seconds}`;

  if (Math.floor(Date.now() / 1000) - LastCheck > 10) {
    LastCheck = Math.floor(Date.now() / 1000);
    $.post(InternalAjaxHost + "web-service/watch/log", {
      videoID: $("#VideoID").val(),
      currentTime: video.currentTime(),
    });
  }
});

progressBar.addEventListener("click", (event) => {
  var pos =
    (event.pageX -
      (progressBar.offsetLeft + progressBar.offsetParent.offsetLeft)) /
    progressBar.offsetWidth;
  video.currentTime(pos * video.duration());

  watchedBar.style.width =
    ((pos * video.duration()) / video.duration()) * 100 + "%";
  setTimeout(() => {
    video.play();
    setTimeout(() => {
      playButton.style.display = "none";
      pauseButton.style.display = "";
    }, 1000);
  }, 2000);
  setTimeout(() => {
    CheckButtons();
  }, 1000);
});

playPauseButton.addEventListener("click", playPause);

rewindButton.addEventListener("click", () => {
  video.currentTime(video.currentTime() - 10);
  setTimeout(() => {
    CheckButtons();
  }, 1000);
});

fastForwardButton.addEventListener("click", () => {
  video.currentTime(video.currentTime() + 10);
  setTimeout(() => {
    CheckButtons();
  }, 1000);
});

try {
  document
    .querySelector(".video-container .controls button.next")
    .addEventListener("click", () => {
      var nextID = $(".video-container .controls button.next").attr(
        "data-next"
      );
      barba.go(nextID);
    });
} catch (error) {}

volumeButton.addEventListener("click", toggleMute);

fullScreenButton.addEventListener("click", toggleFullScreen);

setTimeout(() => {
  $.post(
    InternalAjaxHost + "web-service/watch/time",
    { videoID: $("#VideoID").val() },
    (data) => {
      try {
        video.currentTime(data);
        playPause();
        displayControls();
      } catch (error) {}
      setTimeout(() => {
        if (video.paused()) {
          video.muted(true);
          fullVolumeButton.style.display = "none";
          mutedButton.style.display = "";
          playPause();
        }
        CheckButtons();
        $(".watch_body").click(function () {
          $(".controls-container").click(function () {
            return false;
          });
          playPause();
        });
      }, 1000);
    }
  ).fail(() => {
    window.location = "";
  });
}, 2000);
