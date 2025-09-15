document.addEventListener('DOMContentLoaded', function() {
    const flowTypeSelect = document.getElementById('flow_type');
    const sourceContainer = document.getElementById('source-container');
    const detailPengeluaranContainer = document.getElementById('detail-pengeluaran-container');
    const form = document.getElementById('form-laporan-keuangan');

    function toggleSections() {
        const selectedValue = flowTypeSelect.value;
        const expenseInputs = detailPengeluaranContainer.querySelectorAll('input');

        // Tampilkan 'Sumber Dana' jika 'Pemasukan' atau 'Anggaran' dipilih
        if (selectedValue === 'in' || selectedValue === 'budget') {
            sourceContainer.style.display = 'block';
        } else {
            sourceContainer.style.display = 'none';
        }

        // Tampilkan 'Detail Pengeluaran' hanya jika 'Anggaran' dipilih
        if (selectedValue === 'budget') {
            detailPengeluaranContainer.style.display = 'block';
            // === PERBAIKAN DI SINI: Tambahkan 'required' ke input yang terlihat ===
            expenseInputs.forEach(input => input.setAttribute('required', 'required'));
        } else {
            detailPengeluaranContainer.style.display = 'none';
            // === PERBAIKAN DI SINI: Hapus 'required' dari input yang tersembunyi ===
            expenseInputs.forEach(input => input.removeAttribute('required'));
        }
    }

    flowTypeSelect.addEventListener('change', toggleSections);
    
    // Inisialisasi format Rupiah dan kalkulasi
    initializeExpenseTable();
    new Cleave(document.querySelector('#amount'), {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });
});

function initializeExpenseTable() {
    const tableBody = document.getElementById('budget-expenses-table')?.querySelector('tbody');
    const btnTambahBaris = document.getElementById('tambah-baris');
    
    if (!tableBody) return;

    function addRow() {
        const newIndex = tableBody.rows.length;
        const newRow = tableBody.insertRow();
        // === PERBAIKAN DI SINI: Tambahkan 'required' langsung saat membuat baris ===
        newRow.innerHTML = `
            <td><input type="text" name="budget_expenses[${newIndex}][description]" class="form-control" required></td>
            <td><input type="text" name="budget_expenses[${newIndex}][amount]" class="form-control jumlah-rupiah" required></td>
            <td><button type="button" class="btn btn-sm btn-danger btn-hapus-baris"><i class="bi bi-trash"></i></button></td>
        `;
        // Terapkan Cleave.js pada input jumlah yang baru
        new Cleave(newRow.querySelector('.jumlah-rupiah'), {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    }
    
    // Terapkan Cleave.js pada input jumlah yang sudah ada
    document.querySelectorAll('#budget-expenses-table .jumlah-rupiah').forEach(input => {
        new Cleave(input, {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });

    btnTambahBaris.addEventListener('click', addRow);

    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-baris')) {
            e.target.closest('tr').remove();
            calculateTotal();
        }
    });

    // Kalkulasi total
    const totalPengeluaranSpan = document.getElementById('total-pengeluaran');
    const errorPengeluaranDiv = document.getElementById('error-pengeluaran');
    const anggaranInput = document.getElementById('amount');

    function calculateTotal() {
        let total = 0;
        tableBody.querySelectorAll('.jumlah-rupiah').forEach(input => {
            const value = Number(input.value.replace(/[^0-9,-]+/g,"")) || 0;
            total += value;
        });
        
        totalPengeluaranSpan.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);

        const budgetAmount = Number(anggaranInput.value.replace(/[^0-9,-]+/g,"")) || 0;

        if (total > budgetAmount) {
            errorPengeluaranDiv.textContent = 'Peringatan: Total pengeluaran melebihi jumlah anggaran!';
            errorPengeluaranDiv.style.display = 'block';
        } else {
            errorPengeluaranDiv.style.display = 'none';
        }
    }

    // Hitung total saat halaman dimuat dan setiap kali ada perubahan
    calculateTotal();
    tableBody.addEventListener('input', calculateTotal);
    anggaranInput.addEventListener('input', calculateTotal);

    // Tambah baris pertama jika tabel kosong dan tipenya Anggaran
    if(tableBody.rows.length === 0 && document.getElementById('flow_type').value === 'budget'){
        addRow();
    }
}

