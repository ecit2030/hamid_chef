<?php

declare(strict_types=1);

return [
    'accepted' => 'The :attribute must be accepted.',
    // ... minimal placeholder entries; project already has many front-end JSON translations.

    // Booking rejection reason validation messages
    'rejection_reason_required' => 'The rejection reason is required.',
    'rejection_reason_string' => 'The rejection reason must be a text string.',
    'rejection_reason_min' => 'The rejection reason must be at least :min character.',
    'rejection_reason_max' => 'The rejection reason must not exceed :max characters.',
    'rejection_reason_whitespace' => 'The rejection reason cannot contain only whitespace.',

    // KYC-specific messages
    'kyc' => [
        'errors' => [
            'already_has_pending_or_approved' => 'You already have a pending or approved KYC request. You cannot create a new one until the previous request is rejected.',
        ],
    ],
];
