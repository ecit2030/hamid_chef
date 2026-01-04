<?php

namespace App\Services;

use App\Models\Chef;
use App\Repositories\ChefRepository;

class ChefDetailsService
{
    protected ChefRepository $chefRepository;

    public function __construct(ChefRepository $chefRepository)
    {
        $this->chefRepository = $chefRepository;
    }

    /**
     * Get chef details with all related data for admin panel
     *
     * @param int $chefId
     * @return array
     */
    public function getChefDetails(int $chefId): array
    {
        $chef = $this->chefRepository->findOrFail($chefId, [
            'categories',
            'gallery' => function($query) {
                $query->where('is_active', true)->orderBy('created_at');
            }
        ]);

        return [
            'chef' => $chef,
            'rating_avg' => $this->calculateAverageRating($chef),
            'working_hours' => $this->getWorkingHours($chef),
            'vacations' => $this->getVacations($chef),
            'services' => $this->getServices($chef),
            'bookings' => $this->getRecentBookings($chef),
        ];
    }

    /**
     * Calculate average rating from all service ratings
     *
     * @param Chef $chef
     * @return float
     */
    protected function calculateAverageRating(Chef $chef): float
    {
        $averageRating = $chef->ratings()
            ->where('chef_service_ratings.is_active', true)
            ->avg('rating');

        return $averageRating ? round($averageRating, 2) : 0;
    }

    /**
     * Get working hours grouped by day
     *
     * @param Chef $chef
     * @return array
     */
    protected function getWorkingHours(Chef $chef): array
    {
        // Map day numbers to day names
        $dayMap = [
            0 => 'saturday',
            1 => 'sunday',
            2 => 'monday',
            3 => 'tuesday',
            4 => 'wednesday',
            5 => 'thursday',
            6 => 'friday',
        ];
        
        $grouped = $chef->workingHours()
            ->orderByRaw("FIELD(day_of_week, 0, 1, 2, 3, 4, 5, 6)")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');
        
        // Convert to array format that preserves keys in JSON
        $result = [];
        foreach ($grouped as $dayNumber => $slots) {
            $dayName = $dayMap[$dayNumber] ?? 'saturday';
            $result[$dayName] = $slots->map(function($slot) use ($dayMap) {
                return [
                    'id' => $slot->id,
                    'day_of_week' => $dayMap[$slot->day_of_week] ?? 'saturday',
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'is_active' => $slot->is_active,
                ];
            })->values()->toArray();
        }
        
        return $result;
    }

    /**
     * Get chef vacations
     *
     * @param Chef $chef
     * @return array
     */
    protected function getVacations(Chef $chef): array
    {
        return $chef->vacations()
            ->orderBy('date', 'desc')
            ->get()
            ->map(function($vacation) {
                return [
                    'id' => $vacation->id,
                    'date' => $vacation->date,
                    'note' => $vacation->note,
                    'is_active' => $vacation->is_active,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get chef services with statistics
     *
     * @param Chef $chef
     * @return array
     */
    protected function getServices(Chef $chef): array
    {
        return $chef->services()
            ->withCount(['bookings as total_bookings'])
            ->withCount(['bookings as completed_bookings' => function($q) {
                $q->where('booking_status', 'completed');
            }])
            ->withAvg(['ratings as average_rating'], 'rating')
            ->get()
            ->map(function($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                    'min_hours' => $service->min_hours,
                    'rest_hours_required' => $service->rest_hours_required,
                    'is_active' => $service->is_active,
                    'total_bookings' => $service->total_bookings ?? 0,
                    'completed_bookings' => $service->completed_bookings ?? 0,
                    'average_rating' => $service->average_rating ? round($service->average_rating, 1) : 0,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get recent bookings
     *
     * @param Chef $chef
     * @param int $limit
     * @return array
     */
    protected function getRecentBookings(Chef $chef, int $limit = 10): array
    {
        return $chef->bookings()
            ->with(['customer:id,first_name,last_name', 'service:id,name'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'customer_name' => $booking->customer 
                        ? $booking->customer->first_name . ' ' . $booking->customer->last_name 
                        : '-',
                    'service_name' => $booking->service?->name ?? '-',
                    'date' => $booking->date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'hours_count' => $booking->hours_count,
                    'total_amount' => $booking->total_amount,
                    'booking_status' => $booking->booking_status,
                    'created_at' => $booking->created_at->format('Y-m-d'),
                ];
            })
            ->values()
            ->toArray();
    }
}
