<?php

namespace App\Exports\Admin;

use App\Services\AdminReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;

    public function __construct($startDate = null)
    {
        $this->startDate = $startDate;
    }

    public function collection()
    {
        $service = app(AdminReportService::class);
        return $service->getCustomersForExport($this->startDate);
    }

    public function headings(): array
    {
        return [
            'رقم العميل',
            'الاسم الأول',
            'الاسم الأخير',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'عدد الحجوزات',
            'إجمالي المبالغ',
            'تاريخ التسجيل',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->first_name,
            $customer->last_name,
            $customer->email,
            $customer->phone_number ?? '-',
            $customer->bookings_count ?? 0,
            $customer->bookings_sum_total_amount ?? 0,
            $customer->created_at->format('Y-m-d'),
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
            'F' => 15,
            'G' => 18,
            'H' => 15,
        ];
    }
}
