<?php

namespace App\Http\Controllers;

use App\DTOs\PermissionsDTO;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{
    public function getPermissionsList(){
        $permissions = Permission::all();

        return response()->json($permissions);
    }

    public function getPermissions($id){
        $permissions = Permission::find($id);

        if(!$permissions){
            return response()->json(['message'=>'Permission not found'],404);
        }

        return response()->json($permissions);
    }

    public function createPermission(PermissionRequest $request){
        $permissionDTO = new PermissionsDTO(
            $request->input('name'),
            $request->input('description'),
            $request->input('code'),
        );

        DB::transaction(function() use ($permissionDTO){
            $permission = Permission::create([
                'name' => $permissionDTO->name,
                'description' => $permissionDTO->description,
                'code' => $permissionDTO->code,
            ]);
        });


        return response()->json(['message'=>'Permission created'],201);
    }

    public function updatePermission(PermissionRequest $request, $id){
        $permissions = Permission::find($id);
        if(!$permissions){
            return response()->json(['message'=>'Permission not found'],404);
        }
        $permissionDTO = new PermissionsDTO(
            $request->input('name'),
            $request->input('description'),
            $request->input('code'),
        );

        DB::transaction(function() use ($permissionDTO,$permissions){
            $permissions->update([
                'name' => $permissionDTO->name,
                'description' => $permissionDTO->description,
                'code' => $permissionDTO->code,
            ]);
        });

        return response()->json(['message'=>'Permission updated'],201);
    }

    public function deletePermission($id){
        $permission = Permission::find($id);
        if(!$permission){
            return response()->json(['message'=>'Permission not found'],404);
        }

        $permission->forceDelete();

        return response()->json(['message'=>'Permission deleted'],201);
    }

    public function deletePermissionsSoft($id){
        $permission = Permission::find($id);
        if(!$permission){
            return response()->json(['message'=>'Permission not found'],404);
        }

        $permission->delete();
        return response()->json(['message'=>'Permission deleted soft'],201);
    }

    public function restorePermission($id){
        $permission = Permission::withTrashed()->find($id);
        if(!$permission){
            return response()->json(['message'=>'Permission not found'],404);
        }

        $permission->restore();
    }

}
