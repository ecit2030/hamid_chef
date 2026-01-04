<?php

namespace App\DTOs;

class AdminDashboardDTO extends BaseDTO
{
    // User statistics
    public int $total_users;
    public int $total_customers;
    public int $total_chefs;
    public int $active_chefs;
    public float $users_growth_percentage;
    
    // Booking statistics
    public int $total_bookings;
    public int $monthly_bookings;
    public int $pending_bookings;
    public int $completed_bookings;
    
    // Revenue statistics
    public float $total_revenue;
    public float $monthly_revenue;
    public float $revenue_growth_percentage;
    
    // KYC statistics
    public int $pending_kyc_requests;
    
    // Charts and lists
    public array $bookings_chart;
    public array $revenue_chart;
    public array $top_chefs;
    public array $recent_bookings;
    public array $bookings_by_status;

    public function __construct(array $data)
    {
        // User statistics
        $this->total_users = $data['statistics']['total_users'] ?? 0;
        $this->total_customers = $data['statistics']['total_customers'] ?? 0;
        $this->total_chefs = $data['statistics']['total_chefs'] ?? 0;
        $this->active_chefs = $data['statistics']['active_chefs'] ?? 0;
        $this->users_growth_percentage = (float) ($data['statistics']['users_growth_percentage'] ?? 0);
        
        // Booking statistics
        $this->total_bookings = $data['statistics']['total_bookings'] ?? 0;
        $this->monthly_bookings = $data['statistics']['monthly_bookings'] ?? 0;
        $this->pending_bookings = $data['statistics']['pending_bookings'] ?? 0;
        $this->completed_bookings = $data['statistics']['completed_bookings'] ?? 0;
        
        // Revenue statistics
        $this->total_revenue = (float) ($data['statistics']['total_revenue'] ?? 0);
        $this->monthly_revenue = (float) ($data['statistics']['monthly_revenue'] ?? 0);
        $this->revenue_growth_percentage = (float) ($data['statistics']['revenue_growth_percentage'] ?? 0);
        
        // KYC statistics
        $this->pending_kyc_requests = $data['statistics']['pending_kyc_requests'] ?? 0;
        
        // Charts and lists
        $this->bookings_chart = $data['bookings_chart'] ?? [];
        $this->revenue_chart = $data['revenue_chart'] ?? [];
        $this->top_chefs = $data['top_chefs'] ?? [];
        $this->recent_bookings = $data['recent_bookings'] ?? [];
        $this->bookings_by_status = $data['bookings_by_status'] ?? [];
    }

    public function toArray(): array
    {
        return [
            'total_users' => $this->total_users,
            'total_customers' => $this->total_customers,
            'total_chefs' => $this->total_chefs,
            'active_chefs' => $this->active_chefs,
            'users_growth_percentage' => $this->users_growth_percentage,
            'total_bookings' => $this->total_bookings,
            'monthly_bookings' => $this->monthly_bookings,
            'pending_bookings' => $this->pending_bookings,
            'completed_bookings' => $this->completed_bookings,
            'total_revenue' => $this->total_revenue,
            'monthly_revenue' => $this->monthly_revenue,
            'revenue_growth_percentage' => $this->revenue_growth_percentage,
            'pending_kyc_requests' => $this->pending_kyc_requests,
            'bookings_chart' => $this->bookings_chart,
            'revenue_chart' => $this->revenue_chart,
            'top_chefs' => $this->top_chefs,
            'recent_bookings' => $this->recent_bookings,
            'bookings_by_status' => $this->bookings_by_status,
        ];
    }

    public function toStatisticsArray(): array
    {
        return [
            'total_users' => $this->total_users,
            'total_customers' => $this->total_customers,
            'total_chefs' => $this->total_chefs,
            'active_chefs' => $this->active_chefs,
            'users_growth_percentage' => $this->users_growth_percentage,
            'total_bookings' => $this->total_bookings,
            'monthly_bookings' => $this->monthly_bookings,
            'pending_bookings' => $this->pending_bookings,
            'completed_bookings' => $this->completed_bookings,
            'total_revenue' => $this->total_revenue,
            'monthly_revenue' => $this->monthly_revenue,
            'revenue_growth_percentage' => $this->revenue_growth_percentage,
            'pending_kyc_requests' => $this->pending_kyc_requests,
        ];
    }
}
