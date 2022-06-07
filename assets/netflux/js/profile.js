setTimeout(() => {
  $('[data-rmv="rmv"]').remove();
  $("body").append(
    '<style>font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;</style>'
  );
  lazyload();
}, 500);

var ProfileSelect = (token) => {
  var mod = $("#Mode").val();

  if (mod == "normal") {
    $(".MainContent").attr("style", "pointer-events:none;opacity:0.5");
    window.location =
      InternalAjaxHost + "go?nextpage=/profiles%3Fswitch%3D" + token;
  } else {
    EditModal(token);
  }
};

var EditModal = (token) => {
  $("#editedProfileToken").val("");
  $("#editCurrentAvatarSrc").val("");
  $.post(
    InternalAjaxHost + "web-service/get/profile",
    { token: token },
    (data) => {
      try {
        var JsonData = JSON.parse(data);
        $("#editedProfileToken").val(JsonData.Profile.Token);
        $("#deleteProfileName").text(JsonData.Profile.Name);
        $(".edit-modal").show();
        $(".user-select").hide();
        $(".edit-modal .edit_profile_name").val(JsonData.Profile.Name);
        $(".edit-modal .avatar-area img").attr("src", JsonData.Profile.Avatar);

        $("#edit-profile-language").val(JsonData.Profile.Lang);
        $("#editCurrentAvatarSrc").val(JsonData.Profile.Avatar);
        $("#imagesTopAvatar").attr("src", JsonData.Profile.Avatar);

        $("#editRealName").text(JsonData.Profile.Name);

        if (JsonData.Profile.Level == 5) {
          $("#edit-profile-child-select").show();
          $("#edit-profile-level-select").hide();
          $("#childCheckboxE").prop("checked", true);
        } else {
          $("#edit-profile-child-select").hide();
          $("#edit-profile-level-select").show();
          $("#childCheckboxE").prop("checked", false);
          $("#edit-profile-level-select").val(JsonData.Profile.Level);
        }

        if (JsonData.Profile.PlaybackController1 == 1) {
          $("#edit-playback-contoller1").prop("checked", true);
        } else {
          $("#edit-playback-contoller1").prop("checked", false);
        }

        if (JsonData.Profile.PlaybackController2 == 1) {
          $("#edit-playback-contoller2").prop("checked", true);
        } else {
          $("#edit-playback-contoller2").prop("checked", false);
        }

        $(".edit-modal .submit").on("click", function () {
          if ($(".edit-modal .edit_profile_name").val() != "") {
            $("body").attr("style", "pointer-events:none;opacity:0.7;");
            $.post(
              InternalAjaxHost + "web-service/edit/profile",
              {
                token: $("#editedProfileToken").val(),
                name: $(".edit-modal .edit_profile_name").val(),
                avatar: $("#editProfileStaticAvatar").attr("src"),
                language: $("#edit-profile-language").val(),
                level: $("#edit-profile-level-select").val(),
                childprofile: $("#childCheckboxE").prop("checked")
                  ? "true"
                  : "false",
                playbackcontrol1: $("#edit-playback-contoller1").prop("checked")
                  ? "true"
                  : "false",
                playbackcontrol2: $("#edit-playback-contoller2").prop("checked")
                  ? "true"
                  : "false",
              },
              (data) => {
                window.location = InternalAjaxHost + "profiles";
              }
            ).fail(() => {
              window.location = InternalAjaxHost + "profiles";
            });
          }
        });

        $(".edit-modal .avatar-area").on("click", function () {
          $("#select-avatar-user").show();
          $(".photos-modal").show();
          try {
            $(".js-photos-carousel").slick("refresh");
          } catch (error) {}
          $(".js-photos-carousel")
            .not(".slick-initialized")
            .slick({
              arrows: true,
              infinite: true,
              slidesToShow: 8,
              slidesToScroll: 1,
              responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                  },
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                  },
                },
              ],
              prevArrow: `
              <div class="controls prev js-prev">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492.004 492.004" style="enable-background:new 0 0 492.004 492.004;" xml:space="preserve">
                      <g>
                          <g>
                              <path fill="#fff" d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12    c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028    c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265    c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z"/>
                          </g>
                      </g>
                  </svg>
              </div> 
            `,
              nextArrow: `
            <div class="controls next js-next">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492.004 492.004" style="enable-background:new 0 0 492.004 492.004;" xml:space="preserve">
                    <g>
                        <g>
                            <path fill="#fff"  d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12    c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028    c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265    c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z"/>
                        </g>
                    </g>
                </svg>
            </div>
            `,
            });

          $(".photos-modal .back").on("click", function () {
            $(".photos-modal").hide();
            $(".edit-modal").show();
          });
          $(".photos-modal .photos-row a").on("click", function (e) {
            e.preventDefault();
            var ImgPath = $(this).find("img").attr("data-src");
            $("#apply-photo-img1").attr(
              "src",
              $("#editCurrentAvatarSrc").val()
            );
            $("#apply-photo-img2").attr("src", ImgPath);

            $(".photos-modal").hide();
            $(".apply-photo-modal").show();
          });
          $(".apply-photo-modal .buttons a:nth-child(1)").on(
            "click",
            function (e) {
              e.preventDefault();
              $(".edit-modal .avatar-area img").attr(
                "src",
                $("#apply-photo-img2").attr("src")
              );
              $(".apply-photo-modal").hide();
              $(".edit-modal").show();
            }
          );
          $(".apply-photo-modal .buttons a:nth-child(2)").on(
            "click",
            function (e) {
              e.preventDefault();
              $(".edit-modal").show();
              $(".apply-photo-modal").hide();
            }
          );
          /*let pathname = window.location.pathname.substr(11).split("/");
          $(".edit_avatar_token").val(JsonData.Profile.id);

          $.ajax({
            url: InternalAjaxHost + "web-service/edit/avatar",
            type: "POST",
            data: new FormData($(".ImageFormEdit")[0]),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
              $(".edit-modal .avatar-area img").attr("src", data);        
            },
          }).fail(() => {
            window.location = InternalAjaxHost+'profiles';
          });*/
        });

        $(".edit-modal .cancel").on("click", function () {
          $(".edit-modal").hide();
          $(".user-select").show();
        });
      } catch (error) {
        window.location = InternalAjaxHost + "profiles";
      }
    }
  ).fail(() => {
    window.location = InternalAjaxHost + "profiles";
  });
};

