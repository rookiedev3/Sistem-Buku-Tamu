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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganLaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    /**
     * Status yang dianggap "sudah ada hasil pertemuan", sama persis dengan
     * ManagerController::COMPLETED_STATUSES supaya isi Excel konsisten dengan
     * preview di halaman Laporan & dengan Export PDF.
     */
    private const COMPLETED_STATUSES = ['completed', 'Selesai', 'Meeting Selesai'];

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
            ->whereYear('check_in_at', $this->year)
            ->whereIn('status', self::COMPLETED_STATUSES);

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
            'No', 'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Durasi (Menit)',
            'Nama Tamu', 'Status VIP', 'Instansi', 'Telepon', 'Cabang', 'PIC',
            'Keperluan', 'Produk Diminati', 'Sumber Lead', 'Potential Level',
            'Catatan Hasil', 'Status Akhir',
        ];
    }

    public function map($visit): array
    {
        static $no = 0;
        $no++;

        $leadLabels = [
            'new'         => 'Baru',
            'contacted'   => 'Dihubungi',
            'negotiation' => 'Negosiasi',
            'deal'        => 'Deal',
            'lost'        => 'Lost',
        ];

        $potentialLabels = [
            'hot'      => 'Hot 🔥',
            'warm'     => 'Warm',
            'cold'     => 'Cold',
            'non_lead' => 'Non-Lead',
        ];

        $statusLower = strtolower(trim($visit->status ?? ''));
        $isCompleted = in_array($statusLower, ['completed', 'selesai', 'meeting selesai']);
        $leadStatus = optional($visit->lead)->status;

        // Samakan logic Status Akhir dengan yang dipakai preview manager/laporan
        if (in_array($statusLower, ['cancelled', 'dibatalkan', 'ditolak'])) {
            $statusAkhir = 'Dibatalkan';
        } elseif ($isCompleted && $leadStatus) {
            $statusAkhir = $leadLabels[$leadStatus] ?? ucfirst($leadStatus);
        } elseif ($isCompleted) {
            $statusAkhir = 'Non-Lead';
        } else {
            $statusAkhir = 'Menunggu';
        }

        $durasi = '-';
        if ($visit->check_in_at && $visit->check_out_at) {
            $durasi = Carbon::parse($visit->check_in_at)->diffInMinutes(Carbon::parse($visit->check_out_at));
        }

        return [
            $no,
            $visit->check_in_at ? Carbon::parse($visit->check_in_at)->translatedFormat('d F Y') : '-',
            $visit->check_in_at ? Carbon::parse($visit->check_in_at)->format('H:i') . ' WIB' : '-',
            $visit->check_out_at ? Carbon::parse($visit->check_out_at)->format('H:i') . ' WIB' : '-',
            $durasi,
            $visit->guest->name ?? '-',
            (isset($visit->guest) && $visit->guest->is_vip) ? 'VIP ⭐' : 'Reguler',
            $visit->guest->company_name ?? '-',
            $visit->guest->phone ?? '-',
            optional($visit->branch)->name ?? '-',
            $visit->assignedUser->name ?? '-',
            optional($visit->purpose)->name ?? '-',
            $visit->products && $visit->products->isNotEmpty() ? $visit->products->pluck('name')->implode(', ') : '-',
            optional($visit->source)->name ?? 'Lainnya / Direct',
            $potentialLabels[$visit->potential_level] ?? '-',
            $visit->notes ?? '-',
            $statusAkhir,
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalColumns = count($this->headings());
                $lastCol = Coordinate::stringFromColumnIndex($totalColumns);

                // Sisipkan 4 baris kosong di atas untuk Kop Laporan
                $sheet->insertNewRowBefore(1, 4);

                // Baris 1: Nama Perusahaan
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'IT SOLUTION YOGYAKARTA');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));

                // Baris 2: Judul Laporan
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'Laporan Kunjungan Tamu');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                // Baris 3: Subtitle / Periode & Filter Info
                $periodeText = 'Periode: ' . $this->monthLabel . ' ' . $this->year;
                if ($this->category !== '') {
                    $periodeText .= ' | Kategori: ' . ucfirst($this->category);
                }
                if ($this->branchName) {
                    $periodeText .= ' | Cabang: ' . $this->branchName;
                }
                if ($this->picName) {
                    $periodeText .= ' | PIC: ' . $this->picName;
                }
                $sheet->mergeCells("A3:{$lastCol}3");
                $sheet->setCellValue('A3', $periodeText);
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));

                // Baris 5: Header Table Styling
                $headerRange = "A5:{$lastCol}5";
                $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E293B'));
                $sheet->getStyle($headerRange)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F1F5F9');

                $sheet->getStyle($headerRange)->getBorders()->getBottom()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()->setRGB('CBD5E1');

                // Rata Tengah untuk Header
                $sheet->getStyle($headerRange)->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Rata tengah untuk kolom: No, Tanggal, Jam Masuk, Jam Keluar,
                // Durasi, Status VIP, Status Akhir (index sesuai urutan di headings()).
                $highestRow = $sheet->getHighestRow();
                if ($highestRow >= 6) {
                    $centerColumns = [1, 2, 3, 4, 5, 7, 17];
                    foreach ($centerColumns as $colIndex) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                        $sheet->getStyle("{$colLetter}6:{$colLetter}{$highestRow}")
                            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // Freeze Pane di bawah header
                $sheet->freezePane('A6');

                // Auto-fit Lebar Kolom
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}