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
public function getHashIdAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
    /**
     * Menerjemahkan Hash di URL kembali menjadi ID angka saat query database.
     * Contoh: /expenses/Xk9dz/edit -> ID 1
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // 1. Decode hash string (misal: 'k5') menjadi array angka
        $decoded = Hashids::decode($value);

        // 2. Jika gagal decode (kosong), return null (otomatis 404)
        if (empty($decoded)) {
            return null;
        }

        // 3. Cari data berdasarkan ID asli hasil decode
        return $this->where('id', $decoded[0])->firstOrFail();
    }
}