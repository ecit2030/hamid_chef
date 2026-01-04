<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\ChefWallet;
use App\Models\ChefWalletTransaction;
use App\Models\ChefWithdrawalRequest;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class WalletController extends Controller
{
    /**
     * Display chef's wallet overview.
     */
    public function index(): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $wallet = ChefWallet::firstOrCreate(
            ['chef_id' => $chef->id],
            ['balance' => 0, 'is_active' => true]
        );

        $this->authorize('view', $wallet);

        // Get recent transactions
        $recentTransactions = ChefWalletTransaction::where('chef_id', $chef->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get pending withdrawal requests
        $pendingWithdrawals = ChefWithdrawalRequest::where('chef_id', $chef->id)
            ->where('status', 'pending')
            ->with('method')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get withdrawal methods
        $withdrawalMethods = WithdrawalMethod::where('is_active', true)->get();

        // Calculate statistics
        $totalEarnings = ChefWalletTransaction::where('chef_id', $chef->id)
            ->where('type', 'credit')
            ->sum('amount');

        $totalWithdrawn = ChefWalletTransaction::where('chef_id', $chef->id)
            ->where('type', 'debit')
            ->sum('amount');

        return Inertia::render('Chef/Wallet/Index', [
            'wallet' => [
                'balance' => $wallet->balance,
                'is_active' => $wallet->is_active,
            ],
            'statistics' => [
                'total_earnings' => $totalEarnings,
                'total_withdrawn' => abs($totalWithdrawn),
                'available_balance' => $wallet->balance,
            ],
            'recent_transactions' => $recentTransactions,
            'pending_withdrawals' => $pendingWithdrawals,
            'withdrawal_methods' => $withdrawalMethods,
        ]);
    }

    /**
     * Display chef's wallet transactions.
     */
    public function transactions(Request $request): Response
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $perPage = $request->input('per_page', 20);
        $type = $request->input('type');

        $query = ChefWalletTransaction::where('chef_id', $chef->id)
            ->orderBy('created_at', 'desc');

        if ($type && in_array($type, ['credit', 'debit'])) {
            $query->where('type', $type);
        }

        $transactions = $query->paginate($perPage);

        return Inertia::render('Chef/Wallet/Transactions', [
            'transactions' => $transactions,
            'filters' => [
                'type' => $type,
            ],
        ]);
    }

    /**
     * Create a withdrawal request.
     */
    public function withdraw(Request $request)
    {
        $user = Auth::guard('chef')->user();
        $chef = $user->chef;

        $wallet = ChefWallet::where('chef_id', $chef->id)->first();

        if (!$wallet) {
            return back()->withErrors(['wallet' => __('chef.wallet_not_found')]);
        }

        $this->authorize('withdraw', $wallet);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'withdrawal_method_id' => 'required|exists:withdrawal_methods,id',
            'payment_details' => 'required|string|max:500',
        ]);

        // Check if amount is available
        if ($validated['amount'] > $wallet->balance) {
            return back()->withErrors(['amount' => __('chef.insufficient_balance')]);
        }

        // Check for pending withdrawal requests
        $hasPending = ChefWithdrawalRequest::where('chef_id', $chef->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->withErrors(['withdrawal' => __('chef.pending_withdrawal_exists')]);
        }

        DB::transaction(function () use ($chef, $wallet, $validated) {
            // Create withdrawal request
            ChefWithdrawalRequest::create([
                'chef_id' => $chef->id,
                'amount' => $validated['amount'],
                'withdrawal_method_id' => $validated['withdrawal_method_id'],
                'payment_details' => $validated['payment_details'],
                'status' => 'pending',
                'is_active' => true,
            ]);

            // Deduct from wallet (hold amount)
            $wallet->decrement('balance', $validated['amount']);

            // Create transaction record
            ChefWalletTransaction::create([
                'chef_id' => $chef->id,
                'type' => 'debit',
                'amount' => -$validated['amount'],
                'balance' => $wallet->balance,
                'description' => __('chef.withdrawal_request_created'),
                'is_active' => true,
            ]);
        });

        return back()->with('success', __('chef.withdrawal_request_submitted'));
    }
}
