<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    public $incrementing = false;
    protected $keyType = 'string';
 
    protected $fillable = [
        'id_supplier', 'nama_supplier', 'alamat_supplier', 'no_telp_supplier'
    ];
 
    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'id_supplier', 'id_supplier');
    }
}