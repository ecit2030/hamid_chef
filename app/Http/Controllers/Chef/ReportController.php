<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Services\ChefReportService;
use App\Models\Booking;
use App\Models\ChefService;
use App\Models\ChefWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;
use App\Exports\EarningsExport;
use App\Exports\ServicesExport;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function __construct(
        private ChefReportService $reportService
    ) {}
    /**
     * Display the main reports dashboard.
     */
    public function index(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $period = $request->input('period', 'month'); // week, month, year, all
        $startDate = $this->getStartDate($period);
        
        // Summary Statistics
        $summary = [
            'total_bookings' => Booking::where('chef_id', $chef->id)
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->count(),
            'completed_bookings' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->count(),
            'pending_bookings' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'pending')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->count(),
            'cancelled_bookings' => Booking::where('chef_id', $chef->id)
                ->whereIn('booking_status', ['cancelled_by_customer', 'cancelled_by_chef', 'cancelled_by_admin', 'rejected'])
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->count(),
            'total_earnings' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('total_amount'),
            'total_commission' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('commission_amount'),
            'net_earnings' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum(DB::raw('total_amount - commission_amount')),
            'total_hours' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('hours_count'),
            'total_guests' => Booking::where('chef_id', $chef->id)
                ->where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('number_of_guests'),
            'average_rating' => $chef->rating_avg ?? 0,
            'total_services' => ChefService::where('chef_id', $chef->id)->count(),
            'active_services' => ChefService::where('chef_id', $chef->id)->where('is_active', true)->count(),
        ];
        
        // Bookings by Status Chart Data
        $bookingsByStatus = Booking::where('chef_id', $chef->id)
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->select('booking_status', DB::raw('count(*) as count'))
            ->groupBy('booking_status')
            ->get()
            ->pluck('count', 'booking_status')
            ->toArray();
        
        // Earnings by Month Chart Data (last 6 months)
        $earningsByMonth = Booking::where('chef_id', $chef->id)
            ->where('booking_status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('YEAR(created_at) as year')
            ->selectRaw('MONTH(created_at) as month')
            ->selectRaw('SUM(total_amount) as total')
            ->selectRaw('SUM(commission_amount) as commission')
            ->selectRaw('SUM(total_amount - commission_amount) as net')
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Top Services
        $topServices = ChefService::where('chef_id', $chef->id)
            ->withCount(['bookings' => function($q) use ($startDate) {
                $q->where('booking_status', 'completed');
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }])
            ->withSum(['bookings' => function($q) use ($startDate) {
                $q->where('booking_status', 'completed');
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }], 'total_amount')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'bookings_count' => $s->bookings_count ?? 0,
                'total_earnings' => $s->bookings_sum_total_amount ?? 0,
            ]);
        
        // Recent Bookings
        $recentBookings = Booking::where('chef_id', $chef->id)
            ->with(['customer:id,first_name,last_name', 'service:id,name'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'customer_name' => $b->customer ? $b->customer->first_name . ' ' . $b->customer->last_name : '-',
                'service_name' => $b->service?->name ?? '-',
                'date' => $b->date,
                'total_amount' => $b->total_amount,
                'status' => $b->booking_status,
            ]);
        
        return Inertia::render('Chef/Reports/Index', [
            'summary' => $summary,
            'bookingsByStatus' => $bookingsByStatus,
            'earningsByMonth' => $earningsByMonth,
            'topServices' => $topServices,
            'recentBookings' => $recentBookings,
            'period' => $period,
        ]);
    }
    
    /**
     * Detailed bookings report.
     */
    public function bookings(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
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
        
        $data = $this->reportService->getBookingsReport($chef->id, $startDate, $status, $endDate);
        
        return Inertia::render('Chef/Reports/Bookings', [
            'bookings' => $data['bookings'],
            'stats' => $data['stats'],
            'period' => $period,
            'status' => $status,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    }
    
    /**
     * Detailed earnings report.
     */
    public function earnings(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getEarningsReport($chef->id, $startDate);
        
        // Wallet balance
        $walletBalance = $chef->wallet?->balance ?? 0;
        $pendingWithdrawals = $chef->withdrawalRequests()
            ->where('status', 'pending')
            ->sum('amount');
        
        return Inertia::render('Chef/Reports/Earnings', [
            'dailyEarnings' => $data['dailyEarnings'],
            'summary' => $data['summary'],
            'walletBalance' => $walletBalance,
            'pendingWithdrawals' => $pendingWithdrawals,
            'period' => $period,
        ]);
    }
    
    /**
     * Services performance report.
     */
    public function services(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = $this->reportService->getServicesReport($chef->id, $startDate);
        
        return Inertia::render('Chef/Reports/Services', [
            'services' => $data['services'],
            'summary' => $data['summary'],
            'period' => $period,
        ]);
    }
    
    /**
     * Export report data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $type = $request->input('type', 'bookings');
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
        
        $filename = "report_{$type}_" . now()->format('Y-m-d') . ".xlsx";
        
        return match($type) {
            'bookings' => Excel::download(new BookingsExport($chef->id, $startDate, $status, $endDate), $filename),
            'earnings' => Excel::download(new EarningsExport($chef->id, $startDate), $filename),
            'services' => Excel::download(new ServicesExport($chef->id, $startDate), $filename),
            default => Excel::download(new BookingsExport($chef->id, $startDate, $status, $endDate), $filename),
        };
    }
    
    /**
     * Export report data to PDF.
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $type = $request->input('type', 'bookings');
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
        
        $filename = "report_{$type}_" . now()->format('Y-m-d') . ".pdf";
        
        $data = match($type) {
            'bookings' => $this->getBookingsPdfData($chef, $startDate, $status, $endDate),
            'earnings' => $this->getEarningsPdfData($chef, $startDate),
            'services' => $this->getServicesPdfData($chef, $startDate),
            default => $this->getBookingsPdfData($chef, $startDate, $status, $endDate),
        };
        
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
        
        $html = view("pdf.reports.{$type}", $data)->render();
        $mpdf->WriteHTML($html);
        
        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename);
    }
    
    /**
     * Get bookings data for PDF.
     */
    private function getBookingsPdfData($chef, $startDate, $status = null, $endDate = null): array
    {
        $bookings = $this->reportService->getBookingsForExport($chef->id, $startDate, $status, $endDate);
        
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
            'chef' => $chef,
            'bookings' => $bookings,
            'stats' => $stats,
            'date' => now()->format('Y-m-d'),
            'date_range' => $dateRange,
        ];
    }
    
    /**
     * Get earnings data for PDF.
     */
    private function getEarningsPdfData($chef, $startDate): array
    {
        $dailyEarnings = $this->reportService->getEarningsForExport($chef->id, $startDate);
        
        $summary = [
            'total_earnings' => $dailyEarnings->sum('total'),
            'total_commission' => $dailyEarnings->sum('commission'),
            'net_earnings' => $dailyEarnings->sum('net'),
            'total_bookings' => $dailyEarnings->sum('bookings_count'),
            'total_hours' => $dailyEarnings->sum('hours'),
        ];
        
        return [
            'chef' => $chef,
            'dailyEarnings' => $dailyEarnings,
            'summary' => $summary,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Get services data for PDF.
     */
    private function getServicesPdfData($chef, $startDate): array
    {
        $services = $this->reportService->getServicesForExport($chef->id, $startDate)
            ->map(fn($s) => [
                'name' => $s->name,
                'is_active' => $s->is_active,
                'price' => $s->price,
                'total_bookings' => $s->total_bookings ?? 0,
                'completed_bookings' => $s->completed_bookings ?? 0,
                'total_hours' => $s->total_hours ?? 0,
                'total_earnings' => $s->total_earnings ?? 0,
                'average_rating' => round($s->average_rating ?? 0, 1),
                'conversion_rate' => $s->total_bookings > 0 
                    ? round(($s->completed_bookings / $s->total_bookings) * 100, 1) 
                    : 0,
            ]);
        
        $summary = [
            'total_services' => $services->count(),
            'active_services' => $services->where('is_active', true)->count(),
            'total_bookings' => $services->sum('total_bookings'),
            'total_earnings' => $services->sum('total_earnings'),
        ];
        
        return [
            'chef' => $chef,
            'services' => $services,
            'summary' => $summary,
            'date' => now()->format('Y-m-d'),
        ];
    }
    
    /**
     * Export report data to CSV (legacy support).
     */
    public function export(Request $request)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $type = $request->input('type', 'bookings');
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $filename = "report_{$type}_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($chef, $type, $startDate) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            switch ($type) {
                case 'bookings':
                    $this->exportBookings($file, $chef, $startDate);
                    break;
                case 'earnings':
                    $this->exportEarnings($file, $chef, $startDate);
                    break;
                case 'services':
                    $this->exportServices($file, $chef, $startDate);
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export bookings to CSV.
     */
    private function exportBookings($file, $chef, $startDate): void
    {
        // Header row
        fputcsv($file, [
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
        ]);
        
        $bookings = Booking::where('chef_id', $chef->id)
            ->with(['customer:id,first_name,last_name,email,phone_number', 'service:id,name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach ($bookings as $booking) {
            fputcsv($file, [
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
            ]);
        }
    }
    
    /**
     * Export earnings to CSV.
     */
    private function exportEarnings($file, $chef, $startDate): void
    {
        // Header row
        fputcsv($file, [
            'التاريخ',
            'عدد الحجوزات',
            'عدد الساعات',
            'الإجمالي',
            'العمولة',
            'صافي الأرباح',
        ]);
        
        $dailyEarnings = Booking::where('chef_id', $chef->id)
            ->where('booking_status', 'completed')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(total_amount) as total')
            ->selectRaw('SUM(commission_amount) as commission')
            ->selectRaw('SUM(total_amount - commission_amount) as net')
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw('SUM(hours_count) as hours')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();
        
        foreach ($dailyEarnings as $day) {
            fputcsv($file, [
                $day->date,
                $day->bookings_count,
                $day->hours,
                $day->total,
                $day->commission,
                $day->net,
            ]);
        }
    }
    
    /**
     * Export services to CSV.
     */
    private function exportServices($file, $chef, $startDate): void
    {
        // Header row
        fputcsv($file, [
            'اسم الخدمة',
            'الحالة',
            'السعر',
            'إجمالي الحجوزات',
            'الحجوزات المكتملة',
            'نسبة التحويل',
            'إجمالي الساعات',
            'إجمالي الأرباح',
            'متوسط التقييم',
        ]);
        
        $services = ChefService::where('chef_id', $chef->id)
            ->withCount(['bookings as total_bookings' => function($q) use ($startDate) {
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }])
            ->withCount(['bookings as completed_bookings' => function($q) use ($startDate) {
                $q->where('booking_status', 'completed');
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }])
            ->withSum(['bookings as total_earnings' => function($q) use ($startDate) {
                $q->where('booking_status', 'completed');
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }], 'total_amount')
            ->withSum(['bookings as total_hours' => function($q) use ($startDate) {
                $q->where('booking_status', 'completed');
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }], 'hours_count')
            ->withAvg(['ratings as average_rating'], 'rating')
            ->get();
        
        foreach ($services as $service) {
            $conversionRate = $service->total_bookings > 0 
                ? round(($service->completed_bookings / $service->total_bookings) * 100, 1) 
                : 0;
            
            fputcsv($file, [
                $service->name,
                $service->is_active ? 'نشط' : 'غير نشط',
                $service->price,
                $service->total_bookings ?? 0,
                $service->completed_bookings ?? 0,
                $conversionRate . '%',
                $service->total_hours ?? 0,
                $service->total_earnings ?? 0,
                round($service->average_rating ?? 0, 1),
            ]);
        }
    }
    
    /**
     * Get status label in Arabic.
     */
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
