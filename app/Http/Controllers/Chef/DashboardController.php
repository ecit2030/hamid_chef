<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Services\ChefDashboardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected ChefDashboardService $dashboardService;

    public function __construct(ChefDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the chef dashboard.
     */
    public function index(): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        // Get full dashboard data using the service
        $dashboardData = $this->dashboardService->getFullDashboard($chef->id);

        return Inertia::render('Chef/Dashboard', [
            'chef' => [
                'id' => $chef->id,
                'name' => $chef->name,
                'logo' => $chef->logo,
                'rating_avg' => $chef->rating_avg,
            ],
            'statistics' => $dashboardData->toStatisticsArray(),
            'earnings_chart' => $dashboardData->earnings_chart,
            'bookings_by_status' => $dashboardData->bookings_by_status,
            'upcoming_bookings' => $dashboardData->upcoming_bookings,
            'recent_reviews' => $dashboardData->recent_reviews,
            'services_performance' => $dashboardData->services_performance,
        ]);
    }
}
