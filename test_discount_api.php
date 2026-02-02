<?php

// Create a discount code for testing
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DiscountCode;
use App\Models\User;

// Create or get a discount code
$code = DiscountCode::firstOrCreate(
    ['code' => 'TEST2024'],
    [
        'description' => 'كود اختبار للتطبيق',
        'type' => 'percentage',
        'value' => 20,
        'min_order_amount' => 100,
        'max_discount_amount' => 50,
        'start_date' => now(),
        'end_date' => now()->addDays(30),
        'usage_limit' => 100,
        'per_user_limit' => 3,
        'is_active' => true,
    ]
);

echo "✅ Discount Code: {$code->code} (ID: {$code->id})\n";
echo "   Type: {$code->type}\n";
echo "   Value: {$code->value}%\n";
echo "   Min Order: {$code->min_order_amount}\n";
echo "   Max Discount: {$code->max_discount_amount}\n";
echo "   Status: " . ($code->is_active ? 'Active' : 'Inactive') . "\n\n";

// Get a test user
$user = User::first();
if ($user) {
    echo "✅ Test User: {$user->name} (ID: {$user->id})\n";
    echo "   Email: {$user->email}\n";
    echo "   Phone: {$user->phone_number}\n\n";

    // Create a token for the user
    $token = $user->createToken('test-token')->plainTextToken;
    echo "✅ Bearer Token:\n{$token}\n\n";

    echo "📝 Test API Endpoint:\n";
    echo "POST http://localhost:8000/api/discount-codes/validate\n";
    echo "Headers:\n";
    echo "  Authorization: Bearer {$token}\n";
    echo "  Content-Type: application/json\n";
    echo "  Accept: application/json\n\n";
    echo "Body:\n";
    echo "{\n";
    echo "  \"code\": \"TEST2024\",\n";
    echo "  \"amount\": 200\n";
    echo "}\n\n";
} else {
    echo "❌ No customer user found. Please create a user first.\n";
}
