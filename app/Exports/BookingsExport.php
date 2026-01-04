<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $chefId;
    protected $startDate;
    protected $status;
    protected $endDate;

    public function __construct($chefId, $startDate = null, $status = null, $endDate = null)
    {
        $this->chefId = $chefId;
        $this->startDate = $startDate;
        $this->status = $status;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Booking::where('chef_id', $this->chefId)
            ->with(['customer:id,first_name,last_name,email,phone_number', 'service:id,name'])
            ->when($this->startDate, fn($q) => $q->where('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->where('created_at', '<=', $this->endDate))
            ->when($this->status, fn($q) => $q->where('booking_status', $this->status))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'رقم الحجز',
            'اسم العميل',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'الخدمة',
            'التاريخ',
            'عدد الساعات',
            'عدد الضيوف',
            'المبلغ الإجمالي',
            'العمولة',
            'صافي الأرباح',
            'الحالة',
            'تاريخ الإنشاء',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->customer ? $booking->customer->first_name . ' ' . $booking->customer->last_name : '-',
            $booking->customer?->email ?? '-',
            $booking->customer?->phone_number ?? '-',
            $booking->service?->name ?? '-',
            $booking->date,
            $booking->hours_count,
            $booking->number_of_guests,
            $booking->total_amount,
            $booking->commission_amount,
            $booking->total_amount - $booking->commission_amount,
            $this->getStatusLabel($booking->booking_status),
            $booking->created_at->format('Y-m-d H:i'),
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
            'C' => 25,
            'D' => 15,
            'E' => 25,
            'F' => 15,
            'G' => 12,
            'H' => 12,
            'I' => 15,
            'J' => 12,
            'K' => 15,
            'L' => 20,
            'M' => 18,
        ];
    }

    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'قيد الانتظار',
            'accepted' => 'مقبول',
            'rejected' => 'مرفوض',
            'completed' => 'مكتمل',
            'cancelled_by_customer' => 'ملغي من العميل',
            'cancelled_by_chef' => 'ملغي من الشيف',
            'cancelled_by_admin' => 'ملغي من الإدارة',
            default => $status,
        };
    }
}
