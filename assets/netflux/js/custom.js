$(document).ready(function () {
  $(".side-nav").removeClass("open");
  $(".mobile-menu-background").removeClass("d-block");
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

    input.change(function () {
      checkInput();
    });
  });

  $("input[name=plans]").change(function () {
    var inputVal = $("input[name=plans]:checked").val();

    if (inputVal == "Free") {
      $('td[name="column-one"]').removeClass("unselected");
      $('td[name="column-one"]').addClass("selected");
    } else {
      $('td[name="column-one"]').removeClass("selected");
      $('td[name="column-one"]').addClass("unselected");
    }

    if (inputVal == "Standart") {
      $('td[name="column-two"]').removeClass("unselected");
      $('td[name="column-two"]').addClass("selected");
    } else {
      $('td[name="column-two"]').removeClass("selected");
      $('td[name="column-two"]').addClass("unselected");
    }

    if (inputVal == "Premium") {
      $('td[name="column-three"]').removeClass("unselected");
      $('td[name="column-three"]').addClass("selected");
    } else {
      $('td[name="column-three"]').removeClass("selected");
      $('td[name="column-three"]').addClass("unselected");
    }
  });

  $(".leftmenutrigger").on("click", function (e) {
    $(".side-nav").toggleClass("open");
    $(".mobile-menu-background").toggleClass("d-block");
    e.preventDefault();
  });

  var timer;
  $(".dropdown-menu, .dropdown-toggle").hover(
    function () {
      clearTimeout(timer);
      $(".dropdown, .dropdown-menu").addClass("show");
      $(".dropdown-toggle").attr("aria-expanded", "true");
    },
    function () {
      timer = setTimeout(function () {
        $(".dropdown, .dropdown-menu").removeClass("show");
        $(".dropdown-toggle").attr("aria-expanded", "false");
      }, 300);
    }
  );

  $(document).scroll(function () {
    var scrolled = $(this).scrollTop() > $(".nav-cont").height();
    $(".nav-cont").toggleClass("scrolled", scrolled);
  });
});

function showhidetoggle() {
  var p = document.getElementById("form_password");
  if (p.type == "password") {
    p.type = "text";
  } else {
    p.type = "password";
  }
  $("#showhide").hide();
  $("#showhide2").show();
  document.getElementById("form_password").focus();
}

function showhidetoggle2() {
  var p = document.getElementById("form_password");
  if (p.type == "password") {
    p.type = "text";
  } else {
    p.type = "password";
  }
  $("#showhide2").hide();
  $("#showhide").show();
  document.getElementById("form_password").focus();
}

setTimeout(() => {
  RefreshSearch();
}, 500);
