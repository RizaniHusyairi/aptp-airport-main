async function fetchLetters(type) {
    try {
        const response = await fetch(`/regulasi/surat-${type}/api`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Data dari API:', data); // Tambahkan log untuk debugging
        const { DateTime } = luxon;
        return data.map(item => {
            const isoDate = item.issue_date;
            const formattedDate = DateTime.fromISO(isoDate).toFormat("dd MMMM yyyy");
            return {
                nomor_surat: item.number,
                judul: item.title,
                tanggal_terbit: formattedDate,
                unduh: item.file_path
            };
        });
    } catch (error) {
        console.error(`Error fetching ${type} letters:`, error);
        alert(`Gagal mengambil data surat ${type}. Silakan coba lagi nanti.`);
        return [];
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const pathSegments = window.location.pathname.split('/');
    const typeSegment = pathSegments[pathSegments.length - 1];
    const type = typeSegment === 'surat-utusan' ? 'keputusan' : typeSegment === 'surat-edaran' ? 'edaran' : null;

    if (!type || !['keputusan', 'edaran'].includes(type)) {
        alert('Halaman tidak valid. Silakan akses halaman surat keputusan atau surat edaran.');
        return;
    }

    const tableId = `#surat${type.charAt(0).toUpperCase() + type.slice(1)}Table`;
    const table = new Tabulator(tableId, {
        data: [],
        layout: "fitColumns",
        responsiveLayout: "collapse", // Ubah ke collapse untuk responsivitas
        columns: [
            { 
                title: "Nomor Surat", 
                field: "nomor_surat", 
                headerSort: true, 
                width: 150, 
                headerFilter: "input", // Tambahkan filter pencarian
                headerFilterPlaceholder: "Cari nomor..." 
            },
            { 
                title: "Judul", 
                field: "judul", 
                headerSort: true, 
                minWidth: 200, 
                headerFilter: "input", // Tambahkan filter pencarian
                headerFilterPlaceholder: "Cari judul..." 
            },
            { 
                title: "Tanggal Terbit", 
                field: "tanggal_terbit", 
                headerSort: true, 
                width: 150 
            },
            { 
                title: "Unduh", 
                field: "unduh", 
                formatter: function(cell) {
                    return `<a href="${cell.getValue()}" class="download-btn" target="_blank">Unduh</a>`;
                }, 
                headerSort: false, 
                width: 100, 
                hozAlign: "center" 
            }
        ],
        pagination: "local",
        paginationSize: 6,
        paginationSizeSelector: [3, 6, 8, 10],
        movableColumns: true,
        resizableColumns: true,
        tooltips: true,
        rowClick: function(e, row) {
            row.toggleSelect();
        }
    });

    fetchLetters(type).then(data => {
        if (Array.isArray(data)) {
            table.setData(data);
        } else {
            console.error('Data received is not an array:', data);
        }
    }).catch(error => {
        console.error('Error setting table data:', error);
    });

    window.addEventListener("resize", () => {
        table.redraw(true);
    });
});