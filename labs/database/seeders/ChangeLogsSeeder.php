<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ChangeLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $permissions = [];
        $entities = ['user', 'role', 'permission'];
        $action = 'get-story'; // Простая строка вместо массива
        foreach ($entities as $entity) {
            $permissions[] = [
                'name' => "{$entity}-{$action}",
                'description' => "can {$action} {$entity}",
                'code' => "{$entity}-{$action}"
            ];
        }

        Permission::insert($permissions);
    }
}
