<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email, ?array $with = null): ?User
    {
        return $this->makeQuery($with)->where('email', $email)->first();
    }

    /**
     * Find chef user by email (user_type = 'chef')
     */
    public function findChefByEmail(string $email, ?array $with = null): ?User
    {
        return $this->makeQuery($with)
            ->where('email', $email)
            ->where('user_type', 'chef')
            ->first();
    }

    /**
     * Count users by type
     */
    public function countByType(string $userType): int
    {
        return $this->model->where('user_type', $userType)->count();
    }

    /**
     * Calculate growth percentage for a user type over given days
     */
    public function growthPercentage(string $userType, int $days = 30): float
    {
        $now = now();
        $periodStart = $now->copy()->subDays($days);
        $previousPeriodStart = $periodStart->copy()->subDays($days);

        $currentCount = $this->model
            ->where('user_type', $userType)
            ->where('created_at', '>=', $periodStart)
            ->count();

        $previousCount = $this->model
            ->where('user_type', $userType)
            ->where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $periodStart)
            ->count();

        if ($previousCount === 0) {
            return $currentCount > 0 ? 100.0 : 0.0;
        }

        return round((($currentCount - $previousCount) / $previousCount) * 100, 2);
    }

    /**
     * Get users by type with pagination
     */
    public function paginateByType(string $userType, int $perPage = 15, ?array $with = null)
    {
        return $this->makeQuery($with)
            ->where('user_type', $userType)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get customers for report with pagination.
     */
    public function getCustomersForReport(?\Carbon\Carbon $startDate, int $perPage = 15)
    {
        return User::where('user_type', 'customer')
            ->withCount(['bookings' => function($q) use ($startDate) {
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
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get customers statistics.
     */
    public function getCustomersStats(?\Carbon\Carbon $startDate): array
    {
        return [
            'total_customers' => User::where('user_type', 'customer')->count(),
            'new_customers' => User::where('user_type', 'customer')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->count(),
            'active_customers' => User::where('user_type', 'customer')
                ->whereHas('bookings', function($q) use ($startDate) {
                    if ($startDate) {
                        $q->where('created_at', '>=', $startDate);
                    }
                })
                ->count(),
            'total_bookings' => \App\Models\Booking::when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))->count(),
            'total_revenue' => \App\Models\Booking::where('booking_status', 'completed')
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                ->sum('total_amount'),
        ];
    }
    
    /**
     * Get customers for export.
     */
    public function getCustomersForExport(?\Carbon\Carbon $startDate)
    {
        return User::where('user_type', 'customer')
            ->withCount(['bookings' => function($q) use ($startDate) {
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
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->latest()
            ->get();
    }
}
