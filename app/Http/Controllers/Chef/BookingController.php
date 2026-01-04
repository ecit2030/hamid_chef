<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use App\DTOs\BookingDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of chef's bookings.
     */
    public function index(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');

        $query = Booking::with(['customer:id,first_name,last_name,email,phone_number', 'service:id,name', 'address'])
            ->where('chef_id', $chef->id)
            ->latest();

        if ($status && $status !== 'all') {
            $query->where('booking_status', $status);
        }

        $bookings = $query->paginate($perPage);

        $bookings->getCollection()->transform(function ($booking) {
            return BookingDTO::fromModel($booking)->toIndexArray();
        });

        return Inertia::render('Chef/Bookings/Index', [
            'bookings' => $bookings,
            'filters' => [
                'status' => $status,
            ],
        ]);
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        // Ensure the booking belongs to this chef
        if ($booking->chef_id !== $chef->id) {
            abort(403);
        }

        $booking->load([
            'customer:id,first_name,last_name,email,phone_number',
            'service:id,name,description',
            'address',
            'transactions',
        ]);

        return Inertia::render('Chef/Bookings/Show', [
            'booking' => BookingDTO::fromModel($booking)->toArray(),
        ]);
    }

    /**
     * Accept a pending booking.
     */
    public function accept(int $id)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $booking = Booking::findOrFail($id);
        
        // Ensure the booking belongs to this chef
        if ($booking->chef_id !== $chef->id) {
            abort(403);
        }

        $this->bookingService->accept($id);

        return back()->with('success', __('booking.accepted_successfully'));
    }

    /**
     * Reject a pending booking.
     */
    public function reject(Request $request, int $id)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $booking = Booking::findOrFail($id);
        
        // Ensure the booking belongs to this chef
        if ($booking->chef_id !== $chef->id) {
            abort(403);
        }

        $reason = $request->input('reason', '');
        $this->bookingService->reject($id, $reason);

        return back()->with('success', __('booking.rejected_successfully'));
    }

    /**
     * Mark a booking as completed.
     */
    public function complete(int $id)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;
        
        $booking = Booking::findOrFail($id);
        
        // Ensure the booking belongs to this chef
        if ($booking->chef_id !== $chef->id) {
            abort(403);
        }

        $this->bookingService->complete($id);

        return back()->with('success', __('booking.completed_successfully'));
    }
}
