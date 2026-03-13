<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PermissionsSyncService
{
    /**
     * Sync permissions from config/acl.php to the database.
     * Creates any missing permissions so role updates don't fail.
     */
    public static function syncFromConfig(): void
    {
        $guard = config('acl.guard', 'admin');
        $resources = config('acl.resources', []);
        $resourceLabels = config('acl.resource_labels', []);
        $actionLabels = config('acl.action_labels', [
            'view'   => ['en' => 'View',   'ar' => 'عرض'],
            'create' => ['en' => 'Create', 'ar' => 'إنشاء'],
            'update' => ['en' => 'Update', 'ar' => 'تعديل'],
            'delete' => ['en' => 'Delete', 'ar' => 'حذف'],
        ]);

        foreach ($resources as $resource => $actions) {
            $resourceEn = $resourceLabels[$resource]['en']
                ?? (string) Str::of($resource)->replace('-', ' ')->headline();
            $resourceAr = $resourceLabels[$resource]['ar'] ?? $resource;

            foreach ($actions as $action) {
                $actEn = $actionLabels[$action]['en'] ?? ucfirst($action);
                $actAr = $actionLabels[$action]['ar'] ?? $action;

                DB::table((new Permission)->getTable())->updateOrInsert(
                    ['name' => "{$resource}.{$action}", 'guard_name' => $guard],
                    ['display_name' => json_encode([
                        'en' => "{$actEn} {$resourceEn}",
                        'ar' => "{$actAr} {$resourceAr}",
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
                );
            }
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
