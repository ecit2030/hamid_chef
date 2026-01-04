<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\ChefVacation;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class VacationController extends Controller
{
    /**
     * Display a listing of chef's vacations.
     */
    public function index(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $this->authorize('viewAny', ChefVacation::class);

        // Get all vacations without pagination for inline interface
        $vacations = ChefVacation::where('chef_id', $chef->id)
            ->orderBy('date', 'asc')
            ->get();

        return Inertia::render('Chef/Vacations/Index', [
            'vacations' => ['data' => $vacations],
        ]);
    }

    /**
     * Show the form for creating a new vacation.
     */
    public function create(): Response
    {
        $this->authorize('create', ChefVacation::class);

        return Inertia::render('Chef/Vacations/Create');
    }

    /**
     * Store a newly created vacation.
     */
    public function store(Request $request)
    {
        $this->authorize('create', ChefVacation::class);

        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'note' => 'nullable|string|max:500',
        ]);

        // Check if vacation already exists for this date
        $exists = ChefVacation::where('chef_id', $chef->id)
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'يوجد إجازة مسجلة بالفعل في هذا التاريخ']);
        }

        // Check for active bookings on this date
        $bookings = Booking::where('chef_id', $chef->id)
            ->whereDate('date', $validated['date'])
            ->whereIn('booking_status', ['pending', 'accepted'])
            ->with(['customer:id,first_name,last_name', 'service:id,name'])
            ->get();

        if ($bookings->isNotEmpty()) {
            $bookingDetails = $bookings->map(function($booking) {
                $customerName = $booking->customer 
                    ? $booking->customer->first_name . ' ' . $booking->customer->last_name 
                    : 'عميل غير معروف';
                $serviceName = $booking->service?->name ?? 'خدمة غير معروفة';
                $time = Carbon::parse($booking->start_time)->format('h:i A');
                
                return "• حجز مع {$customerName} - {$serviceName} في الساعة {$time}";
            })->join("\n");

            $errorMessage = "لا يمكن إضافة إجازة في هذا التاريخ. لديك الحجوزات التالية:\n\n{$bookingDetails}\n\nيجب على العميل إلغاء أو تأجيل الحجز أولاً.";
            
            return back()->withErrors(['date' => $errorMessage]);
        }

        ChefVacation::create([
            'chef_id' => $chef->id,
            'date' => $validated['date'],
            'note' => $validated['note'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('chef.vacations.index')
            ->with('success', 'تم إضافة الإجازة بنجاح');
    }

    /**
     * Show the form for editing the specified vacation.
     */
    public function edit(ChefVacation $vacation): Response
    {
        $this->authorize('update', $vacation);

        return Inertia::render('Chef/Vacations/Edit', [
            'vacation' => $vacation,
        ]);
    }

    /**
     * Update the specified vacation.
     */
    public function update(Request $request, ChefVacation $vacation)
    {
        $this->authorize('update', $vacation);

        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $validated = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Check if vacation already exists for this date (excluding current)
        $exists = ChefVacation::where('chef_id', $chef->id)
            ->where('date', $validated['date'])
            ->where('id', '!=', $vacation->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'يوجد إجازة مسجلة بالفعل في هذا التاريخ']);
        }

        // Check for active bookings on this date (only if date is changing)
        if ($validated['date'] !== $vacation->date) {
            $bookings = Booking::where('chef_id', $chef->id)
                ->whereDate('date', $validated['date'])
                ->whereIn('booking_status', ['pending', 'accepted'])
                ->with(['customer:id,first_name,last_name', 'service:id,name'])
                ->get();

            if ($bookings->isNotEmpty()) {
                $bookingDetails = $bookings->map(function($booking) {
                    $customerName = $booking->customer 
                        ? $booking->customer->first_name . ' ' . $booking->customer->last_name 
                        : 'عميل غير معروف';
                    $serviceName = $booking->service?->name ?? 'خدمة غير معروفة';
                    $time = Carbon::parse($booking->start_time)->format('h:i A');
                    
                    return "• حجز مع {$customerName} - {$serviceName} في الساعة {$time}";
                })->join("\n");

                $errorMessage = "لا يمكن تعديل الإجازة إلى هذا التاريخ. لديك الحجوزات التالية:\n\n{$bookingDetails}\n\nيجب على العميل إلغاء أو تأجيل الحجز أولاً.";
                
                return back()->withErrors(['date' => $errorMessage]);
            }
        }

        $vacation->update($validated);

        return redirect()->route('chef.vacations.index')
            ->with('success', 'تم تحديث الإجازة بنجاح');
    }

    /**
     * Remove the specified vacation.
     */
    public function destroy(ChefVacation $vacation)
    {
        $this->authorize('delete', $vacation);

        $vacation->delete();

        return redirect()->route('chef.vacations.index')
            ->with('success', 'تم حذف الإجازة بنجاح');
    }
}
