<?php

namespace App\Exports\Admin;

use App\Services\AdminReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;

    public function __construct($startDate = null)
    {
        $this->startDate = $startDate;
    }

    public function collection()
    {
        $service = app(AdminReportService::class);
        return $service->getServicesForExport($this->startDate);
    }

    public function headings(): array
    {
        return [
            'رقم الخدمة',
            'اسم الخدمة',
            'اسم الطاهي',
            'الحالة',
            'السعر',
            'إجمالي الحجوزات',
            'الحجوزات المكتملة',
            'نسبة التحويل',
            'إجمالي الأرباح',
            'متوسط التقييم',
            'تاريخ الإنشاء',
        ];
    }

    public function map($service): array
    {
        $conversionRate = $service->total_bookings > 0 
            ? round(($service->completed_bookings / $service->total_bookings) * 100, 1) 
            : 0;
            
        return [
            $service->id,
            $service->name,
            $service->chef && $service->chef->user ? $service->chef->user->first_name . ' ' . $service->chef->user->last_name : '-',
            $service->is_active ? 'نشط' : 'غير نشط',
            $service->price,
            $service->total_bookings ?? 0,
            $service->completed_bookings ?? 0,
            $conversionRate . '%',
            $service->total_earnings ?? 0,
            round($service->average_rating ?? 0, 1),
            $service->created_at->format('Y-m-d'),
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
            'B' => 30,
            'C' => 25,
            'D' => 12,
            'E' => 12,
            'F' => 18,
            'G' => 20,
            'H' => 15,
            'I' => 18,
            'J' => 15,
            'K' => 15,
        ];
    }
}
