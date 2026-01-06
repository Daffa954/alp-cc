<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HashableId
{
    /**
     * Mengubah ID angka menjadi Hash saat membuat URL.
     * Contoh: route('expenses.edit', 1) -> /expenses/Xk9dz/edit
     */
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    /**
     * Menerjemahkan Hash di URL kembali menjadi ID angka saat query database.
     * Contoh: /expenses/Xk9dz/edit -> ID 1
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Coba decode hashnya
        $decoded = Hashids::decode($value);

        // Jika hasil decode kosong (artinya hash tidak valid/asal-asalan), return null (404)
        if (empty($decoded)) {
            return null;
        }

        // Ambil angka aslinya (array index ke-0)
        $id = $decoded[0];

        // Cari di database berdasarkan ID asli
        return $this->where($this->getKeyName(), $id)->firstOrFail();
    }
}