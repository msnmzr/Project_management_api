<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['text', 'date', 'number', 'select'])],
        ];

        if ($this->isMethod('POST')) {
            $rules['name'][] = 'unique:attributes';
        } elseif ($this->isMethod('PUT')) {
            $rules['name'][] = Rule::unique('attributes')->ignore($this->route('attribute'));
        }

        return $rules;
    }
}
