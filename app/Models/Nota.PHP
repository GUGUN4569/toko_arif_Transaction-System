<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Nota extends Model
{
    protected $table = 'nota';
    protected $primaryKey = 'id_nota';
    public $incrementing = false;
    protected $keyType = 'string';
 
    protected $fillable = [
        'id_nota', 'tanggal_nota', 'total_jumlah_nota',
        'tanda_terima', 'id_customer', 'id_pegawai'
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
}
