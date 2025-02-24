<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'region' => ['required'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
            'quantity' => ['required', 'integer'],
            'thickness' => ['required_with:size', 'in:20+8 x 28 w/ hole,20+8 x 28 w/out hole'],
            'size' => ['required_with:thickness', 'in:Tiny,Small,Medium,Large,XL'],
            'note' => ['nullable'],
            'design' => ['file', 'nullable'],
        ];

        return $rules;
    }
}
