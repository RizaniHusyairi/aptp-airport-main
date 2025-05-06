@extends('layouts.laravel-default')

@section('title', 'Lalu Lintas Angkutan Udara | APT PRANOTO')

@section('content')
<section class="hubud-secondary min-vh-100 py-4">
  <h4 class="mb-4">Lalu Lintas Angkutan Udara</h4>

  <form method="GET" action="{{ route('laluLintas') }}" class="mb-3">
    <div class="row">
      <div class="col-md-4">
        <label for="filter_type">Jenis Filter</label>
        <select name="filter_type" id="filter_type" class="form-control">
          <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Per Tahun</option>
          <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Per Bulan</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="year">Tahun</label>
        <input type="number" name="year" id="year" class="form-control" value="{{ request('year', date('Y')) }}">
      </div>
      <div class="col-md-4 month-filter d-none">
        <label for="month">Bulan</label>
        <select name="month" id="month" class="form-control">
          @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
              {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-primary">Terapkan Filter</button>
    </div>
  </form>

  <canvas id="trafficChart" height="100"></canvas>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const filterType = document.getElementById('filter_type');
  const monthFilter = document.querySelector('.month-filter');

  function toggleMonthFilter() {
    monthFilter.classList.toggle('d-none', filterType.value !== 'month');
  }

  filterType.addEventListener('change', toggleMonthFilter);
  toggleMonthFilter(); // Initial

  const ctx = document.getElementById('trafficChart').getContext('2d');

  const chartData = @json($chartData); // Format data dari controller

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartData.labels,
      datasets: chartData.datasets
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        title: {
          display: true,
          text: 'Lalu Lintas Angkutan Udara ' + chartData.title
        }
      }
    }
  });
</script>
@endpush
@endsection