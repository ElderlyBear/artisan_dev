<?php

namespace App\Http\Controllers;

use App\Models\RoleAndPermission;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\DTOs\RolesAndPermissionsDTO;

class RoleAndPermissionController extends Controller
{

    public function getPermissionsRole($id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($role->permissions);
    }

    public function AddPermissionToRole(Request $request,$id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message'=>'Role not found'],404);
        }

        $permissions = $request->input('permission_id');
        $existPermission = RoleAndPermission::where('role_id',$id)->where('permission_id',$permissions)->exists();

        if($existPermission){
            return response()->json(['message'=>'Permission already exist'],404);
        }
        $dto = new RolesAndPermissionsDTO(
            $id,
            $request->input('permission_id'),
        );

        $rolePermission = RoleAndPermission::create([
            'role_id' => $dto->role_id,
            'permission_id' => $dto->permission_id,
        ]);

        return response()->json(['message'=> 'Permission added successfully'],200);
    }

    public function RemovePermissionToRole($role_id, $permission_id)
    {
        $role = Role::find($role_id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Проверим, есть ли разрешение у роли
        $existed = $role->permissions()->where('permission_id', $permission_id)->exists();
        if (!$existed) {
            return response()->json(['message' => 'Permission not found for this role'], 404);
        }

        // Удалить разрешение из роли
        $role->permissions()->detach($permission_id);

        return response()->json(['message' => 'Permission removed successfully'], 200);
    }
}