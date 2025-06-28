<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::firstOrCreate(['name' => 'create store']);
        Permission::firstOrCreate(['name' => 'edit store']);
        Permission::firstOrCreate(['name' => 'create transactions']);
        Permission::firstOrCreate(['name' => 'create items']);
        Permission::firstOrCreate(['name' => 'record payments']);
        Permission::firstOrCreate(['name' => 'create borrower']);
        Permission::firstOrCreate(['name' => 'edit borrowers password']);
        Permission::firstOrCreate(['name' => 'view dashboard']);
        Permission::firstOrCreate(['name' => 'view borrowers']);
        Permission::firstOrCreate(['name' => 'view borrower lent items']);
        Permission::firstOrCreate(['name' => 'view own lent items']);

        // Create roles
        $storeOwner = Role::firstOrCreate(['name' => 'store_owner']);
        $borrower = Role::firstOrCreate(['name' => 'borrower']);

        // Assign permissions to roles
        $storeOwner->givePermissionTo([
            'create store',
            'edit store',
            'create transactions',
            'create items',
            'record payments',
            'create borrower',
            'edit borrowers password',
            'view dashboard',
            'view borrowers',
            'view borrower lent items'
        ]);

        $borrower->givePermissionTo([
            'view own lent items',
            'view borrower lent items'
        ]);
    }
}
