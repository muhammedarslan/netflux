function shuffle(arr) {
  for (
    var j, x, i = arr.length;
    i;
    j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x
  );
  return arr;
}

$(document).on("click", 'a[href="#"]', function (e) {
  e.preventDefault();
});

var sliderSettings = function () {
  return {
    arrows: true,
    infinite: false,
    slidesToShow: 6,
    slidesToScroll: 6,
    responsive: [
      {
        breakpoint: 1500,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 5,
        },
      },
      {
        breakpoint: 1360,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
        },
      },
      {
        breakpoint: 1030,
        settings: {
          centerMode: false,
          slidesToShow: 3,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 992,
        settings: {
          centerMode: false,
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 560,
        settings: {
          centerMode: false,
          slidesToShow: 1,
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
  };
};

var BrowseRow = (element) => {
  var SourceUrl = $(element).attr("data-source");

  if (typeof $(element).attr("data-send") != "undefined") {
    var AjaxData = { data: $(element).attr("data-send") };
  } else {
    var AjaxData = { load: "slides" };
  }

  if (typeof $(element).attr("data-speed") != "undefined") {
    var Sleep = $(element).attr("data-speed");
  } else {
    var Sleep = 1500;
  }

  setTimeout(() => {
    $.post(InternalAjaxHost + "web-service/" + SourceUrl, AjaxData, (data) => {
      try {
        var Jsn = JSON.parse(data);
        var windowWidth = $(window).width();
        var columnCount = 4;

        if (windowWidth < 500) {
          columnCount = 2;
        } else if (windowWidth < 800) {
          columnCount = 3;
        } else if (windowWidth < 1100) {
          columnCount = 4;
        } else if (windowWidth < 1400) {
          columnCount = 5;
        } else {
          columnCount = 6;
        }

        columnCount2 = columnCount;

        if (columnCount > Jsn.ItemCount) {
          columnCount = Jsn.ItemCount;
        }

        $("#PageJwtToken").val(Jsn.Jwt);

        var PerLine = columnCount;
        var LineCount = Math.ceil(Jsn.ItemCount / PerLine);
        var HtmlOutput = "";
        var ItemCounter = 0;

        if (Jsn.ItemCount == 0) {
          if (typeof $(element).attr("data-hide") != "undefined") {
            $(element).remove();
          }

          HtmlOutput += '<div class="hero">' + Jsn.SomeTexts.NoItem + "</div>";
        } else if ($(element).attr("data-title") != "null") {
          HtmlOutput +=
            '<div class="hero">' + $(element).attr("data-title") + "</div>";
        }

        if (typeof $(element).attr("data-single") != "undefined") {
          LineCount = 1;
          PerLine = Jsn.ItemCount;
        }

        for (var index = 0; index < LineCount; index++) {
          AttrStyle = "";
          if (index != 0) AttrStyle = 'style="margin-top: 50px;"';

          HtmlOutput +=
            "<div " +
            AttrStyle +
            " >" +
            '<div class="list-carousel js-carousel">';

          var SingleElement;
          var SingleHtml;
          var CatArr = [];
          var Images = [];

          for (var Innerindex = 0; Innerindex < PerLine; Innerindex++) {
            if (typeof Jsn.Items[ItemCounter] == "undefined") {
              break;
            }

            SingleElement = Jsn.Items[ItemCounter];

            Images = shuffle(SingleElement.Images.ImagesArray);

            if (Images.length == 1) {
              Images[1] = Images[0];
            }

            var badges = "";
            CatArr = SingleElement.Video.Categories;
            for (
              var CategoryIndex = 0;
              CategoryIndex < CatArr.length;
              CategoryIndex++
            ) {
              badges += `
                    <span>${CatArr[CategoryIndex]}</span>
                  `;
            }

            var progressBar = "";

            if (SingleElement.PercentageWatched > 0) {
              progressBar =
                '<div class="progress"><span style="--item-percentage: ' +
                SingleElement.PercentageWatched +
                '%;" ></span></div>';
            }

            if (SingleElement.ListAdded == false) {
              var ListDisplay1 = "";
              var ListDisplay2 = "display:none;";
            } else {
              var ListDisplay2 = "";
              var ListDisplay1 = "display:none;";
            }

            var LikedClass1 = "";
            var LikedClass2 = "";

            if (SingleElement.Liked == true) {
              LikedClass1 = "listliked";
            }

            if (SingleElement.Unliked == true) {
              LikedClass2 = "listliked";
            }

            SingleHtml = `
                <div>
                    <div class="item">
                      ${progressBar}
                      <div class="item-container">
                          <a href="${InternalAjaxHost}browse/87654${
              SingleElement.ItemID
            }" ><div class="image" style="background-image: url(${Images[0]})">
                          <div class="name">${SingleElement.Video.Name}</div>
                          </div></a>
                          <div class="content">
                              <div class="buttons">
                                  <span class="d-none" data-tooltip="${
                                    Jsn.SomeTexts.watch
                                  }" onclick="WatchVideo(${
              SingleElement.ItemID
            });" >
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 494.148 494.148" style="enable-background:new 0 0 494.148 494.148;" xml:space="preserve">
                                          <g>
                                              <g>
                                                  <path d="M405.284,201.188L130.804,13.28C118.128,4.596,105.356,0,94.74,0C74.216,0,61.52,16.472,61.52,44.044v406.124    c0,27.54,12.68,43.98,33.156,43.98c10.632,0,23.2-4.6,35.904-13.308l274.608-187.904c17.66-12.104,27.44-28.392,27.44-45.884    C432.632,229.572,422.964,213.288,405.284,201.188z"/>
                                              </g>
                                          </g>
                                      </svg>
                                  </span>
                                  <span data-tooltip="${
                                    Jsn.SomeTexts.watch
                                  }" onclick="WatchVideo(${
              SingleElement.ItemID
            });" >
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 494.148 494.148" style="enable-background:new 0 0 494.148 494.148;" xml:space="preserve">
                                          <g>
                                              <g>
                                                  <path d="M405.284,201.188L130.804,13.28C118.128,4.596,105.356,0,94.74,0C74.216,0,61.52,16.472,61.52,44.044v406.124    c0,27.54,12.68,43.98,33.156,43.98c10.632,0,23.2-4.6,35.904-13.308l274.608-187.904c17.66-12.104,27.44-28.392,27.44-45.884    C432.632,229.572,422.964,213.288,405.284,201.188z"/>
                                              </g>
                                          </g>
                                      </svg>
                                  </span>
                                  <span style="${ListDisplay1}" data-tooltip="${
              Jsn.SomeTexts.addList
            }" data-token="${
              SingleElement.ItemToken
            }" onClick="ListAdded1(this);">
                                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve">
                                          <g>
                                              <g>
                                                  <path d="M465.064,207.566l0.028,0H284.436V27.25c0-14.84-12.016-27.248-26.856-27.248h-23.116    c-14.836,0-26.904,12.408-26.904,27.248v180.316H26.908c-14.832,0-26.908,12-26.908,26.844v23.248    c0,14.832,12.072,26.78,26.908,26.78h180.656v180.968c0,14.832,12.064,26.592,26.904,26.592h23.116    c14.84,0,26.856-11.764,26.856-26.592V284.438h180.624c14.84,0,26.936-11.952,26.936-26.78V234.41    C492,219.566,479.904,207.566,465.064,207.566z"/>
                                              </g>
                                          </g>
                                      </svg>
                                  </span>
                                  <span style="${ListDisplay2}" data-tooltip="${
              Jsn.SomeTexts.removeList
            }" data-token="${
              SingleElement.ItemToken
            }" onClick="ListAdded2(this);">
                                      <svg style="transform: rotate(135deg)" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve">
                                          <g>
                                              <g>
                                                  <path d="M465.064,207.566l0.028,0H284.436V27.25c0-14.84-12.016-27.248-26.856-27.248h-23.116    c-14.836,0-26.904,12.408-26.904,27.248v180.316H26.908c-14.832,0-26.908,12-26.908,26.844v23.248    c0,14.832,12.072,26.78,26.908,26.78h180.656v180.968c0,14.832,12.064,26.592,26.904,26.592h23.116    c14.84,0,26.856-11.764,26.856-26.592V284.438h180.624c14.84,0,26.936-11.952,26.936-26.78V234.41    C492,219.566,479.904,207.566,465.064,207.566z"/>
                                              </g>
                                          </g>
                                      </svg>
                                  </span>
                                  <span class="${LikedClass1}" data-token="${
              SingleElement.ItemToken
            }" onClick="ListLiked1(this);" data-tooltip="${
              Jsn.SomeTexts.liked
            }">
                                      <svg viewBox="0 0 24 24"><path d="M15.167 8.994h3.394l.068.023c1.56.138 2.867.987 2.867 2.73 0 .275-.046.527-.092.78.367.435.596.986.596 1.72 0 .963-.39 1.52-1.032 1.978.023.183.023.252.023.39 0 .963-.39 1.784-1.009 2.243.023.206.023.275.023.39 0 1.743-1.33 2.591-2.89 2.73L12.21 22c-2.04 0-3.05-.252-4.563-.895-.917-.39-1.353-.527-2.27-.619L4 20.371v-8.234l2.476-1.445 2.27-4.427c0-.046.085-1.552.253-4.52l.871-.389c.092-.069.275-.138.505-.184.664-.206 1.398-.252 2.132 0 1.261.436 2.064 1.537 2.408 3.258.142.829.226 1.695.26 2.564l-.008 2zm-4.42-2.246l-2.758 5.376L6 13.285v5.26c.845.113 1.44.3 2.427.72 1.37.58 2.12.735 3.773.735l4.816-.023c.742-.078.895-.3 1.015-.542.201-.4.201-.876 0-1.425.558-.184.917-.479 1.078-.883.182-.457.182-.966 0-1.528.601-.228.901-.64.901-1.238s-.202-1.038-.608-1.32c.23-.46.26-.892.094-1.294-.168-.404-.298-.627-1.043-.738l-.172-.015h-5.207l.095-2.09c.066-1.448-.009-2.875-.216-4.082-.216-1.084-.582-1.58-1.096-1.758-.259-.09-.546-.086-.876.014-.003.06-.081 1.283-.235 3.67z"></path></svg>
                                  </span>
                                  <span class="${LikedClass2}" data-token="${
              SingleElement.ItemToken
            }" onClick="ListLiked2(this);" data-tooltip="${
              Jsn.SomeTexts.unliked
            }">
                                      <svg viewBox="0 0 24 24"><path d="M8.833 15.006H5.44l-.068-.023c-1.56-.138-2.867-.987-2.867-2.73 0-.275.046-.527.092-.78C2.23 11.038 2 10.487 2 9.753c0-.963.39-1.52 1.032-1.978-.023-.183-.023-.252-.023-.39 0-.963.39-1.784 1.009-2.243-.023-.206-.023-.275-.023-.39 0-1.743 1.33-2.591 2.89-2.73L11.79 2c2.04 0 3.05.252 4.563.895.917.39 1.353.527 2.27.619L20 3.629v8.234l-2.476 1.445-2.27 4.427c0 .046-.085 1.552-.253 4.52l-.871.389c-.092.069-.275.138-.505.184-.664.206-1.398.252-2.132 0-1.261-.436-2.064-1.537-2.408-3.258a19.743 19.743 0 0 1-.26-2.564l.008-2zm4.42 2.246l2.758-5.376L18 10.715v-5.26c-.845-.113-1.44-.3-2.427-.72C14.203 4.156 13.453 4 11.8 4l-4.816.023c-.742.078-.895.3-1.015.542-.201.4-.201.876 0 1.425-.558.184-.917.479-1.078.883-.182.457-.182.966 0 1.528-.601.228-.901.64-.901 1.238s.202 1.038.608 1.32c-.23.46-.26.892-.094 1.294.168.404.298.627 1.043.738l.172.015h5.207l-.095 2.09c-.066 1.448.009 2.875.216 4.082.216 1.084.582 1.58 1.096 1.758.259.09.546.086.876-.014.003-.06.081-1.283.235-3.67z"></path></svg>
                                  </span>
                                  <span onclick="MoreDetail('${
                                    SingleElement.ItemID
                                  }');" data-tooltip="${
              Jsn.SomeTexts.More
            }" class="js-modal-show">
                                      <svg viewBox="0 0 24 24"><path d="M5.689 7.924L4.387 9.442 12.038 16l7.651-6.558-1.302-1.518-6.349 5.442z"></path></svg>
                                  </span>                                  
                              </div>
                              <div class="details">
                                  <span class="similar">${
                                    SingleElement.Video.MatchScore
                                  }</span>
                                  ${
                                    SingleElement.Video.Level != "__noLevel__"
                                      ? '<span class="age">' +
                                        SingleElement.Video.Level +
                                        "</span>"
                                      : '<span class="nolevel"><img src="/assets/media/noLevel.png"/> </span>'
                                  }
                                  ${
                                    SingleElement.Video.SeasonCount > 0
                                      ? '<span class="season">' +
                                        SingleElement.Video.SeasonCount +
                                        " " +
                                        Jsn.SomeTexts.Season +
                                        "</span>"
                                      : ""
                                  }
                              </div>
                              <div class="badges">
                                  ${badges}
                              </div>
                          </div>
                      </div>
                    </div>
                </div>  
                `;

            HtmlOutput += SingleHtml;
            ItemCounter++;
          }

          HtmlOutput += "</div>";

          HtmlOutput += "</div>";
        }

        $(element).html(HtmlOutput);

        $(element).hide();
        $(element).show();
        setTimeout(() => {
          $("img.lazyload").lazyload();
        }, 500);
        setTimeout(() => {
          $(".js-carousel").not(".slick-initialized").slick(sliderSettings());
        }, 250);
      } catch (error) {
        $(element).html(
          '<div class="carousel-title">' + "Something were wrong." + "</div>"
        );
      }
    }).fail(() => {
      $(element).html(
        '<div class="carousel-title">' + "Something were wrong." + "</div>"
      );
    });
  }, Sleep);
};

setTimeout(() => {
  $(".list_videos").each((index, element) => {
    setTimeout(() => {
      BrowseRow(element);
    }, 1000 * index);
  });
}, 100);
