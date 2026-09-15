<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal'     => ['required', 'date_format:Y-m-d'],
            'kode_barang' => ['required', 'string', 'max:50'],
            'nama_bean'   => ['required', 'string', 'max:100'],
            'jenis'       => ['required', 'string', 'max:50'],
            'kategori'    => ['required', 'string', 'max:50'],
            'keterangan'  => ['required', 'string', 'max:50'],
            'keluar'      => ['required', 'numeric', 'min:0'],
            'masuk'       => ['required', 'numeric', 'min:0'],
            'satuan'      => ['required', 'string', 'max:20'],
        ];
    }
}
