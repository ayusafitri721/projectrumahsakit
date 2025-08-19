<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('dokters', function (Blueprint $table) {
        $table->id();
        $table->char('idDokter');
        $table->text('namaDokter');
        $table->text('tanggalLahir');
        $table->text('spesialis');
        $table->text('lokasiPraktik');
        $table->text('jamPraktik');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};
