<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAndRole;
use App\Http\Requests\UserAndRolesRequest;
use App\DTOs\UserAndRolesDTO;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserAndRolesController extends Controller
{
    use AuthorizesRequests;
    public function getUsers(){
        $users = User::all();

        return response()->json($users);
    }
    public function getUserRoles($id){
        $user = User::find($id);

        if(!$user){
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user->roles);
    }
    // связка пользователя с ролью
    public function AddUserRole(Request $request, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json(['error' => 'User not found'], 404);
        }

        $role = Role::find($request->input('role_id'));

        if(!$role){
            return response()->json(['error' => 'Role not found'], 404);
        }

        $existingRole = UserAndRole::where('user_id', $id)->where('role_id', $role)->first();

        if($existingRole){
            return response()->json(['error' => 'Role already assigned to this user'], 409);
        }

        $userRole = UserAndRole::create([
            'user_id' => $id,
            'role_id' => $request->input('role_id'),
        ]);

        return response()->json(['message'=> 'User role added'], 201);
    }

    // Удаление роли у пользователя
    public function RemoveUserRole($user_id, $role_id){
        $user = User::find($user_id);
        if(!$user){
            return response()->json(['error' => 'User not found'], 404);
        }

        $role = Role::find($role_id);
        if(!$role){
            return response()->json(['error' => 'Role not found'], 404);
        }

        $userRole = UserAndRole::where('user_id', $user_id)->where('role_id', $role_id)->first();
        if(!$userRole){
            return response()->json(['error'=>'User role not found'], 404);
        }
        $userRole->forceDelete();
        return response()->json(['message'=> 'User role removed'], 201);
    }


}
