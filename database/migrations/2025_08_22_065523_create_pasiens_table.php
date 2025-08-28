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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('nomorRekamMedis');
            $table->text('namaPasien');
            $table->date('tanggalLahir');
            $table->enum('jenisKelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamatPasien');
            $table->text('kotaPasien');
            $table->integer('usiaPasien');
            $table->text('penyakitPasien');
            $table->text('idDokter');
            $table->date('tanggalMasuk');
            $table->date('tanggalKeluar')->nullable();
            $table->text('nomorKamar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};