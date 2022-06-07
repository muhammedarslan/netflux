var CreateTable = () => {
  var dataListView = $(".data-table").DataTable({
    ajax: {
      url: InternalAjaxHost + "web-service/table/content",
      type: "POST",
      data: {
        load: $("table").attr("data-source"),
        fast: true,
      },
    },
    order: [[0, "desc"]],
    processing: true,
    serverSide: true,
    initComplete: function (settings, json) {
      LoadAdminPage();
      $(".data-table").on("page.dt", function () {
        setTimeout(() => {
          LoadAdminPage();
        }, 1000);
      });
      $(".data-table").on("order.dt", function () {
        setTimeout(() => {
          LoadAdminPage();
        }, 1000);
      });
      $(".data-table").on("search.dt", function () {
        setTimeout(() => {
          LoadAdminPage();
        }, 3000);
      });
    },
    language: {
      sSearch: "",
      sSearchPlaceholder: "Search in movies",
      sLoadingRecords:
        "<img src='/assets/media/loading.gif' style='width:60px;' alt='loading'>",
    },
  });

  dataListView.on("draw", function () {
    setTimeout(() => {
      LoadAdminPage();
    }, 3000);
  });
};

var CreateTableFast = () => {
  var dataListView = $(".data-table").DataTable({
    ajax: {
      url: InternalAjaxHost + "web-service/table/content",
      type: "POST",
      data: {
        load: $("table").attr("data-source"),
        fast: true,
      },
    },
    order: [[0, "desc"]],
    processing: true,
    serverSide: true,
    initComplete: function (settings, json) {
      LoadAdminPage();
      $(".data-table").on("page.dt", function () {
        setTimeout(() => {
          LoadAdminPage();
        }, 1000);
      });
      $(".data-table").on("order.dt", function () {
        setTimeout(() => {
          LoadAdminPage();
        }, 1000);
      });
      $(".data-table").on("search.dt", function () {
        setTimeout(() => {
          LoadAdminPage();
        }, 1000);
      });
    },
    language: {
      sLoadingRecords:
        "<img src='/assets/media/loading.gif' style='width:60px;' alt='loading'>",
    },
  });
  dataListView.on("draw", function () {
    setTimeout(() => {
      LoadAdminPage();
    }, 3000);
  });
};

CreateTable();
