<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
