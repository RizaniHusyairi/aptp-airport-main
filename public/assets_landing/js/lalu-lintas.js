const trafficData = {
  2023: {
    aircraft: [120, 130, 125, 140, 135, 150, 145, 160, 155, 170, 165, 180],
    passengers: [15000, 16000, 15500, 17000, 16500, 18000, 17500, 19000, 18500, 20000, 19500, 21000],
    transit: [2000, 2100, 2050, 2200, 2150, 2300, 2250, 2400, 2350, 2500, 2450, 2600],
    cargo: [500, 520, 510, 530, 525, 540, 535, 550, 545, 560, 555, 570],
    baggage: [3000, 3100, 3050, 3200, 3150, 3300, 3250, 3400, 3350, 3500, 3450, 3600],
    mail: [50, 55, 52, 58, 56, 60, 59, 62, 61, 65, 64, 68]
  },
  2024: {
    aircraft: [130, 140, 135, 150, 145, 160, 155, 170, 165, 180, 175, 190],
    passengers: [16000, 17000, 16500, 18000, 17500, 19000, 18500, 20000, 19500, 21000, 20500, 22000],
    transit: [2100, 2200, 2150, 2300, 2250, 2400, 2350, 2500, 2450, 2600, 2550, 2700],
    cargo: [520, 540, 530, 550, 545, 560, 555, 570, 565, 580, 575, 590],
    baggage: [3100, 3200, 3150, 3300, 3250, 3400, 3350, 3500, 3450, 3600, 3550, 3700],
    mail: [55, 60, 58, 62, 60, 65, 63, 68, 66, 70, 69, 72]
  },
  2025: {
    aircraft: [140, 150, 145, 160, 155, 170, 165, 180, 175, 190, 185, 200],
    passengers: [17000, 18000, 17500, 19000, 18500, 20000, 19500, 21000, 20500, 22000, 21500, 23000],
    transit: [2200, 2300, 2250, 2400, 2350, 2500, 2450, 2600, 2550, 2700, 2650, 2800],
    cargo: [540, 560, 550, 570, 565, 580, 575, 590, 585, 600, 595, 610],
    baggage: [3200, 3300, 3250, 3400, 3350, 3500, 3450, 3600, 3550, 3700, 3650, 3800],
    mail: [60, 65, 63, 68, 66, 70, 69, 72, 71, 75, 74, 78]
  }
};

const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
const years = Object.keys(trafficData);
const categories = ["Pesawat", "Penumpang", "Penumpang Transit", "Kargo", "Bagasi", "Pos"];

// Referensi elemen DOM
const yearFilter = document.getElementById('yearFilter');
const monthFilterContainer = document.getElementById('monthFilterContainer');
const monthFilter = document.getElementById('monthFilter');
const lineChartContainer = document.getElementById('lineChartContainer');
const barChartContainer = document.getElementById('barChartContainer');

// Fungsi untuk menentukan grafik mana yang ditampilkan
function toggleChartVisibility(isBarChart) {
  if (isBarChart) {
    lineChartContainer.classList.add('hidden');
    barChartContainer.classList.remove('hidden');
  } else {
    lineChartContainer.classList.remove('hidden');
    barChartContainer.classList.add('hidden');
  }
}

// Fungsi untuk memperbarui grafik berdasarkan filter
function updateCharts() {
  const year = yearFilter.value;
  const month = monthFilter.value;

  // Validasi: Pastikan year ada di trafficData
  if (year !== 'all' && (!trafficData[year] || !Object.keys(trafficData[year]).length)) {
    console.warn(`Data untuk tahun ${year} tidak tersedia.`);
    toggleChartVisibility(false);
    updateLineChart(['Tidak ada data'], trafficData, year, month, years, months, categories);
    return;
  }

  const isBarChart = year !== 'all' && month !== 'all';
  toggleChartVisibility(isBarChart);

  if (isBarChart) {
    updateBarChart(trafficData, year, month, categories);
  } else {
    updateLineChart(categories, trafficData, year, month, years, months, categories);
  }
}

// Event listener untuk filter
yearFilter.addEventListener('change', () => {
  if (yearFilter.value === 'all') {
    monthFilterContainer.classList.add('hidden');
    monthFilter.value = 'all';
  } else {
    monthFilterContainer.classList.remove('hidden');
  }
  updateCharts();
});

monthFilter.addEventListener('change', updateCharts);

// Lazy load grafik
const observer = new IntersectionObserver((entries) => {
  if (entries[0].isIntersecting) {
    updateCharts();
    observer.disconnect();
  }
});
observer.observe(document.querySelector('.chart-container'));