<?php

namespace App\Http\Requests;

use App\DTOs\RolesAndPermissionsDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RoleAndPermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(!Auth::check()){
            return false;
        }else{
            return true;
        }
    }

    public function toDTO(){
        return new RolesAndPermissionsDTO(
            $this->input('role_id'),
            $this->input('permission_id'),
        );
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ];
    }
}
