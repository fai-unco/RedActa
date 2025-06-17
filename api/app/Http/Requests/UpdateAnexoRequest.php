<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Anexo;

class UpdateAnexoRequest extends FormRequest
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
            'index' => 'sometimes|numeric',
            'document_id' => 'sometimes|numeric|exists:documents,id',
            'file_id' => 'sometimes|numeric|exists:files,id',
            'title' => 'sometimes|nullable|string',
            'subtitle' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
        ];
    }
}
