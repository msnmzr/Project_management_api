<?php

namespace App\Http\Requests\Api;

use App\Models\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeValueRequest extends FormRequest
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
            'attribute_id' => ['required', 'exists:attributes,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'value' => ['required'],
        ];

        if ($this->filled('attribute_id')) {
            $attribute = Attribute::find($this->attribute_id);
            if ($attribute) {
                switch ($attribute->type) {
                    case 'date':
                        $rules['value'][] = 'date';
                        break;
                    case 'number':
                        $rules['value'][] = 'numeric';
                        break;
                }
            }
        }

        if ($this->isMethod('POST')) {
            $rules['attribute_id'][] = Rule::unique('attribute_values')
                ->where('project_id', $this->project_id);
        } elseif ($this->isMethod('PUT')) {
            $rules['attribute_id'][] = Rule::unique('attribute_values')
                ->where('project_id', $this->project_id)
                ->ignore($this->route('attribute_value'));
        }

        return $rules;
    }
}
