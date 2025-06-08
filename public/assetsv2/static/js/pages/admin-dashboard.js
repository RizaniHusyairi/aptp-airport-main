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
      data: [9, 20, 30, 20, 10, 20, 30],
    },
  ],
  colors: "#435ebe",
  xaxis: {
    categories: [
      "25/05/2025",
      "24/05/2025",
      "23/05/2025",
      "22/05/2025",
      "21/05/2025",
      "20/05/2025",
      "19/05/2025",
    ],
  },
};

// Data untuk grafik pemasukan berdasarkan periode
const pemasukanData = {
  monthly: {
    categories: [
      "Jan 2025",
      "Feb 2025",
      "Mar 2025",
      "Apr 2025",
      "Mei 2025",
      "Jun 2025",
      "Jul 2025",
      "Agu 2025",
      "Sep 2025",
      "Okt 2025",
      "Nov 2025",
      "Des 2025",
    ],
    data: [100, 120, 150, 130, 140, 160, 170, 180, 190, 200, 210, 220],
  },
  yearly: {
    categories: ["2020", "2021", "2022", "2023", "2024", "2025"],
    data: [1000, 1200, 1500, 1800, 2000, 2200],
  },
};

// Data untuk grafik anggaran dan belanja
const anggaranBelanjaData = {
  monthly: {
    categories: [
      "Jan 2025",
      "Feb 2025",
      "Mar 2025",
      "Apr 2025",
      "Mei 2025",
      "Jun 2025",
      "Jul 2025",
      "Agu 2025",
      "Sep 2025",
      "Okt 2025",
      "Nov 2025",
      "Des 2025",
    ],
    anggaran: [200, 220, 250, 230, 240, 260, 270, 280, 290, 300, 310, 320],
    belanja: [180, 200, 230, 210, 220, 240, 250, 260, 270, 280, 290, 300],
  },
  yearly: {
    categories: ["2020", "2021", "2022", "2023", "2024", "2025"],
    anggaran: [2000, 2200, 2500, 2800, 3000, 3200],
    belanja: [1800, 2000, 2300, 2600, 2800, 3000],
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
      data: pemasukanData.monthly.data,
    },
  ],
  colors: "#435ebe",
  xaxis: {
    categories: pemasukanData.monthly.categories,
  },
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
      data: anggaranBelanjaData.monthly.anggaran,
    },
    {
      name: "Belanja",
      data: anggaranBelanjaData.monthly.belanja,
    },
  ],
  colors: ["#435ebe", "#55c6e8"],
  xaxis: {
    categories: anggaranBelanjaData.monthly.categories,
  },
  tooltip: {
    shared: true,
    intersect: false,
  },
  legend: {
    position: "bottom",
  },
};

// let optionsVisitorsProfile = {
//   series: [70, 30],
//   labels: ["Male", "Female"],
//   colors: ["#435ebe", "#55c6e8"],
//   chart: {
//     type: "donut",
//     width: "100%",
//     height: "350px",
//   },
//   legend: {
//     position: "bottom",
//   },
//   plotOptions: {
//     pie: {
//       donut: {
//         size: "30%",
//       },
//     },
//   },
// };

var optionsEurope = {
  series: [
    {
      name: "series1",
      data: [310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605],
    },
  ],
  chart: {
    height: 80,
    type: "area",
    toolbar: {
      show: false,
    },
  },
  colors: ["#5350e9"],
  stroke: {
    width: 2,
  },
  grid: {
    show: false,
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    type: "datetime",
    categories: [
      "2018-09-19T00:00:00.000Z",
      "2018-09-19T01:30:00.000Z",
      "2018-09-19T02:30:00.000Z",
      "2018-09-19T03:30:00.000Z",
      "2018-09-19T04:30:00.000Z",
      "2018-09-19T05:30:00.000Z",
      "2018-09-19T06:30:00.000Z",
      "2018-09-19T07:30:00.000Z",
      "2018-09-19T08:30:00.000Z",
      "2018-09-19T09:30:00.000Z",
      "2018-09-19T10:30:00.000Z",
      "2018-09-19T11:30:00.000Z",
    ],
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: false,
    },
  },
  show: false,
  yaxis: {
    labels: {
      show: false,
    },
  },
  tooltip: {
    x: {
      format: "dd/MM/yy HH:mm",
    },
  },
};

let optionsAmerica = {
  ...optionsEurope,
  colors: ["#008b75"],
};
let optionsIndonesia = {
  ...optionsEurope,
  colors: ["#dc3545"],
};

var chartProfileVisit = new ApexCharts(
  document.querySelector("#chart-profile-visit"),
  optionsProfileVisit
);
// var chartVisitorsProfile = new ApexCharts(
//   document.getElementById("chart-visitors-profile"),
//   optionsVisitorsProfile
// );
var chartPemasukan = new ApexCharts(
  document.getElementById("chart-pemasukan"),
  optionsPemasukan
);
var chartAnggaranBelanja = new ApexCharts(
  document.getElementById("chart-anggaran-belanja"),
  optionsAnggaranBelanja
);

// Fungsi untuk memperbarui grafik pemasukan berdasarkan filter
function updatePemasukanChart(period) {
  optionsPemasukan.series[0].data = pemasukanData[period].data;
  optionsPemasukan.xaxis.categories = pemasukanData[period].categories;
  chartPemasukan.updateOptions(optionsPemasukan);
}

// Fungsi untuk memperbarui grafik anggaran dan belanja berdasarkan filter
function updateAnggaranBelanjaChart(period) {
  optionsAnggaranBelanja.series[0].data = anggaranBelanjaData[period].anggaran;
  optionsAnggaranBelanja.series[1].data = anggaranBelanjaData[period].belanja;
  optionsAnggaranBelanja.xaxis.categories = anggaranBelanjaData[period].categories;
  chartAnggaranBelanja.updateOptions(optionsAnggaranBelanja);
}

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

// Render grafik
chartProfileVisit.render();
chartPemasukan.render();
// chartVisitorsProfile.render();
chartAnggaranBelanja.render();