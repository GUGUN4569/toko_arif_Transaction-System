<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFakturRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     * (Middleware 'admin' sudah menangani otorisasi di route,
     * jadi di sini cukup return true. Sesuaikan jika perlu policy tambahan)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules validasi.
     * Header faktur + array items (detail barang) divalidasi sekaligus.
     */
    public function rules(): array
    {
        return [
            'no_faktur'      => ['required', 'string', 'max:50'],
            'tanggal_faktur' => ['required', 'date', 'before_or_equal:today'],
            'id_supplier'    => ['nullable', 'string', 'exists:supplier,id_supplier'],
            'id_pegawai'     => ['nullable', 'integer', 'exists:pegawai,id_pegawai'],

            // Items wajib array, minimal 1 baris barang
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.id_barang'       => ['required', 'string', 'exists:barang,id_barang'],
            'items.*.quantity'        => ['required', 'integer', 'min:1'],
            'items.*.subtotal_faktur' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Pesan error custom (Bahasa Indonesia, konsisten dengan controller existing)
     */
    public function messages(): array
    {
        return [
            'tanggal_faktur.before_or_equal' => 'Tanggal faktur tidak boleh lebih dari hari ini.',
            'items.required'                 => 'Minimal harus ada 1 barang dalam faktur.',
            'items.*.id_barang.exists'       => 'Salah satu barang yang dipilih tidak valid.',
            'items.*.quantity.min'           => 'Quantity barang minimal 1.',
        ];
    }
}