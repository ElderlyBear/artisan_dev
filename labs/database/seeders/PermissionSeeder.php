<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = ['user', 'role', 'permission'];
        $actions = ['get-list', 'read', 'create', 'update', 'delete', 'restore'];

        $permissions = [];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => "{$action}-{$entity}",
                    'description' => "can {$action} {$entity}",
                    'code' => "{$action}-{$entity}",
                ];
            }
        }

        Permission::insert($permissions);
    }
}
