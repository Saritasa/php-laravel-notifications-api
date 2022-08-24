<?php

namespace App\Http\Requests;

use Saritasa\LaravelControllers\Requests\PageRequest;

/**
 * Class NotificationRequest
 *
 * @property string $type
 *
 * @package App\Http\Requests
 */
class NotificationRequest extends PageRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param array $rules
     *
     * @return array
     */
    public function rules(array $rules = []): array
    {
        return parent::rules(array_merge($rules, [
            'type' => ['string'],
        ]));
    }
}
