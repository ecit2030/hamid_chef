<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\ChefWorkingHour;
use App\Repositories\ChefWorkingHourRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChefWorkingHourService
{
    public function __construct(protected ChefWorkingHourRepository $repo)
    {
    }

    protected function currentChefId(): int
    {
        $chefId = optional(Auth::user()->chef)->id;
        if (!$chefId) {
            throw ValidationException::withMessages([
                'chef' => ['يجب إنشاء ملف الطاهي قبل إدارة جدول العمل.']
            ]);
        }
        return (int) $chefId;
    }

    public function getForCurrentChef()
    {
        $chefId = $this->currentChefId();
        return $this->repo->forChef($chefId)->get();
    }

    /**
     * Replace the weekly schedule for current chef.
     * @param array $schedule e.g. [ ['day_of_week'=>1,'ranges'=>[['start_time'=>'09:00','end_time'=>'12:00']]] ]
     */
    public function replaceForCurrentChef(array $schedule)
    {
        $chefId = $this->currentChefId();

        // Flatten schedule to records
        $records = [];
        foreach ($schedule as $day) {
            $dayOfWeek = (int) ($day['day_of_week'] ?? -1);
            $ranges = $day['ranges'] ?? [];
            foreach ($ranges as $r) {
                $records[] = [
                    'day_of_week' => $dayOfWeek,
                    'start_time' => $r['start_time'],
                    'end_time' => $r['end_time'],
                ];
            }
        }

        DB::transaction(function () use ($chefId, $records) {
            $this->repo->deleteByChef($chefId);
            $this->repo->bulkInsert($chefId, $records);
        });

        return $this->repo->forChef($chefId)->get();
    }

    /** Create single working hour interval for current chef */
    public function createForCurrentChef(array $data)
    {
        $chefId = $this->currentChefId();
        $day = (int) $data['day_of_week'];
        $start = $data['start_time'];
        $end = $data['end_time'];

        // Check overlaps on the same day
        $intervals = $this->repo->getDayIntervals($chefId, $day);
        foreach ($intervals as $iv) {
            if ($start < $iv->end_time && $end > $iv->start_time) {
                throw ValidationException::withMessages([
                    'ranges' => ['الفترة الزمنية تتقاطع مع فترة موجودة لنفس اليوم']
                ]);
            }
        }

        return $this->repo->create([
            'chef_id' => $chefId,
            'day_of_week' => $day,
            'start_time' => $start,
            'end_time' => $end,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /** Update single working hour interval for current chef */
    public function updateForCurrentChef(int $id, array $data)
    {
        $chefId = $this->currentChefId();
        $record = $this->repo->findForChefById($id, $chefId);

        $day = (int) ($data['day_of_week'] ?? $record->day_of_week);
        $start = $data['start_time'] ?? $record->start_time;
        $end = $data['end_time'] ?? $record->end_time;

        // Check overlaps excluding current record
        $intervals = $this->repo->getDayIntervals($chefId, $day);
        foreach ($intervals as $iv) {
            if ($iv->id === $record->id) continue;
            if ($start < $iv->end_time && $end > $iv->start_time) {
                throw ValidationException::withMessages([
                    'ranges' => ['الفترة الزمنية تتقاطع مع فترة موجودة لنفس اليوم']
                ]);
            }
        }

        $attributes = [
            'day_of_week' => $day,
            'start_time' => $start,
            'end_time' => $end,
        ];
        if (array_key_exists('is_active', $data)) {
            $attributes['is_active'] = (bool) $data['is_active'];
        }

        return $this->repo->updateModel($record, $attributes);
    }

    /** Delete single working hour interval for current chef */
    public function deleteForCurrentChef(int $id): bool
    {
        $chefId = $this->currentChefId();
        $this->repo->findForChefById($id, $chefId); // ensures ownership
        return $this->repo->delete($id);
    }

    /**
     * Get off-hours (non-working hours) for the current chef
     * Returns the time slots when the chef is NOT working
     */
    public function getOffHoursForCurrentChef(array $params = []): array
    {
        $chefId = $this->currentChefId();
        $dayOfWeek = $params['day_of_week'] ?? null;

        $result = [];
        $daysToProcess = $dayOfWeek !== null ? [(int) $dayOfWeek] : range(0, 6);

        foreach ($daysToProcess as $day) {
            $intervals = $this->repo->getDayIntervals($chefId, $day);
            $offHours = $this->calculateOffHours($intervals);
            
            $result[] = [
                'day_of_week' => $day,
                'day_name' => $this->getDayName($day),
                'off_hours' => $offHours,
            ];
        }

        return $result;
    }

    /**
     * Calculate off-hours from working intervals
     * Returns time slots when chef is NOT working (24-hour day minus working hours)
     */
    protected function calculateOffHours($workingIntervals): array
    {
        if ($workingIntervals->isEmpty()) {
            // No working hours defined = entire day is off
            return [
                ['start_time' => '00:00', 'end_time' => '23:59']
            ];
        }

        // Sort intervals by start time
        $sorted = $workingIntervals->sortBy('start_time')->values();
        
        $offHours = [];
        $dayStart = 0; // 00:00 in minutes
        $dayEnd = 24 * 60 - 1; // 23:59 in minutes

        $currentTime = $dayStart;

        foreach ($sorted as $interval) {
            $workStart = $this->timeToMinutes($interval->start_time);
            $workEnd = $this->timeToMinutes($interval->end_time);

            // If there's a gap before this working interval
            if ($currentTime < $workStart) {
                $offHours[] = [
                    'start_time' => $this->minutesToTime($currentTime),
                    'end_time' => $this->minutesToTime($workStart - 1),
                ];
            }

            $currentTime = max($currentTime, $workEnd);
        }

        // If there's time remaining after the last working interval
        if ($currentTime < $dayEnd) {
            $offHours[] = [
                'start_time' => $this->minutesToTime($currentTime),
                'end_time' => $this->minutesToTime($dayEnd),
            ];
        }

        return $offHours;
    }

    /**
     * Convert time string (HH:MM) to minutes from midnight
     */
    protected function timeToMinutes(string $time): int
    {
        $parts = explode(':', $time);
        return (int) $parts[0] * 60 + (int) $parts[1];
    }

    /**
     * Convert minutes from midnight to time string (HH:MM)
     */
    protected function minutesToTime(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    /**
     * Get day name in Arabic
     */
    protected function getDayName(int $dayOfWeek): string
    {
        $days = [
            0 => 'الأحد',
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];
        return $days[$dayOfWeek] ?? '';
    }
}
