<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    public $incrementing = false;
    protected $keyType = 'string';
 
    protected $fillable = [
        'id_customer', 'nama_customer', 'kontak'
    ];
 
    public function nota()
    {
        return $this->hasMany(Nota::class, 'id_customer', 'id_customer');
    }
}