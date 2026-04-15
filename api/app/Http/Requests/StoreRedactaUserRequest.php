<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedactaUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',        
            'last_name' => 'required|string|max:255',        
            'email' => 'required|email|unique:redacta_users',              
            'password' => 'required|confirmed|min:8',
            'role_id' => 'sometimes|exists:roles,id',
        ];
    }
}