var NewProfile = () => {
  $(".new-profile-modal").show();
  $(".user-select").hide();
  $(".new-profile-modal .edit_profile_name").val("");
  //$(".new-profile-modal .avatar-area img").attr("src", "");

  $(".new-profile-modal .submit").on("click", function () {
    if ($(".new-profile-modal .edit_profile_name").val() != "") {
      $("body").attr("style", "pointer-events:none;opacity:0.7;");
      $.post(
        InternalAjaxHost + "web-service/add/profile",
        {
          name: $(".new-profile-modal .edit_profile_name").val(),
          avatar: $("#new_profile_default_avatar").attr("src"),
          language: $("#new-profile-language").val(),
          level: $("#new-profile-level-select").val(),
          childprofile: $("#childCheckboxD").prop("checked") ? "true" : "false",
          playbackcontrol1: $("#new-account-playback-c1").prop("checked")
            ? "true"
            : "false",
          playbackcontrol2: $("#new-account-playback-c2").prop("checked")
            ? "true"
            : "false",
        },
        (data) => {
          window.location = InternalAjaxHost + "profiles";
        }
      ).fail(() => {
        window.location = InternalAjaxHost + "profiles";
      });
    }
  });

  $(".new-profile-modal .avatar-area").on("click", function () {
    $("#select-avatar-user").hide();
    $(".photos-modal").show();
    try {
      $(".js-photos-carousel").slick("refresh");
    } catch (error) {}
    $(".js-photos-carousel")
      .not(".slick-initialized")
      .slick({
        arrows: true,
        infinite: true,
        slidesToShow: 8,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 5,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
            },
          },
        ],
        prevArrow: `
              <div class="controls prev js-prev">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492.004 492.004" style="enable-background:new 0 0 492.004 492.004;" xml:space="preserve">
                      <g>
                          <g>
                              <path fill="#fff" d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12    c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028    c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265    c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z"/>
                          </g>
                      </g>
                  </svg>
              </div> 
            `,
        nextArrow: `
            <div class="controls next js-next">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492.004 492.004" style="enable-background:new 0 0 492.004 492.004;" xml:space="preserve">
                    <g>
                        <g>
                            <path fill="#fff"  d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12    c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028    c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265    c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z"/>
                        </g>
                    </g>
                </svg>
            </div>
            `,
      });

    $(".photos-modal .back").on("click", function () {
      $(".photos-modal").hide();
      $(".new-profile-modal").show();
    });
    $(".photos-modal .photos-row a").on("click", function (e) {
      e.preventDefault();
      $(".photos-modal").hide();
      var ImgPath = $(this).find("img").attr("data-src");
      $("#new_profile_default_avatar").attr("src", ImgPath);
    });
  });

  $(".new-profile-modal .cancel").on("click", function () {
    $(".new-profile-modal").hide();
    $(".user-select").show();
  });
};

