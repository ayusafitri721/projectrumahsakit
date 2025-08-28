<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            // Tambah kolom ruangan_id sebagai foreign key
            $table->unsignedBigInteger('ruangan_id')->nullable()->after('spesialis');
            
            // Tambah foreign key constraint
            $table->foreign('ruangan_id')->references('id')->on('ruangan')->onDelete('set null');
            
            // Index untuk performa
            $table->index('ruangan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            // Drop foreign key constraint dulu
            $table->dropForeign(['ruangan_id']);
            
            // Drop kolom
            $table->dropColumn('ruangan_id');
        });
    }
};