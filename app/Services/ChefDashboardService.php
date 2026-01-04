<?php

namespace App\Services;

use App\DTOs\ChefDashboardDTO;
use App\Repositories\BookingRepository;
use App\Repositories\ChefServiceRepository;
use App\Repositories\ChefServiceRatingRepository;
use App\Models\Chef;
use App\Models\ChefWallet;
use Carbon\Carbon;

class ChefDashboardService
{
    protected BookingRepository $bookingRepository;
    protected ChefServiceRepository $serviceRepository;
    protected ChefServiceRatingRepository $ratingRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        ChefServiceRepository $serviceRepository,
        ChefServiceRatingRepository $ratingRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->serviceRepository = $serviceRepository;
        $this->ratingRepository = $ratingRepository;
    }

    /**
     * Get full dashboard data for a chef
     */
    public function getFullDashboard(int $chefId): ChefDashboardDTO
    {
        return new ChefDashboardDTO([
            'statistics' => $this->getStatistics($chefId),
            'earnings_chart' => $this->getEarningsChart($chefId, 6),
            'bookings_by_status' => $this->getBookingsByStatus($chefId),
            'upcoming_bookings' => $this->getUpcomingBookings($chefId),
            'recent_reviews' => $this->getRecentReviews($chefId, 5),
            'services_performance' => $this->getServicesPerformance($chefId),
            'wallet_balance' => $this->getWalletBalance($chefId),
        ]);
    }

    /**
     * Get statistics for a chef
     */
    public function getStatistics(int $chefId): array
    {
        $now = now();
        
        return [
            'total_bookings' => $this->countBookingsForChef($chefId),
            'monthly_bookings' => $this->countBookingsForChefInMonth($chefId, $now),
            'pending_bookings' => $this->countBookingsForChef($chefId, 'pending'),
            'confirmed_bookings' => $this->countBookingsForChef($chefId, 'accepted'),
            'completed_bookings' => $this->countBookingsForChef($chefId, 'completed'),
            'total_earnings' => $this->getTotalEarningsForChef($chefId),
            'monthly_earnings' => $this->getMonthlyEarningsForChef($chefId, $now),
            'average_rating' => $this->getAverageRatingForChef($chefId),
            'total_reviews' => $this->countReviewsForChef($chefId),
            'total_services' => $this->countServicesForChef($chefId),
            'active_services' => $this->countServicesForChef($chefId, true),
        ];
    }

    /**
     * Get earnings chart data for the last N months
     */
    public function getEarningsChart(int $chefId, int $months = 6): array
    {
        $chart = [];
        $now = now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $earnings = $this->getMonthlyEarningsForChef($chefId, $date);
            
            $chart[] = [
                'month' => $date->format('Y-m'),
                'month_name' => $date->translatedFormat('F'),
                'earnings' => $earnings,
            ];
        }

        return $chart;
    }

    /**
     * Get bookings count by status
     */
    public function getBookingsByStatus(int $chefId): array
    {
        return [
            'pending' => $this->countBookingsForChef($chefId, 'pending'),
            'confirmed' => $this->countBookingsForChef($chefId, 'confirmed'),
            'completed' => $this->countBookingsForChef($chefId, 'completed'),
            'cancelled' => $this->countCancelledBookingsForChef($chefId),
        ];
    }

    /**
     * Get upcoming bookings for a chef
     */
    public function getUpcomingBookings(int $chefId, int $limit = 5): array
    {
        $bookings = $this->bookingRepository->query(['customer', 'service'])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
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
                'service_name' => $booking->service?->name,
            ];
        })->toArray();
    }

    /**
     * Get recent reviews for a chef
     */
    public function getRecentReviews(int $chefId, int $limit = 5): array
    {
        $reviews = $this->ratingRepository->query(['customer', 'booking.service'])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->latest()
            ->limit($limit)
            ->get();

        return $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'review' => $review->review,
                'customer_name' => $review->customer?->name,
                'service_name' => $review->booking?->service?->name,
                'created_at' => $review->created_at->format('Y-m-d'),
            ];
        })->toArray();
    }

    /**
     * Get services performance for a chef
     */
    public function getServicesPerformance(int $chefId, int $limit = 5): array
    {
        $services = \App\Models\ChefService::where('chef_id', $chefId)
            ->where('is_active', true)
            ->withCount(['bookings', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->orderByDesc('bookings_count')
            ->limit($limit)
            ->get();

        return $services->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'bookings_count' => $service->bookings_count ?? 0,
                'ratings_count' => $service->ratings_count ?? 0,
                'average_rating' => round($service->ratings_avg_rating ?? 0, 1),
            ];
        })->toArray();
    }

    /**
     * Get wallet balance for a chef
     */
    public function getWalletBalance(int $chefId): float
    {
        $wallet = ChefWallet::where('chef_id', $chefId)->first();
        return (float) ($wallet?->balance ?? 0);
    }

    // Private helper methods

    private function countBookingsForChef(int $chefId, ?string $status = null): int
    {
        $query = $this->bookingRepository->query([])
            ->where('chef_id', $chefId)
            ->where('is_active', true);

        if ($status) {
            $query->where('booking_status', $status);
        }

        return $query->count();
    }

    private function countBookingsForChefInMonth(int $chefId, Carbon $date): int
    {
        return $this->bookingRepository->query([])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->count();
    }

    private function countCancelledBookingsForChef(int $chefId): int
    {
        return $this->bookingRepository->query([])
            ->where('chef_id', $chefId)
            ->whereIn('booking_status', ['cancelled_by_customer', 'cancelled_by_chef', 'rejected'])
            ->count();
    }

    private function getTotalEarningsForChef(int $chefId): float
    {
        return (float) $this->bookingRepository->query([])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->where('booking_status', 'completed')
            ->sum('total_amount');
    }

    private function getMonthlyEarningsForChef(int $chefId, Carbon $date): float
    {
        return (float) $this->bookingRepository->query([])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->where('booking_status', 'completed')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('total_amount');
    }

    private function getAverageRatingForChef(int $chefId): float
    {
        $avg = $this->ratingRepository->query([])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->avg('rating');

        return round((float) ($avg ?? 0), 1);
    }

    private function countReviewsForChef(int $chefId): int
    {
        return $this->ratingRepository->query([])
            ->where('chef_id', $chefId)
            ->where('is_active', true)
            ->count();
    }

    private function countServicesForChef(int $chefId, ?bool $activeOnly = null): int
    {
        $query = \App\Models\ChefService::where('chef_id', $chefId);

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->count();
    }
}
