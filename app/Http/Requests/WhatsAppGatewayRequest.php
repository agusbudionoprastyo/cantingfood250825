<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhatsAppGatewayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'whatsapp_enabled' => ['nullable', 'boolean'],
            'whatsapp_api_url' => ['required_if:whatsapp_enabled,true', 'url', 'max:500'],
            'whatsapp_session' => ['required_if:whatsapp_enabled,true', 'string', 'max:100'],
            'whatsapp_phone' => ['required_if:whatsapp_enabled,true', 'string', 'max:128'],
            'whatsapp_company_name' => ['nullable', 'string', 'max:100'],
            'whatsapp_message_template' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp_api_url.required_if' => 'API URL is required when WhatsApp is enabled',
            'whatsapp_session.required_if' => 'Session is required when WhatsApp is enabled',
            'whatsapp_phone.required_if' => 'Phone number or group ID is required when WhatsApp is enabled',
            'whatsapp_api_url.url' => 'Please enter a valid API URL',
        ];
    }
}
