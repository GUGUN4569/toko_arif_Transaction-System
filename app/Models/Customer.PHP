<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName(): string
    {
        return 'id_customer';
    }

    // ── Hashing ID untuk URL ──────────────────────────
    public function getRouteKey()
    {
        return urlencode(Crypt::encryptString($this->id_customer));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idAsli = Crypt::decryptString(urldecode($value));
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->where('id_customer', $idAsli)->firstOrFail();
    }

    protected $fillable = [
        'id_customer', 'nama_customer', 'kontak'
    ];

    public function nota()
    {
        return $this->hasMany(Nota::class, 'id_customer', 'id_customer');
    }
}