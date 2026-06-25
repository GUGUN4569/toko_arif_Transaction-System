<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName(): string
    {
        return 'id_supplier';
    }

    // ── Hashing ID untuk URL ──────────────────────────
    public function getRouteKey()
    {
        return urlencode(Crypt::encryptString($this->id_supplier));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idAsli = Crypt::decryptString(urldecode($value));
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->where('id_supplier', $idAsli)->firstOrFail();
    }

    protected $fillable = [
        'id_supplier', 'nama_supplier', 'alamat_supplier', 'no_telp_supplier'
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'id_supplier', 'id_supplier');
    }
}