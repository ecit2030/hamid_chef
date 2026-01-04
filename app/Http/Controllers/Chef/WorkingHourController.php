<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\ChefWorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class WorkingHourController extends Controller
{
    /**
     * Display chef's working hours.
     */
    public function index(): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $this->authorize('viewAny', ChefWorkingHour::class);

        $workingHours = ChefWorkingHour::where('chef_id', $chef->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // If no working hours exist, create default ones
        if ($workingHours->isEmpty()) {
            $this->createDefaultWorkingHours($chef->id);
            $workingHours = ChefWorkingHour::where('chef_id', $chef->id)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();
        }

        // Group by day_of_week
        $groupedHours = $workingHours->groupBy('day_of_week')->map(function ($slots) {
            return $slots->values();
        });

        return Inertia::render('Chef/WorkingHours/Index', [
            'workingHours' => $groupedHours,
            'days' => [
                0 => __('days.sunday'),
                1 => __('days.monday'),
                2 => __('days.tuesday'),
                3 => __('days.wednesday'),
                4 => __('days.thursday'),
                5 => __('days.friday'),
                6 => __('days.saturday'),
            ],
        ]);
    }

    /**
     * Update chef's working hours.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $validated = $request->validate([
            'working_hours' => 'required|array',
            'working_hours.*.id' => 'nullable|integer|exists:chef_working_hours,id',
            'working_hours.*.day_of_week' => 'required|integer|between:0,6',
            'working_hours.*.start_time' => 'nullable|date_format:H:i',
            'working_hours.*.end_time' => 'nullable|date_format:H:i|after:working_hours.*.start_time',
            'working_hours.*.is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($chef, $validated) {
            // Get all existing IDs from the request
            $requestIds = collect($validated['working_hours'])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete slots that are not in the request (were removed by user)
            ChefWorkingHour::where('chef_id', $chef->id)
                ->whereNotIn('id', $requestIds)
                ->delete();

            // Update or create slots
            foreach ($validated['working_hours'] as $hour) {
                if (isset($hour['id'])) {
                    // Update existing slot
                    $workingHour = ChefWorkingHour::where('chef_id', $chef->id)
                        ->where('id', $hour['id'])
                        ->first();

                    if ($workingHour) {
                        $this->authorize('update', $workingHour);
                        
                        $workingHour->update([
                            'start_time' => $hour['start_time'] ?? null,
                            'end_time' => $hour['end_time'] ?? null,
                            'is_active' => $hour['is_active'] ?? false,
                        ]);
                    }
                } else {
                    // Create new slot
                    $this->authorize('create', ChefWorkingHour::class);
                    
                    ChefWorkingHour::create([
                        'chef_id' => $chef->id,
                        'day_of_week' => $hour['day_of_week'],
                        'start_time' => $hour['start_time'] ?? null,
                        'end_time' => $hour['end_time'] ?? null,
                        'is_active' => $hour['is_active'] ?? false,
                    ]);
                }
            }
        });

        return back()->with('success', 'تم تحديث ساعات العمل بنجاح');
    }

    /**
     * Create default working hours for a chef.
     */
    private function createDefaultWorkingHours(int $chefId): void
    {
        $defaultHours = [
            ['day_of_week' => 0, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => false], // Sunday
            ['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],  // Monday
            ['day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],  // Tuesday
            ['day_of_week' => 3, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],  // Wednesday
            ['day_of_week' => 4, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],  // Thursday
            ['day_of_week' => 5, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => false], // Friday
            ['day_of_week' => 6, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => false], // Saturday
        ];

        foreach ($defaultHours as $hour) {
            ChefWorkingHour::create(array_merge($hour, ['chef_id' => $chefId]));
        }
    }
}
