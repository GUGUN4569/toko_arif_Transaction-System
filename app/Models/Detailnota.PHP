<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class DetailNota extends Model
{
    protected $table = 'detail_nota';
 
    protected $fillable = [
        'id_nota', 'id_barang', 'banyaknya', 'diskon', 'subtotal_nota'
    ];
 
    public function nota()
    {
        return $this->belongsTo(Nota::class, 'id_nota', 'id_nota');
    }
 
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}