<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Nota extends Model
{
    protected $table      = 'nota';
    protected $primaryKey = 'id_nota';
    public    $incrementing = false;
    protected $keyType    = 'string';

    public function getRouteKeyName(): string
    {
        return 'id_nota';
    }

    // ── Hashing ID untuk URL ──────────────────────────
    // Otomatis dipanggil saat route('nota.show', $nota) dibuat
    public function getRouteKey()
    {
        return urlencode(Crypt::encryptString($this->id_nota));
    }

    // Otomatis dipanggil saat Laravel resolve {nota} dari URL
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idAsli = Crypt::decryptString(urldecode($value));
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->where('id_nota', $idAsli)->firstOrFail();
    }

    protected $fillable = [
        'id_nota', 'tanggal_nota', 'total_jumlah_nota',
        'tanda_terima', 'id_customer', 'id_pegawai',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function detailNota()
    {
        return $this->hasMany(DetailNota::class, 'id_nota', 'id_nota');
    }

    // ── Cek apakah nota sudah terkunci (lebih dari 14 hari) ──
    public function isLocked(): bool
    {
        return \Carbon\Carbon::parse($this->tanggal_nota)->lt(now()->subDays(14)->startOfDay());
    }
}