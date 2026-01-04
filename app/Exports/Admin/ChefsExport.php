<?php

namespace App\Exports\Admin;

use App\Services\AdminReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChefsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;

    public function __construct($startDate = null)
    {
        $this->startDate = $startDate;
    }

    public function collection()
    {
        $service = app(AdminReportService::class);
        return $service->getChefsForExport($this->startDate);
    }

    public function headings(): array
    {
        return [
            'رقم الطاهي',
            'الاسم الأول',
            'الاسم الأخير',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'الحالة',
            'عدد الحجوزات',
            'الحجوزات المكتملة',
            'إجمالي الأرباح',
            'تاريخ التسجيل',
        ];
    }

    public function map($chef): array
    {
        return [
            $chef->id,
            $chef->user?->first_name ?? '-',
            $chef->user?->last_name ?? '-',
            $chef->user?->email ?? '-',
            $chef->user?->phone_number ?? '-',
            $chef->is_active ? 'نشط' : 'غير نشط',
            $chef->bookings_count ?? 0,
            $chef->completed_bookings ?? 0,
            $chef->total_earnings ?? 0,
            $chef->created_at->format('Y-m-d'),
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
            'A' => 12,
            'B' => 20,
            'C' => 20,
            'D' => 30,
            'E' => 15,
            'F' => 12,
            'G' => 15,
            'H' => 18,
            'I' => 18,
            'J' => 15,
        ];
    }
}
