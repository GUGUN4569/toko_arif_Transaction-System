<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';
 
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