<?php

namespace App\Http\Requests;

use App\Models\User;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
                       Rule::unique(User::class)->ignore($this->user()->id)],
            
            // Tambahkan field baru
            'address' => ['nullable', 'string', 'max:500'],
            'job' => ['nullable', 'string', 'max:100'],
            'job_location' => ['nullable', 'string', 'max:100'],
        ];
    }
    
    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'address.max' => 'Alamat maksimal 500 karakter',
            'job.max' => 'Pekerjaan maksimal 100 karakter',
            'job_location.max' => 'Lokasi kerja maksimal 100 karakter',
        ];
    }
}
