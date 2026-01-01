<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookingTransaction;

class BookingTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة معاملات الحجوزات...');

        $bookings = Booking::all();

        foreach ($bookings as $booking) {
            BookingTransaction::firstOrCreate([
                'booking_id' => $booking->id,
            ], [
                'transaction_id' => 'TXN' . strtoupper(uniqid()),
                'payment_method' => ['credit_card', 'mada', 'apple_pay', 'stc_pay'][rand(0, 3)],
                'amount' => $booking->total_amount,
                'currency' => 'SAR',
            ]);
        }

        $this->command->info('✅ تم إضافة معاملات الحجوزات بنجاح');
    }
}
