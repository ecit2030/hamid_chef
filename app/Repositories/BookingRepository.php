<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Repositories\Eloquent\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository extends BaseRepository
{
    protected array $defaultWith = [
        'customer',
        'chef',
        'chef.ratings',
        'service',
        'address',
        'transactions',
        'rating',
        'discountCode',
    ];

    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    /**
     * Check if using SQLite database
     */
    protected function isSqlite(): bool
    {
        return DB::connection()->getDriverName() === 'sqlite';
    }

    /**
     * Get database-agnostic datetime concatenation expression
     */
    protected function getDateTimeConcat(): string
    {
        if ($this->isSqlite()) {
            return "datetime(date || ' ' || start_time)";
        }
        return "CONCAT(date, ' ', start_time)";
    }

    /**
     * Get database-agnostic datetime addition expression
     */
    protected function getDateTimeAddHours(string $baseExpr, string $hoursColumn): string
    {
        if ($this->isSqlite()) {
            return "datetime({$baseExpr}, '+' || {$hoursColumn} || ' hours')";
        }
        return "DATE_ADD({$baseExpr}, INTERVAL {$hoursColumn} HOUR)";
    }

    /**
     * Find bookings that conflict with the given time range
     */
    public function findConflictingBookings(
        int $chefId,
        Carbon $date,
        Carbon $startDateTime,
        Carbon $endDateTime,
        ?int $excludeBookingId = null
    ): Collection {
        $dateTimeConcat = $this->getDateTimeConcat();
        $dateTimeAddHours = $this->getDateTimeAddHours($dateTimeConcat, 'hours_count');

        $query = $this->model
            ->forChef($chefId)
            ->active()
            ->onDate($date)
            ->where(function ($q) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                // Check for overlapping time ranges
                $q->where(function ($subQ) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                    // New booking starts during existing booking
                    $subQ->whereRaw("{$dateTimeConcat} <= ?", [$startDateTime->format('Y-m-d H:i:s')])
                         ->whereRaw("{$dateTimeAddHours} > ?", [$startDateTime->format('Y-m-d H:i:s')]);
                })
                ->orWhere(function ($subQ) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                    // New booking ends during existing booking
                    $subQ->whereRaw("{$dateTimeConcat} < ?", [$endDateTime->format('Y-m-d H:i:s')])
                         ->whereRaw("{$dateTimeAddHours} >= ?", [$endDateTime->format('Y-m-d H:i:s')]);
                })
                ->orWhere(function ($subQ) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                    // New booking completely contains existing booking
                    $subQ->whereRaw("{$dateTimeConcat} >= ?", [$startDateTime->format('Y-m-d H:i:s')])
                         ->whereRaw("{$dateTimeAddHours} <= ?", [$endDateTime->format('Y-m-d H:i:s')]);
                })
                ->orWhere(function ($subQ) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                    // Existing booking completely contains new booking
                    $subQ->whereRaw("{$dateTimeConcat} <= ?", [$startDateTime->format('Y-m-d H:i:s')])
                         ->whereRaw("{$dateTimeAddHours} >= ?", [$endDateTime->format('Y-m-d H:i:s')]);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->get();
    }

    /**
     * Lock chef bookings for a specific date to prevent race conditions
     */
    public function lockChefBookingsForDate(int $chefId, Carbon $date): void
    {
        $this->model
            ->forChef($chefId)
            ->onDate($date)
            ->active()
            ->lockForUpdate()
            ->get();
    }

    /**
     * Find bookings within a specific time range for gap validation
     */
    public function findBookingsWithinTimeRange(
        int $chefId,
        Carbon $startDateTime,
        Carbon $endDateTime,
        ?int $excludeBookingId = null
    ): Collection {
        $dateTimeConcat = $this->getDateTimeConcat();
        $dateTimeAddHours = $this->getDateTimeAddHours($dateTimeConcat, 'hours_count');

        $query = $this->model
            ->forChef($chefId)
            ->active()
            ->inDateRange($startDateTime, $endDateTime)
            ->where(function ($q) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                // Find bookings that are within the search range
                $q->whereRaw("{$dateTimeConcat} BETWEEN ? AND ?", [
                    $startDateTime->format('Y-m-d H:i:s'),
                    $endDateTime->format('Y-m-d H:i:s')
                ])
                ->orWhereRaw("{$dateTimeAddHours} BETWEEN ? AND ?", [
                    $startDateTime->format('Y-m-d H:i:s'),
                    $endDateTime->format('Y-m-d H:i:s')
                ])
                ->orWhere(function ($subQ) use ($startDateTime, $endDateTime, $dateTimeConcat, $dateTimeAddHours) {
                    // Bookings that span across the search range
                    $subQ->whereRaw("{$dateTimeConcat} <= ?", [$startDateTime->format('Y-m-d H:i:s')])
                         ->whereRaw("{$dateTimeAddHours} >= ?", [$endDateTime->format('Y-m-d H:i:s')]);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->get();
    }

    /**
     * Get chef bookings for a specific date range with optimized query
     */
    public function getChefBookingsInRange(int $chefId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->model
            ->forChef($chefId)
            ->active()
            ->inDateRange($startDate, $endDate)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Check if chef has any active bookings on a specific date
     */
    public function hasActiveBookingsOnDate(int $chefId, Carbon $date): bool
    {
        return $this->model
            ->forChef($chefId)
            ->active()
            ->onDate($date)
            ->exists();
    }

    /**
     * Get bookings for report with pagination.
     */
    public function getBookingsForReport(?Carbon $startDate, ?string $status, int $perPage = 15, ?Carbon $endDate = null)
    {
        return Booking::with(['customer:id,first_name,last_name,email,phone_number', 'chef.user:id,first_name,last_name', 'service:id,name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->when($status, fn($q) => $q->where('booking_status', $status))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get bookings statistics.
     */
    public function getBookingsStats(?Carbon $startDate, ?string $status, ?Carbon $endDate = null): array
    {
        $query = Booking::query()
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
     * Get bookings for export.
     */
    public function getBookingsForExport(?Carbon $startDate, ?string $status, ?Carbon $endDate = null): Collection
    {
        return Booking::with(['customer:id,first_name,last_name,email,phone_number', 'chef.user:id,first_name,last_name', 'service:id,name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->when($status, fn($q) => $q->where('booking_status', $status))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get daily earnings.
     */
    public function getDailyEarnings(?Carbon $startDate): Collection
    {
        return Booking::where('booking_status', 'completed')
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
}
