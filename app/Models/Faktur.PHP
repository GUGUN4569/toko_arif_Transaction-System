<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Faktur extends Model
{
    protected $table      = 'faktur';
    protected $primaryKey = 'id_faktur';
    public    $incrementing = false;
    protected $keyType    = 'string';

    public function getRouteKeyName(): string
    {
        return 'id_faktur';
    }

    // ── Hashing ID untuk URL ──────────────────────────
    public function getRouteKey()
    {
        return urlencode(Crypt::encryptString($this->id_faktur));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idAsli = Crypt::decryptString(urldecode($value));
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->where('id_faktur', $idAsli)->firstOrFail();
    }

    protected $fillable = [
        'id_faktur', 'no_faktur', 'tanggal_faktur',
        'total_jumlah_faktur', 'file_faktur', 'id_supplier', 'id_pegawai',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function detailFaktur()
    {
        return $this->hasMany(DetailFaktur::class, 'id_faktur', 'id_faktur');
    }

    // ── Cek apakah faktur sudah terkunci (lebih dari 14 hari) ──
    public function isLocked(): bool
    {
        return \Carbon\Carbon::parse($this->tanggal_faktur)->lt(now()->subDays(14)->startOfDay());
    }
}