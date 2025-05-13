@extends('layouts.laravel-default')

@section('title', 'Laporan Keuangan | APT PRANOTO')

@push('styles')
    <style>
      .select-filter {
        width: 150px;
      }

    </style>
@endpush

@section('content')
<section class="container pb-5">
  <h2 class="mb-4 fw-bold fs-1">Laporan Keuangan Bandara A.P.T. Pranoto</h2>

  {{-- Filter Form untuk Grafik Batang --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">Filter Grafik Pemasukan</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('laporanKeuangan') }}" id="formGrafikBatang">
        <div class="row g-3 align-items-center">
          {{-- Jenis Pertumbuhan --}}
          <div class="col-auto">
            <label for="jenis_filter" class="col-form-label">Pertumbuhan</label>
          </div>
          <div class="col-auto">
            <select name="jenis_filter" id="jenis_filter" class="form-select select-filter">
              <option value="bulan" {{ ($jenis_filter == 'bulan' || !$jenis_filter) ? 'selected' : '' }}>Per Bulan</option>
              <option value="tahun" {{ $jenis_filter == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
            </select>
          </div>

          {{-- Pilih Tahun - Hanya tampilkan untuk filter bulan --}}
          <div class="col-auto" id="tahun-container" style="{{ $jenis_filter == 'tahun' ? 'display:none;' : '' }}">
            <div class="d-flex gap-2">
              <label for="tahunSelect" class="col-form-label">Tahun</label>
              <select name="tahun" id="tahunSelect" class="form-select select-filter">
                @foreach ($years as $year)
                  <option value="{{ $year }}" {{ $filterTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Menyimpan nilai filter tahun pie saat form ini disubmit --}}
          <input type="hidden" name="tahun_pie" value="{{ $filterTahunPie }}">
          @if(isset($anggaran))
          <input type="hidden" name="anggaran" value="{{ $anggaran }}">
          @endif

          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Terapkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Grafik Batang --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">
        @if($jenis_filter == 'bulan')
          Grafik Pemasukan APT Pranoto Tahun {{ $filterTahun }} (Per Bulan)
        @else
          Grafik Pemasukan APT Pranoto (Per Tahun)
        @endif
      </h5>
    </div>
    <div class="card-body">
      <canvas id="grafikKeuangan"></canvas>
    </div>
  </div>

  <div class="card mb-5">
    <div class="card-header">
      
      <h4 class="card-title mb-4">Grafik Anggaran dan Pengeluaran</h4>
    </div>
    <div class="card-body">

      <div class="row mb-3">
        <div class="col-md-4">
            <label for="period" class="form-label">Periode</label>
            <select id="period" class="form-control">
                <option value="monthly">Bulanan</option>
                <option value="yearly">Tahunan</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="year" class="form-label">Tahun</label>
            <select id="year" class="form-control">
                @foreach ($years as $y)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
      </div>
      <canvas id="financeChart" height="100"></canvas>  
    </div>
    <div class="card-footer text-muted">
      <div class="row">
        <div class="col-md-6">
          <p><strong>Anggaran:</strong> Rp {{ number_format($anggaran, 0, ',', '.') }}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="col-12">
          @if($anggaran > $totalPengeluaran)
            <div class="alert alert-success">
              <strong>Sisa Anggaran:</strong> Rp {{ number_format($anggaran - $totalPengeluaran, 0, ',', '.') }} ({{ round(($anggaran - $totalPengeluaran) / $anggaran * 100, 2) }}% dari anggaran)
            </div>
          @else
            <div class="alert alert-danger">
              <strong>Kelebihan Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran - $anggaran, 0, ',', '.') }} ({{ round(($totalPengeluaran - $anggaran) / $anggaran * 100, 2) }}% dari anggaran)
            </div>
          @endif
        </div>
      </div>
    </div>
</div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const jenisFilter = document.getElementById('jenis_filter');
  const tahunContainer = document.getElementById('tahun-container');

  function updateFilterState() {
    if (jenisFilter.value === 'bulan') {
      tahunContainer.style.display = 'block'; // Menampilkan dropdown tahun
    } else {
      tahunContainer.style.display = 'none'; // Menyembunyikan dropdown tahun
    }
  }

  // Jalankan setiap kali pilihan jenis filter berubah
  jenisFilter.addEventListener('change', updateFilterState);
</script>

<script>
  
  const ctx = document.getElementById('grafikKeuangan').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($labels) !!},
      datasets: [{
        label: 'Pemasukan (Rp)',
        data: {!! json_encode($dataPemasukan) !!},
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            }
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Pemasukan: Rp ' + context.raw.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });

  
  document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('financeChart').getContext('2d');
            let financeChart;

            function formatRupiah(value) {
                return 'Rp' + value.toLocaleString('id-ID', { minimumFractionDigits: 0 });
            }

            function fetchData() {
                const period = document.getElementById('period').value;
                const year = document.getElementById('year').value;

                fetch(`/informasi-keuangan/data?period=${period}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        if (financeChart) {
                            financeChart.destroy();
                        }

                        financeChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [
                                    {
                                        label: 'Anggaran',
                                        data: data.budget,
                                        borderColor: 'blue',
                                        backgroundColor: 'blue',
                                        fill: false,
                                        tension: 0.1,
                                        pointRadius: 5,
                                        pointHoverRadius: 8
                                    },
                                    {
                                        label: 'Pengeluaran',
                                        data: data.expense,
                                        borderColor: 'red',
                                        backgroundColor: 'red',
                                        fill: false,
                                        tension: 0.1,
                                        pointRadius: 5,
                                        pointHoverRadius: 8
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function (value) {
                                                return formatRupiah(value);
                                            }
                                        },
                                        title: {
                                            display: true,
                                            text: 'Jumlah (IDR)'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: period === 'monthly' ? 'Bulan' : 'Tahun'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += formatRupiah(context.parsed.y);
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
            }

            // Initial fetch
            fetchData();

            // Update chart on filter change
            document.getElementById('period').addEventListener('change', fetchData);
            document.getElementById('year').addEventListener('change', fetchData);
        });
</script>
@endpush
@endsection
