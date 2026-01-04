<?php

namespace App\Exports\Admin;

use App\Services\AdminReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $type;

    public function __construct($startDate = null, $type = null)
    {
        $this->startDate = $startDate;
        $this->type = $type;
    }

    public function collection()
    {
        $service = app(AdminReportService::class);
        return $service->getTransactionsForExport($this->startDate, $this->type);
    }

    public function headings(): array
    {
        return [
            'رقم المعاملة',
            'اسم الطاهي',
            'النوع',
            'المبلغ',
            'الوصف',
            'التاريخ',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->chef && $transaction->chef->user 
                ? $transaction->chef->user->first_name . ' ' . $transaction->chef->user->last_name 
                : '-',
            $this->getTypeLabel($transaction->type),
            $transaction->amount,
            $transaction->description ?? '-',
            $transaction->created_at->format('Y-m-d H:i'),
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
            'B' => 25,
            'C' => 12,
            'D' => 15,
            'E' => 35,
            'F' => 18,
        ];
    }

    private function getTypeLabel(string $type): string
    {
        return match($type) {
            'credit' => 'إيداع',
            'debit' => 'سحب',
            default => $type,
        };
    }
}
