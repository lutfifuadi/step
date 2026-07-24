<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKonselorContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'role' => ['required', 'string', 'min:3', 'max:255'],
            'institusi' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => [
                'required',
                'string',
                'regex:/^(\+62|0)[0-9]{9,12}$/'
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'room' => ['nullable', 'string', 'max:255'],
            'availability' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama konselor wajib diisi.',
            'name.min' => 'Nama konselor minimal 3 karakter.',
            'role.required' => 'Jabatan konselor wajib diisi.',
            'role.min' => 'Jabatan konselor minimal 3 karakter.',
            'institusi.required' => 'Nama institusi/sekolah wajib diisi.',
            'institusi.min' => 'Nama institusi minimal 3 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format Indonesia (+62 atau 0 diikuti 9-12 digit).',
            'email.email' => 'Format email tidak valid.',
            'sort_order.required' => 'Urutan tampil wajib diisi.',
            'sort_order.integer' => 'Urutan tampil harus berupa angka.',
            'sort_order.min' => 'Urutan tampil minimal 0.',
        ];
    }
}
