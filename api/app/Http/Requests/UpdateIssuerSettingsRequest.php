<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\IssuerSettings;

class UpdateIssuerSettingsRequest extends FormRequest
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
            'suggested_operative_section_beginning_id' => 'sometimes|numeric|exists:operative_section_beginnings,id',
            'suggested_true_copy_stamp_id' => 'sometimes|numeric|exists:stamps,id',
            'suggested_heading_id' => 'sometimes|numeric|exists:headings,id',
        ];
    }
}
