<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Admin\BookingsExport;
use App\Exports\Admin\CustomersExport;
use App\Exports\Admin\ChefsExport;
use App\Exports\Admin\ServicesExport;
use App\Exports\Admin\EarningsExport;
use App\Exports\Admin\TransactionsExport;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function __construct(
        private AdminReportService $reportService
    ) {}
    
    /**
     * Bookings report.
     */
    public function bookings(Request $request): Response
    {
        $period = $request->input('period', 'month');
        $status = $request->input('status');
        $startDate = null;
        $endDate = null;
        
        if ($period === 'custom') {
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        } else {
            $startDate = $this->getStartDate($period);
            $endDate = null;
        }
        
        $data = $this->reportService->getBookingsReport($startDate, $status, $endDate);
        
        return Inertia::render('Admin/Reports/Bookings', [
            'bookings' => $data['bookings'],
            'stats' => $data['stats'],
            'period' => $period,
            'status' => $status,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    }
    
    /**
     * Customers report.
     */
    public function customers(Request $request): Response
    {
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getCustomersReport($startDate);
        
        return Inertia::render('Admin/Reports/Customers', [
            'customers' => $data['customers'],
            'stats' => $data['stats'],
            'period' => $period,
        ]);
    }
    
    /**
     * Chefs report.
     */
    public function chefs(Request $request): Response
    {
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getChefsReport($startDate);
        
        return Inertia::render('Admin/Reports/Chefs', [
            'chefs' => $data['chefs'],
            'stats' => $data['stats'],
            'period' => $period,
        ]);
    }
    
    /**
     * Services report.
     */
    public function services(Request $request): Response
    {
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getServicesReport($startDate);
        
        return Inertia::render('Admin/Reports/Services', [
            'services' => $data['services'],
            'stats' => $data['stats'],
            'period' => $period,
        ]);
    }
    
    /**
     * Earnings report.
     */
    public function earnings(Request $request): Response
    {
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getEarningsReport($startDate);
        
        return Inertia::render('Admin/Reports/Earnings', [
            'dailyEarnings' => $data['dailyEarnings'],
            'summary' => $data['summary'],
            'period' => $period,
        ]);
    }
    
    /**
     * Transactions report.
     */
    public function transactions(Request $request): Response
    {
        $period = $request->input('period', 'month');
        $type = $request->input('type');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getTransactionsReport($startDate, $type);
        
        return Inertia::render('Admin/Reports/Transactions', [
            'transactions' => $data['transactions'],
            'stats' => $data['stats'],
            'period' => $period,
            'type' => $type,
        ]);
    }
    
    /**
     * Export bookings report.
     */
    public function exportBookings(Request $request)
    {
        $format = $request->input('format', 'excel');
        $period = $request->input('period', 'month');
        $status = $request->input('status');
        $startDate = null;
        $endDate = null;
        
        if ($period === 'custom') {
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        } else {
            $startDate = $this->getStartDate($period);
            $endDate = null;
        }
        
        $filename = "bookings_report_" . now()->format('Y-m-d');
        
        if ($format === 'pdf') {
            $bookings = $this->reportService->getBookingsForExport($startDate, $status, $endDate);
            $data = $this->prepareBookingsPdfData($bookings, $startDate, $endDate);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $html = view('pdf.reports.bookings', $data)->render();
            $mpdf->WriteHTML($html);
            
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename . '.pdf');
        }
        
        return Excel::download(new BookingsExport($startDate, $status, $endDate), $filename . '.xlsx');
    }
    
    /**
     * Export customers report.
     */
    public function exportCustomers(Request $request)
    {
        $format = $request->input('format', 'excel');
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $filename = "customers_report_" . now()->format('Y-m-d');
        
        if ($format === 'pdf') {
            $customers = $this->reportService->getCustomersForExport($startDate);
            $data = $this->prepareCustomersPdfData($customers);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $html = view('pdf.reports.customers', $data)->render();
            $mpdf->WriteHTML($html);
            
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename . '.pdf');
        }
        
        return Excel::download(new CustomersExport($startDate), $filename . '.xlsx');
    }
    
    /**
     * Export chefs report.
     */
    public function exportChefs(Request $request)
    {
        $format = $request->input('format', 'excel');
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $filename = "chefs_report_" . now()->format('Y-m-d');
        
        if ($format === 'pdf') {
            $chefs = $this->reportService->getChefsForExport($startDate);
            $data = $this->prepareChefsPdfData($chefs);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $html = view('pdf.reports.chefs', $data)->render();
            $mpdf->WriteHTML($html);
            
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename . '.pdf');
        }
        
        return Excel::download(new ChefsExport($startDate), $filename . '.xlsx');
    }
    
    /**
     * Export services report.
     */
    public function exportServices(Request $request)
    {
        $format = $request->input('format', 'excel');
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $filename = "services_report_" . now()->format('Y-m-d');
        
        if ($format === 'pdf') {
            $services = $this->reportService->getServicesForExport($startDate);
            $data = $this->prepareServicesPdfData($services);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $html = view('pdf.reports.services', $data)->render();
            $mpdf->WriteHTML($html);
            
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename . '.pdf');
        }
        
        return Excel::download(new ServicesExport($startDate), $filename . '.xlsx');
    }
    
    /**
     * Export earnings report.
     */
    public function exportEarnings(Request $request)
    {
        $format = $request->input('format', 'excel');
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $filename = "earnings_report_" . now()->format('Y-m-d');
        
        if ($format === 'pdf') {
            $dailyEarnings = $this->reportService->getEarningsForExport($startDate);
            $data = $this->prepareEarningsPdfData($dailyEarnings);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $html = view('pdf.reports.earnings', $data)->render();
            $mpdf->WriteHTML($html);
            
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename . '.pdf');
        }
        
        return Excel::download(new EarningsExport($startDate), $filename . '.xlsx');
    }
    
    /**
     * Export transactions report.
     */
    public function exportTransactions(Request $request)
    {
        $format = $request->input('format', 'excel');
        $period = $request->input('period', 'month');
        $type = $request->input('type');
        $startDate = $this->getStartDate($period);
        
        $filename = "transactions_report_" . now()->format('Y-m-d');
        
        if ($format === 'pdf') {
            $transactions = $this->reportService->getTransactionsForExport($startDate, $type);
            $data = $this->prepareTransactionsPdfData($transactions);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font' => 'dejavusans',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            
            $html = view('pdf.reports.transactions', $data)->render();
            $mpdf->WriteHTML($html);
            
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename . '.pdf');
        }
        
        return Excel::download(new TransactionsExport($startDate, $type), $filename . '.xlsx');
    }
    
    /**
     * Prepare bookings data for PDF.
     */
    private function prepareBookingsPdfData($bookings, $startDate = null, $endDate = null): array
    {
        $stats = [
            'total' => $bookings->count(),
            'completed' => $bookings->where('booking_status', 'completed')->count(),
            'total_amount' => $bookings->where('booking_status', 'completed')->sum('total_amount'),
            'total_hours' => $bookings->where('booking_status', 'completed')->sum('hours_count'),
        ];
        
        $dateRange = '';
        if ($startDate && $endDate) {
            $dateRange = 'من ' . $startDate->format('Y-m-d') . ' إلى ' . $endDate->format('Y-m-d');
        } elseif ($startDate) {
            $dateRange = 'من ' . $startDate->format('Y-m-d');
        } else {
            $dateRange = 'جميع الفترات';
        }
        
        return [
            'bookings' => $bookings,
            'stats' => $stats,
            'date' => now()->format('Y-m-d'),
            'date_range' => $dateRange,
        ];
    }
    
    /**
     * Prepare customers data for PDF.
     */
    private function prepareCustomersPdfData($customers): array
    {
        $stats = [
            'total_customers' => $customers->count(),
            'total_bookings' => $customers->sum('bookings_count'),
            'total_revenue' => $customers->sum('bookings_sum_total_amount'),
        ];
        
        return [
            'customers' => $customers,
            'stats' => $stats,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Prepare chefs data for PDF.
     */
    private function prepareChefsPdfData($chefs): array
    {
        $stats = [
            'total_chefs' => $chefs->count(),
            'total_bookings' => $chefs->sum('bookings_count'),
            'total_earnings' => $chefs->sum('total_earnings'),
        ];
        
        return [
            'chefs' => $chefs,
            'stats' => $stats,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Prepare services data for PDF.
     */
    private function prepareServicesPdfData($services): array
    {
        $stats = [
            'total_services' => $services->count(),
            'total_bookings' => $services->sum('total_bookings'),
            'total_earnings' => $services->sum('total_earnings'),
        ];
        
        return [
            'services' => $services,
            'stats' => $stats,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Prepare earnings data for PDF.
     */
    private function prepareEarningsPdfData($dailyEarnings): array
    {
        $summary = [
            'total_earnings' => $dailyEarnings->sum('total'),
            'total_commission' => $dailyEarnings->sum('commission'),
            'net_earnings' => $dailyEarnings->sum('net'),
            'total_bookings' => $dailyEarnings->sum('bookings_count'),
            'total_hours' => $dailyEarnings->sum('hours'),
        ];
        
        return [
            'dailyEarnings' => $dailyEarnings,
            'summary' => $summary,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Prepare transactions data for PDF.
     */
    private function prepareTransactionsPdfData($transactions): array
    {
        $stats = [
            'total_transactions' => $transactions->count(),
            'total_credits' => $transactions->where('type', 'credit')->sum('amount'),
            'total_debits' => $transactions->where('type', 'debit')->sum('amount'),
            'net_amount' => $transactions->where('type', 'credit')->sum('amount') - $transactions->where('type', 'debit')->sum('amount'),
        ];
        
        return [
            'transactions' => $transactions,
            'stats' => $stats,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Get start date based on period.
     */
    private function getStartDate(string $period): ?Carbon
    {
        return match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            'all' => null,
            default => now()->subMonth(),
        };
    }
}
