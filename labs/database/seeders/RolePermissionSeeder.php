<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();
        $guestRole = Role::where('name', 'Guest')->first();


        $permissionsAll = Permission::pluck('id')->toArray();


        $permissionsUser = Permission::whereIn('name', [
            'get-list-user',
            'read-user',
            'update-user',
        ])->pluck('id')->toArray();


        $permissionsGuest = Permission::where('name', 'get-list-user')->value('id');


        $adminRole->permissions()->attach($permissionsAll);
        $userRole->permissions()->attach($permissionsUser);
        $guestRole->permissions()->attach($permissionsGuest);
    }
}
