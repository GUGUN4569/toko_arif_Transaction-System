<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName(): string
    {
        return 'id_barang';
    }

    // ── Hashing ID untuk URL ──────────────────────────
    public function getRouteKey()
    {
        return urlencode(Crypt::encryptString($this->id_barang));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idAsli = Crypt::decryptString(urldecode($value));
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->where('id_barang', $idAsli)->firstOrFail();
    }

    protected $fillable = [
        'id_barang', 'nama_barang', 'harga', 'disc', 'satuan'
    ];

    public function detailNota()
    {
        return $this->hasMany(DetailNota::class, 'id_barang', 'id_barang');
    }

    public function detailFaktur()
    {
        return $this->hasMany(DetailFaktur::class, 'id_barang', 'id_barang');
    }
}