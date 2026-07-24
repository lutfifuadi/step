<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpressionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'is_anonymous' => ['boolean'],
            'real_name' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'min:20', 'max:2000'],
            'consent_agreed' => ['required', 'accepted'],
            'honeypot' => ['max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Pilih kategori ekspresi kamu.',
            'content.required' => 'Ceritamu tidak boleh kosong.',
            'content.min' => 'Ceritamu terlalu singkat, minimal 20 karakter.',
            'content.max' => 'Ceritamu terlalu panjang, maksimal 2000 karakter.',
            'consent_agreed.required' => 'Kamu perlu menyetujui pernyataan persetujuan.',
            'consent_agreed.accepted' => 'Kamu perlu menyetujui pernyataan persetujuan.',
            'honeypot.max' => 'Spam terdeteksi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->boolean('is_anonymous')) {
            $this->merge(['real_name' => null]);
        }
    }
}
