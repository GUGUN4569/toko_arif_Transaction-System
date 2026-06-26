<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_nota', function (Blueprint $table) {
            // Diskon per item (dalam persen, 0-100), diinput manual saat transaksi
            $table->decimal('diskon', 5, 2)->default(0)->after('banyaknya');
        });
    }
 
    public function down(): void
    {
        Schema::table('detail_nota', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });
    }
};