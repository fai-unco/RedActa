<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DocumentSharedAccess;

class UpdateDocumentSharedAccessRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'redacta_user_id' => 'sometimes|numeric|exists:redacta_users,id',
            'document_id' => 'sometimes|numeric|exists:documents,id',
            'access_mode_id' => 'sometimes|numeric|exists:access_modes,id'
        ];
    }
}
