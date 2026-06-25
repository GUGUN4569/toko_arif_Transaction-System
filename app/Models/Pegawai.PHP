<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    public function getRouteKeyName(): string
    {
        return 'id_pegawai';
    }

    // ── Hashing ID untuk URL ──────────────────────────
    // id_pegawai integer, di-cast ke string sebelum dienkripsi
    public function getRouteKey()
    {
        return urlencode(Crypt::encryptString((string) $this->id_pegawai));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idAsli = Crypt::decryptString(urldecode($value));
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->where('id_pegawai', $idAsli)->firstOrFail();
    }

    protected $fillable = ['nama_pegawai'];

    public function nota()
    {
        return $this->hasMany(Nota::class, 'id_pegawai', 'id_pegawai');
    }

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'id_pegawai', 'id_pegawai');
    }
}