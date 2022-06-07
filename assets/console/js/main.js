$(function () {
  $(".js-accordion").on("click", function () {
    $(this).find(".js-accordion-item").toggleClass("active");
  });

  $(".js-tab-nav a").on("click", function (e) {
    e.preventDefault();
    $(this).closest(".js-tabs").find(".js-tab-nav li a").removeClass("active");
    $(this).addClass("active");
    $(this).closest(".js-tabs").find(".js-tab-content").removeClass("active");
    $(this)
      .closest(".js-tabs")
      .find(".js-tab-content[data-name=" + $(this).data("target") + "]")
      .addClass("active");
  });

  $(".star-rating").starRating({
    initialRating: 0,
    starSize: 45,
    strokeWidth: 0,
    strokeColor: "black",
    ratedColors: ["#e2c181", "#e2c181", "#e2c181", "#e2c181", "#e2c181"],
  });

  $(".js-modal-toggle").on("click", function (e) {
    e.preventDefault();
    $(".js-modal").hide();
    $(".js-modal[data-name=" + $(this).data("target") + "]").show();
  });

  $(".js-modal-close").on("click", function (e) {
    e.preventDefault();
    $(".js-modal").hide();
  });

  $(".js-alert").on("click", function (e) {
    e.preventDefault();
    swal({
      title: "Product Was Added!",
      icon: "success", // success, warning, error
      button: "Close",
    });
  });

  $(".js-mobile-toggle").on("click", function () {
    $("body").addClass("menu-active");
  });
  $(".js-backdrop").on("click", function () {
    $("body").removeClass("menu-active");
  });

  $(".user-menu").on("click", function () {
    $(this).toggleClass("active");
  });

  $(".js-dropdown").on("click", function () {
    $(this).toggleClass("active");
  });
});

var LoadAdminPage = () => {
  $(".js-accordion").on("click", function () {
    $(this).find(".js-accordion-item").toggleClass("active");
  });

  $(".js-tab-nav a").on("click", function (e) {
    e.preventDefault();
    $(this).closest(".js-tabs").find(".js-tab-nav li a").removeClass("active");
    $(this).addClass("active");
    $(this).closest(".js-tabs").find(".js-tab-content").removeClass("active");
    $(this)
      .closest(".js-tabs")
      .find(".js-tab-content[data-name=" + $(this).data("target") + "]")
      .addClass("active");
  });

  $(".star-rating").starRating({
    initialRating: 0,
    starSize: 45,
    strokeWidth: 0,
    strokeColor: "black",
    ratedColors: ["#e2c181", "#e2c181", "#e2c181", "#e2c181", "#e2c181"],
  });

  $(".js-modal-toggle").on("click", function (e) {
    e.preventDefault();
    $(".js-modal").hide();
    $(".js-modal[data-name=" + $(this).data("target") + "]").show();
  });

  $(".js-modal-close").on("click", function (e) {
    e.preventDefault();
    $(".js-modal").hide();
  });

  $(".js-alert").on("click", function (e) {
    e.preventDefault();
    swal({
      title: "Product Was Added!",
      icon: "success", // success, warning, error
      button: "Close",
    });
  });

  $(".js-mobile-toggle").on("click", function () {
    $("body").addClass("menu-active");
  });
  $(".js-backdrop").on("click", function () {
    $("body").removeClass("menu-active");
  });

  $(".js-dropdown").on("click", function () {
    $(this).toggleClass("active");
  });

  $(document).on("click", function (e) {
    var container = $(".js2-dropdown");
    if (!$(e.target).closest(container).length) {
      $(".js2-dropdown").removeClass("active");
    }
  });
};

var OpenDropdownR = (e) => {
  $(e).toggleClass("active");
  return false;
};

var MenuClick = (f) => {
  e.preventDefault();
  $(".user-menu").toggleClass("active");
  return false;
};

