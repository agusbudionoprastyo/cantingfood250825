<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'template_content' => 'required|string|min:10',
            'type' => 'required|in:order_notification,custom,marketing',
            'description' => 'nullable|string|max:500',
            'variables' => 'nullable|array',
            'variables.*' => 'string',
            'is_active' => 'boolean'
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Template name is required',
            'name.max' => 'Template name cannot exceed 255 characters',
            'template_content.required' => 'Template content is required',
            'template_content.min' => 'Template content must be at least 10 characters',
            'type.required' => 'Template type is required',
            'type.in' => 'Template type must be order_notification, custom, or marketing',
            'description.max' => 'Description cannot exceed 500 characters',
            'variables.array' => 'Variables must be an array',
            'variables.*.string' => 'Each variable must be a string'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'template name',
            'template_content' => 'template content',
            'type' => 'template type',
            'description' => 'description',
            'variables' => 'variables',
            'is_active' => 'active status'
        ];
    }
}
