<?php

namespace App\Services;

use App\DTOs\AdminDashboardDTO;
use App\Repositories\BookingRepository;
use App\Repositories\ChefRepository;
use App\Repositories\UserRepository;
use App\Models\Kyc;
use Carbon\Carbon;

class AdminDashboardService
{
    protected BookingRepository $bookingRepository;
    protected ChefRepository $chefRepository;
    protected UserRepository $userRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        ChefRepository $chefRepository,
        UserRepository $userRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->chefRepository = $chefRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get full dashboard data for admin
     */
    public function getFullDashboard(): AdminDashboardDTO
    {
        return new AdminDashboardDTO([
            'statistics' => $this->getStatistics(),
            'bookings_chart' => $this->getBookingsChart(7),
            'revenue_chart' => $this->getRevenueChart(6),
            'top_chefs' => $this->getTopChefs(5),
            'recent_bookings' => $this->getRecentBookings(10),
            'bookings_by_status' => $this->getBookingsByStatus(),
        ]);
    }

    /**
     * Get all statistics for admin dashboard
     */
    public function getStatistics(): array
    {
        $now = now();
        $lastMonth = $now->copy()->subMonth();
        
        return [
            // User statistics
            'total_users' => $this->countUsers(),
            'total_customers' => $this->countUsers('customer'),
            'total_chefs' => $this->countChefs(),
            'active_chefs' => $this->countChefs(true),
            'users_growth_percentage' => $this->calculateUsersGrowth($now, $lastMonth),
            
            // Booking statistics
            'total_bookings' => $this->countBookings(),
            'monthly_bookings' => $this->countBookingsInMonth($now),
            'pending_bookings' => $this->countBookings('pending'),
            'completed_bookings' => $this->countBookings('completed'),
            
            // Revenue statistics
            'total_revenue' => $this->getTotalRevenue(),
            'monthly_revenue' => $this->getMonthlyRevenue($now),
            'revenue_growth_percentage' => $this->calculateRevenueGrowth($now, $lastMonth),
            
            // KYC statistics
            'pending_kyc_requests' => $this->countPendingKyc(),
        ];
    }


    /**
     * Get bookings chart data for the last N days
     */
    public function getBookingsChart(int $days = 7): array
    {
        $chart = [];
        $now = now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $count = $this->countBookingsOnDate($date);
            
            $chart[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('D'),
                'bookings' => $count,
            ];
        }

        return $chart;
    }

    /**
     * Get revenue chart data for the last N months
     */
    public function getRevenueChart(int $months = 6): array
    {
        $chart = [];
        $now = now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $revenue = $this->getMonthlyRevenue($date);
            
            $chart[] = [
                'month' => $date->format('Y-m'),
                'month_name' => $date->translatedFormat('F'),
                'revenue' => $revenue,
            ];
        }

        return $chart;
    }

    /**
     * Get top rated chefs
     */
    public function getTopChefs(int $limit = 5): array
    {
        $chefs = $this->chefRepository->query(['user'])
            ->where('is_active', true)
            ->withCount(['bookings', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->limit($limit)
            ->get();

        return $chefs->map(function ($chef) {
            return [
                'id' => $chef->id,
                'name' => $chef->name,
                'logo' => $chef->logo ? asset('storage/' . $chef->logo) : null,
                'bookings_count' => $chef->bookings_count ?? 0,
                'ratings_count' => $chef->ratings_count ?? 0,
                'average_rating' => round($chef->ratings_avg_rating ?? 0, 1),
            ];
        })->toArray();
    }

    /**
     * Get recent bookings
     */
    public function getRecentBookings(int $limit = 10): array
    {
        $bookings = $this->bookingRepository->query(['customer', 'chef', 'service'])
            ->where('is_active', true)
            ->latest()
            ->limit($limit)
            ->get();

        return $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'date' => $booking->date->format('Y-m-d'),
                'start_time' => $booking->start_time->format('H:i'),
                'hours_count' => $booking->hours_count,
                'status' => $booking->booking_status,
                'total_amount' => (float) $booking->total_amount,
                'customer_name' => $booking->customer?->name,
                'chef_name' => $booking->chef?->name,
                'service_name' => $booking->service?->name,
                'created_at' => $booking->created_at->format('Y-m-d H:i'),
            ];
        })->toArray();
    }

    /**
     * Get bookings count by status
     */
    public function getBookingsByStatus(): array
    {
        return [
            'pending' => $this->countBookings('pending'),
            'accepted' => $this->countBookings('accepted'),
            'completed' => $this->countBookings('completed'),
            'cancelled' => $this->countCancelledBookings(),
            'rejected' => $this->countBookings('rejected'),
        ];
    }

    // Private helper methods

    private function countUsers(?string $type = null): int
    {
        $query = $this->userRepository->query([])
            ->where('is_active', true);

        if ($type) {
            $query->where('user_type', $type);
        }

        return $query->count();
    }

    private function countChefs(?bool $activeOnly = null): int
    {
        $query = $this->chefRepository->query([]);

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->count();
    }

    private function calculateUsersGrowth(Carbon $currentMonth, Carbon $lastMonth): float
    {
        $currentCount = $this->userRepository->query([])
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();

        $lastCount = $this->userRepository->query([])
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        if ($lastCount === 0) {
            return $currentCount > 0 ? 100 : 0;
        }

        return round((($currentCount - $lastCount) / $lastCount) * 100, 1);
    }

    private function countBookings(?string $status = null): int
    {
        $query = $this->bookingRepository->query([])
            ->where('is_active', true);

        if ($status) {
            $query->where('booking_status', $status);
        }

        return $query->count();
    }

    private function countBookingsInMonth(Carbon $date): int
    {
        return $this->bookingRepository->query([])
            ->where('is_active', true)
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->count();
    }

    private function countBookingsOnDate(Carbon $date): int
    {
        return $this->bookingRepository->query([])
            ->where('is_active', true)
            ->whereDate('date', $date->toDateString())
            ->count();
    }

    private function countCancelledBookings(): int
    {
        return $this->bookingRepository->query([])
            ->whereIn('booking_status', ['cancelled_by_customer', 'cancelled_by_chef'])
            ->count();
    }

    private function getTotalRevenue(): float
    {
        return (float) $this->bookingRepository->query([])
            ->where('is_active', true)
            ->where('booking_status', 'completed')
            ->sum('total_amount');
    }

    private function getMonthlyRevenue(Carbon $date): float
    {
        return (float) $this->bookingRepository->query([])
            ->where('is_active', true)
            ->where('booking_status', 'completed')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('total_amount');
    }

    private function calculateRevenueGrowth(Carbon $currentMonth, Carbon $lastMonth): float
    {
        $currentRevenue = $this->getMonthlyRevenue($currentMonth);
        $lastRevenue = $this->getMonthlyRevenue($lastMonth);

        if ($lastRevenue == 0) {
            return $currentRevenue > 0 ? 100 : 0;
        }

        return round((($currentRevenue - $lastRevenue) / $lastRevenue) * 100, 1);
    }

    private function countPendingKyc(): int
    {
        return Kyc::where('status', 'pending')->count();
    }
}
