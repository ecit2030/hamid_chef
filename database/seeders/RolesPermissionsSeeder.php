<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    protected function json(array $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () {
            $guard = config('acl.guard', 'admin');
            $resources = config('acl.resources', []);
            $resourceLabels = config('acl.resource_labels', []);
            $actionLabels = config('acl.action_labels', [
                'view'   => ['en' => 'View',   'ar' => 'عرض'],
                'create' => ['en' => 'Create', 'ar' => 'إنشاء'],
                'update' => ['en' => 'Update', 'ar' => 'تعديل'],
                'delete' => ['en' => 'Delete', 'ar' => 'حذف'],
            ]);

            // Create permissions
            foreach ($resources as $resource => $actions) {
                $resourceEn = $resourceLabels[$resource]['en']
                    ?? (string) Str::of($resource)->replace('-', ' ')->headline();
                $resourceAr = $resourceLabels[$resource]['ar'] ?? $resource;

                foreach ($actions as $action) {
                    $actEn = $actionLabels[$action]['en'] ?? ucfirst($action);
                    $actAr = $actionLabels[$action]['ar'] ?? $action;

                    DB::table((new Permission)->getTable())->updateOrInsert(
                        ['name' => "{$resource}.{$action}", 'guard_name' => $guard],
                        ['display_name' => $this->json([
                            'en' => "{$actEn} {$resourceEn}",
                            'ar' => "{$actAr} {$resourceAr}",
                        ])]
                    );
                }
            }

            // Helper to collect permission names
            $allActionPerms = collect($resources)
                ->flatMap(fn ($acts, $res) => collect($acts)->map(fn ($a) => "{$res}.{$a}"))
                ->values();

            $allViewPerms = collect($resources)
                ->filter(fn ($acts) => in_array('view', $acts, true))
                ->keys()
                ->map(fn ($res) => "{$res}.view")
                ->values();

            // Create roles
            
            // Super Admin: All permissions
            $superAdmin = Role::updateOrCreate(
                ['name' => 'super_admin', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Super Admin', 'ar' => 'مدير النظام'])]
            );
            $superAdmin->syncPermissions($allActionPerms->all());

            // Admin: All except system settings and role management
            $admin = Role::updateOrCreate(
                ['name' => 'admin', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Admin', 'ar' => 'مشرف'])]
            );
            $adminPerms = $allActionPerms
                ->reject(fn ($name) => Str::startsWith($name, ['system-settings.', 'roles.create', 'roles.delete']))
                ->values();
            $admin->syncPermissions($adminPerms->all());

            // Support: Bookings, users view, dashboard
            $support = Role::updateOrCreate(
                ['name' => 'support', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Support', 'ar' => 'دعم فني'])]
            );
            $supportPerms = $allActionPerms
                ->filter(fn ($name) => Str::startsWith($name, [
                    'bookings.', 'booking-transactions.', 'users.view', 
                    'chefs.view', 'chef-services.view', 'dashboard.'
                ]))
                ->values();
            $support->syncPermissions($supportPerms->all());

            // Chef Manager: All chef-related permissions
            $chefManager = Role::updateOrCreate(
                ['name' => 'chef_manager', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Chef Manager', 'ar' => 'مدير الطهاة'])]
            );
            $chefManagerPerms = $allActionPerms
                ->filter(fn ($name) => Str::startsWith($name, [
                    'chefs.', 'chef-', 'categories.', 'tags.', 'dashboard.'
                ]))
                ->values();
            $chefManager->syncPermissions($chefManagerPerms->all());

            // Content Manager: Landing pages, categories, tags
            $contentManager = Role::updateOrCreate(
                ['name' => 'content_manager', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Content Manager', 'ar' => 'مدير المحتوى'])]
            );
            $contentManagerPerms = $allActionPerms
                ->filter(fn ($name) => Str::startsWith($name, [
                    'landing-page-sections.', 'categories.', 'tags.', 'dashboard.'
                ]))
                ->values();
            $contentManager->syncPermissions($contentManagerPerms->all());

            // Financial Manager: Wallets, transactions, withdrawals
            $financialManager = Role::updateOrCreate(
                ['name' => 'financial_manager', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Financial Manager', 'ar' => 'مدير مالي'])]
            );
            $financialManagerPerms = $allActionPerms
                ->filter(fn ($name) => Str::startsWith($name, [
                    'chef-wallets.', 'chef-wallet-transactions.', 'chef-withdrawal-requests.',
                    'withdrawal-methods.', 'booking-transactions.', 'financial-reports.', 'dashboard.'
                ]))
                ->values();
            $financialManager->syncPermissions($financialManagerPerms->all());

            // Viewer: View only
            $viewer = Role::updateOrCreate(
                ['name' => 'viewer', 'guard_name' => $guard],
                ['display_name' => $this->json(['en' => 'Viewer', 'ar' => 'مشاهد'])]
            );
            $viewer->syncPermissions($allViewPerms->all());
        });

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        $this->command->info('✅ تم إضافة الأدوار والصلاحيات بنجاح');
    }
}
