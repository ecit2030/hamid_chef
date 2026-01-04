<?php

namespace App\Repositories;

use App\Models\ChefService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ChefServiceRepository
{
    /**
     * Get services for report with pagination.
     */
    public function getServicesForReport(?Carbon $startDate, int $perPage = 15): LengthAwarePaginator
    {
        return ChefService::with(['chef.user:id,first_name,last_name'])
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
            ->withAvg(['ratings as average_rating'], 'rating')
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get services statistics.
     */
    public function getServicesStats(?Carbon $startDate): array
    {
        return [
            'total_services' => ChefService::count(),
            'active_services' => ChefService::where('is_active', true)->count(),
            'total_bookings' => \App\Models\Booking::when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))->count(),
            'total_earnings' => \App\Models\Booking::where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('total_amount'),
        ];
    }
    
    /**
     * Get services for export.
     */
    public function getServicesForExport(?Carbon $startDate): Collection
    {
        return ChefService::with(['chef.user:id,first_name,last_name'])
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
            ->withAvg(['ratings as average_rating'], 'rating')
            ->latest()
            ->get();
    }
}
