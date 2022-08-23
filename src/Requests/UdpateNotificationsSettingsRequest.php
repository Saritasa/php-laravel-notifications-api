<?php

namespace Saritasa\PushNotifications\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UdpateNotificationsSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            '*.id' => 'integer|required',
            '*.isOn' => 'boolean|required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
