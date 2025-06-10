<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
class AddNewPermissionsToAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = ['user-get-story', 'role-get-story', 'permission-get-story'];
        $adminRole = Role::where('name', 'Admin')->first();
        $permissionsUser = Permission::where('name', $arr[0])->first();
        $permissionsRole = Permission::where('name', $arr[1])->first();
        $permissionsPermissions = Permission::where('name', $arr[2])->first();

        $adminRole->permissions()->attach($permissionsUser);
        $adminRole->permissions()->attach($permissionsPermissions);
        $adminRole->permissions()->attach($permissionsRole);

    }
}
