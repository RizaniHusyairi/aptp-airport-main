let barChart = null;

// Fungsi untuk menginisialisasi atau memperbarui grafik bar
function updateBarChart(trafficData, year, month, categories) {
    const ctx = document.getElementById('barChart').getContext('2d');
    const monthIndex = parseInt(month) - 1;

    // Validasi monthIndex
    if (isNaN(monthIndex) || monthIndex < 0 || monthIndex >= 12) {
        console.warn(`Indeks bulan ${month} tidak valid.`);
        return;
    }

    // === PERBAIKAN LOGIKA DIMULAI DI SINI ===

    // 1. Definisikan kunci dan warna untuk setiap kategori
    const categoryConfig = [
        { key: 'aircraft', label: 'Pesawat', color: 'rgba(217, 158, 78, 0.6)' },
        { key: 'passengers', label: 'Penumpang', color: 'rgba(54, 162, 235, 0.6)' },
        { key: 'transit', label: 'Penumpang Transit', color: 'rgba(75, 192, 192, 0.6)' },
        { key: 'cargo', label: 'Kargo (Ton)', color: 'rgba(255, 99, 132, 0.6)' },
        { key: 'baggage', label: 'Bagasi (Ton)', color: 'rgba(153, 102, 255, 0.6)' },
        { key: 'mail', label: 'Pos (Ton)', color: 'rgba(255, 205, 86, 0.6)' }
    ];

    // 2. Buat satu array data untuk bulan yang dipilih
    const dataForThisMonth = categoryConfig.map(config => {
        const yearData = trafficData[year]?.[config.key];
        if (!yearData || !Array.isArray(yearData) || yearData.length <= monthIndex) {
            console.warn(`Data untuk ${config.key} pada tahun ${year} dan bulan ${month} tidak tersedia.`);
            return 0;
        }
        return yearData[monthIndex] || 0;
    });

    // 3. Buat satu dataset, bukan enam
    const datasets = [{
        label: `Data LLAU untuk ${months[monthIndex]} ${year}`,
        data: dataForThisMonth,
        // Ambil array warna dari config
        backgroundColor: categoryConfig.map(config => config.color),
        borderWidth: 1
    }];

    // 4. Pastikan label sudah benar (sesuai dengan 'categories' dari lalu-lintas.js)
    const labels = categories;

    // === AKHIR PERBAIKAN LOGIKA ===

    // Jika grafik sudah ada, hancurkan dulu
    if (barChart) {
        barChart.destroy();
    }

    // Buat grafik bar baru
    barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels, // Harusnya: ["Pesawat", "Penumpang", ...]
            datasets: datasets // Hanya satu dataset
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
                    // Sembunyikan legenda karena kita sudah punya label di sumbu-x
                    display: false 
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label;
                            const value = context.parsed.y;
                            if (label === 'Pesawat') return `${label}: ${value.toLocaleString('id-ID')} penerbangan`;
                            if (label === 'Penumpang' || label === 'Penumpang Transit') return `${label}: ${value.toLocaleString('id-ID')} orang`;
                            // Gunakan format standar untuk sisanya
                            return `${label}: ${value.toLocaleString('id-ID')}`;
                        }
                    }
                }
            }
        }
    });
}