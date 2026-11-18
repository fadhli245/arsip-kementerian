<?php

namespace App\Http\Requests\Pegawai;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArchiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && in_array($this->user()->role, ['user', 'pegawai'], true);
    }

    public function rules(): array
    {
        return [
            'judul_arsip' => ['required', 'string', 'max:200'],
            'kategori' => ['required', 'string', Rule::exists('archive_categories', 'name')->where('is_active', true)],
            'referensi' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul_arsip.required' => 'Judul atau perihal arsip wajib diisi.',
            'judul_arsip.max' => 'Judul arsip maksimal 200 karakter.',
            'kategori.required' => 'Pilih kategori dokumen.',
            'kategori.exists' => 'Kategori dokumen yang dipilih tidak tersedia.',
            'referensi.max' => 'Perkiraan tahun atau nomor surat maksimal 100 karakter.',
            'keterangan.required' => 'Keperluan permohonan wajib diisi.',
            'keterangan.max' => 'Keperluan permohonan maksimal 500 karakter.',
        ];
    }
}
