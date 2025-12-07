<?php

namespace App\Exports;

use App\Models\NataruFlight;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NataruFlightExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return NataruFlight::where('nataru_event_id', $this->eventId)
                           ->orderBy('flight_date', 'asc')
                           ->orderBy('flight_time', 'asc')
                           ->get();
    }

    public function map($flight): array
    {
        return [
            $flight->flight_date->format('d/m/Y'),
            \Carbon\Carbon::parse($flight->flight_time)->format('H:i'),
            $flight->airline,
            $flight->flight_number,
            $flight->route,
            ucfirst($flight->direction), // Arrival/Departure
            $flight->status_flight,
            $flight->aircraft_type ?? '-',
            $flight->aircraft_registration ?? '-',
            $flight->pax_adult,
            $flight->pax_child,
            $flight->pax_infant,
            $flight->pax_total,
            $flight->cargo,
            $flight->baggage,
            $flight->load_factor ? $flight->load_factor . '%' : '-',
            $flight->ticket_price_high,
            $flight->ticket_price_low,
            $flight->officer_name,
            $flight->remarks
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam',
            'Maskapai',
            'No. Flight',
            'Rute',
            'Arah',
            'Status',
            'Tipe Pesawat',
            'Registrasi',
            'Pax Dewasa',
            'Pax Anak',
            'Pax Bayi',
            'Total Pax',
            'Kargo (Kg)',
            'Bagasi (Kg)',
            'Load Factor',
            'Harga Tertinggi',
            'Harga Terendah',
            'Petugas',
            'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header bold
        ];
    }
}