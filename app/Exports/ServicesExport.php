<?php

namespace App\Exports;

use App\Models\ChefService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $chefId;
    protected $startDate;

    public function __construct($chefId, $startDate = null)
    {
        $this->chefId = $chefId;
        $this->startDate = $startDate;
    }

    public function collection()
    {
        return ChefService::where('chef_id', $this->chefId)
            ->withCount(['bookings as total_bookings' => function($q) {
                if ($this->startDate) {
                    $q->where('created_at', '>=', $this->startDate);
                }
            }])
            ->withCount(['bookings as completed_bookings' => function($q) {
                $q->where('booking_status', 'completed');
                if ($this->startDate) {
                    $q->where('created_at', '>=', $this->startDate);
                }
            }])
            ->withSum(['bookings as total_earnings' => function($q) {
                $q->where('booking_status', 'completed');
                if ($this->startDate) {
                    $q->where('created_at', '>=', $this->startDate);
                }
            }], 'total_amount')
            ->withSum(['bookings as total_hours' => function($q) {
                $q->where('booking_status', 'completed');
                if ($this->startDate) {
                    $q->where('created_at', '>=', $this->startDate);
                }
            }], 'hours_count')
            ->withAvg(['ratings as average_rating'], 'rating')
            ->get();
    }

    public function headings(): array
    {
        return [
            'اسم الخدمة',
            'الحالة',
            'السعر',
            'إجمالي الحجوزات',
            'الحجوزات المكتملة',
            'نسبة التحويل',
            'إجمالي الساعات',
            'إجمالي الأرباح',
            'متوسط التقييم',
        ];
    }

    public function map($service): array
    {
        $conversionRate = $service->total_bookings > 0 
            ? round(($service->completed_bookings / $service->total_bookings) * 100, 1) 
            : 0;

        return [
            $service->name,
            $service->is_active ? 'نشط' : 'غير نشط',
            $service->price,
            $service->total_bookings ?? 0,
            $service->completed_bookings ?? 0,
            $conversionRate . '%',
            $service->total_hours ?? 0,
            $service->total_earnings ?? 0,
            round($service->average_rating ?? 0, 1),
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
            'A' => 25,
            'B' => 12,
            'C' => 12,
            'D' => 18,
            'E' => 18,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
        ];
    }
}
