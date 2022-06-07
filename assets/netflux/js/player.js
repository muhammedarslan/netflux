var videoContainer = document.querySelector(".video-container");
var video = document.querySelector(".video-container video");

//var video = videojs("VideoStream");

var controlsContainer = document.querySelector(
  ".video-container .controls-container"
);

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
  controlsContainer.style.opacity = "1";
  document.body.style.cursor = "initial";
  if (controlsTimeout) {
    clearTimeout(controlsTimeout);
  }
  controlsTimeout = setTimeout(() => {
    controlsContainer.style.opacity = "0";
    document.body.style.cursor = "none";
  }, 5000);
};

var playPause = () => {
  if (video.paused) {
    video.play();
    playButton.style.display = "none";
    pauseButton.style.display = "";
  } else {
    video.pause();
    playButton.style.display = "";
    pauseButton.style.display = "none";
  }
};

var toggleMute = () => {
  video.muted = !video.muted;
  if (video.muted) {
    fullVolumeButton.style.display = "none";
    mutedButton.style.display = "";
  } else {
    fullVolumeButton.style.display = "";
    mutedButton.style.display = "none";
  }
};

var toggleFullScreen = () => {
  if (!document.fullscreenElement) {
    videoContainer.requestFullscreen();
  } else {
    document.exitFullscreen();
  }
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
});

document.addEventListener("mousemove", () => {
  displayControls();
});

video.addEventListener("timeupdate", () => {
  watchedBar.style.width = (video.currentTime / video.duration) * 100 + "%";
  var totalSecondsRemaining = video.duration - video.currentTime;
  var time = new Date(null);
  time.setSeconds(totalSecondsRemaining);
  var hours = null;

  if (totalSecondsRemaining >= 3600) {
    hours = time.getHours().toString().padStart("2", "0");
  }

  var minutes = time.getMinutes().toString().padStart("2", "0");
  var seconds = time.getSeconds().toString().padStart("2", "0");

  timeLeft.textContent = `${hours ? hours : "00"}:${minutes}:${seconds}`;

  console.log("Video izleniyor!!!");
});

progressBar.addEventListener("click", (event) => {
  var pos =
    (event.pageX -
      (progressBar.offsetLeft + progressBar.offsetParent.offsetLeft)) /
    progressBar.offsetWidth;
  video.currentTime = pos * video.duration;
});

playPauseButton.addEventListener("click", playPause);

rewindButton.addEventListener("click", () => {
  video.currentTime -= 10;
});

fastForwardButton.addEventListener("click", () => {
  video.currentTime += 10;
});

volumeButton.addEventListener("click", toggleMute);

fullScreenButton.addEventListener("click", toggleFullScreen);

setTimeout(() => {
  playPause();
  setTimeout(() => {
    if (video.paused) {
      $(".volume").click();
      playPause();
    }
  }, 1000);
}, 2000);

$(".watch_body").click(function () {
  $(".controls-container").click(function () {
    return false;
  });
  playPause();
});
