<?php

namespace App\Http\Requests;

use App\DTOs\RoleDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RoleRequest extends FormRequest
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
        return new RoleDTO(
            $this->input('name'),
            $this->input('description'),
            $this->input('code'),
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
            'name' => 'required|string|unique:roles,name' ,
            'description' => 'string|required',
            'code' => 'required|string|unique:roles,code',
        ];
    }
}
