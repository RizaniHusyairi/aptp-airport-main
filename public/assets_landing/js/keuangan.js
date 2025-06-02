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