<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OperativeSectionBeginning;

class UpdateOperativeSectionBeginningRequest extends FormRequest
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
            'issuer_id' => 'sometimes|numeric|exists:issuers,id',
            'content' => 'sometimes|string',
        ];
    }
}