var DeleteData = (type, id) => {
  Swal.fire({
    title: SomeTextLangs.Delete1,
    text: SomeTextLangs.Delete2,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: SomeTextLangs.Delete3,
    cancelButtonText: SomeTextLangs.Delete4,
  }).then((result) => {
    if (result.value) {
      topbar.show();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/delete/data",
          { DataType: type, DataId: id },
          (data) => {
            var JsonResponse = JSON.parse(data);
            if (JsonResponse.status == "success") {
              Swal.fire(JsonResponse.label, JsonResponse.text, "success");
            } else {
              Swal.fire("Error!", "Something were wrong.", "error");
            }
            setTimeout(() => {
              topbar.hide();
            }, 1000);
            try {
              $("#DataTableD").DataTable().destroy();
              CreateTableFast();
            } catch (error) {}
          }
        ).fail(() => {
          window.location = "";
        });
      }, 500);
    }
  });
};

var DeleteDataGallery = (type, id) => {
  Swal.fire({
    title: SomeTextLangs.Delete1,
    text: SomeTextLangs.Delete2,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: SomeTextLangs.Delete3,
    cancelButtonText: SomeTextLangs.Delete4,
  }).then((result) => {
    if (result.value) {
      topbar.show();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/delete/data",
          { DataType: type, DataId: id },
          (data) => {
            var JsonResponse = JSON.parse(data);
            if (JsonResponse.status == "success") {
              Swal.fire(JsonResponse.label, JsonResponse.text, "success");
            } else {
              Swal.fire("Error!", "Something were wrong.", "error");
            }
            setTimeout(() => {
              topbar.hide();
            }, 1000);
            try {
              barba.go("");
            } catch (error) {}
          }
        ).fail(() => {
          window.location = "";
        });
      }, 500);
    }
  });
};

var DeleteLang = (type, id) => {
  Swal.fire({
    title: SomeTextLangs.Delete1,
    text: SomeTextLangs.Delete2,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: SomeTextLangs.Delete3,
    cancelButtonText: SomeTextLangs.Delete4,
  }).then((result) => {
    if (result.value) {
      topbar.show();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/delete/language",
          { DataType: type, DataId: id },
          (data) => {
            var JsonResponse = JSON.parse(data);
            if (JsonResponse.status == "success") {
              Swal.fire(JsonResponse.label, JsonResponse.text, "success");
            } else {
              Swal.fire("Error!", "Something were wrong.", "error");
            }
            setTimeout(() => {
              topbar.hide();
            }, 1000);
            try {
              $("#DataTableD").DataTable().destroy();
              CreateTableFast();
            } catch (error) {}
          }
        ).fail(() => {
          window.location = "";
        });
      }, 500);
    }
  });
};

var BlockUser = (id) => {
  Swal.fire({
    title: SomeTextLangs.Block1,
    text: SomeTextLangs.Block2,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: SomeTextLangs.Block3,
    cancelButtonText: SomeTextLangs.Block4,
  }).then((result) => {
    if (result.value) {
      topbar.show();
      setTimeout(() => {
        $.post(
          InternalAjaxHost + "web-service/block/user",
          { DataId: id },
          (data) => {
            var JsonResponse = JSON.parse(data);
            if (JsonResponse.status == "success") {
              Swal.fire(JsonResponse.label, JsonResponse.text, "success");
            } else {
              Swal.fire("Error!", "Something were wrong.", "error");
            }
            setTimeout(() => {
              topbar.hide();
            }, 1000);
            try {
              $("#DataTableD").DataTable().destroy();
              CreateTableFast();
            } catch (error) {}
          }
        ).fail(() => {
          window.location = "";
        });
      }, 500);
    }
  });
};

var ActiveUser = (id) => {
  topbar.show();
  setTimeout(() => {
    $.post(
      InternalAjaxHost + "web-service/active/user",
      { DataId: id },
      (data) => {
        var JsonResponse = JSON.parse(data);
        if (JsonResponse.status == "success") {
          Swal.fire(JsonResponse.label, JsonResponse.text, "success");
        } else {
          Swal.fire("Error!", "Something were wrong.", "error");
        }
        setTimeout(() => {
          topbar.hide();
        }, 1000);
        try {
          $("#DataTableD").DataTable().destroy();
          CreateTableFast();
        } catch (error) {}
      }
    ).fail(() => {
      window.location = "";
    });
  }, 100);
};

