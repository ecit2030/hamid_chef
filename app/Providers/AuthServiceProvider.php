<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Chef;
use App\Models\ChefGallery;
use App\Models\ChefServiceRating;
use App\Models\ChefVacation;
use App\Models\ChefWallet;
use App\Models\ChefWithdrawalRequest;
use App\Models\ChefWorkingHour;
use App\Models\Conversation;
use App\Models\Booking;
use App\Models\ChefService;
use App\Policies\AddressPolicy;
use App\Policies\ChefGalleryPolicy;
use App\Policies\ChefPolicy;
use App\Policies\ChefServiceRatingPolicy;
use App\Policies\ChefVacationPolicy;
use App\Policies\ChefWalletPolicy;
use App\Policies\ChefWithdrawalRequestPolicy;
use App\Policies\ChefWorkingHourPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\BookingPolicy;
use App\Policies\ChefServicePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Address::class => AddressPolicy::class,
        Chef::class => ChefPolicy::class,
        ChefGallery::class => ChefGalleryPolicy::class,
        ChefServiceRating::class => ChefServiceRatingPolicy::class,
        ChefVacation::class => ChefVacationPolicy::class,
        ChefWallet::class => ChefWalletPolicy::class,
        ChefWithdrawalRequest::class => ChefWithdrawalRequestPolicy::class,
        ChefWorkingHour::class => ChefWorkingHourPolicy::class,
        Booking::class => BookingPolicy::class,
        ChefService::class => ChefServicePolicy::class,
        Conversation::class => ConversationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}