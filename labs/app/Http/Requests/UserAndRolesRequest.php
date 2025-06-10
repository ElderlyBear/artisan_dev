<?php

namespace App\Http\Requests;

use App\DTOs\UserAndRolesDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserAndRolesRequest extends FormRequest
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
        return new UserAndRolesDTO(
            $this->input('user_id'),
            $this->input('role_id'),
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
            'user_id' => 'nullable|int|exists:users,id',
            'role_id' => 'nullable|int|exists:roles,id',
        ];
    }
}
