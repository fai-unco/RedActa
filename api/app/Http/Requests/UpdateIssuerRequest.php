<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Issuer;

class UpdateIssuerRequest extends FormRequest
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
            'description' => 'sometimes|string',
            'phone' => 'sometimes|numeric|nullable',
            'address' => 'sometimes|string|nullable',
            'postal_code' => 'sometimes|string|nullable',
            'province' => 'sometimes|string|nullable',
            'city' => 'sometimes|string|nullable',
            'website_url' => 'sometimes|string|nullable',
            'email' => 'sometimes|string|nullable',
            'code' => 'sometimes|string|nullable'
        ];
    }
}
