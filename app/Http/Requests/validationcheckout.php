<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validationcheckout extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nim' => ['nullable', 'string'],
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
            'gambar' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'gambar.required' => 'Bukti pembayaran harus diunggah',
            'gambar.file' => 'File harus berupa file yang valid',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP',
            'gambar.max' => 'Ukuran file maksimal adalah 5MB',
        ];
    }
}
