<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Document;

class UpdateDocumentRequest extends FormRequest
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
            'issuer_id' => 'sometimes|numeric|exists:issuers,id|nullable',
            'name' => 'sometimes|string|nullable',
            'number' => 'sometimes|numeric|nullable',
            'issue_date' => 'sometimes|date|nullable',
            'body' => 'sometimes',
            'subject' => 'sometimes|nullable|string',
            'destinatary' => 'sometimes|nullable|string',
            'has_anexo_unico' => 'sometimes|boolean',
            'true_copy_stamp_id' => 'sometimes|numeric|nullable',
            'heading_id' => 'sometimes|numeric|nullable',
            'operative_section_beginning_id' => 'sometimes|numeric|nullable',
            'stamps' => 'sometimes|array'
        ];
    }
}
