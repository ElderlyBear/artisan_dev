<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DTOs\RoleDTO;
use App\Models\Role;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class RoleController extends Controller
{
    public function getRoleList(){
        $roles = Role::all();
        return response()->json($roles);
    }

    public function getRole($id){
        $role = Role::find($id);

        if(!$role){
            return response()->json('Role not found', 404);
        }

        return response()->json($role);
    }

    public function createRole(RoleRequest $request){
        $roleDTO = new RoleDTO(
            $request->input('name'),
            $request->input('description'),
            $request->input('code'),
        );


        DB::transaction(function() use ($roleDTO){
            $role = Role::create([
                'name' => $roleDTO->name,
                'description' => $roleDTO->description,
                'code' => $roleDTO->code,
            ]);
        });


        return response()->json(['message'=> 'Role created!']);
    }

    public function updateRole(RoleRequest $request, $id){
        $role = Role::find($id);
        if(!$role){
            return response()->json('Role not found', 404);
        }
        $roleDTO = new RoleDTO(
            $request->input('name'),
            $request->input('description'),
            $request->input('code'),
        );

        DB::transaction(function() use ($role, $roleDTO){
            $role->update([
                'name' => $roleDTO->name,
                'description' => $roleDTO->description,
                'code' => $roleDTO->code,
            ]);

//            throw new Exception('error to update role');

        });
        return response()->json(['message'=> 'Role updated!']);
    }

    public function deleteRole($id){
        $role = Role::find($id);
        if(!$role){
            return response()->json('Role not found', 404);
        }
        $role->forceDelete();

        return response()->json(['message'=> 'Role deleted!']);
    }

    public function deleteRoleSoft($id){
        $role = Role::find($id);
        if(!$role){
            return response()->json('Role not found', 404);
        }

        $role->delete();

        return response()->json(['message'=> 'Role deleted soft!']);
    }


    public function restoreRole($id){
        $role = Role::withTrashed()->find($id);
        if(!$role){
            return response()->json('Role not found', 404);
        }
        $role->restore();
    }

}
