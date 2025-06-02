$(document).ready(function() {
      // Inisialisasi DataTables
      $('#arrivalTable').DataTable({
        info:false,
        ordering:false,
        paging:false,
        scrollX:true,
        data: [
          ['JT123', 'Lion Air', 'Soekarno-Hatta (Jakarta)', '2025-05-23 08:30',"A3", 'Arrived'],
          ['GA456', 'Garuda Indonesia', 'Ngurah Rai (Denpasar)', '2025-05-23 10:15',"A1", 'Arrived'],
          ['IW789', 'Wings Air', 'Sultan Aji Muhammad Sulaiman (Balikpapan)', '2025-05-23 12:00',"B1", 'Estimate'],
          ['QG321', 'Citilink', 'Juanda (Surabaya)', '2025-05-23 14:45', "B2",'Estimate'],
          ['SJ654', 'Sriwijaya Air', 'Adisutjipto (Yogyakarta)', '2025-05-23 16:30',"B1", 'Estimate']
        ],
        columns: [
          { title: 'Kode Penerbangan' },
          { title: 'Maskapai' },
          { title: 'Tujuan Bandara (Kota)' },
          { title: 'Waktu Kedatangan' },
          { title: 'Gate' },
          { title: 'Status' }
        ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
      });
    });