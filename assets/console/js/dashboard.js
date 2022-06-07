$.post(
  InternalAjaxHost + "web-service/load/chart",
  { data: "data" },
  (data) => {
    var Jsn = JSON.parse(data);

    $.getJSON(
      "https://cdn.jsdelivr.net/npm/apexcharts/dist/locales/" +
        Jsn.Lang +
        ".json",
      function (data) {
        var lc = data;
        var options = {
          series: [
            {
              name: Jsn.Text,
              data: Jsn.Data2,
            },
          ],
          chart: {
            locales: [lc],
            defaultLocale: Jsn.Lang,
            height: 350,
            type: "line",
          },
          stroke: {
            width: 7,
            curve: "smooth",
          },
          xaxis: {
            type: "datetime",
            categories: Jsn.Data1,
          },
          title: {
            text: Jsn.Title,
            align: "left",
            style: {
              fontSize: "16px",
              color: "#666",
            },
          },
          fill: {
            type: "gradient",
            gradient: {
              shade: "dark",
              gradientToColors: ["#FDD835"],
              shadeIntensity: 1,
              type: "horizontal",
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100, 100, 100],
            },
          },
          markers: {
            size: 4,
            colors: ["#FFA41B"],
            strokeColors: "#fff",
            strokeWidth: 2,
            hover: {
              size: 7,
            },
          },
          yaxis: {
            min: 0,
            max: Jsn.Max,
            title: {
              text: Jsn.Text2,
            },
          },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      }
    );
  }
);