var ChildCheckbox = () => {
  if ($("#childCheckboxD").prop("checked")) {
    $("#new-profile-level-select").hide();
    $("#new-profile-child-select").show();
  } else {
    $("#new-profile-child-select").hide();
    $("#new-profile-level-select").show();
  }
};

var EditChildCheckbox = () => {
  if ($("#childCheckboxE").prop("checked")) {
    $("#edit-profile-level-select").hide();
    $("#edit-profile-child-select").show();
  } else {
    $("#edit-profile-child-select").hide();
    $("#edit-profile-level-select").show();
  }
};

var NewProfileImage = () => {
  $(".new_profile_avatar").last().click();
};

var EditProfileImage = () => {
  $(".edit_profile_avatar").last().click();
};

var ManageProfiles = (mode) => {
  var mod = $("#Mode").val();

  if (mod == "normal") {
    $(".profile-wrap").attr("style", "width:" + $("#EditWidth").val() + "px;");
    $("#add_prf").show();
    $("#Mode").val("edited");
    $(".edit_prof").show();
    $(".user-select").addClass("edit-mode");
    $(".js-hero-text").text($("#Title2").val() + ":");
  } else {
    $(".profile-wrap").attr(
      "style",
      "width:" + $("#NormalWidth").val() + "px;"
    );
    $("#add_prf").hide();
    $("#Mode").val("normal");
    $(".edit_prof").hide();
    $(".user-select").removeClass("edit-mode");
    $(".js-hero-text").text($("#Title1").val());
  }
};

var DeleteProfile = () => {
  var profileToken = $("#editedProfileToken").val();

  $(".delete-profile-modal").show();
  $(".delete-profile-modal .buttons a:nth-child(1)").on("click", function (e) {
    e.preventDefault();
    $("body").attr("style", "pointer-events:none;opacity:0.7;");
    $.post(
      InternalAjaxHost + "web-service/delete/profile",
      { token: $("#editedProfileToken").val() },
      (j) => {
        window.location = InternalAjaxHost + "profiles";
      }
    ).fail(() => {
      window.location = InternalAjaxHost + "profiles";
    });
  });
  $(".delete-profile-modal .buttons a:nth-child(2)").on("click", function (e) {
    e.preventDefault();
    $(".edit-modal").show();
    $(".delete-profile-modal").hide();
  });
};

setTimeout(() => {
  var urlPath = window.location.pathname;
  if (urlPath.split("/")[2] == "manage") {
    ManageProfiles();
  }
}, 100);
