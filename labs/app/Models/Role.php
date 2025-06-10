<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Exception;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'code'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Откат изменений роли по записи лога.
     *
     * @param int $historyId ID записи лога
     * @return \Illuminate\Http\JsonResponse
     */
    public function roleRollback(int $historyId)
    {
        // Получаем запись лога
        $mutation = ChangeLogs::find($historyId);

        // Проверяем, существует ли такая запись
        if (!$mutation) {
            return FacadeResponse::json(['message' => 'Log entry not found'], 404);
        }

        // Проверяем тип и ID сущности
      if ($mutation->entity_type !== 'App\Models\Role' || $mutation->entity_id != $this->id) {
            return FacadeResponse::json(['message' => 'This mutation is not allowed for this role'], 403);
        }

        // Декодируем данные из JSON
        $data = json_decode($mutation->before);

        // Проверяем, что данные корректны
        if (!isset($data->name) || !isset($data->description) || !isset($data->code)) {
            return FacadeResponse::json(['message' => 'Incomplete or invalid data in log entry'], 400);
        }

        try {
            DB::transaction(function () use ($data) {
                // Обновляем роль
                $this->update([
                    'name' => $data->name,
                    'description' => $data->description,
                    'code' => $data->code,
                ]);
            });

            return FacadeResponse::json(['message' => 'Role successfully rolled back']);
        } catch (\Exception $e) {
            return FacadeResponse::json(['message' => 'Error during rollback: ' . $e->getMessage()], 500);
        }
    }
}