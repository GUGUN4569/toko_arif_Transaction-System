<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faktur', function (Blueprint $table) {
            $table->string('file_faktur')->nullable()->after('total_jumlah_faktur');
        });
    }
 
    public function down(): void
    {
        Schema::table('faktur', function (Blueprint $table) {
            $table->dropColumn('file_faktur');
        });
    }
};