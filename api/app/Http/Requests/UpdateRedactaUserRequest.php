<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRedactaUserRequest extends FormRequest
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
        $userId = $this->route('redacta_user')?->id ?? $this->route('id') ?? $this->user()?->id;
        return [
            'name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('redacta_users', 'email')->ignore($userId),
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|string|in:super_admin,local_admin,editor'
        ];
    }
}
