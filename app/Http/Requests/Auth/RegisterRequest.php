<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:pengguna,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,distributor,kepala_desa'],
            'nama_desa' => ['required_if:role,kepala_desa', 'string', 'max:255'],
            'kecamatan' => ['required_if:role,kepala_desa', 'string', 'max:255'],
            'kabupaten' => ['required_if:role,kepala_desa', 'string', 'max:255'],
            'provinsi' => ['required_if:role,kepala_desa', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'email' => 'alamat email',
            'password' => 'kata sandi',
            'password_confirmation' => 'konfirmasi kata sandi',
            'role' => 'peran',
            'nama_desa' => 'nama desa',
            'kecamatan' => 'kecamatan',
            'kabupaten' => 'kabupaten',
            'provinsi' => 'provinsi',
            'alamat' => 'alamat',
            'kontak' => 'nomor kontak',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_desa.required_if' => 'Nama desa wajib diisi untuk Kepala Desa.',
            'kecamatan.required_if' => 'Kecamatan wajib diisi untuk Kepala Desa.',
            'kabupaten.required_if' => 'Kabupaten wajib diisi untuk Kepala Desa.',
            'provinsi.required_if' => 'Provinsi wajib diisi untuk Kepala Desa.',
        ];
    }
}
