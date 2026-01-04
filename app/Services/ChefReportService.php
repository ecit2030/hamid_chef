<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ChefService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChefReportService
{
    /**
     * Get bookings report data for a chef.
     */
    public function getBookingsReport(int $chefId, ?Carbon $startDate, ?string $status, ?Carbon $endDate = null, int $perPage = 15): array
    {
        $query = Booking::where('chef_id', $chefId)
            ->with(['customer:id,first_name,last_name,email,phone_number', 'service:id,name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->when($status, fn($q) => $q->where('booking_status', $status));
        
        $bookings = $query->latest()->paginate($perPage);
        $stats = $this->getBookingsStats($chefId, $startDate, $status, $endDate);
        
        return [
            'bookings' => $bookings,
            'stats' => $stats,
        ];
    }
    
    /**
     * Get bookings statistics for a chef.
     */
    public function getBookingsStats(int $chefId, ?Carbon $startDate, ?string $status, ?Carbon $endDate = null): array
    {
        $query = Booking::where('chef_id', $chefId)
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->when($status, fn($q) => $q->where('booking_status', $status));
        
        return [
            'total' => (clone $query)->count(),
            'completed' => (clone $query)->where('booking_status', 'completed')->count(),
            'pending' => (clone $query)->where('booking_status', 'pending')->count(),
            'accepted' => (clone $query)->where('booking_status', 'accepted')->count(),
            'cancelled' => (clone $query)->whereIn('booking_status', ['cancelled_by_customer', 'cancelled_by_chef', 'cancelled_by_admin', 'rejected'])->count(),
            'total_amount' => (clone $query)->where('booking_status', 'completed')->sum('total_amount'),
            'total_hours' => (clone $query)->where('booking_status', 'completed')->sum('hours_count'),
        ];
    }
    
    /**
     * Get earnings report data for a chef.
     */
    public function getEarningsReport(int $chefId, ?Carbon $startDate): array
    {
        $dailyEarnings = $this->getDailyEarnings($chefId, $startDate);
        
        $summary = [
            'total_earnings' => $dailyEarnings->sum('total'),
            'total_commission' => $dailyEarnings->sum('commission'),
            'net_earnings' => $dailyEarnings->sum('net'),
            'total_bookings' => $dailyEarnings->sum('bookings_count'),
            'total_hours' => $dailyEarnings->sum('hours'),
            'average_per_booking' => $dailyEarnings->sum('bookings_count') > 0 
                ? $dailyEarnings->sum('net') / $dailyEarnings->sum('bookings_count') 
                : 0,
            'average_per_day' => $dailyEarnings->count() > 0 
                ? $dailyEarnings->sum('net') / $dailyEarnings->count() 
                : 0,
        ];
        
        return [
            'dailyEarnings' => $dailyEarnings,
            'summary' => $summary,
        ];
    }
    
    /**
     * Get daily earnings for a chef.
     */
    public function getDailyEarnings(int $chefId, ?Carbon $startDate)
    {
        return Booking::where('chef_id', $chefId)
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
    }
    
    /**
     * Get services report data for a chef.
     */
    public function getServicesReport(int $chefId, ?Carbon $startDate): array
    {
        $services = ChefService::where('chef_id', $chefId)
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
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'is_active' => $s->is_active,
                'price' => $s->price,
                'total_bookings' => $s->total_bookings ?? 0,
                'completed_bookings' => $s->completed_bookings ?? 0,
                'total_earnings' => $s->total_earnings ?? 0,
                'total_hours' => $s->total_hours ?? 0,
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
            'best_service' => $services->sortByDesc('total_earnings')->first(),
            'most_booked' => $services->sortByDesc('completed_bookings')->first(),
        ];
        
        return [
            'services' => $services->values(),
            'summary' => $summary,
        ];
    }
    
    /**
     * Get bookings data for export.
     */
    public function getBookingsForExport(int $chefId, ?Carbon $startDate, ?string $status, ?Carbon $endDate = null)
    {
        return Booking::where('chef_id', $chefId)
            ->with(['customer:id,first_name,last_name,email,phone_number', 'service:id,name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->when($status, fn($q) => $q->where('booking_status', $status))
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Get earnings data for export.
     */
    public function getEarningsForExport(int $chefId, ?Carbon $startDate)
    {
        return $this->getDailyEarnings($chefId, $startDate);
    }
    
    /**
     * Get services data for export.
     */
    public function getServicesForExport(int $chefId, ?Carbon $startDate)
    {
        return ChefService::where('chef_id', $chefId)
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
    }
}
