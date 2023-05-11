<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20)->unique();
            $table->string('nama_barang', 100)->nullable();
            $table->double('harga_jual')->nullable();
            $table->double('harga_beli')->nullable();
            $table->string('satuan', 20)->nullable();
            $table->foreignId('ms_kategori_id')->references('id')->on('ms_kategori')->cascadeOnUpdate()->cascadeOnDelete();
            $table->double('stok_barang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_barang');
    }
};
