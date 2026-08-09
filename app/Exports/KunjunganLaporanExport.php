<?php

namespace App\Exports;

use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganLaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected int $month;
    protected int $year;
    protected string $category;

    public function __construct(int $month, int $year, string $category = '')
    {
        $this->month    = $month;
        $this->year     = $year;
        $this->category = $category;
    }

    public function collection()
    {
        $query = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products']) // <-- tambahkan
            ->whereMonth('check_in_at', $this->month)
            ->whereYear('check_in_at', $this->year);

        if (Schema::hasColumn('guests', 'is_vip')) {
            if ($this->category === 'vip') {
                $query->whereHas('guest', fn($q) => $q->where('is_vip', true));
            } elseif ($this->category === 'reguler') {
                $query->whereHas('guest', function ($q) {
                    $q->where('is_vip', false)->orWhereNull('is_vip');
                });
            }
        }

        return $query->orderBy('check_in_at', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'Waktu', 'Nama Tamu', 'Instansi', 'Status VIP',
            'Tujuan PIC', 'Keperluan', 'Produk Diminati', 'Sumber Lead', 'Potential Level',
            'Status Kunjungan', 'Tahap Lead',
        ];
    }

    public function map($visit): array
    {
        static $no = 0;
        $no++;

        $leadLabels = [
            'new' => 'Baru', 'contacted' => 'Dihubungi', 'negotiation' => 'Negosiasi',
            'deal' => 'Deal', 'lost' => 'Lost',
        ];

        $potentialLabels = [
            'hot' => 'Hot 🔥', 'warm' => 'Warm', 'cold' => 'Cold', 'non_lead' => 'Non-Lead',
        ];

        return [
            $no,
            $visit->check_in_at ? Carbon::parse($visit->check_in_at)->translatedFormat('d F Y') : '-',
            $visit->check_in_at ? Carbon::parse($visit->check_in_at)->format('H:i') . ' WIB' : '-',
            $visit->guest->name ?? '-',
            $visit->guest->company_name ?? '-',
            (isset($visit->guest) && $visit->guest->is_vip) ? 'VIP' : 'Reguler',
            $visit->assignedUser->name ?? '-',
            optional($visit->purpose)->name ?? '-',
            $visit->products && $visit->products->isNotEmpty() ? $visit->products->pluck('name')->implode(', ') : '-',
            optional($visit->source)->name ?? '-',
            $potentialLabels[$visit->potential_level] ?? '-',
            $visit->status ?? '-',
            $leadLabels[optional($visit->lead)->status] ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Kunjungan';
    }
}