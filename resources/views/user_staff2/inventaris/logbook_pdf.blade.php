<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Logbook Peralatan - {{ $inventory->name }} - {{ $periode }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header h2 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        .header p { margin: 0; font-size: 12px; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-size: 11px;
            text-align: center;
        }
        .text-center { text-align: center; }
        .notes { min-width: 150px; } /* Sedikit diubah untuk memberi ruang */
        
        /* === CSS BARU UNTUK DOKUMENTASI === */
        .doc-gallery { 
            width: 200px; /* Lebar kolom dokumentasi */
            text-align: center;
        }
        .doc-thumb {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LOGBOOK PERALATAN</h1>
        <h2>BANDAR UDARA A.P.T. PRANOTO - SAMARINDA</h2>
        <p><strong>NAMA ALAT:</strong> {{ strtoupper($inventory->name) }}</p>
        <p><strong>PERIODE:</strong> {{ $periode }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 50px;">Jadwal</th>
                <th class="notes">Catatan / Tindakan</th>
                <th style="width: 100px;">Teknisi</th>
                <th class="doc-gallery">Dokumentasi</th> {{-- <<< KOLOM BARU --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($logbooks as $log)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $log->log_date->format('d/m/Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($log->schedule_time)->format('H:i') }}</td>
                    <td>{!! nl2br(e($log->notes)) !!}</td> {{-- Ubah agar baris baru di catatan tampil --}}
                    <td>{{ $log->user->name ?? '-' }}</td>
                    <td class="text-center"> {{-- <<< DATA KOLOM BARU --}}
                        @if($log->documentation && count($log->documentation) > 0)
                            @foreach($log->documentation as $photoPath)
                                {{-- 
                                    Kita gunakan public_path() agar dompdf bisa mengakses file
                                    langsung dari sistem file server.
                                --}}
                                @if(file_exists(public_path('uploads/' . $photoPath)))
                                    <img src="{{ public_path('uploads/' . $photoPath) }}" class="doc-thumb" alt="Doc">
                                @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada catatan logbook untuk periode ini.</td> {{-- <<< COLSPAN DIUBAH --}}
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