var EditData = (e) => {
  var editData = $(e).attr("data-edit");
  $.post(
    InternalAjaxHost + "web-service/edit/data",
    { data: editData },
    (data) => {
      var Jsn = JSON.parse(data);

      $.each(Jsn, (key, item) => {
        $('[name="edit_' + key + '"]').val(item);
      });
    }
  );
};

var EditLang = (e) => {
  var editData = $(e).attr("data-edit");
  $.post(
    InternalAjaxHost + "web-service/edit/language/data",
    { data: editData },
    (data) => {
      var Jsn = JSON.parse(data);

      $.each(Jsn, (key, item) => {
        $('[name="edit_' + key + '"]').val(item);
      });
    }
  );
};

var SubmitForm = (e) => {
  var ajaxPath = $(e).attr("data-source");
  $("form").attr("style", "pointer-events:none;opacity:0.6");
  $(".form_button").addClass("m-progress");
  //$(".visit-button").focus();
  $("input[type=text], textarea").blur();

  setTimeout(() => {
    var f = document.body.querySelector('form[data-source="' + ajaxPath + '"]');
    var d = new FormData(f);

    $.ajax({
      url: InternalAjaxHost + ajaxPath,
      type: "POST",
      data: d,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        toastr.options = {
          closeButton: false,
          debug: false,
          newestOnTop: true,
          progressBar: true,
          positionClass: "toast-top-right",
          preventDuplicates: false,
          onclick: null,
          showDuration: "300",
          hideDuration: "1000",
          timeOut: "3000",
          extendedTimeOut: "1000",
          showEasing: "swing",
          hideEasing: "linear",
          showMethod: "fadeIn",
          hideMethod: "fadeOut",
        };

        var JsonResponseAjax = JSON.parse(data);

        if (JsonResponseAjax.clearInput == true) {
          $("input").val("");
        }

        try {
          if (JsonResponseAjax.closeModal != false) {
            $(".js-modal-close").click();
          }
        } catch (error) {}

        if (JsonResponseAjax.refreshTable == true) {
          $("#DataTableD").DataTable().destroy();
          CreateTableFast();
        }

        $("form").removeAttr("style");
        $(".form_button").removeClass("m-progress");

        toastr[JsonResponseAjax.label](JsonResponseAjax.text);
      },
    }).fail(() => {
      window.location = "";
    });
  }, 1000);
};

var SubmitFormGallery = (e) => {
  var ajaxPath = $(e).attr("data-source");
  $("form").attr("style", "pointer-events:none;opacity:0.6");
  $(".form_button").addClass("m-progress");
  //$(".visit-button").focus();

  setTimeout(() => {
    var f = document.body.querySelector('form[data-source="' + ajaxPath + '"]');
    var d = new FormData(f);

    $.ajax({
      url: InternalAjaxHost + ajaxPath,
      type: "POST",
      data: d,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        toastr.options = {
          closeButton: false,
          debug: false,
          newestOnTop: true,
          progressBar: true,
          positionClass: "toast-top-right",
          preventDuplicates: false,
          onclick: null,
          showDuration: "300",
          hideDuration: "1000",
          timeOut: "3000",
          extendedTimeOut: "1000",
          showEasing: "swing",
          hideEasing: "linear",
          showMethod: "fadeIn",
          hideMethod: "fadeOut",
        };

        var JsonResponseAjax = JSON.parse(data);

        if (JsonResponseAjax.clearInput == true) {
          $("input").val("");
        }

        try {
          if (JsonResponseAjax.closeModal != false) {
            $(".js-modal-close").click();
          }
        } catch (error) {}

        barba.go("");
        $("form").removeAttr("style");
        $(".form_button").removeClass("m-progress");

        toastr[JsonResponseAjax.label](JsonResponseAjax.text);
      },
    }).fail(() => {
      window.location = "";
    });
  }, 1000);
};

var Profile = (id) => {
  var editData = "users-" + id;
  $.post(
    InternalAjaxHost + "web-service/edit/data",
    { data: editData },
    (data) => {
      var Jsn = JSON.parse(data);

      $.each(Jsn, (key, item) => {
        $('[name="profile_' + key + '"]').val(item);
      });
    }
  );
};
