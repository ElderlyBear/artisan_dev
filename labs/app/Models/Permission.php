<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class Permission extends Model
{
    protected $fillable = ['name', 'description', 'code'];

     
/**
 * Связь ролей
  */
  public function roles()
  {
      return $this->belongsToMany(Role::class, 'permission_role');
    }
     
/**
 * Откат изменений разрешения по логу*
* @param int $historyId
* @return JsonResponse
*/
public function rollbackPermission(int $historyId): JsonResponse
{
    $mutation = ChangeLogs::find($historyId);

        if (!$mutation) {
            return response()->json(['message' => 'Log entry not found'], 404);
        }

        if ($mutation->entity_type !== 'App\Models\Permission' || $mutation->entity_id !== $this->id) {
            return response()->json(['message' => 'This log entry does not belong to this permission'], 403);
        }

        $data = json_decode($mutation->before);

        if (!isset($data->name, $data->description, $data->code)) {
            return response()->json(['message' => 'Invalid or incomplete data in log entry'], 400);
        }

        try {
            DB::transaction(function () use ($data) {
                $this->update([
                    'name' => $data->name,
                    'description' => $data->description,
                    'code' => $data->code
                ]);
            });

            return response()->json(['message' => 'Permission successfully rolled back']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred during rollback', 'error' => $e->getMessage()], 500);
        }
    }
}