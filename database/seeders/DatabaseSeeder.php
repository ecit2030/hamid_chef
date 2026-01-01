<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Roles & Permissions (based on ACL config)
            RolesPermissionsSeeder::class,
            
            // 2. Admins
            AdminSeeder::class,
            
            // 3. Locations
            LocationSeeder::class,
            
            // 4. Categories & Tags
            CategorySeeder::class,
            TagSeeder::class,
            
            // 5. Withdrawal Methods
            WithdrawalMethodSeeder::class,
            
            // 6. Users (Customers)
            UserSeeder::class,
            
            // 7. Chefs
            ChefSeeder::class,
            ChefAddressSeeder::class,
            ChefKycSeeder::class,
            ChefWorkingHourSeeder::class,
            ChefVacationSeeder::class,
            ChefWalletSeeder::class,
            ChefGallerySeeder::class,
            
            // 8. Chef Services
            ChefServiceSeeder::class,
            ChefServiceEquipmentSeeder::class,
            ChefServiceTagSeeder::class,
            ChefServiceImageSeeder::class,
            
            // 9. Bookings
            BookingSeeder::class,
            BookingTransactionSeeder::class,
            ChefServiceRatingSeeder::class,
            
            // 10. Wallet Transactions
            ChefWalletTransactionSeeder::class,
            ChefWithdrawalRequestSeeder::class,
            
            // 11. Landing Page Sections
            LandingPageSectionSeeder::class,
        ]);
    }
}
