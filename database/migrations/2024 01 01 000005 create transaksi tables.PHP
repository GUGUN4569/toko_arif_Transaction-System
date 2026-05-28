<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota', function (Blueprint $table) {
            $table->string('id_nota', 10)->primary();
            $table->date('tanggal_nota');
            $table->decimal('total_jumlah_nota', 12, 2)->default(0.00);
            $table->string('tanda_terima', 100)->nullable();
            $table->string('id_customer', 10)->nullable();
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->timestamps();
 
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai');
        });
 
        Schema::create('detail_nota', function (Blueprint $table) {
            $table->id();
            $table->string('id_nota', 10)->nullable();
            $table->string('id_barang', 10)->nullable();
            $table->integer('banyaknya');
            $table->decimal('subtotal_nota', 12, 2)->default(0.00);
            $table->timestamps();
 
            $table->foreign('id_nota')->references('id_nota')->on('nota');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
        });
 
        Schema::create('faktur', function (Blueprint $table) {
            $table->string('id_faktur', 10)->primary();
            $table->string('no_faktur', 50);
            $table->date('tanggal_faktur');
            $table->decimal('total_jumlah_faktur', 12, 2)->default(0.00);
            $table->string('id_supplier', 10)->nullable();
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->timestamps();
 
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai');
        });
 
        Schema::create('detail_faktur', function (Blueprint $table) {
            $table->id();
            $table->string('id_faktur', 10)->nullable();
            $table->string('id_barang', 10)->nullable();
            $table->integer('quantity');
            $table->decimal('subtotal_faktur', 12, 2)->default(0.00);
            $table->timestamps();
 
            $table->foreign('id_faktur')->references('id_faktur')->on('faktur');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('detail_faktur');
        Schema::dropIfExists('faktur');
        Schema::dropIfExists('detail_nota');
        Schema::dropIfExists('nota');
    }
};