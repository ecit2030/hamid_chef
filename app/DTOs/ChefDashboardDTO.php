<?php

namespace App\DTOs;

class ChefDashboardDTO extends BaseDTO
{
    public int $total_bookings;
    public int $monthly_bookings;
    public int $pending_bookings;
    public int $confirmed_bookings;
    public int $completed_bookings;
    public float $total_earnings;
    public float $monthly_earnings;
    public float $average_rating;
    public int $total_reviews;
    public int $total_services;
    public int $active_services;
    public array $earnings_chart;
    public array $bookings_by_status;
    public array $upcoming_bookings;
    public array $recent_reviews;
    public array $services_performance;
    public float $wallet_balance;

    public function __construct(array $data)
    {
        $this->total_bookings = $data['statistics']['total_bookings'] ?? 0;
        $this->monthly_bookings = $data['statistics']['monthly_bookings'] ?? 0;
        $this->pending_bookings = $data['statistics']['pending_bookings'] ?? 0;
        $this->confirmed_bookings = $data['statistics']['confirmed_bookings'] ?? 0;
        $this->completed_bookings = $data['statistics']['completed_bookings'] ?? 0;
        $this->total_earnings = (float) ($data['statistics']['total_earnings'] ?? 0);
        $this->monthly_earnings = (float) ($data['statistics']['monthly_earnings'] ?? 0);
        $this->average_rating = (float) ($data['statistics']['average_rating'] ?? 0);
        $this->total_reviews = $data['statistics']['total_reviews'] ?? 0;
        $this->total_services = $data['statistics']['total_services'] ?? 0;
        $this->active_services = $data['statistics']['active_services'] ?? 0;
        $this->earnings_chart = $data['earnings_chart'] ?? [];
        $this->bookings_by_status = $data['bookings_by_status'] ?? [];
        $this->upcoming_bookings = $data['upcoming_bookings'] ?? [];
        $this->recent_reviews = $data['recent_reviews'] ?? [];
        $this->services_performance = $data['services_performance'] ?? [];
        $this->wallet_balance = (float) ($data['wallet_balance'] ?? 0);
    }

    public function toArray(): array
    {
        return [
            'total_bookings' => $this->total_bookings,
            'monthly_bookings' => $this->monthly_bookings,
            'pending_bookings' => $this->pending_bookings,
            'confirmed_bookings' => $this->confirmed_bookings,
            'completed_bookings' => $this->completed_bookings,
            'total_earnings' => $this->total_earnings,
            'monthly_earnings' => $this->monthly_earnings,
            'average_rating' => $this->average_rating,
            'total_reviews' => $this->total_reviews,
            'total_services' => $this->total_services,
            'active_services' => $this->active_services,
            'earnings_chart' => $this->earnings_chart,
            'bookings_by_status' => $this->bookings_by_status,
            'upcoming_bookings' => $this->upcoming_bookings,
            'recent_reviews' => $this->recent_reviews,
            'services_performance' => $this->services_performance,
            'wallet_balance' => $this->wallet_balance,
        ];
    }

    public function toStatisticsArray(): array
    {
        return [
            'total_bookings' => $this->total_bookings,
            'monthly_bookings' => $this->monthly_bookings,
            'pending_bookings' => $this->pending_bookings,
            'confirmed_bookings' => $this->confirmed_bookings,
            'completed_bookings' => $this->completed_bookings,
            'total_earnings' => $this->total_earnings,
            'monthly_earnings' => $this->monthly_earnings,
            'average_rating' => $this->average_rating,
            'total_reviews' => $this->total_reviews,
            'total_services' => $this->total_services,
            'active_services' => $this->active_services,
            'wallet_balance' => $this->wallet_balance,
        ];
    }
}
