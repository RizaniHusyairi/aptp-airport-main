async function fetchFinancialData(year = 'all', month = 'all') {
  try {
    const url = `${document.getElementById('incomeChart').dataset.url}?year=${year}&month=${month}`;
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    if (data.error) {
      throw new Error(data.error);
    }
    return data;
  } catch (error) {
    console.error('Error fetching financial data:', error);
    alert('Gagal mengambil data keuangan. Silakan coba lagi nanti.');
    return { labels: [], income: [], budget: [], expense: [] };
  }
}

const incomeChartCtx = document.getElementById('incomeChart').getContext('2d');
const budgetVsExpenseChartCtx = document.getElementById('budgetVsExpenseChart').getContext('2d');

let incomeChart = new Chart(incomeChartCtx, {
  type: 'bar',
  data: {
    labels: [],
    datasets: [{
      label: 'Pemasukan (Juta Rp)',
      data: [],
      backgroundColor: 'rgba(217, 158, 78, 0.6)',
      borderColor: 'rgba(217, 158, 78, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Juta Rupiah'
        }
      },
      x: {
        title: {
          display: true,
          text: 'Tahun'
        }
      }
    },
    plugins: {
      legend: {
        position: 'top'
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            return `${context.dataset.label}: ${context.parsed.y} Juta Rp`;
          }
        }
      }
    }
  }
});

let budgetVsExpenseChart = new Chart(budgetVsExpenseChartCtx, {
  type: 'line',
  data: {
    labels: [],
    datasets: [
      {
        label: 'Anggaran (Juta Rp)',
        data: [],
        borderColor: 'rgba(217, 158, 78, 1)',
        backgroundColor: 'rgba(217, 158, 78, 0.2)',
        fill: true,
        tension: 0.4
      },
      {
        label: 'Pengeluaran (Juta Rp)',
        data: [],
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        fill: true,
        tension: 0.4
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Juta Rupiah'
        }
      },
      x: {
        title: {
          display: true,
          text: 'Tahun'
        }
      }
    },
    plugins: {
      legend: {
        position: 'top'
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            return `${context.dataset.label}: ${context.parsed.y} Juta Rp`;
          }
        }
      }
    }
  }
});

async function updateCharts() {
  const year = document.getElementById('yearFilter').value;
  const month = document.getElementById('monthFilter').value;

  const data = await fetchFinancialData(year, month);

  let labels = data.labels;
  let incomeData = data.income;
  let budgetData = data.budget;
  let expenseData = data.expense;

  incomeChart.data.labels = labels;
  incomeChart.data.datasets[0].data = incomeData;
  incomeChart.options.scales.x.title.text = (year === 'all' || month === 'all') ? 'Tahun' : 'Bulan';
  incomeChart.update();

  budgetVsExpenseChart.data.labels = labels;
  budgetVsExpenseChart.data.datasets[0].data = budgetData;
  budgetVsExpenseChart.data.datasets[1].data = expenseData;
  budgetVsExpenseChart.options.scales.x.title.text = (year === 'all' || month === 'all') ? 'Tahun' : 'Bulan';
  budgetVsExpenseChart.update();
}

const yearFilter = document.getElementById('yearFilter');
const monthFilterContainer = document.getElementById('monthFilterContainer');
yearFilter.addEventListener('change', () => {
  if (yearFilter.value === 'all') {
    monthFilterContainer.classList.add('hidden');
    document.getElementById('monthFilter').value = 'all';
  } else {
    monthFilterContainer.classList.remove('hidden');
  }
  updateCharts();
});

document.getElementById('monthFilter').addEventListener('change', updateCharts);

updateCharts();

// ==========================================================
    // BAGIAN 2: LOGIKA UNTUK GRAFIK STATIS (BAWAH)
    // ==========================================================
    const staticChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.raw);
                    }
                }
            }
        }
    };

    // Chart Jenis Program (Pie)
    const programCtx = document.getElementById('programChart');
    if (programCtx) {
        new Chart(programCtx, {
            type: 'pie',
            data: {
                labels: ['Infrastruktur Konektivitas', 'Dukungan Manajemen'],
                datasets: [{ data: [55702543000, 54526033000], backgroundColor: ['#36a2eb', '#ffcd56'] }]
            },
            options: { ...staticChartOptions, plugins: { ...staticChartOptions.plugins, datalabels: {
                formatter: (value, ctx) => {
                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    return (value * 100 / sum).toFixed(2) + '%';
                },
                color: '#fff', font: { weight: 'bold' }
            }}}
        });
    }

    // Chart Jenis Belanja (Bar)
    const belanjaCtx = document.getElementById('belanjaChart');
    if (belanjaCtx) {
        new Chart(belanjaCtx, {
            type: 'bar',
            data: {
                labels: ['Pegawai', 'Barang', 'Modal'],
                datasets: [{ data: [9947052000, 76804764000, 23476760000], backgroundColor: ['#36a2eb', '#ff6384', '#4bc0c0'] }]
            },
            options: { ...staticChartOptions, scales: { y: { ticks: { callback: value => `${value / 1e9} M` } } } }
        });
    }

    // Chart Jenis Kegiatan (Bar Horizontal)
    const kegiatanCtx = document.getElementById('kegiatanChart');
    if (kegiatanCtx) {
        new Chart(kegiatanCtx, {
            type: 'bar',
            data: {
                labels: ['Pelayanan', 'Infrastruktur', 'Keselamatan', 'Penunjang', 'Keuangan & SDM', 'Perencanaan'],
                datasets: [{ data: [24310000000, 22000000000, 1300000000, 8000000000, 53987000000, 631576000], backgroundColor: ['#36a2eb', '#ff6384', '#4bc0c0', '#ffcd56', '#c9cbcf', '#9966ff'] }]
            },
            options: { ...staticChartOptions, indexAxis: 'y', scales: { x: { ticks: { callback: value => `${value / 1e9} M` } } } }
        });
    }

    // Chart Sumber Dana (Pie)
    const sumberDanaCtx = document.getElementById('sumberDanaChart');
    if (sumberDanaCtx) {
        new Chart(sumberDanaCtx, {
            type: 'pie',
            data: {
                labels: ['Rupiah Murni', 'PNBP BLU'],
                datasets: [{ data: [69804875000, 40423701000], backgroundColor: ['#4bc0c0', '#ff9f40'] }]
            },
            options: { ...staticChartOptions, plugins: { ...staticChartOptions.plugins, datalabels: {
                formatter: (value, ctx) => {
                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    return (value * 100 / sum).toFixed(2) + '%';
                },
                color: '#fff', font: { weight: 'bold' }
            }}}
        });
    }