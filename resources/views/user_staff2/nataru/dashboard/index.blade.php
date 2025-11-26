@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard Monitoring Posko')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/choices.js/public/assets/styles/choices.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <h3>Dashboard Monitoring & Evaluasi Posko</h3>
    <p class="text-muted">Bandingkan performa antar event posko (Tahun Ini vs Tahun Lalu).</p>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Filter Perbandingan</h4>
        </div>
        <div class="card-body">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-primary">Event Utama (Tahun Ini)</label>
                    <select class="form-select choices" id="event1" name="event_id_1">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }} ({{ $event->start_date->format('Y') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 text-center d-none d-md-block">
                    <h4 class="mb-2 text-muted">VS</h4>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary">Event Pembanding (Tahun Lalu)</label>
                    <select class="form-select choices" id="event2" name="event_id_2">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }} ({{ $event->start_date->format('Y') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" id="btnFilter" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Grafik Penumpang --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Grafik Perbandingan Penumpang (Pax)</h4>
                </div>
                <div class="card-body">
                    <div id="chartPax"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Pesawat & Kargo (Side by Side) --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Grafik Perbandingan Pesawat (Movement)</h4>
                </div>
                <div class="card-body">
                    <div id="chartFlights"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Grafik Perbandingan Kargo (Kg)</h4>
                </div>
                <div class="card-body">
                    <div id="chartCargo"></div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>

    <script>
        // Init Choices
        const choices = document.querySelectorAll('.choices');
        choices.forEach(choice => { new Choices(choice); });

        // Init Charts Variables
        let chartPax, chartFlights, chartCargo;

        // Fungsi Render Chart (Generic)
        function renderChart(elementId, title, categories, seriesData, colors) {
            var options = {
                series: seriesData,
                chart: {
                    type: 'line',
                    height: 350,
                    zoom: { enabled: false }
                },
                colors: colors,
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                title: { text: '', align: 'left' },
                grid: { row: { colors: ['#f3f3f3', 'transparent'], opacity: 0.5 } },
                xaxis: { categories: categories },
                tooltip: {
                    y: { formatter: function (val) { return val + " " + title } }
                }
            };

            return new ApexCharts(document.querySelector("#" + elementId), options);
        }

        // Load Data via AJAX
        function loadData() {
            const event1 = $('#event1').val();
            const event2 = $('#event2').val();

            if(!event1 || !event2) {
                alert("Mohon pilih kedua event untuk dibandingkan.");
                return;
            }

            // Show loading state (optional)
            
            $.ajax({
                url: "{{ route('staff.nataru.dashboard.data') }}",
                type: "GET",
                data: { event_id_1: event1, event_id_2: event2 },
                success: function(response) {
                    
                    const labels = response.labels;
                    const name1 = response.event1_name;
                    const name2 = response.event2_name;

                    // Update Chart Penumpang
                    const seriesPax = [
                        { name: name1, data: response.dataset1.pax },
                        { name: name2, data: response.dataset2.pax }
                    ];
                    if(chartPax) chartPax.destroy();
                    chartPax = renderChart('chartPax', 'Orang', labels, seriesPax, ['#435ebe', '#dc3545']);
                    chartPax.render();

                    // Update Chart Pesawat
                    const seriesFlights = [
                        { name: name1, data: response.dataset1.flights },
                        { name: name2, data: response.dataset2.flights }
                    ];
                    if(chartFlights) chartFlights.destroy();
                    chartFlights = renderChart('chartFlights', 'Pergerakan', labels, seriesFlights, ['#435ebe', '#dc3545']);
                    chartFlights.render();

                    // Update Chart Kargo
                    const seriesCargo = [
                        { name: name1, data: response.dataset1.cargo },
                        { name: name2, data: response.dataset2.cargo }
                    ];
                    if(chartCargo) chartCargo.destroy();
                    chartCargo = renderChart('chartCargo', 'Kg', labels, seriesCargo, ['#435ebe', '#dc3545']);
                    chartCargo.render();

                },
                error: function(xhr) {
                    alert("Gagal memuat data: " + xhr.responseText);
                }
            });
        }

        // Event Listener
        $('#btnFilter').on('click', loadData);

        // Load default data (jika ada opsi terpilih)
        if($('#event1').val() && $('#event2').val()) {
            loadData();
        }
    
    </script>
@endsection