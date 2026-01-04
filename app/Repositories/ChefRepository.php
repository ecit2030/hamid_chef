<?php

namespace App\Repositories;

use App\Models\Chef;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class ChefRepository extends BaseRepository
{
    protected array $defaultWith = [
        'user:id,first_name,last_name,email,phone_number',
        'services:id,chef_id,name,slug,service_type',
        'wallet:id,chef_id,balance',
        'governorate:id,name_ar,name_en',
        'district:id,name_ar,name_en',
        'area:id,name_ar,name_en',
        'categories:id,name,slug',
    ];

    public function __construct(Chef $model)
    {
        parent::__construct($model);
    }

    /**
     * Return a query for chefs that belong to the given category (cuisine_id on pivot)
     *
     * @param int $categoryId
     * @param array|null $with
     * @return Builder
     */
    public function queryByCategory(int $categoryId, ?array $with = null): Builder
    {
        return $this->makeQuery($with)->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }
    
    /**
     * Get chefs for report with pagination.
     */
    public function getChefsForReport(?\Carbon\Carbon $startDate, int $perPage = 15)
    {
        return Chef::with('user:id,first_name,last_name,email,phone_number')
            ->withCount(['bookings' => function($q) use ($startDate) {
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
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get chefs statistics.
     */
    public function getChefsStats(?\Carbon\Carbon $startDate): array
    {
        return [
            'total_chefs' => Chef::count(),
            'active_chefs' => Chef::where('is_active', true)->count(),
            'new_chefs' => Chef::when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))->count(),
            'total_bookings' => \App\Models\Booking::when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))->count(),
            'total_earnings' => \App\Models\Booking::where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('total_amount'),
        ];
    }
    
    /**
     * Get chefs for export.
     */
    public function getChefsForExport(?\Carbon\Carbon $startDate)
    {
        return Chef::with('user:id,first_name,last_name,email,phone_number')
            ->withCount(['bookings' => function($q) use ($startDate) {
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
            ->latest()
            ->get();
    }


}
