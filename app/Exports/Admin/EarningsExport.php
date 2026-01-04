<?php

namespace App\Exports\Admin;

use App\Services\AdminReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EarningsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;

    public function __construct($startDate = null)
    {
        $this->startDate = $startDate;
    }

    public function collection()
    {
        $service = app(AdminReportService::class);
        return $service->getEarningsForExport($this->startDate);
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'عدد الحجوزات',
            'عدد الساعات',
            'إجمالي الإيرادات',
            'العمولة',
            'صافي الأرباح',
        ];
    }

    public function map($earning): array
    {
        return [
            $earning->date,
            $earning->bookings_count,
            $earning->hours,
            $earning->total,
            $earning->commission,
            $earning->net,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 18,
            'E' => 15,
            'F' => 18,
        ];
    }
}
