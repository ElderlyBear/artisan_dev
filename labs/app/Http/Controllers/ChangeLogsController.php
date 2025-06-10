<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ChangeLogs;
use App\Models\User;
use App\Models\Role;

class ChangeLogsController extends Controller
{
    public function getUserStory($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }
        $story = ChangeLogs::where('entity_id',$id)->get();

        return response()->json([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'story' => $story
        ]);
    }

    public function getRoleStory($id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message' => 'User not found'], 404);
        }
        $story = ChangeLogs::where('entity_id',$id)->get();

        return response()->json(['Role' => [
            'id' => $role->id,
            'name' => $role->name,
            'description' => $role->description,
        ], 'story' => $story]);
    }

    public function getPermissionsStory($id){
        $permission = Permission::find($id);
        if(!$permission){
            return response()->json(['message' => 'User not found'], 404);
        }
        $story = ChangeLogs::where('entity_id',$id)->get();
        return response()->json([
            'permission' => ['id' => $permission->id, 'name' => $permission->name, 'description' => $permission->description],
            'story' => $story
        ]);
    }

    public function RollbackRole($roleId, $historyId){
        $role = Role::find($roleId);

        if(!$role){
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->roleRollback($historyId);

        return response()->json(['message' => 'Role successfully rollback']);
    }

    public function RollbackPermission($permissionsId,$historyId){
        $permission = Permission::find($permissionsId);

        if(!$permission){
            return response()->json(['message' => 'Permission not found'], 404);
        }

        $permission->rollbackPermission($historyId);

        return response()->json(['message' => 'Permission successfully rollback']);
    }


    public function RollbackUser($historyId, $userId){
        $user = User::find($userId);
        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->rollback($historyId);

        return response()->json(['message' => 'User successfully rollback']);

    }
}
