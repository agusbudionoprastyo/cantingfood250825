<?php

namespace App\Http\SmsGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Whatsapp extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->whatsapp_status == Activity::ENABLE) {
            return [
                'whatsapp_api_url' => ['required', 'url'],
                'whatsapp_session' => ['required', 'string'],
                'whatsapp_status' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'whatsapp_api_url' => ['nullable', 'url'],
                'whatsapp_session' => ['nullable', 'string'],
                'whatsapp_status' => ['nullable', 'numeric'],
            ];
        }
    }
}
