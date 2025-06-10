<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;


class UserPermissionsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function admin_can_get_users_list()
    {
        // 1. Создаем тестовые данные
        $adminRole = Role::create(['name' => 'Admin']);
        $permission = Permission::factory()->create([
            'name' => 'get-list-user',
            'code' => 'get-list-user'
        ]);

        $adminRole->permissions()->attach($permission);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole);

        // 2. Выполняем запрос
        $response = $this->actingAs($adminUser)
            ->get('/api/ref/user');

        // 3. Проверяем результат
        $response->assertStatus(200);
        $response->assertJsonCount(User::count());
    }

}
