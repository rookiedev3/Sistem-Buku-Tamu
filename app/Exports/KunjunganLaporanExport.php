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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganLaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected int $month;
    protected int $year;
    protected string $category;
    protected string $branchId;
    protected string $picId;
    protected string $monthLabel;
    protected ?string $branchName;
    protected ?string $picName;

    public function __construct(
        int $month,
        int $year,
        string $category = '',
        string $branchId = '',
        string $picId = '',
        string $monthLabel = '',
        ?string $branchName = null,
        ?string $picName = null
    ) {
        $this->month      = $month;
        $this->year       = $year;
        $this->category   = $category;
        $this->branchId   = $branchId;
        $this->picId      = $picId;
        $this->monthLabel = $monthLabel;
        $this->branchName = $branchName;
        $this->picName    = $picName;
    }

    public function collection()
    {
        $query = visits::with(['guest.category', 'assignedUser', 'lead', 'purpose', 'source', 'products', 'branch'])
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

        if ($this->branchId !== '') {
            $query->where('branch_id', $this->branchId);
        }
        if ($this->picId !== '') {
            $query->where('assigned_to', $this->picId);
        }

        return $query->orderBy('check_in_at', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'Waktu', 'Nama Tamu', 'Instansi', 'Status VIP', 'Cabang',
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
            'hot' => 'Hot', 'warm' => 'Warm', 'cold' => 'Cold', 'non_lead' => 'Non-Lead',
        ];

        return [
            $no,
            $visit->check_in_at ? Carbon::parse($visit->check_in_at)->translatedFormat('d F Y') : '-',
            $visit->check_in_at ? Carbon::parse($visit->check_in_at)->format('H:i') . ' WIB' : '-',
            $visit->guest->name ?? '-',
            $visit->guest->company_name ?? '-',
            (isset($visit->guest) && $visit->guest->is_vip) ? 'VIP' : 'Reguler',
            optional($visit->branch)->name ?? '-',
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
            1 => ['font' => ['bold' => true]], // ini menata header kolom SEBELUM baris kop disisipkan
        ];
    }

    public function title(): string
    {
        return 'Laporan Kunjungan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalColumns = count($this->headings());
                $lastCol = Coordinate::stringFromColumnIndex($totalColumns);

                // Sisipkan 4 baris kosong di atas untuk kop surat
                $sheet->insertNewRowBefore(1, 4);

                // Baris 1: Nama perusahaan
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'IT SOLUTION YOGYAKARTA');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // Baris 2: Judul laporan
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'Laporan Kunjungan Tamu');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                // Baris 3: Periode & filter yang diterapkan
                $periodeText = 'Periode: ' . $this->monthLabel . ' ' . $this->year;
                if ($this->category !== '') {
                    $periodeText .= ' — Kategori: ' . ucfirst($this->category);
                }
                if ($this->branchName) {
                    $periodeText .= ' — Cabang: ' . $this->branchName;
                }
                if ($this->picName) {
                    $periodeText .= ' — PIC: ' . $this->picName;
                }
                $sheet->mergeCells("A3:{$lastCol}3");
                $sheet->setCellValue('A3', $periodeText);
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9);
                $sheet->getStyle('A3')->getFont()->getColor()->setRGB('64748B');

                // Baris 4 dikosongkan sebagai spasi

                // Baris 5 sekarang jadi header kolom (karena disisipkan 4 baris) — beri styling
                $sheet->getStyle("A5:{$lastCol}5")->getFont()->setBold(true);
                $sheet->getStyle("A5:{$lastCol}5")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8FAFC');
                $sheet->getStyle("A5:{$lastCol}5")->getBorders()->getBottom()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Freeze panes supaya header kolom tetap terlihat saat scroll
                $sheet->freezePane('A6');

                // Auto-size semua kolom
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}