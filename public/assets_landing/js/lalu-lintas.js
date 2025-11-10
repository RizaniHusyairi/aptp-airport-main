const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];


// === PERBAIKAN DI SINI ===
// Daftar ini HARUS cocok dengan 'categoryConfig' di bar-chart.js dan 'datasets' di line-chart.js
const categories = ["Pesawat", "Penumpang", "Penumpang Transit", "Kargo", "Bagasi", "Pos"];
// === AKHIR PERBAIKAN ===

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

// Fungsi untuk mengambil data dari API
async function fetchTrafficData(year, month) {
    try {
        const url = new URL('/api/air-freight-traffic', window.location.origin);
        if (year !== 'all') url.searchParams.append('year', year);
        if (month !== 'all') url.searchParams.append('month', month);

        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        if (!result.success) {
            throw new Error(result.message || 'Gagal mengambil data.');
        }
        console.log('Fetched traffic data:', result);
        return result;
    } catch (error) {
        console.error('Error fetching traffic data:', error);
        return { success: false, data: {}, years: [] };
    }
}

// Fungsi untuk memperbarui filter tahun
function updateYearFilter(years) {
    yearFilter.innerHTML = '<option value="all">Semua Tahun</option>';
    let currentYearSelected = false;
    const currentYear = new Date().getFullYear().toString();

    years.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year.toString() === currentYear) {
            option.selected = true;
            currentYearSelected = true;
        }
        yearFilter.appendChild(option);
    });

    // Jika tahun saat ini tidak ada di data, pilih tahun terbaru
    if (!currentYearSelected && years.length > 0) {
        yearFilter.value = years[0];
    }
}

// Fungsi untuk memperbarui grafik berdasarkan filter
async function updateCharts() {
    const year = yearFilter.value;
    const month = monthFilter.value;

    // Ambil data dari API
    const { success, data, years } = await fetchTrafficData(year, month);

    // Perbarui filter tahun jika belum diisi (hanya sekali)
    if (yearFilter.options.length <= 1 && years.length > 0) {
        updateYearFilter(years);
        // Panggil updateCharts lagi jika tahun berubah (misalnya, dari 'all' ke tahun terbaru)
        if (yearFilter.value !== year) {
            updateCharts();
            return;
        }
    }

    if (!success || Object.keys(data).length === 0) {
        console.warn('Tidak ada data tersedia.');
        toggleChartVisibility(false); // Tampilkan line chart
        // Kirim data kosong ke line chart agar bersih
        updateLineChart([], {}, year, month, years, months, categories); 
        return;
    }


    const isBarChart = year !== 'all' && month !== 'all';
    toggleChartVisibility(isBarChart);

    if (isBarChart) {
        // 'data[year]' berisi objek { aircraft: [...], passengers: [...] }
        updateBarChart(data, year, month, categories);
    } else {
        // 'data' berisi objek { 2024: {...}, 2025: {...} }
        updateLineChart(categories, data, year, month, years, months, categories);
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
}, { threshold: 0.1 }); // Trigger saat 10% elemen terlihat

const chartContainer = document.querySelector('.chart-container');
if (chartContainer) {
    observer.observe(chartContainer);
}