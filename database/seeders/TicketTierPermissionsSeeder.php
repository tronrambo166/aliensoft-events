<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class TicketTierPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ([
                     'ticket-tier.view',
                     'ticket-tier.create',
                     'ticket-tier.update',
                     'ticket-tier.delete',
                 ] as $permission) {
            Permission::findOrCreate($permission);
        }
    }

}
