<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\DTOs\RoleDTO;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminDTO = new RoleDTO(
            'Admin',
            'Admin Role',
            'Admin'
        );

        $userDTO = new RoleDTO(
            'User',
            'User Role',
            'User'
        );

        $guestDTO = new RoleDTO(
            'Guest',
            'Guest Role',
            'Guest'
        );


        Role::create([
            'name' => $adminDTO->name,
            'description' => $adminDTO->description,
            'code' => $adminDTO->code,
        ]);

        Role::create([
            'name' => $userDTO->name,
            'description' => $userDTO->description,
            'code' => $userDTO->code,
        ]);

        Role::create([
            'name' => $guestDTO->name,
            'description' => $guestDTO->description,
            'code' => $guestDTO->code,
        ]);

    }
}
