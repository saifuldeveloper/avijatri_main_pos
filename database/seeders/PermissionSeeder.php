<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Permission::create(['name' => 'manage bank accounts']);
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'manage cheques']);
        Permission::create(['name' => 'manage colors']);
        Permission::create(['name' => 'manage employees']);
        Permission::create(['name' => 'manage expenses']);
        Permission::create(['name' => 'manage factories']);
        Permission::create(['name' => 'manage gifts']);
        Permission::create(['name' => 'manage gift suppliers']);
        Permission::create(['name' => 'manage invoices']);
        Permission::create(['name' => 'manage loans']);
        Permission::create(['name' => 'manage purchases']);
        Permission::create(['name' => 'manage reports']);
        Permission::create(['name' => 'manage retail stores']);
        Permission::create(['name' => 'manage returns to factory']);
        Permission::create(['name' => 'manage returns from retail stores']);
        Permission::create(['name' => 'manage pending returns']);
        Permission::create(['name' => 'manage shoes']);
        Permission::create(['name' => 'manage transactions']);

        $role = Role::findByName('operator');
        $role->givePermissionTo('manage bank accounts');
        $role->givePermissionTo('manage categories');
        $role->givePermissionTo('manage cheques');
        $role->givePermissionTo('manage colors');
        $role->givePermissionTo('manage expenses');
        $role->givePermissionTo('manage factories');
        $role->givePermissionTo('manage gifts');
        $role->givePermissionTo('manage gift suppliers');
        $role->givePermissionTo('manage invoices');
        $role->givePermissionTo('manage loans');
        $role->givePermissionTo('manage purchases');
        $role->givePermissionTo('manage retail stores');
        $role->givePermissionTo('manage returns to factory');
        $role->givePermissionTo('manage returns from retail stores');
        $role->givePermissionTo('manage pending returns');
        $role->givePermissionTo('manage shoes');
        $role->givePermissionTo('manage transactions');
    }
}
