let barChart = null;

// Fungsi untuk menginisialisasi atau memperbarui grafik bar
function updateBarChart(trafficData, year, month, categories) {
    const ctx = document.getElementById('barChart').getContext('2d');
    const monthIndex = parseInt(month) - 1;

    // Validasi monthIndex
    if (isNaN(monthIndex) || monthIndex < 0 || monthIndex >= 12) {
        console.warn(`Indeks bulan ${month} tidak valid. Menggunakan nilai default.`);
        monthIndex = 0;
    }

    let datasets = [
        { key: 'aircraft', label: 'Pesawat', borderColor: 'rgba(217, 158, 78, 1)', backgroundColor: 'rgba(217, 158, 78, 0.6)' },
        { key: 'passengers', label: 'Penumpang', borderColor: 'rgba(54, 162, 235, 1)', backgroundColor: 'rgba(54, 162, 235, 0.6)' },
        { key: 'transit', label: 'Penumpang Transit', borderColor: 'rgba(75, 192, 192, 1)', backgroundColor: 'rgba(75, 192, 192, 0.6)' },
        { key: 'cargo', label: 'Kargo (Ton)', borderColor: 'rgba(255, 99, 132, 1)', backgroundColor: 'rgba(255, 99, 132, 0.6)' },
        { key: 'baggage', label: 'Bagasi (Ton)', borderColor: 'rgba(153, 102, 255, 1)', backgroundColor: 'rgba(153, 102, 255, 0.6)' },
        { key: 'mail', label: 'Pos (Ton)', borderColor: 'rgba(255, 205, 86, 1)', backgroundColor: 'rgba(255, 205, 86, 0.6)' }
    ];

    datasets = datasets.map((dataset, index) => {
        const dataArray = Array(categories.length).fill(0);
        const yearData = trafficData[year]?.[dataset.key];
        if (!yearData || !Array.isArray(yearData) || yearData.length <= monthIndex) {
            console.warn(`Data untuk ${dataset.key} pada tahun ${year} dan bulan ${month} tidak tersedia.`);
            dataArray[index] = 0;
        } else {
            dataArray[index] = yearData[monthIndex] || 0;
        }

        return {
            label: dataset.label,
            data: dataArray,
            borderColor: dataset.borderColor,
            backgroundColor: dataset.backgroundColor,
            fill: true,
            borderWidth: 1,
            barPercentage: 10,
            categoryPercentage:  0.1
        };
    });

    // Jika grafik sudah ada, hancurkan dulu
    if (barChart) {
        barChart.destroy();
    }

    // Buat grafik bar baru
    barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categories,
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
                        text: 'Kategori'
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