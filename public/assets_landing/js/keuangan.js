document.addEventListener('DOMContentLoaded', function() {
    const incomeChartCanvas = document.getElementById('incomeChart');
    const budgetVsExpenseChartCanvas = document.getElementById('budgetVsExpenseChart');
    const sumberDanaChartCanvas = document.getElementById('sumberDanaChart');

    if (!incomeChartCanvas || !budgetVsExpenseChartCanvas || !sumberDanaChartCanvas) {
        console.error('Satu atau lebih elemen canvas tidak ditemukan.');
        return;
    }

    async function fetchFinancialData(year = 'all') { // Hapus parameter 'month'
        try {
            const baseUrl = incomeChartCanvas.dataset.url;
            if (!baseUrl) throw new Error('Atribut data-url tidak ditemukan.');
            
            const url = `${baseUrl}?year=${year}`; // URL disederhanakan
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            
            const data = await response.json();
            if (data.error) throw new Error(data.error);

            return data;
        } catch (error) {
            console.error('Error fetching financial data:', error);
            incomeChartCanvas.parentElement.innerHTML = '<p class="text-center text-danger">Gagal memuat data grafik.</p>';
            return null;
        }
    }

    // Inisialisasi Chart.js
    const incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Pemasukan (Miliar Rp)', // <<< GANTI SATUAN
                data: [],
                backgroundColor: 'rgba(240, 165, 0, 0.6)',
                borderColor: 'rgba(240, 165, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, title: { display: true, text: 'Miliar Rupiah' } }, x: { title: { display: true, text: 'Periode' } } }, // <<< GANTI SATUAN
            plugins: { legend: { position: 'top' } }
        }
    });

    const budgetVsExpenseChart = new Chart(budgetVsExpenseChartCanvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Anggaran (Miliar Rp)', // <<< GANTI SATUAN
                    data: [],
                    borderColor: 'rgba(240, 165, 0, 1)',
                    backgroundColor: 'rgba(240, 165, 0, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Belanja (Miliar Rp)', // <<< GANTI SATUAN
                    data: [],
                    borderColor: 'rgba(13, 44, 74, 1)',
                    backgroundColor: 'rgba(13, 44, 74, 0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, title: { display: true, text: 'Miliar Rupiah' } }, x: { title: { display: true, text: 'Periode' } } }, // <<< GANTI SATUAN
            plugins: { legend: { position: 'top' } }
        }
    });

    const sumberDanaChart = new Chart(sumberDanaChartCanvas.getContext('2d'), {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{ data: [], backgroundColor: ['#0d2c4a', '#f0a500', '#ff6384', '#36a2eb'], borderWidth: 2 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.raw)}`;
                        }
                    }
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        if (sum === 0) return '0%';
                        return ((value * 100) / sum).toFixed(1) + '%';
                    },
                    color: '#fff',
                    font: { weight: 'bold' }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    async function updateCharts() {
        const year = document.getElementById('yearFilter').value;
        const data = await fetchFinancialData(year); // Panggil API hanya dengan tahun

        if (!data) return;

        const periodLabel = (year === 'all') ? 'Tahun' : 'Bulan';

        // Update Grafik 1 & 2
        incomeChart.data.labels = data.labels;
        incomeChart.data.datasets[0].data = data.income;
        incomeChart.options.scales.x.title.text = periodLabel;
        incomeChart.update();

        budgetVsExpenseChart.data.labels = data.labels;
        budgetVsExpenseChart.data.datasets[0].data = data.budget;
        budgetVsExpenseChart.data.datasets[1].data = data.expense;
        budgetVsExpenseChart.options.scales.x.title.text = periodLabel;
        budgetVsExpenseChart.update();

        // Update Grafik 3 & Tabel Sumber Dana
        if (data.sourceData) {
            sumberDanaChart.data.labels = data.sourceData.labels;
            sumberDanaChart.data.datasets[0].data = data.sourceData.values;
            sumberDanaChart.update();

            const tableBody = document.getElementById('sumberDanaTableBody');
            tableBody.innerHTML = '';
            const totalSource = data.sourceData.values.reduce((sum, value) => sum + value, 0);

            if (totalSource > 0) {
                // ... (logika update tabel tidak berubah)
            } else {
                tableBody.innerHTML = `<tr><td colspan="3" class="text-center text-muted">Data tidak tersedia.</td></tr>`;
            }
        }
    }

    // Event listener hanya untuk filter tahun
    document.getElementById('yearFilter').addEventListener('change', updateCharts);
    
    // Muat data awal
    updateCharts();
});

