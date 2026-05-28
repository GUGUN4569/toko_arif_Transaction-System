<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class DetailFaktur extends Model
{
    protected $table = 'detail_faktur';
 
    protected $fillable = [
        'id_faktur', 'id_barang', 'quantity', 'subtotal_faktur'
    ];
 
    public function faktur()
    {
        return $this->belongsTo(Faktur::class, 'id_faktur', 'id_faktur');
    }
 
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}