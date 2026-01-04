<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EarningsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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
        return Booking::where('chef_id', $this->chefId)
            ->where('booking_status', 'completed')
            ->when($this->startDate, fn($q) => $q->where('created_at', '>=', $this->startDate))
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(total_amount) as total')
            ->selectRaw('SUM(commission_amount) as commission')
            ->selectRaw('SUM(total_amount - commission_amount) as net')
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw('SUM(hours_count) as hours')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'عدد الحجوزات',
            'عدد الساعات',
            'الإجمالي',
            'العمولة',
            'صافي الأرباح',
        ];
    }

    public function map($day): array
    {
        return [
            $day->date,
            $day->bookings_count,
            $day->hours,
            $day->total,
            $day->commission,
            $day->net,
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
            'D' => 15,
            'E' => 15,
            'F' => 15,
        ];
    }
}
