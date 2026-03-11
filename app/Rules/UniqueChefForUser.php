<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniqueChefForUser implements ValidationRule
{
    protected ?int $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId ?? Auth::id();
    }

    /**
     * Run the validation rule.
     * Uses $value (from form) when provided, otherwise $this->userId (Auth::id() for API).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userIdToCheck = $value ?? $this->userId;
        if (!$userIdToCheck) {
            return;
        }

        $existingChef = DB::table('chefs')
            ->where('user_id', $userIdToCheck)
            ->whereNull('deleted_at') // Respect soft deletes
            ->exists();

        if ($existingChef) {
            $fail('المستخدم لديه بالفعل ملف طاهي');
        }
    }
}