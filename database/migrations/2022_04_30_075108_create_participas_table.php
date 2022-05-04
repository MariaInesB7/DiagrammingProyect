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
        Schema::create('participas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('documentoId');
            $table->foreign('usuarioId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('documentoId')->references('id')->on('documentos')->onDelete('cascade');
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
        Schema::dropIfExists('documento_user');
    }
};
