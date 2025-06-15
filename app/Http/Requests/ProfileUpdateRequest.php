<?php

namespace App\Http\Requests;

use App\Models\TelegramSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'chat_id' => ['required', 'integer', 'max_digits::11'],
            'username' => ['nullable', 'string', 'lowercase', 'max:20', Rule::unique(TelegramSetting::class)->ignore($this->user()->id, 'user_id')],
        ];
    }
}
