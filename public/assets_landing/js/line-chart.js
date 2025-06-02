let lineChart = null;

// Fungsi untuk menginisialisasi atau memperbarui grafik garis
function updateLineChart(labels, trafficData, year, month, years, months, categories) {
  const ctx = document.getElementById('lineChart').getContext('2d');
  let datasets = [
    { key: 'aircraft', label: 'Pesawat', borderColor: 'rgba(217, 158, 78, 1)', backgroundColor: 'rgba(217, 158, 78, 0.2)' },
    { key: 'passengers', label: 'Penumpang', borderColor: 'rgba(54, 162, 235, 1)', backgroundColor: 'rgba(54, 162, 235, 0.2)' },
    { key: 'transit', label: 'Penumpang Transit', borderColor: 'rgba(75, 192, 192, 1)', backgroundColor: 'rgba(75, 192, 192, 0.2)' },
    { key: 'cargo', label: 'Kargo (Ton)', borderColor: 'rgba(255, 99, 132, 1)', backgroundColor: 'rgba(255, 99, 132, 0.2)' },
    { key: 'baggage', label: 'Bagasi (Ton)', borderColor: 'rgba(153, 102, 255, 1)', backgroundColor: 'rgba(153, 102, 255, 0.2)' },
    { key: 'mail', label: 'Pos (Ton)', borderColor: 'rgba(255, 205, 86, 1)', backgroundColor: 'rgba(255, 205, 86, 0.2)' }
  ];

  if (year === 'all') {
    labels = years;
    datasets = datasets.map(dataset => ({
      label: dataset.label,
      data: years.map(y => {
        const yearData = trafficData[y]?.[dataset.key];
        return yearData ? yearData.reduce((sum, val) => sum + val, 0) : 0;
      }),
      borderColor: dataset.borderColor,
      backgroundColor: dataset.backgroundColor,
      fill: false,
      tension: 0.4,
      borderWidth: 2
    }));
  } else {
    labels = months;
    datasets = datasets.map(dataset => {
      const yearData = trafficData[year]?.[dataset.key];
      if (!yearData || !Array.isArray(yearData)) {
        return {
          label: dataset.label,
          data: Array(12).fill(0),
          borderColor: dataset.borderColor,
          backgroundColor: dataset.backgroundColor,
          fill: false,
          tension: 0.4,
          borderWidth: 2
        };
      }
      return {
        label: dataset.label,
        data: yearData,
        borderColor: dataset.borderColor,
        backgroundColor: dataset.backgroundColor,
        fill: false,
        tension: 0.4,
        borderWidth: 2
      };
    });
  }

  // Jika grafik sudah ada, hancurkan dulu
  if (lineChart) {
    lineChart.destroy();
  }

  // Buat grafik garis baru
  lineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 1000,
        easing: 'easeInOutQuad'
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Jumlah'
          },
          ticks: {
            callback: function(value) {
              return value.toLocaleString('id-ID');
            }
          }
        },
        x: {
          title: {
            display: true,
            text: year === 'all' ? 'Tahun' : 'Bulan'
          },
          ticks: {
            padding: window.innerWidth < 768 ? 5 : 10,
            font: {
              size: window.innerWidth < 768 ? 10 : 12
            }
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 20,
            padding: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.dataset.label;
              const value = context.parsed.y;
              if (label === 'Pesawat') return `${label}: ${value.toLocaleString('id-ID')} penerbangan`;
              if (label === 'Penumpang' || label === 'Penumpang Transit') return `${label}: ${value.toLocaleString('id-ID')} orang`;
              return `${label}: ${value.toLocaleString('id-ID')} ton`;
            }
          }
        }
      }
    }
  });
}