// Ambil elemen chart
const chartProfileVisitEl = document.getElementById('chart-profile-visit');
const chartPemasukanEl = document.getElementById('chart-pemasukan');
const chartAnggaran = document.getElementById('chart-anggaran-belanja');

// Ambil data dari atribut data-*
const categories = JSON.parse(chartProfileVisitEl.dataset.categories);
const series = JSON.parse(chartProfileVisitEl.dataset.series);
const pemasukan = JSON.parse(chartPemasukanEl.dataset.pemasukan);
const anggaran = JSON.parse(chartAnggaran.dataset.anggaran);

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

var optionsPemasukan = {
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
            name: "pemasukan",
            data: pemasukan.all.data,
        },
    ],
    colors: "#435ebe",
    xaxis: {
        categories: pemasukan.all.categories,
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return val.toFixed(2) + " Juta";
            }
        }
    }
};

var optionsAnggaranBelanja = {
    chart: {
        type: "area",
        height: 300,
        stacked: false,
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        width: [2, 2],
        curve: "smooth",
    },
    fill: {
        type: "gradient",
        gradient: {
            opacityFrom: 0.6,
            opacityTo: 0.8,
        },
    },
    series: [
        {
            name: "Anggaran",
            data: anggaran.all.anggaran,
        },
        {
            name: "Belanja",
            data: anggaran.all.belanja,
        },
    ],
    colors: ["#435ebe", "#55c6e8"],
    xaxis: {
        categories: anggaran.all.categories,
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return val.toFixed(2) + " Juta";
            }
        }
    },
    tooltip: {
        shared: true,
        intersect: false,
        y: {
            formatter: function (val) {
                return val.toFixed(2) + " Juta";
            }
        }
    },
    legend: {
        position: "bottom",
    },
};

// Fungsi untuk memperbarui grafik pemasukan berdasarkan filter
function updatePemasukanChart(period) {
    optionsPemasukan.series[0].data = pemasukan[period].data;
    optionsPemasukan.xaxis.categories = pemasukan[period].categories;
    chartPemasukan.updateOptions(optionsPemasukan);
}

// Fungsi untuk memperbarui grafik anggaran dan belanja berdasarkan filter
function updateAnggaranBelanjaChart(period) {
    optionsAnggaranBelanja.series[0].data = anggaran[period].anggaran;
    optionsAnggaranBelanja.series[1].data = anggaran[period].belanja;
    optionsAnggaranBelanja.xaxis.categories = anggaran[period].categories;
    chartAnggaranBelanja.updateOptions(optionsAnggaranBelanja);
}

// Render grafik
var chartProfileVisit = new ApexCharts(
    document.querySelector("#chart-profile-visit"),
    optionsProfileVisit
);
var chartPemasukan = new ApexCharts(
    document.getElementById("chart-pemasukan"),
    optionsPemasukan
);
var chartAnggaranBelanja = new ApexCharts(
    document.getElementById("chart-anggaran-belanja"),
    optionsAnggaranBelanja
);

chartProfileVisit.render();
chartPemasukan.render();
chartAnggaranBelanja.render();

// Event listener untuk dropdown filter pemasukan
document.getElementById("pemasukan-filter").addEventListener("change", function () {
    const selectedPeriod = this.value;
    updatePemasukanChart(selectedPeriod);
});

// Event listener untuk dropdown filter anggaran dan belanja
document.getElementById("anggaran-belanja-filter").addEventListener("change", function () {
    const selectedPeriod = this.value;
    updateAnggaranBelanjaChart(selectedPeriod);
});