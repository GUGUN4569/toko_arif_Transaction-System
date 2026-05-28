<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->string('id_supplier', 10)->primary();
            $table->string('nama_supplier', 100);
            $table->text('alamat_supplier')->nullable();
            $table->string('no_telp_supplier', 20)->nullable();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};