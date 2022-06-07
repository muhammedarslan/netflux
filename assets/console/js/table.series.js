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
    order: [
      [2, "asc"],
      [3, "asc"],
    ],
    rowGroup: {
      dataSrc: [2, 3],
    },
    columnDefs: [
      {
        targets: [2, 3],
        visible: false,
      },
    ],
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
    }, 1000);
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
    order: [
      [2, "asc"],
      [3, "asc"],
    ],
    rowGroup: {
      dataSrc: [2, 3],
    },
    columnDefs: [
      {
        targets: [2, 3],
        visible: false,
      },
    ],
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
    }, 1000);
  });
};

CreateTable();
