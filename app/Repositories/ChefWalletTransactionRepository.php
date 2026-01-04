<?php

namespace App\Repositories;

use App\Models\ChefWalletTransaction;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ChefWalletTransactionRepository
{
    /**
     * Get transactions for report with pagination.
     */
    public function getTransactionsForReport(?Carbon $startDate, ?string $type, int $perPage = 15): LengthAwarePaginator
    {
        return ChefWalletTransaction::with(['chef.user:id,first_name,last_name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($type, fn($q) => $q->where('type', $type))
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get transactions statistics.
     */
    public function getTransactionsStats(?Carbon $startDate, ?string $type): array
    {
        $query = ChefWalletTransaction::query()
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($type, fn($q) => $q->where('type', $type));
        
        return [
            'total_transactions' => (clone $query)->count(),
            'total_credits' => (clone $query)->where('type', 'credit')->sum('amount'),
            'total_debits' => (clone $query)->where('type', 'debit')->sum('amount'),
            'net_amount' => (clone $query)->where('type', 'credit')->sum('amount') - (clone $query)->where('type', 'debit')->sum('amount'),
        ];
    }
    
    /**
     * Get transactions for export.
     */
    public function getTransactionsForExport(?Carbon $startDate, ?string $type): Collection
    {
        return ChefWalletTransaction::with(['chef.user:id,first_name,last_name'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($type, fn($q) => $q->where('type', $type))
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
