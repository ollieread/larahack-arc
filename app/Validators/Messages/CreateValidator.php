<?php

namespace Arc\Validators\Messages;

use Arc\Models\Message;
use Arc\Support\Validator;
use Illuminate\Validation\Rule;

class CreateValidator extends Validator
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type'     => ['required'],
            'message'  => [
                Rule::requiredIf(function () {
                    $type = $this->data['type'];
                    return (($type & Message::TEXT) || ($type & Message::NOTICE) || ($type & Message::NSFW) || ($type & Message::SPOILER));
                }),
            ],
            'action'   => [
                Rule::requiredIf(function () {
                    return (bool)$this->data['type'] & Message::ACTION;
                }),
            ],
            'media'    => [
                Rule::requiredIf(function () {
                    return (bool)$this->data['type'] & Message::MEDIA;
                }),
            ],
            'mentions' => ['sometimes:array'],
            'metadata' => ['sometimes:array'],
        ];
    }
}