<?php

namespace Saritasa\PushNotifications\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UdpateNotificationsSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            '*.id' => 'integer|required',
            '*.is_on' => 'boolean|required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
