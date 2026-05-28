<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
 
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