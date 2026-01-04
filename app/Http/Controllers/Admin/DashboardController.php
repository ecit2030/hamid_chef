<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected AdminDashboardService $dashboardService;

    public function __construct(AdminDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the admin dashboard
     */
    public function index(): Response
    {
        $dashboard = $this->dashboardService->getFullDashboard();

        return Inertia::render('Dashboard', [
            'dashboard' => $dashboard->toArray(),
        ]);
    }
}
