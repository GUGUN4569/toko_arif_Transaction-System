<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Faktur extends Model
{
    protected $table = 'faktur';
    protected $primaryKey = 'id_faktur';
    public $incrementing = false;
    protected $keyType = 'string';
 
    protected $fillable = [
        'id_faktur', 'no_faktur', 'tanggal_faktur',
        'total_jumlah_faktur', 'id_supplier', 'id_pegawai'
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
}