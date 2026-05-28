<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 10)->primary();
            $table->string('nama_barang', 100);
            $table->decimal('harga', 12, 2);
            $table->decimal('disc', 5, 2)->default(0.00);
            $table->string('satuan', 20)->nullable();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};