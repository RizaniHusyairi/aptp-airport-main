// Ambil elemen chart
const chartProfileVisitEl = document.getElementById('chart-profile-visit');

// Ambil data dari atribut data-*
const categories = JSON.parse(chartProfileVisitEl.dataset.categories);
const series = JSON.parse(chartProfileVisitEl.dataset.series);

var optionsProfileVisit = {
    annotations: {
        position: "back",
    },
    dataLabels: {
        enabled: false,
    },
    chart: {
        type: "bar",
        height: 300,
    },
    fill: {
        opacity: 1,
    },
    plotOptions: {},
    series: [
        {
            name: "pengunjung",
            data: series,
        },
    ],
    colors: "#435ebe",
    xaxis: {
        categories: categories,
    },
};




// Render grafik
var chartProfileVisit = new ApexCharts(
    document.querySelector("#chart-profile-visit"),
    optionsProfileVisit
);

chartProfileVisit.render();

// Event listener untuk dropdown filter pemasukan
