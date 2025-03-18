<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'completed'])],
            'user_ids' => ['sometimes', 'array'],
            'user_ids.*' => ['exists:users,id'],
            'attributes' => ['sometimes', 'array'],
            'attributes.*.id' => ['required', 'exists:attributes,id'],
            'attributes.*.value' => ['required', 'string'],
        ];
    }
}
