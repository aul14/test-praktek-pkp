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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_faktur')->nullable();
            $table->string('no_faktur', 25)->unique();
            $table->string('nama_konsumen', 80)->nullable();
            $table->foreignId('ms_barang_id')->references('id')->on('ms_barang')->cascadeOnUpdate()->cascadeOnDelete();
            $table->double('jumlah')->nullable();
            $table->double('harga_satuan')->nullable();
            $table->double('total')->nullable();
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
        Schema::dropIfExists('penjualan');
    }
};
