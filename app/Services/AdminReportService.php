<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\UserRepository;
use App\Repositories\ChefRepository;
use App\Repositories\ChefServiceRepository;
use App\Repositories\ChefWalletTransactionRepository;
use Carbon\Carbon;

class AdminReportService
{
    public function __construct(
        private BookingRepository $bookingRepository,
        private UserRepository $userRepository,
        private ChefRepository $chefRepository,
        private ChefServiceRepository $chefServiceRepository,
        private ChefWalletTransactionRepository $transactionRepository
    ) {}
    
    /**
     * Get bookings report data.
     */
    public function getBookingsReport(?Carbon $startDate, ?string $status, ?Carbon $endDate = null, int $perPage = 15): array
    {
        $bookings = $this->bookingRepository->getBookingsForReport($startDate, $status, $perPage, $endDate);
        $stats = $this->bookingRepository->getBookingsStats($startDate, $status, $endDate);
        
        return [
            'bookings' => $bookings,
            'stats' => $stats,
        ];
    }
    
    /**
     * Get customers report data.
     */
    public function getCustomersReport(?Carbon $startDate, int $perPage = 15): array
    {
        $customers = $this->userRepository->getCustomersForReport($startDate, $perPage);
        $stats = $this->userRepository->getCustomersStats($startDate);
        
        return [
            'customers' => $customers,
            'stats' => $stats,
        ];
    }
    
    /**
     * Get chefs report data.
     */
    public function getChefsReport(?Carbon $startDate, int $perPage = 15): array
    {
        $chefs = $this->chefRepository->getChefsForReport($startDate, $perPage);
        $stats = $this->chefRepository->getChefsStats($startDate);
        
        return [
            'chefs' => $chefs,
            'stats' => $stats,
        ];
    }
    
    /**
     * Get services report data.
     */
    public function getServicesReport(?Carbon $startDate, int $perPage = 15): array
    {
        $services = $this->chefServiceRepository->getServicesForReport($startDate, $perPage);
        $stats = $this->chefServiceRepository->getServicesStats($startDate);
        
        return [
            'services' => $services,
            'stats' => $stats,
        ];
    }
    
    /**
     * Get earnings report data.
     */
    public function getEarningsReport(?Carbon $startDate): array
    {
        $dailyEarnings = $this->bookingRepository->getDailyEarnings($startDate);
        
        $summary = [
            'total_earnings' => $dailyEarnings->sum('total'),
            'total_commission' => $dailyEarnings->sum('commission'),
            'net_earnings' => $dailyEarnings->sum('net'),
            'total_bookings' => $dailyEarnings->sum('bookings_count'),
            'total_hours' => $dailyEarnings->sum('hours'),
            'average_per_booking' => $dailyEarnings->sum('bookings_count') > 0 
                ? $dailyEarnings->sum('net') / $dailyEarnings->sum('bookings_count') 
                : 0,
            'average_per_day' => $dailyEarnings->count() > 0 
                ? $dailyEarnings->sum('net') / $dailyEarnings->count() 
                : 0,
        ];
        
        return [
            'dailyEarnings' => $dailyEarnings,
            'summary' => $summary,
        ];
    }
    
    /**
     * Get transactions report data.
     */
    public function getTransactionsReport(?Carbon $startDate, ?string $type, int $perPage = 15): array
    {
        $transactions = $this->transactionRepository->getTransactionsForReport($startDate, $type, $perPage);
        $stats = $this->transactionRepository->getTransactionsStats($startDate, $type);
        
        return [
            'transactions' => $transactions,
            'stats' => $stats,
        ];
    }
    
    /**
     * Get bookings data for export.
     */
    public function getBookingsForExport(?Carbon $startDate, ?string $status, ?Carbon $endDate = null)
    {
        return $this->bookingRepository->getBookingsForExport($startDate, $status, $endDate);
    }
    
    /**
     * Get customers data for export.
     */
    public function getCustomersForExport(?Carbon $startDate)
    {
        return $this->userRepository->getCustomersForExport($startDate);
    }
    
    /**
     * Get chefs data for export.
     */
    public function getChefsForExport(?Carbon $startDate)
    {
        return $this->chefRepository->getChefsForExport($startDate);
    }
    
    /**
     * Get services data for export.
     */
    public function getServicesForExport(?Carbon $startDate)
    {
        return $this->chefServiceRepository->getServicesForExport($startDate);
    }
    
    /**
     * Get earnings data for export.
     */
    public function getEarningsForExport(?Carbon $startDate)
    {
        return $this->bookingRepository->getDailyEarnings($startDate);
    }
    
    /**
     * Get transactions data for export.
     */
    public function getTransactionsForExport(?Carbon $startDate, ?string $type)
    {
        return $this->transactionRepository->getTransactionsForExport($startDate, $type);
    }
}
